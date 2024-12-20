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
        Schema::table('business_product_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('product_number')->nullable()->after('id');
            $table->index('product_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_product_prices', function (Blueprint $table) {
            $table->dropIndex(['product_number']);
            $table->dropColumn('product_number');
        });
    }
};
