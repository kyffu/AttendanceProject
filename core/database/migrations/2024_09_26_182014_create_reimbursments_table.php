<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reimbursments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->double('amount', 10, 2);
            $table->string('description');
            $table->date('reimbursement_date');
            $table->string('evidence_photo');
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursments');
    }
};
