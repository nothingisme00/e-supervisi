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
        Schema::create('evaluasi_rubrik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisi_id')->constrained('supervisi')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->constrained('users');
            $table->unsignedSmallInteger('skor_total');
            $table->unsignedSmallInteger('skor_maksimal');
            $table->decimal('nilai_akhir', 5, 2);
            $table->string('predikat', 2);
            $table->text('masukan_umum')->nullable();
            $table->string('nama_pengawas')->nullable();
            $table->timestamps();

            $table->unique('supervisi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_rubrik');
    }
};
