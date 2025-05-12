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
        Schema::create('absents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('absent_id'); 
            $table->foreign('absent_id')->references('id')->on('absent_masters')->onDelete('cascade');
            $table->date('start_date'); 
            $table->date('end_date');
            $table->string('evidence_file')->nullable();
            $table->boolean('status')->default(false);
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('validated_by')->nullable(); 
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absents');
    }
};
