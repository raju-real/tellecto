<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        ini_set('memory_limit', '2048M'); // Increase memory limit if needed
        set_time_limit(0); // Remove the execution time limit
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
       // echo "Inserting products";
        try {
            // Path to the CSV file
            $filePath = storage_path('app/csv/tellecto_products.csv');

            // Open the CSV file
            if (($handle = fopen($filePath, 'r')) !== false) {
                // Read the header row
                $header = fgetcsv($handle);

                // Define an array to hold the data for bulk insert
                $data = [];

                // Set chunk size
                $chunkSize = 1000;

                // Start a database transaction
                DB::beginTransaction();

                // Read each row of the CSV file
                while (($row = fgetcsv($handle)) !== false) {
                    // Map the CSV data to the table columns
                    $data[] = [
                        'product_from' => 'tellecto',
                        'product_number' => $row[0],
                        'category_id' => Product::getCategoryID($row[1]),
                        'subcategory_id' => Product::getSubCategoryID($row[1], $row[2]),
                        'product_name' => $row[3],
                        'previous_price' => $row[4],
                        'price' => $row[4],
                        'profit_type' => 'FLAT',
                        'profit' => 0.00,
                        'sale_price' => $row[4],
                        'profit_amount' => 0.00,
                        'inventory' => $row[5],
                        'weight' => $row[6],
                        'model_name' => $row[7],
                        'brand_id' => Product::getBrandID($row[8]),
                        'delivery_time' => $row[9],
                        'return_date' => $row[10],
                        'quantity_ordered' => $row[11],
                        'ean_number' => $row[12],
                        'stock_status' => $row[13],
                        'active_status' => false,
                        'field_name_1' => $row[14],
                        'field_name_2' => $row[15],
                        'days_from_external_stock' => $row[16],
                        'reverse_charge' => $row[17],
                        'product_link' => $row[18],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Insert data in chunks to optimize performance and avoid memory issues
                    if (count($data) >= $chunkSize) {
                        Product::insert($data);
                        $data = []; // Reset the data array
                    }
                }

                // Insert any remaining data
                if (!empty($data)) {
                    Product::insert($data);
                }

                // Close the file
                fclose($handle);

                // Commit the transaction
                DB::commit();

                echo "CSV data imported successfully.";
            } else {
                echo "Unable to open the file.";
            }
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            echo 'Error: ' . $e->getMessage();
            Log::error('Error processing CSV data: ' . $e->getMessage());
        }
    }
}
