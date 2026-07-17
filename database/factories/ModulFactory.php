<?php

namespace Database\Factories;

use App\Models\ModulKategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModulFactory extends Factory
{
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(),
            'modul_kategori_id' => ModulKategori::factory(),
            'file_path' => 'modul/' . fake()->uuid() . '.pdf',
            'jumlah_halaman' => 10,
            'is_active' => true,
        ];
    }

    public function withThumbnail(): static
    {
        return $this->state(fn () => ['thumbnail_path' => 'modul-thumbnails/' . fake()->uuid() . '.webp']);
    }
}
