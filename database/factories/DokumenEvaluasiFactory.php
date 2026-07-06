<?php

namespace Database\Factories;

use App\Models\DokumenEvaluasi;
use App\Models\Supervisi;
use Illuminate\Database\Eloquent\Factories\Factory;

class DokumenEvaluasiFactory extends Factory
{
    protected $model = DokumenEvaluasi::class;

    public function definition(): array
    {
        return [
            'supervisi_id' => Supervisi::factory(),
            'jenis_dokumen' => fake()->randomElement([
                'capaian_pembelajaran',
                'alur_tujuan_pembelajaran',
                'kalender',
                'program_tahunan',
                'program_semester',
                'modul_ajar',
                'bahan_ajar',
            ]),
            'nama_file' => fake()->word() . '.pdf',
            'path_file' => 'supervisi/1/' . fake()->word() . '.pdf',
            'tipe_file' => 'pdf',
            'ukuran_file' => fake()->numberBetween(1024, 2048000),
        ];
    }
}
