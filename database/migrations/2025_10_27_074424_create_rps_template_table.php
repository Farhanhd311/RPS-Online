<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rps_template', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('template_id');
            $table->string('nama_template');
            $table->text('template_structure');
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rps_template');
    }
};
