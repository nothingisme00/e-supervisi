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
         Schema::create('supervisis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
        $table->string('judul')->nullable();
        $table->text('deskripsi')->nullable();
        $table->string('link_youtube'); // wajib
        $table->string('link_meet')->nullable();
        $table->enum('status', ['pending','disetujui','ditolak'])->default('pending');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisis');
    }
};
