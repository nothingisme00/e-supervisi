<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisi_id')->constrained('supervisi')->onDelete('cascade');
            $table->string('jenis_dokumen'); // RPP, Silabus, dll
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('tipe_file'); // pdf, jpg, png
            $table->integer('ukuran_file'); // dalam bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_evaluasi');
    }
};