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
        Schema::table('orders', function (Blueprint $table) {
            $table->longText('canceled_for')->nullable()->after('canceled_by')->comment('stores user_id When canceled for from business');
            $table->longText('rejected_for')->nullable()->after('rejected_by')->comment('stores user_id When rejected for from tellecto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('canceled_for');
            $table->dropColumn('rejected_for');
        });
    }
};
