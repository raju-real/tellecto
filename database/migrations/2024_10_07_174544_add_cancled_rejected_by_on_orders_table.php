<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('canceled_by')->nullable()->after('confirmed_by')->comment('stores user_id When canceled by business');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_by')->comment('stores user_id When rejected by tellecto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('canceled_by');
            $table->dropColumn('rejected_by');
        });
    }
};
