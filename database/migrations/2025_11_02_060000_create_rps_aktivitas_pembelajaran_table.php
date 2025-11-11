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
        Schema::create('rps_aktivitas_pembelajaran', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('aktivitas_id');
            $table->unsignedBigInteger('rps_id');
            $table->string('minggu_ke', 50); // e.g., "1-2", "3-4", "UTS", "UAS"
            $table->string('cpmk_kode', 20); // e.g., "CPMK-01", "CPMK-02"
            $table->text('indikator_penilaian')->nullable();
            $table->string('bentuk_penilaian_jenis')->nullable(); // e.g., "Quiz", "Proposal", "UTS"
            $table->decimal('bentuk_penilaian_bobot', 5, 2)->nullable(); // percentage
            $table->text('aktivitas_sinkron_luring')->nullable(); // Synchronous offline
            $table->text('aktivitas_sinkron_daring')->nullable(); // Synchronous online
            $table->text('aktivitas_asinkron_mandiri')->nullable(); // Asynchronous independent
            $table->text('aktivitas_asinkron_kolaboratif')->nullable(); // Asynchronous collaborative
            $table->text('media')->nullable(); // Media/tools used
            $table->text('materi_pembelajaran')->nullable();
            $table->text('referensi')->nullable();
            $table->integer('urutan')->default(0); // For ordering
            $table->timestamps();

            // Relasi ke tabel rps
            $table->foreign('rps_id')->references('rps_id')->on('rps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rps_aktivitas_pembelajaran');
    }
};

