<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            // Skip if columns already exist
            if (!Schema::hasColumn('rps', 'sks')) {
                $table->integer('sks')->nullable()->after('nama_matakuliah');
            }
            if (!Schema::hasColumn('rps', 'semester')) {
                $table->integer('semester')->nullable()->after('sks');
            }
            if (!Schema::hasColumn('rps', 'bahan_kajian')) {
                $table->text('bahan_kajian')->nullable()->after('semester');
            }
            if (!Schema::hasColumn('rps', 'tanggal_penyusunan')) {
                $table->date('tanggal_penyusunan')->nullable()->after('bahan_kajian');
            }
            if (!Schema::hasColumn('rps', 'dosen_pengembang')) {
                $table->string('dosen_pengembang')->nullable()->after('tanggal_penyusunan');
            }
            if (!Schema::hasColumn('rps', 'dosen_pengembang_id')) {
                $table->unsignedBigInteger('dosen_pengembang_id')->nullable()->after('dosen_pengembang');
            }
            if (!Schema::hasColumn('rps', 'koordinasi_bk')) {
                $table->string('koordinasi_bk')->nullable()->after('dosen_pengembang_id');
            }
            if (!Schema::hasColumn('rps', 'kaprodi')) {
                $table->string('kaprodi')->nullable()->after('koordinasi_bk');
            }
            if (!Schema::hasColumn('rps', 'cpl_prodi')) {
                $table->json('cpl_prodi')->nullable()->after('kaprodi');
            }
            if (!Schema::hasColumn('rps', 'indikator')) {
                $table->json('indikator')->nullable()->after('cpl_prodi');
            }
            if (!Schema::hasColumn('rps', 'cpmk')) {
                $table->json('cpmk')->nullable()->after('indikator');
            }
            if (!Schema::hasColumn('rps', 'korelasi')) {
                $table->json('korelasi')->nullable()->after('cpmk');
            }
            if (!Schema::hasColumn('rps', 'asesmen')) {
                $table->json('asesmen')->nullable()->after('korelasi');
            }
            if (!Schema::hasColumn('rps', 'deskripsi_mk')) {
                $table->text('deskripsi_mk')->nullable()->after('asesmen');
            }
            if (!Schema::hasColumn('rps', 'materi_pembelajaran')) {
                $table->json('materi_pembelajaran')->nullable()->after('deskripsi_mk');
            }
            if (!Schema::hasColumn('rps', 'pustaka_utama')) {
                $table->json('pustaka_utama')->nullable()->after('materi_pembelajaran');
            }
            if (!Schema::hasColumn('rps', 'pustaka_pendukung')) {
                $table->json('pustaka_pendukung')->nullable()->after('pustaka_utama');
            }
            if (!Schema::hasColumn('rps', 'perangkat_lunak')) {
                $table->json('perangkat_lunak')->nullable()->after('pustaka_pendukung');
            }
            if (!Schema::hasColumn('rps', 'perangkat_keras')) {
                $table->json('perangkat_keras')->nullable()->after('perangkat_lunak');
            }
            if (!Schema::hasColumn('rps', 'dosen_pengampu')) {
                $table->json('dosen_pengampu')->nullable()->after('perangkat_keras');
            }
            if (!Schema::hasColumn('rps', 'mk_prasyarat')) {
                $table->json('mk_prasyarat')->nullable()->after('dosen_pengampu');
            }
        });

        // Make template_id nullable for direct form submissions
        Schema::table('rps', function (Blueprint $table) {
            if (Schema::hasColumn('rps', 'template_id')) {
                $table->unsignedBigInteger('template_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            $columnsToRemove = [
                'sks', 'semester', 'bahan_kajian', 'tanggal_penyusunan',
                'dosen_pengembang', 'dosen_pengembang_id', 'koordinasi_bk', 'kaprodi',
                'cpl_prodi', 'indikator', 'cpmk', 'korelasi', 'asesmen',
                'deskripsi_mk', 'materi_pembelajaran', 'pustaka_utama', 'pustaka_pendukung',
                'perangkat_lunak', 'perangkat_keras', 'dosen_pengampu', 'mk_prasyarat'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('rps', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
