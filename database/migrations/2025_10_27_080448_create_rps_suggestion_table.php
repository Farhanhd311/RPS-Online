<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_suggestion', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('suggestion_id');
            $table->unsignedBigInteger('rps_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->text('saran');
            $table->enum('status', ['pending', 'reviewed', 'approved']);
            $table->dateTime('created_at');

            // Relasi ke tabel rps dan users
            $table->foreign('rps_id')->references('rps_id')->on('rps')->onDelete('cascade');
            $table->foreign('mahasiswa_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_suggestion');
    }
};
