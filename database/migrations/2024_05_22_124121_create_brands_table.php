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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name',191);
            $table->string('slug',255);
            $table->string('thumbnail',255)->nullable();
            $table->string('original',255)->nullable();
            $table->boolean('active_status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        \App\Models\Brand::insert([
            'name' => 'Non brand',
            'slug' => 'non-brand',
            'thumbnail' => null,
            'original' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
