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
        Schema::table('mata_kuliah', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_kuliah', 'kode_matakuliah')) {
                $table->string('kode_matakuliah', 20)->unique()->after('id');
            }
            if (!Schema::hasColumn('mata_kuliah', 'nama_matakuliah')) {
                $table->string('nama_matakuliah')->after('kode_matakuliah');
            }
            if (!Schema::hasColumn('mata_kuliah', 'sks')) {
                $table->integer('sks')->default(0)->after('nama_matakuliah');
            }
            if (!Schema::hasColumn('mata_kuliah', 'semester')) {
                $table->integer('semester')->after('sks');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            if (Schema::hasColumn('mata_kuliah', 'semester')) {
                $table->dropColumn('semester');
            }
            if (Schema::hasColumn('mata_kuliah', 'sks')) {
                $table->dropColumn('sks');
            }
            if (Schema::hasColumn('mata_kuliah', 'nama_matakuliah')) {
                $table->dropColumn('nama_matakuliah');
            }
            if (Schema::hasColumn('mata_kuliah', 'kode_matakuliah')) {
                $table->dropColumn('kode_matakuliah');
            }
        });
    }
};

