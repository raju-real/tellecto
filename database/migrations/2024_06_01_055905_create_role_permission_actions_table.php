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


        Schema::create('role_permission_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('permission_action_id')->nullable();
            $table->foreign('permission_action_id')->references('id')->on('permission_actions')
                ->nullOnDelete()->cascadeOnUpdate();
            $this->common_column($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission_actions');
    }
};
