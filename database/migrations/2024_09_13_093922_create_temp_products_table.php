<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempProductsTable extends Migration
{
    public function up()
    {
        Schema::create('temp_products', function (Blueprint $table) {
            $table->string('product_number')->primary();
            $table->float('price',15,2)->nullable();
            $table->integer('inventory')->nullable();
            $table->string('stock_status')->nullable();
            $table->decimal('profit_amount', 15, 2)->nullable();
            $table->decimal('dcs_last_price', 15, 2)->nullable();
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_products');
    }
}
