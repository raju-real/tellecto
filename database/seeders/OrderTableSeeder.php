<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::insert([
            'business_id' => 3,
            'agent_id' => 2,
            'order_no' => Order::getOrderNumber(),
            'order_date' => today(),
            'customer_name' => 'Mohammed Mehdi Karam',
            'customer_mobile' => '+46762164706',
            'customer_phone' => '+46768798877',
            'customer_email' => 'dev@tellecto.se',
            'from_country' => "SE",
            'from_city' => "Malmö",
            'from_zip' => "21466",
            'from_address' => "Lagmansgatan 2B",
            'delivery_country' =>  "SE",
            'delivery_zip' =>  "21583",
            'delivery_city' =>  "Malmö",
            'delivery_type' =>  "PostDanmarkEx",
            'delivery_address' =>  "PostDanmarkEx",
            'total_amount' => \App\Models\Product::where('product_number', 1001101896)->first()->price,
            'freight_cost' => 0.00,
            'vat_percentage' => 0.00,
            'total_vat' => 0.00,
            'total_excluding_vat' => \App\Models\Product::where('product_number', 1001101896)->first()->price,
            'total_including_vat' => \App\Models\Product::where('product_number', 1001101896)->first()->price,
            'comment' => "Comment",
            'order_status' => 0,
            'created_by' => 2,
            'updated_by' => 2,
        ]);

        OrderItem::insert([
            'order_id' => 1,
            'product_id' => \App\Models\Product::where('product_number', 1001101896)->first()->id,
            'quantity' => 1,
            'sales_price' => \App\Models\Product::where('product_number', 1001101896)->first()->price,
            'unit' => "Pcs",
            'order_price' => \App\Models\Product::where('product_number', 1001101896)->first()->price,
            'dcs_last_price' => \App\Models\Product::where('product_number', 1001101896)->first()->dcs_last_price,
            'tellecto_last_price' => \App\Models\Product::where('product_number', 1001101896)->first()->tellecto_last_price,
            'business_last_price' => \App\Models\Product::where('product_number', 1001101896)->first()->tellecto_last_price,
        ]);
    }
}
