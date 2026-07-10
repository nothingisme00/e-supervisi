<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modul_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_id')->constrained('moduls')->cascadeOnDelete();
            $table->string('judul');
            $table->string('youtube_url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_videos');
    }
};
