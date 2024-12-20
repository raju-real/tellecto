<?php

namespace App\Jobs;

use App\Events\SaveNotification;
use App\Imports\ProductPriceImport;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class BulkProductUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $updated_by;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     */
    public function __construct($filePath,$updated_by)
    {
        $this->filePath = $filePath;
        $this->updated_by = $updated_by;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit
        Log::info("Bulk product update job started...");

        $import = new ProductPriceImport();
        Excel::import($import, $this->filePath);
        $product_prices = $import->getData();

        // Collect updates
        $updates = [];
        foreach ($product_prices as $data) {
            $profit = $data->sale_price - $data->price;
            $updates[] = [
                'product_number' => $data->product_number,
                'profit_type' => "FLAT",
                'profit' => $profit,
                'profit_amount' => $profit,
                'sale_price' => $data->sale_price,
                'updated_by' => $this->updated_by,
                'updated_at' => now()
            ];
        }

        // Perform batch update
        foreach (array_chunk($updates, 1000) as $chunk) { // Process in chunks
            foreach ($chunk as $update) {
                Product::where('product_number', $update['product_number'])
                    ->update(Arr::except($update, ['product_number']));
            }
        }

        // Update the business products table
        foreach ($updates as $update) {
            $product = Product::where('product_number', $update['product_number'])->first();
            if ($product) {
                updateBusinessByRawQuery($product->id);
            }
        }

        $notification = new Notification();
        $notification->notification_for = 'admin';
        $notification->event_type = 'bulk-product-update';
        $notification->user_id = null;
        $notification->message = "Bulk product update job ended at ".now();
        $notification->seen_status = false;
        $notification->save();

        event(new SaveNotification($notification));
        Log::info("Bulk product update job end...");
    }
}
