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
        Schema::create('delivery_charges', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->double('dcs_charge');
            $table->string('delivery_type');
            $table->string('delivery_dcs');
            $table->double('delivery_charge');
            $table->double('vat_rate');
            $table->decimal('max_weight')->default(0);
            $table->integer('parcel_shop_status')->default(0);
            $table->text('description')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        \App\Models\DeliveryCharge::insert([
            'code' => '459874',
            'delivery_type' => 'PostNord - Business package',
            'delivery_dcs' => 'PostDanmarkEx',
            'dcs_charge' => 79,
            'delivery_charge' => 99,
            'vat_rate' => 25,
            'parcel_shop_status' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        \App\Models\DeliveryCharge::insert([
            'code' => '459875',
            'delivery_type' => 'PostNord - Parcel shop',
            'delivery_dcs' => 'PostDanmarkMaxi',
            'dcs_charge' => 69,
            'delivery_charge' => 199,
            'vat_rate' => 25,
            'parcel_shop_status' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        \App\Models\DeliveryCharge::insert([
            'code' => '459876',
            'delivery_type' => 'PostNord - Locker',
            'delivery_dcs' => 'PostDanmarkPrivatEx',
            'dcs_charge' => 69,
            'delivery_charge' => 149,
            'vat_rate' => 25,
            'parcel_shop_status' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_charges');
    }
};
