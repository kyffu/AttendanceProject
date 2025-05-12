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
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('date'); // Date of overtime
            $table->decimal('hours', 5, 2); // Overtime hours claimed
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of the claim
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('validated_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};
