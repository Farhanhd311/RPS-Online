<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_review', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('review_id');
            $table->unsignedBigInteger('rps_id');
            $table->unsignedBigInteger('reviewer_id');
            $table->text('komentar');
            $table->enum('action', ['approve', 'reject', 'revision']);
            $table->dateTime('created_at');

            // Relasi ke tabel rps dan users
            $table->foreign('rps_id')->references('rps_id')->on('rps')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_review');
    }
};
