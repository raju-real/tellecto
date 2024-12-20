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
        Schema::create('admin_menu_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('menu_id');
            $table->string('activity_name');
            $table->string('route_name');
            $table->string('is_dependant')->default("No");
            $table->string('auto_select')->default("No");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_menu_activities');
    }
};
