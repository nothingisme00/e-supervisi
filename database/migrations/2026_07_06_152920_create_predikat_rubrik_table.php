<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('predikat_rubrik', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 2)->unique();
            $table->string('label');
            $table->decimal('batas_minimal', 5, 2);
            $table->unsignedTinyInteger('urutan');
            $table->timestamps();
        });

        $now = now();
        DB::table('predikat_rubrik')->insert([
            ['kode' => 'SB', 'label' => 'Sangat Baik', 'batas_minimal' => 91, 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'B', 'label' => 'Baik', 'batas_minimal' => 81, 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'C', 'label' => 'Cukup', 'batas_minimal' => 71, 'urutan' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['kode' => 'K', 'label' => 'Kurang', 'batas_minimal' => 0, 'urutan' => 4, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predikat_rubrik');
    }
};
