<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class UpdateProducts extends Command
{
    protected $signature = 'products:update';
    protected $description = 'Update products from an API CSV source';

    public $totalInactive = 0;

    public function handle()
    {
        $apiUrl = env("DCS_UPDATE_PRODUCT");

        try {
            Log::info("Product update started at " . now());

            // Fetch CSV data
            $response = Http::timeout(3600)->get($apiUrl);
            if (!$response->successful()) {
                $this->error("Failed to fetch data from API. Status: " . $response->status());
                Log::error('API request failed: ' . $response->status());
                return;
            }

            $csvData = Reader::createFromString($response->body());
            $csvData->setDelimiter(';')->setHeaderOffset(0);
            $records = $csvData->getRecords();
            // Process records in chunks
            foreach (array_chunk(iterator_to_array($records), 2000) as $chunk) {
                $this->processChunk($chunk);
            }
            // turn on Update source active status
            $this->updateSourceActiveStatus($records);

            //call this function for update business product prices
//            $this->updateBusinessProductTable();

            // Update product on algolia
            $environment = env('APP_ENV');
            if ($environment == 'production') {
                Artisan::call('scout:import');
            }
            Log::info("Product update completed at " . now());
        } catch (\Exception $e) {
            Log::error("Product update failed: " . $e->getMessage());
        }
    }

    private function processChunk(array $chunk): void
    {
        $productNumbers = array_column($chunk, 'Product number');
        $existingProducts = Product::whereIn('product_number', $productNumbers)
            ->get()
            ->keyBy('product_number');

        $data = [];
        foreach ($chunk as $record) {
            $productNumber = $record['Product number'];
            $price = $record['Price'];
            $inventory = $record['Inventory'];
            $stockStatus = $record['Stock status [yes or expected delivery time]'];

            // process data for update product
            if (isset($existingProducts[$productNumber])) {
                $existing = $existingProducts[$productNumber];
                $profit_amount = Product::calculateProfitForUpdateProduct($price, $existing->sale_price);
                $data[] = [
                    'product_number' => $productNumber,
                    'price' => $price,
                    'inventory' => $inventory,
                    'stock_status' => $stockStatus,
                    'profit_amount' => $profit_amount,
//                    'sale_price' => $calculatedValues['sale_price'],
                    'dcs_last_price' => $existing->price
                ];
            }
        }

        if (!empty($data)) {
            // Update product prices from process data
            DB::transaction(function () use ($data) {
                foreach ($data as $record) {

//                    $product = Product::where('product_number', $record['product_number'])->first();
//
//                    if ($product) {
//                        // Update the product's attributes
//                        $product->source_active_status = true;
//                        $product->price = $record['price'];
//                        $product->inventory = $record['inventory'];
//                        $product->stock_status = $record['stock_status'];
//                        $product->profit_amount = $record['profit_amount'];
//                        $product->sale_price = $record['sale_price'];
//                        $product->dcs_last_price = $record['dcs_last_price'];
//                        $product->last_updated_at = now();
//
//                        // Save the changes
//                        $product->save();
//                    }
                    Product::where('product_number', $record['product_number'])
                        ->update([
                            'source_active_status' => true,
                            'price' => $record['price'],
                            'inventory' => $record['inventory'],
                            'stock_status' => $record['stock_status'],
                            'profit_amount' => $record['profit_amount'],
                            'profit' => $record['profit_amount'],
                            //'sale_price' => $record['sale_price'],
                            'dcs_last_price' => $record['dcs_last_price'],
                            'last_updated_at' => now()
                        ]);
                }
            });


        }
    }

    private function updateSourceActiveStatus($records): void
    {
        $recordsArray = [];
        foreach ($records as $record) {
            $recordsArray[] = (object)$record;
        }
        $productNumbers = array_column($recordsArray, 'Product number');
        $activeProductNumbers = Product::where('active_status', 1)
            ->pluck('product_number')
            ->toArray();
        // Get the difference
        $differenceArray = array_diff($activeProductNumbers, $productNumbers);
        // Convert to collection for chunking
        $differenceCollection = collect($differenceArray);
        // Process in chunks
        $differenceCollection->chunk(100)->each(function ($chunk) {

//            Product::whereIn('product_number', $chunk)->get()->each(function ($product) {
//                $product->source_active_status = false;
//                $product->stock_status = "NO";
//                $product->inventory = 0;
//                $product->save();
//            });

            $total_updated = Product::whereIn('product_number', $chunk)
                ->update([
                    'source_active_status' => false,
                    'stock_status' => "NO",
                    'inventory' => 0,
                    'last_updated_at' => now(),
                ]);
            $this->totalInactive += $total_updated;

        });
        Log::info("Total " . $this->totalInactive . " products has been inactivated at " . now());
    }

    private function updateBusinessProductTable(): void
    {
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
                        bpp.previous_price = CASE
                            WHEN bpp.price != p.sale_price THEN bpp.price
                            ELSE bpp.previous_price
                        END,
                        bpp.profit_amount = CASE
                            WHEN bpp.profit_type = "PERCENTAGE" THEN (p.sale_price * bpp.profit / 100)
                            WHEN bpp.profit_type = "FLAT" THEN bpp.profit
                            ELSE 0
                        END,
                        bpp.updated_at = NOW(),
                        bpp.last_updated_at = NOW();
                ');

    }


}
