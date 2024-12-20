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
        Schema::create('order_parcel_shops', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('service_point_id')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('city')->nullable();
            $table->string('street_name')->nullable();
            $table->string('street_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_parcel_shops');
    }
};
