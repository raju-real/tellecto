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


        Schema::create('permission_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id')->nullable();
            $table->foreign('permission_id')->references('id')->on('permissions')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('action_id')->nullable();
            $table->foreign('action_id')->references('id')->on('actions')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->string('path')->nullable();
            $table->string('method')->nullable();
            $table->string('tooltip')->nullable();
            $this->common_column($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_actions');
    }
};
