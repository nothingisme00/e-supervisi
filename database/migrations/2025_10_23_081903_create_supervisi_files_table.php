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
        Schema::create('supervisi_files', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supervisi_id')->constrained('supervisis')->onDelete('cascade');
        $table->enum('nama_berkas', [
            'CP','ATP','Kalender','Program Tahunan','Program Semester','Modul Ajar','Bahan Ajar'
        ]);
        $table->string('file_path');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisi_files');
    }
};
