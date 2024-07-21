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
        Schema::create('daily_upload_images', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('client');
            $table->string('state');
            $table->string('branch');
            $table->string('address')->nullable();
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_upload_images');
    }
};
