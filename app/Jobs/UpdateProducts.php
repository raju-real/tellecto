<?php

namespace App\Jobs;

use App\Models\JobLog;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class UpdateProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;
    protected $apiUrl;
    protected $jobLog;
    protected $totalUpdate = 0;

    public function __construct($apiUrl = null)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        $this->apiUrl = $apiUrl;
    }

    public function handle()
    {
        $this->jobLog = JobLog::create([
            'job_name' => 'UpdateProducts',
            'started_at' => now(),
            'status' => 'started'
        ]);

        try {
            Log::info("Product updating job started at " . now());
            //Log::info('Job started for API URL: ' . $this->apiUrl);
            // Fetch CSV data directly from the API
            $response = Http::timeout(3600)->get($this->apiUrl);

            if ($response->successful()) {
                //Log::info('Data fetched from API successfully');
                $csvData = $response->body();
            } else {
                Log::error('Failed to fetch product data', ['status' => $response->status()]);
                return;
            }

            if (!$csvData) {
                Log::error('No CSV data available.');
                return;
            }

            $this->processCsvInChunks($csvData);
            Log::info('Total product update on last job and end at ' . now());

        } catch (\Exception $e) {
            Log::error('Job failed with exception: ' . $e->getMessage());
            $this->jobLog->update([
                'ended_at' => now(),
                'status' => 'failed',
                'error_message' => 'Failed to fetch product data'
            ]);
        } finally {
            $this->jobLog->update([
                'ended_at' => now(),
                'status' => 'completed'
            ]);
        }
    }

    private function processCsvInChunks($csvData)
    {
        $csv = Reader::createFromString($csvData);
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $chunkSize = 1000;
        foreach (array_chunk(iterator_to_array($records), $chunkSize) as $chunk) {
            //UpdateProductChunk::dispatch($chunk);
            $this->startProductUpdate($chunk);
        }
    }

    private function startProductUpdate($chunk)
    {
        //Log::info("ATTEMP " . $attempt . " maxRetries " . $maxRetries);
        try {
            DB::beginTransaction();
            $data = [];
            foreach ($chunk as $record) {
                $productNumber = $record['Product number'];
                //$price = $this->sanitizePrice($record['Price']);
                $price = round($record['Price'], 2);
                $inventory = $record['Inventory'];
                $stockStatus = $record['Stock status [yes or expected delivery time]'];
                // Fetch the existing product to get its profit_amount and dcs_last_price
                $existingProduct = DB::table('products')
                    ->where('product_number', $productNumber)
                    ->first();

                if ($existingProduct) {
                    $dcsLastPrice = $existingProduct->price;
                    // Calculate the sale price
                    $calculatedValues = Product::calculateProfit($price, $existingProduct->profit_type, $existingProduct->profit);
                    // Prepare the data for the upsert
                    $data[] = [
                        'product_number' => $productNumber,
                        'price' => $price,
                        'inventory' => $inventory,
                        'stock_status' => $stockStatus,
                        'profit_amount' => $calculatedValues['profit_amount'],
                        'sale_price' => $calculatedValues['sale_price'],
                        'dcs_last_price' => $dcsLastPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($data)) {
                // Upsert data into the temporary table
                DB::table('temp_products')->upsert(
                    $data,
                    ['product_number'],
                    ['price', 'inventory', 'stock_status', 'profit_amount', 'sale_price', 'dcs_last_price', 'updated_at']
                );
                try {
                    DB::beginTransaction();
                    // Update the `products` table
                    DB::statement('
                        UPDATE products p
                        JOIN temp_products t ON p.product_number = t.product_number
                        SET
                            p.price = t.price,
                            p.inventory = t.inventory,
                            p.stock_status = t.stock_status,
                            p.profit_amount = t.profit_amount,
                            p.sale_price = t.sale_price,
                            p.dcs_last_price = t.dcs_last_price,
                            p.updated_at = t.updated_at,
                            p.last_updated_at = NOW()
                    ');
                    // Commit the first transaction to ensure changes are persisted
                    DB::commit();
                    // Begin a new transaction for the second update
                    DB::beginTransaction();
                    // Update the `business_product_prices` table
                    DB::statement('
                        UPDATE business_product_prices bpp
                        JOIN products p ON bpp.product_number = p.product_number
                        SET
                            bpp.price = ROUND(p.sale_price),
                            bpp.sale_price = ROUND(
                                CASE
                                    WHEN bpp.profit_type = "PERCENTAGE" THEN p.sale_price + (p.sale_price * bpp.profit / 100)
                                    WHEN bpp.profit_type = "FLAT" THEN p.sale_price + bpp.profit
                                    ELSE p.sale_price
                                END
                            ),
                            bpp.previous_price = ROUND(
                                CASE
                                    WHEN bpp.price != p.sale_price THEN bpp.price
                                    ELSE bpp.previous_price
                                END
                            ),
                            bpp.profit_amount = ROUND(
                                CASE
                                    WHEN bpp.profit_type = "PERCENTAGE" THEN (p.sale_price * bpp.profit / 100)
                                    WHEN bpp.profit_type = "FLAT" THEN bpp.profit
                                    ELSE 0
                                END
                            ),
                            bpp.updated_at = NOW(),
                            bpp.last_updated_at = NOW()
                    ');
                    DB::commit(); // Commit the second transaction
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error in updating product data: ' . $e->getMessage());
                    throw $e;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Check if it's a deadlock error and retry
            if (str_contains($e->getMessage(), 'Deadlock')) {
                Log::warning("Deadlock detected, retrying transaction... Attempt: ");
            } else {
                Log::error('UpdateProductChunk failed: ' . $e->getMessage());
                throw $e;  // Rethrow the exception if it's not a deadlock
            }
        }
    }

    private function sanitizePrice($price)
    {
        $price = preg_replace('/[^0-9.]/', '', $price);
        if (!is_numeric($price) || $price < 0 || $price > 999999999.99) {
            Log::error('Invalid price: ' . $price);
            return null;
        }
        return (float)$price;
    }
}
