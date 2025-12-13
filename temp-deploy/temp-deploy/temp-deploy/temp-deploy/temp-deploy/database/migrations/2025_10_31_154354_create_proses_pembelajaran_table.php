<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proses_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisi_id')->constrained('supervisi')->onDelete('cascade');
            $table->string('link_video')->nullable();
            $table->string('link_meeting')->nullable();
            $table->text('refleksi_1')->nullable();
            $table->text('refleksi_2')->nullable();
            $table->text('refleksi_3')->nullable();
            $table->text('refleksi_4')->nullable();
            $table->text('refleksi_5')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proses_pembelajaran');
    }
};