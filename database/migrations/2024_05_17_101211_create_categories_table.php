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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name',191);
            $table->string('slug',255);
            $table->string('thumbnail',255)->nullable();
            $table->string('original',255)->nullable();
            $table->boolean('is_mega')->default(false);
            $table->boolean('active_status')->default(true);
            $table->enum('vat_type', ['VAT_FREE', 'VAT', 'PARTIAL_VAT'])->default("VAT");
            $table->timestamps();
            $table->softDeletes();
        });
        \App\Models\Category::insert([
            'name' => 'Uncategorized',
            'slug' => 'uncategorized',
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
        Schema::dropIfExists('categories');
    }
};
