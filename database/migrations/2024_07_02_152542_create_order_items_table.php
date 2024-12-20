<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            // Price
            $table->double('dcs_last_price', 10, 2);
            $table->double('tellecto_last_price', 10, 2);
            $table->double('business_last_price', 10, 2);
            // Quantity
            $table->unsignedInteger('quantity');
            $table->string('unit')->default('Pcs');
            $table->integer('color_id')->nullable();
            $table->integer('size_id')->nullable();
            // Item total
            $table->double('item_total_agent',10,2)->default(0.00);
            $table->double('item_total_business',10,2)->default(0.00);
            $table->double('item_total_admin',10,2)->default(0.00);
            // Vat Type
            $table->string('vat_type_agent')->nullable();
            $table->string('vat_type_business')->nullable();
            $table->string('vat_type_admin')->nullable();
            // Total Vat
            $table->double('total_vat_agent',8,2)->default(0.00);
            $table->double('total_vat_business',8,2)->default(0.00);
            $table->double('total_vat_admin',8,2)->default(0.00);
            // Order Price (Total)
            $table->double('total_price_agent', 10, 2);
            $table->double('total_price_business', 10, 2);
            $table->double('total_price_admin', 10, 2);
            // Tellecto & Business sales total
            $table->double('total_sales_price_admin', 10, 2)->default(0.00);
            $table->double('total_sales_price_business', 10, 2)->default(0.00);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
