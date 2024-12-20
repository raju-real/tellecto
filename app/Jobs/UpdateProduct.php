<?php

namespace App\Jobs;

use App\Events\SaveNotification;
use App\Models\Notification;
use App\Models\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class UpdateProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $updatedCount = 0;
    protected $skippedCount = 0;
    public $timeout = 18000; // Increase the timeout to 5 hour

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Fetch the CSV data from the API with a longer timeout
            $response = Http::timeout(300)->get(env("DCS_UPDATE_PRODUCT")); // Replace with your API URL

            if ($response->successful()) {
                Log::info("Tellecto Product updating job started...");

                $csvData = $response->body();

                // Create a CSV reader from the response body
                $csvReader = Reader::createFromString($csvData);
                $csvReader->setDelimiter(';'); // Use the semicolon as the delimiter
                $csvReader->setEnclosure('"'); // Handle fields enclosed with double quotes

                // Get the header row
                $header = $csvReader->fetchOne();
                $expectedColumnCount = count($header);

                // Fetch all current product numbers
                //$currentProducts = Product::pluck('product_number')->all();
                $csvProducts = [];

                // Process rows
                $csvReader->setHeaderOffset(0);
                foreach ($csvReader->getRecords() as $record) {
                    $row = array_values($record);
                    if (count($row) < $expectedColumnCount) {
                        $row = array_pad($row, $expectedColumnCount, '');
                    }
                    if (count($row) > $expectedColumnCount) {
                        $row = array_slice($row, 0, $expectedColumnCount);
                    }

                    $productNumber = $row[0];
                    $inventory = $row[1];
                    $price = $row[2]; // Adjust the index based on your CSV structure
                    $stockStatus = $row[3]; // Adjust the index based on your CSV structure

                    // Fetch the existing product
                    $product = Product::where('product_number', $productNumber)->first();

                    if ($product) {
                        // Update previous price
                        if($price === $product->price) {
                            $dcs_last_price = $product->dcs_last_price;
                        } else {
                            $dcs_last_price = $product->price;
                        }

                        // Calculate the profit and sale price
                        $profitType = $product->profit_type;
                        $profit = $product->profit;
                        $calculatedValues = Product::calculateProfit($price, $profitType, $profit);

                        // Update product data
                        $product->update([
                            'inventory' => $inventory,
                            'dcs_last_price' => $dcs_last_price,
                            'tellecto_last_price' => $product->sale_price,
                            'price' => $price,
                            'stock_status' => $stockStatus,
                            'profit_amount' => $calculatedValues['profit_amount'],
                            'sale_price' => $calculatedValues['sale_price'],
                            'updated_at' => now()
                        ]);

                        $this->updatedCount++;
                    } else {
                        $this->skippedCount++;
                    }

                    // Collect CSV product numbers
                    $csvProducts[] = $productNumber;
                }

                // Mark products as inactive if they are not in the CSV data
                //Product::whereNotIn('product_number', $csvProducts)->update(['active_status' => 0]);
                foreach ($csvProducts as $product_number) {
                    Product::where('product_number',$product_number)->update(['active_status' => 0]);
                }

                Log::info("Product updating job done...");
                $notification = new Notification();
                $notification->notification_for = 'admin';
                $notification->event_type = 'update-product-price';
                $notification->user_id = null;
                $notification->message = "Total " . $this->updatedCount . " products have been updated successfully and " . $this->skippedCount . " were skipped and ".count($csvProducts) . " inactive";
                $notification->seen_status = false;
                $notification->save();

                Log::info("Product updating job result event with: $notification");
                event(new SaveNotification($notification));
                UpdateBusinessProductPrice::dispatch();
            } else {
                // Handle the error
                Log::error('HTTP request failed with status: ' . $response->status());
            }
        } catch (Exception $e) {
            Log::error('Error processing CSV data: ' . $e->getMessage());
        }
    }
}
