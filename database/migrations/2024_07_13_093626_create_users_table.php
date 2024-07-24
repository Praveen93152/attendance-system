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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('employee_name');
            $table->string('mobile_no', 10)->unique();
            $table->json('branch_ids')->nullable(); 
            $table->string('role');
            $table->integer('max_pics')->default(5);
            $table->string('password')->default('$2y$12$Btz7xvxvzXBUGo5/o9CaVuSprkGh9ggoLxq0HrtoPGEEQI7F5u.um');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
