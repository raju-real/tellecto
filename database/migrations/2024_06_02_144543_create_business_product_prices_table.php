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
        Schema::create('business_product_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id');
            $table->integer('product_id');
            $table->double('previous_price',10,2)->default(0.00);
            $table->double('price',10,2)->default(0.00);
            $table->string('profit_type',20)->default('FLAT');
            $table->double('profit',10,2)->default(0.00);
            $table->double('sale_price',10,2)->default(0.00);
            $table->double('profit_amount',10,2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_product_prices');
    }
};
