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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_from',50);
            $table->string('product_number',191)->nullable()->unique();
            $table->integer('category_id')->nullable();
            $table->integer('subcategory_id')->nullable();
            $table->string('product_name',255)->nullable();
            $table->string('slug', 700)->nullable()->unique();
            $table->double('dcs_last_price',10,2)->default(0.00);
            $table->double('tellecto_last_price',10,2)->default(0.00);
            $table->double('price',10,2)->default(0.00);
            $table->string('profit_type',20)->default('FLAT');
            $table->double('profit',10,2)->default(0.00);
            $table->double('sale_price',10,2)->default(0.00);
            $table->double('profit_amount',10,2)->default(0.00);
            $table->string('inventory')->default(0);
            $table->string('weight')->default(0);
            $table->string('model_name',255)->nullable();
            $table->integer('brand_id')->nullable();
            $table->string('delivery_time',191)->nullable();
            $table->string('return_date',100)->nullable();
            $table->string('quantity_ordered',100)->nullable();
            $table->string('ean_number',100)->nullable();
            $table->string('stock_status',20)->nullable();
            $table->boolean('active_status')->default(false);
            $table->string('field_name_1',100)->nullable();
            $table->string('field_name_2',100)->nullable();
            $table->string('days_from_external_stock',100)->nullable();
            $table->string('reverse_charge',100)->nullable();
            $table->longText('product_description')->nullable();
            $table->longText('product_specification')->nullable();
            $table->string('product_link',1000)->nullable();
            $table->string('storage',191)->nullable();
            $table->string('color',50)->nullable();
            $table->string('product_type',100)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
