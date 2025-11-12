<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            // Drop foreign key constraint jika ada
            try {
                $table->dropForeign(['template_id']);
            } catch (\Exception $e) {
                // Ignore if foreign key doesn't exist
            }
            
            // Make template_id nullable and drop the foreign key requirement
            if (Schema::hasColumn('rps', 'template_id')) {
                $table->unsignedBigInteger('template_id')->nullable()->change();
            }
            
            // Make template_content nullable
            if (Schema::hasColumn('rps', 'template_content')) {
                $table->text('template_content')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            if (Schema::hasColumn('rps', 'template_id')) {
                $table->unsignedBigInteger('template_id')->nullable(false)->change();
            }
            
            if (Schema::hasColumn('rps', 'template_content')) {
                $table->text('template_content')->nullable(false)->change();
            }
        });
    }
};
