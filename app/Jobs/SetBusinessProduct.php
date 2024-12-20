<?php

namespace App\Jobs;

use App\Events\SaveNotification;
use App\Models\BusinessProductPrice;
use App\Models\Notification;
use App\Models\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetBusinessProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // Increase the timeout to 1 hour
    protected $businessId = null;
    protected $insertedCount = 0;

    /**
     * Create a new job instance.
     */
    public function __construct($businessId)
    {
        $this->businessId = $businessId;
        ini_set('memory_limit', '2048M'); // Increase memory limit if needed
        set_time_limit(0); // Remove the execution time limit
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Business product adding job started at ' . now());
        try {
            $businessId = $this->businessId;
            //Business::fetchProduct($businessId);
            Product::active()
                ->whereNotIn('id', function ($query) use ($businessId) {
                    $query->where('business_id', $businessId)->select('product_id')->from('business_product_prices');
                })
                ->chunkById(5000, function ($products) use ($businessId) { // Use chunkById for better handling
                    try {
                        $insertData = []; // Array to collect data for batch insert
                        foreach ($products as $product) {
                            $price = $product->sale_price;
                            // Prepare data for each row
                            $insertData[] = [
                                'business_id' => $businessId,
                                'product_id' => $product->id,
                                'product_number' => $product->product_number,
                                'previous_price' => $price,
                                'price' => $price,
                                'profit_type' => 'FLAT',
                                'profit' => 0.00,
                                'profit_amount' => 0.00,
                                'sale_price' => $price,
                                'created_at' => now()
                            ];
                            $this->insertedCount++;
                            // Insert in chunks of 1000 rows for better memory management
                            if (count($insertData) >= 1000) {
                                BusinessProductPrice::insert($insertData);
                                $insertData = []; // Reset array after insert
                            }
                        }
                        // Insert remaining data if any
                        if (!empty($insertData)) {
                            BusinessProductPrice::insert($insertData);
                        }
                    } catch (\Exception $e) {
                        Log::error("Error: " . $e->getMessage());
                        throw $e;
                    }
                });

            Log::info("Business product insertion job done at " . now());
            $notification = new Notification();
            $notification->notification_for = 'business';
            $notification->event_type = 'business-owner-product-set';
            $notification->user_id = $businessId;
            $notification->message = "Total " . $this->insertedCount . " products has been added on your business.";
            $notification->seen_status = false;
            $notification->save();
            //Log::info("Business product insertion result event with: $notification");
            event(new SaveNotification($notification));
        } catch (Exception $e) {
            Log::error("Error: " . $e->getMessage());
        }
    }
}
