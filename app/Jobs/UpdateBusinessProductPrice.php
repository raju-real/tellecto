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
use Illuminate\Support\Facades\Log;

class UpdateBusinessProductPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // Increase the timeout to 1 hour
    protected $businessId = null;
    protected $updateCount = 0;

    /**
     * Create a new job instance.
     */
    public function __construct($businessId = null)
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
        Log::info("Business product price updating job started at ".now());
        try {
            $query = BusinessProductPrice::query();

            if ($this->businessId) {
                $query->where('business_id', $this->businessId);
            }

            $query->chunk(1000, function ($products) {
                try {
                    foreach ($products as $product) {
//                        if ($product->active_status === 0) {
//                            BusinessProductPrice::whereIn('product_id',[$product->id])->delete();
//                        } else {
//                            $base_product = Product::find($product->product_id);
//                            $price = $base_product->sale_price;
//                            $profit_type = $product->profit_type;
//                            $profit = $product->profit;
//                            $calculatedValues = Product::calculateProfit($price, $profit_type, $profit);
//
//                            $product->previous_price = $product->sale_price;
//                            $product->price = $price;
//                            $product->profit_type = $profit_type;
//                            $product->profit = $profit;
//                            $product->profit_amount = $calculatedValues['profit_amount'];
//                            $product->sale_price = $calculatedValues['sale_price'];
//                            $product->updated_at = now();
//                            $product->save();
//                            $this->updateCount++;
//                        }
                        $base_product = Product::find($product->product_id);
                        $price = $base_product->sale_price;
                        $profit_type = $product->profit_type;
                        $profit = $product->profit;
                        $calculatedValues = Product::calculateProfit($price, $profit_type, $profit);

                        $product->previous_price = $product->sale_price;
                        $product->price = $price;
                        $product->profit_type = $profit_type;
                        $product->profit = $profit;
                        $product->profit_amount = $calculatedValues['profit_amount'];
                        $product->sale_price = $calculatedValues['sale_price'];
                        $product->updated_at = now();
                        $product->save();
                        $this->updateCount++;
                    }
                } catch (Exception $e) {
                    Log::error("Error: " . $e->getMessage());
                    throw $e;
                }
            });
            Log::info("Business product price updating job end at ".now());
            $notification = new Notification();
            $notification->notification_for = 'business';
            $notification->event_type = 'business-owner-product-update';
            $notification->user_id = $this->businessId;
            $notification->message = "Products has been updated successfully.";
            $notification->seen_status = false;
            $notification->save();
            //Log::info("Creating business product updating job result event with: $notification");
            event(new SaveNotification($notification));
        } catch (Exception $e) {
            Log::error("Error: " . $e->getMessage());
        }
    }
}
