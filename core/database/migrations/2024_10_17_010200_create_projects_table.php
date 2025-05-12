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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('desc');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('foreman_id');
            $table->foreign('foreman_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('status')->comment('0: Terdaftar, 1: Terkunci, 2: Selesai, 3: Tunggu Validasi, 4: Disetujui, 5: Ditolak')->default(0);
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
        Schema::dropIfExists('projects');
    }
};
