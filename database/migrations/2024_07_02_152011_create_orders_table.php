<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            // Order Information
            $table->string('tellecto_order_no')->unique();
            $table->string('dcs_order_no')->unique()->nullable();
            $table->string('dcs_online_order_no')->unique()->nullable();
            $table->string('invoice_no')->nullable();
            $table->longText('tracking_number')->nullable();
            $table->string('carrier',100)->nullable();
            $table->date('order_date')->nullable();
            // Customer Information (Agent)
            $table->string('customer_name')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('from_country')->nullable();
            $table->string('from_city')->nullable();
            $table->string('from_zip')->nullable();
            $table->text('from_address')->nullable();
            // Delivery information
            $table->string('delivery_name')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_zip')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('requsition')->nullable();
            $table->string('delivery_country')->nullable();
            $table->string('delivery_mobile')->nullable();
            $table->string('delivery_street')->nullable();
            $table->integer('delivery_id')->nullable();
            $table->string('delivery_code')->nullable();
            $table->string('delivery_type')->nullable();
            $table->double('dcs_delivery_charge', 10, 2)->default(0.00);
            $table->double('delivery_charge', 10, 2)->default(0.00);
            $table->double('delivery_charge_vat_rate', 10, 2)->default(0.00);
            $table->double('delivery_charge_with_vat', 10, 2)->default(0.00);
            // Admin calculation
            $table->double('total_excluding_vat_admin', 10, 2)->default(0.00);
            $table->double('total_vat_admin', 7, 2)->default(0.00);
            $table->double('total_including_vat_admin', 10, 2)->default(0.00);
            $table->double('total_order_amount_admin', 10, 2)->default(0.00);
            // Business calculation
            $table->double('total_excluding_vat_business', 10, 2)->default(0.00);
            $table->double('total_vat_business', 7, 2)->default(0.00);
            $table->double('total_including_vat_business', 10, 2)->default(0.00);
            $table->double('total_order_amount_business', 10, 2)->default(0.00);
            // Agent calculation
            $table->double('total_excluding_vat_agent', 10, 2)->default(0.00);
            $table->double('total_vat_agent', 7, 2)->default(0.00);
            $table->double('total_including_vat_agent', 10, 2)->default(0.00);
            $table->double('total_order_amount_agent', 10, 2)->default(0.00);
            // Sales total for tellecto and business
            $table->double('total_sales_amount_admin', 10, 2)->default(0.00);
            $table->double('total_sales_amount_business', 10, 2)->default(0.00);
            // Payment method
            $table->string('payment_method')->nullable();
            $table->string('promotion_code')->nullable();
            $table->text('comment')->nullable();
            $table->tinyInteger('order_status')->comment('0 => Pending,1 => Confirmed By Business,2 => Canceled by Business, 3 => Confirmed By Tellecto, 4 => Canceled By Tellecto, 5=> Processing, 6 => Shipped, 7 => Delivered');
            $table->unsignedBigInteger('confirmed_by')->nullable()->comment('stores user_id When confirmed by business');
            $table->unsignedBigInteger('approved_by')->nullable()->comment('stores user_id When confirmed by tellecto admin');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
