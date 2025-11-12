<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            // Change tanggal_penyusunan from date to datetime
            if (Schema::hasColumn('rps', 'tanggal_penyusunan')) {
                $table->dateTime('tanggal_penyusunan')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            if (Schema::hasColumn('rps', 'tanggal_penyusunan')) {
                $table->date('tanggal_penyusunan')->nullable()->change();
            }
        });
    }
};
