<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateProductChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunk;

    /**
     * Create a new job instance.
     *
     * @param array $chunk
     * @return void
     */
    public function __construct(array $chunk)
    {
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $maxRetries = 5;
        $attempt = 0;
        $delaySeconds = 6;
        Log::info("ATTEMP ".$attempt . " maxRetries ".$maxRetries);
        while ($attempt < $maxRetries) {
            Log::info("Product updating started on chunk...");
            try {
                DB::beginTransaction();
                $data = [];

                foreach ($this->chunk as $record) {
                    $productNumber = $record['Product number'];
                    $price = $this->sanitizePrice($record['Price']);
                    $inventory = $record['Inventory'];
                    $stockStatus = $record['Stock status [yes or expected delivery time]'];

                    // Fetch the existing product to get its profit_amount and dcs_last_price
                    $existingProduct = DB::table('products')
                        ->where('product_number', $productNumber)
                        ->first();

                    if ($existingProduct) {
                        $profitAmount = $existingProduct->profit_amount;
                        $dcsLastPrice = $existingProduct->dcs_last_price;

                        // Update the last price if it has changed
                        if ($dcsLastPrice != $price) {
                            $dcsLastPrice = $price;
                        }

                        // Calculate the sale price
                        $salePrice = $price + $profitAmount;

                        // Prepare the data for the upsert
                        $data[] = [
                            'product_number' => $productNumber,
                            'price' => $price,
                            'inventory' => $inventory,
                            'stock_status' => $stockStatus,
                            'profit_amount' => $profitAmount,
                            'sale_price' => $salePrice,
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


                    // Update the products table from the temporary table
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
                        p.updated_at = t.updated_at
                ');

                    // Update the business_product_prices table
                    DB::statement('
                    UPDATE business_product_prices bpp
                    JOIN products p ON bpp.product_number = p.product_number
                    JOIN temp_products t ON p.product_number = t.product_number
                    SET
                        bpp.price = p.sale_price,

                        bpp.sale_price = CASE
                            WHEN bpp.profit_type = "PERCENTAGE" THEN p.sale_price * (1 + (bpp.profit / 100))
                            WHEN bpp.profit_type = "FLAT" THEN p.sale_price + bpp.profit
                            ELSE p.sale_price
                        END,
                        bpp.previous_price = CASE
                            WHEN bpp.price != t.sale_price THEN bpp.price
                            ELSE bpp.previous_price
                        END,
                        bpp.updated_at = NOW()
                ');
                }

                DB::commit();
                break;  // Exit loop if successful
            } catch (\Exception $e) {
                DB::rollBack();

                // Check if it's a deadlock error and retry
                if (str_contains($e->getMessage(), 'Deadlock')) {
                    $attempt++;
                    Log::warning("Deadlock detected, retrying transaction... Attempt: $attempt");
                    sleep($delaySeconds);  // Add a short delay before retrying
                } else {
                    Log::error('UpdateProductChunk failed: ' . $e->getMessage());
                    throw $e;  // Rethrow the exception if it's not a deadlock
                }
            }
        }

        // If the maximum number of retries was reached, log an error
        if ($attempt === $maxRetries) {
            Log::error('UpdateProductChunk failed after maximum retries due to deadlock.');
        }
    }


    /**
     * Sanitize the price value.
     *
     * @param string $price
     * @return float|null
     */
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
