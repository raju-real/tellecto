<?php

namespace App\Console\Commands;

use App\Models\BusinessProductPrice;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SetBusinessProduct extends Command
{
    protected $signature = 'business:set-product';
    protected $description = 'Set business product prices';
    protected $insertedCount = 0;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $this->info('Starting the process on command...');
            Product::whereDoesntHave('businessProductPrices', function($query) {
                $query->where('business_id', auth()->user()->id);
            })->chunk(100, function ($products) {
                DB::beginTransaction();
                foreach ($products as $product) {
                    $price = $product->price;
                    $profit_type = $product->profit_type;
                    $profit = $product->profit;

                    $calculatedValues = Product::calculateProfit($price, $profit_type, $profit);

                    $row = new BusinessProductPrice();
                    $row->business_id = auth()->user()->id;
                    $row->product_number = $product->product_number;
                    $row->previous_price = $product->previous_price;
                    $row->price = $price;
                    $row->profit_type = $profit_type;
                    $row->profit = $profit;
                    $row->profit_amount = $calculatedValues['profit_amount'];
                    $row->sale_price = $calculatedValues['sale_price'];
                    $row->active_status = $product->active_status;
                    $row->updated_at = now();
                    $row->save();
                    $this->insertedCount++;
                }
                DB::commit();
            });

            $this->info("Process completed. {$this->insertedCount} products updated.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing products: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
