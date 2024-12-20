<?php

namespace App\Traits;

trait DatabaseMigrationTrait
{
    public function common_column($table, $status_column = true): void
    {

        if ($status_column) {
            $table->tinyInteger('status')->default(1)->comment('1 - Active, 0 - Inactive');
        }
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        $table->softDeletes();
        $table->timestamps();
    }
}
