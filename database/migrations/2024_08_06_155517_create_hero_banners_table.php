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
        Schema::create('hero_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title',191)->nullable();
            $table->string('link',255)->nullable();
            $table->string('image',255)->nullable();
            $table->string('banner_type')->default('hero-banner')->comment('sliders,banner_top_right,banner_page_middle,banner_promo');
            $table->integer('order_no')->default(1);
            $table->boolean('active_status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_banners');
    }
};
