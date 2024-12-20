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
        Schema::table('users', function (Blueprint $table) {
            $table->string('responsible_by')->nullable()->after('is_active');
            $table->enum('user_status', ['accept', 'pending', 'suspended'])->default('pending')->after('responsible_by');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('responsible_by');
            $table->dropColumn('user_status');
        });
    }
};
