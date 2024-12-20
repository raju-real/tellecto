<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UpdateProductSlug implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 3600; // Increase the timeout to 1 hour

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
    public function handle(): void
    {
        try {
            Log::info("Product slug updating job started...");
            // Fetch the inserted products without a slug
            $products = Product::whereNull('slug')->get();

            foreach ($products as $product) {
                // Generate a unique slug
                $slug = Str::slug($product->product_name . '-' . $product->id);
                // Update the product with the generated slug
                $product->update(['slug' => $slug]);
            }
            Log::info("Product slug updating job done...");
        } catch (\Exception $exception) {
            Log::error('Product slug updating error: ' . $exception->getMessage());
        }
    }
}
