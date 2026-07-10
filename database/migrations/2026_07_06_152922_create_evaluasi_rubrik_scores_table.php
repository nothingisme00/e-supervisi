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
        Schema::create('evaluasi_rubrik_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_rubrik_id')->constrained('evaluasi_rubrik')->cascadeOnDelete();
            $table->foreignId('rubrik_item_id')->constrained('rubrik_items');
            $table->unsignedTinyInteger('skor');
            $table->timestamps();

            $table->unique(['evaluasi_rubrik_id', 'rubrik_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_rubrik_scores');
    }
};
