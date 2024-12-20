<?php

namespace App\Jobs;

use App\Events\SaveNotification;
use App\Models\Notification;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetBulkProductProfit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $profit_type;
    protected $profit;
    protected $updatedCount = 0;
    public $timeout = 3600; // 1-hour timeout

    public function __construct($data)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit
        $this->profit_type = $data->profit_type;
        $this->profit = $data->profit;
    }

    public function handle(): void
    {
        Log::info("Bulk product profit job started at " . now());
        try {
//            Product::chunk(1000, function ($products) {
//                $dataToUpdate = [];
//                foreach ($products as $product) {
//                    $calculatedValues = Product::calculateProfit($product->price, $this->profit_type, $this->profit);
//                    $dataToUpdate[$product->product_number] = [
//                        'profit_type' => $this->profit_type,
//                        'profit' => $this->profit,
//                        'profit_amount' => $calculatedValues['profit_amount'],
//                        'sale_price' => $calculatedValues['sale_price'],
//                        'updated_at' => now(),
//                    ];
//                }
//
//                // Bulk update for all products in the current chunk
//                foreach ($dataToUpdate as $productNumber => $values) {
//                    Product::where('product_number', $productNumber)->update($values);
//                    $this->updatedCount++;
//                }
//            });
            // update products profit and profit type
            DB::table('products')->update(['profit_type' => $this->profit_type, 'profit' => $this->profit]);
            // Update products table
            DB::table('products')
                ->update([
                    'sale_price' => DB::raw("
                        CASE
                            WHEN profit_type = 'PERCENTAGE' THEN price * (1 + (profit / 100))
                            WHEN profit_type = 'FLAT' THEN price + profit
                            ELSE price
                        END
                    "),
                    'profit_amount' => DB::raw("
                        CASE
                            WHEN profit_type = 'PERCENTAGE' THEN price * (profit / 100)
                            WHEN profit_type = 'FLAT' THEN profit
                            ELSE 0
                        END
                    "),
                    'updated_at' => Carbon::now(),
                ]);
            // Update the business_product_prices table
//            DB::table('business_product_prices as bpp')
//                ->join('products as p', 'bpp.product_number', '=', 'p.product_number')
//                ->update([
//                    'bpp.price' => DB::raw('p.sale_price'),
//                    'bpp.sale_price' => DB::raw("
//                                CASE
//                                    WHEN bpp.profit_type = 'PERCENTAGE' THEN p.sale_price * (1 + (bpp.profit / 100))
//                                    WHEN bpp.profit_type = 'FLAT' THEN p.sale_price + bpp.profit
//                                    ELSE p.sale_price
//                                END
//                            "),
//                    'bpp.previous_price' => DB::raw("
//                                CASE
//                                    WHEN bpp.price != p.sale_price THEN bpp.price
//                                    ELSE bpp.previous_price
//                                END
//                            "),
//                    'bpp.updated_at' => DB::raw('NOW()')
//                ]);
            // Notification handling after batch update
            Log::info("Bulk product profit set job end at " . now());
            $notification = new Notification();
            $notification->notification_for = 'admin';
            $notification->event_type = 'bulk-product-profit-set';
            $notification->user_id = null;
            $notification->message = "Total " . $this->updatedCount . " products' profit updated successfully.";
            $notification->seen_status = false;
            $notification->save();
            event(new SaveNotification($notification));
        } catch (\Exception $e) {
            $this->fail($e);
            Log::error('Error during bulk product profit update: ' . $e->getMessage());
        }
    }
}
