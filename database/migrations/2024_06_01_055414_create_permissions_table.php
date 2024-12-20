<?php

use App\Traits\DatabaseMigrationTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    use DatabaseMigrationTrait;

    public function up(): void
    {


        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('order_no')->nullable();
            $table->string('path')->nullable();
            $table->string('path_group')->nullable();
            $table->string('backend_path')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('permissions')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->enum('type', ['Side Bar', 'Tab'])->default('Side Bar');
            $this->common_column($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
