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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use League\Csv\Reader;

class SaveProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $insertedCount = 0;
    public $timeout = 3600;

    public function __construct()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
    }

    public function handle()
    {
        try {
            Log::info("Product inserting job started at " . now());
            $response = Http::timeout(300)->get(env('DCS_CREATE_PRODUCT'));

            if ($response->successful()) {
                $csvData = $response->body();
                $csvReader = Reader::createFromString($csvData);
                $csvReader->setDelimiter(';');
                $csvReader->setEnclosure('"');
                $header = $csvReader->fetchOne();
                $expectedColumnCount = count($header);

                $data = [];
                $chunkSize = 1000;
                $existingProductNumbers = Product::pluck('product_number')->toArray();
                $csvReader->setHeaderOffset(0);

                foreach ($csvReader->getRecords() as $record) {
                    $row = array_values($record);

                    if (count($row) < $expectedColumnCount) {
                        $row = array_pad($row, $expectedColumnCount, '');
                    }
                    if (count($row) > $expectedColumnCount) {
                        $row = array_slice($row, 0, $expectedColumnCount);
                    }

                    if (in_array($row[0], $existingProductNumbers)) {
                        continue;
                    }

                    // Set maximum length for product_name
                    $maxProductNameLength = 255;

                    $data[] = [
                        'product_from' => 'tellecto',
                        'product_number' => $row[0],
                        'category_id' => Product::getCategoryID($row[1]),
                        'subcategory_id' => Product::getSubCategoryID($row[1], $row[2]),
                        'product_name' => Str::limit($row[3], $maxProductNameLength, ''),
                        'source_product_name' => Str::limit($row[3], $maxProductNameLength, ''),
                        'slug' => Str::slug(Str::limit($row[3], $maxProductNameLength, '') . '-' . $row[0] . '-' . $row[12]),
                        'dcs_last_price' => $row[4],
                        'tellecto_last_price' => $row[4],
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
                        'source_active_status' => true,
                        'field_name_1' => $row[14],
                        'field_name_2' => $row[15],
                        'days_from_external_stock' => $row[16],
                        'reverse_charge' => $row[17],
                        'product_link' => $row[18],
                        'created_at' => now(),
                    ];

                    if (count($data) >= $chunkSize) {
                        Product::insert($data);
                        $this->insertedCount += count($data);
                        $data = [];
                    }
                }

                if (!empty($data)) {
                    Product::insert($data);
                    $this->insertedCount += count($data);
                }

                Log::info("Product insertion done at " . now());
                $notification = new Notification();
                $notification->notification_for = 'admin';
                $notification->event_type = 'fetch-products';
                $notification->user_id = null;
                $notification->message = "Total " . $this->insertedCount . " products have been inserted successfully on " . now();
                $notification->seen_status = false;
                $notification->save();

                Log::info("Product inserting job result event with: $notification");
                event(new SaveNotification($notification));
            } else {
                Log::error('Error on response: ' . $response->status());
            }
        } catch (Exception $e) {
            Log::error('Error processing CSV data: ' . $e->getMessage());
        }
    }
}
