<?php

namespace App\Console\Commands;

use App\Models\BusinessProductPrice;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetBulkData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set-bulk-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit
        $this->setBusinessProductPrice();
    }

    protected function setBusinessProductPrice()
    {
        $this->info("Setting business product price");
        // Update products table
        Product::query()
            ->update([
                'profit_amount' => DB::raw('ROUND(CASE WHEN profit_type = "FLAT" THEN profit WHEN profit_type = "PERCENTAGE" THEN (price * profit) / 100 ELSE 0 END)'),
                'sale_price' => DB::raw('ROUND(CASE WHEN profit_type = "FLAT" THEN price + profit WHEN profit_type = "PERCENTAGE" THEN price + ((price * profit) / 100) ELSE price END)'),
                'updated_at' => now(),
            ]);
        // Or
        DB::table('products')
            ->update([
                'profit_amount' => DB::raw('ROUND(CASE WHEN profit_type = "FLAT" THEN profit WHEN profit_type = "PERCENTAGE" THEN (price * profit) / 100 ELSE 0 END)'),
                'sale_price' => DB::raw('ROUND(CASE WHEN profit_type = "FLAT" THEN price + profit WHEN profit_type = "PERCENTAGE" THEN price + ((price * profit) / 100) ELSE price END)'),
                'updated_at' => now(),
            ]);
        // Update business products prices table
        BusinessProductPrice::query()
            ->join('products', 'business_product_prices.product_number', '=', 'products.product_number')
            ->update([
                'price' => DB::raw('ROUND(products.sale_price)'),
                'sale_price' => DB::raw('ROUND(CASE WHEN profit_type = "PERCENTAGE" THEN (products.sale_price * profit / 100) WHEN profit_type = "FLAT" THEN products.sale_price + profit ELSE products.sale_price END)'),
                'previous_price' => DB::raw('ROUND(CASE WHEN price != products.sale_price THEN price ELSE previous_price END)'),
                'profit_amount' => DB::raw('ROUND(CASE WHEN profit_type = "PERCENTAGE" THEN (products.sale_price * profit / 100) WHEN profit_type = "FLAT" THEN profit ELSE 0 END)'),
                'updated_at' => now(),
                'last_updated_at' => now(),
            ]);
        // Or
        DB::table('business_product_prices as bpp')
            ->join('products as p', 'bpp.product_number', '=', 'p.product_number')
            ->update([
                'bpp.price' => DB::raw('ROUND(p.sale_price)'),
                'bpp.sale_price' => DB::raw('ROUND(CASE WHEN bpp.profit_type = "PERCENTAGE" THEN (p.sale_price * bpp.profit / 100) WHEN bpp.profit_type = "FLAT" THEN p.sale_price + bpp.profit ELSE p.sale_price END)'),
                'bpp.previous_price' => DB::raw('ROUND(CASE WHEN bpp.price != p.sale_price THEN bpp.price ELSE bpp.previous_price END)'),
                'bpp.profit_amount' => DB::raw('ROUND(CASE WHEN bpp.profit_type = "PERCENTAGE" THEN (p.sale_price * bpp.profit / 100) WHEN bpp.profit_type = "FLAT" THEN bpp.profit ELSE 0 END)'),
                'bpp.updated_at' => now(),
                'bpp.last_updated_at' => now(),
            ]);
        $this->info("Product inserted successfully!");
    }
}
