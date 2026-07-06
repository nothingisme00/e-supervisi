<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modul_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modul_id')->constrained('moduls')->cascadeOnDelete();
            $table->unsignedSmallInteger('halaman_terjauh')->default(1);
            $table->timestamp('terakhir_dibuka_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'modul_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_progress');
    }
};
