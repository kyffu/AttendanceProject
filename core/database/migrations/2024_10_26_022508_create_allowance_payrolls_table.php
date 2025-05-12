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
        Schema::create('allowance_payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('period',10);
            $table->unsignedBigInteger('allow_id');
            $table->foreign('allow_id')->references('id')->on('allowances')->onDelete('cascade');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance_payrolls');
    }
};
