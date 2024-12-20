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
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('employee_id', 50)->nullable();
            $table->dateTime('joining_date', 6)->nullable();
            $table->string('company_name', 191)->nullable();
            $table->string('org_no', 50)->nullable();
            $table->string('vat_no', 50)->nullable();
            $table->string('contact_person', 50)->nullable();
            $table->enum('business_type', ['B2B', 'B2C', 'TELCO', 'OTHERS'])->nullable();
            $table->string('website_url', 1000)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('company_email', 50)->nullable();
            $table->string('logo')->nullable();
            $table->text('street')->nullable();
            $table->text('city')->nullable();
            $table->string('zip_code', 5)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_information');
    }
};
