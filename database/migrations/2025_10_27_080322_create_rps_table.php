<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('rps_id');
            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('template_id');
            $table->string('kode_matakuliah', 20);
            $table->string('nama_matakuliah');
            $table->text('template_content');
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'published']);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

            // Relasi ke tabel users dan rps_template
            $table->foreign('dosen_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('template_id')->references('template_id')->on('rps_template')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps');
    }
};
