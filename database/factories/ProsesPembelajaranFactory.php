<?php

namespace Database\Factories;

use App\Models\ProsesPembelajaran;
use App\Models\Supervisi;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProsesPembelajaranFactory extends Factory
{
    protected $model = ProsesPembelajaran::class;

    public function definition(): array
    {
        return [
            'supervisi_id' => Supervisi::factory(),
            'link_video' => fake()->url(),
            'link_meeting' => fake()->optional()->url(),
            'refleksi_1' => fake()->sentence(),
            'refleksi_2' => fake()->sentence(),
            'refleksi_3' => fake()->sentence(),
            'refleksi_4' => fake()->sentence(),
            'refleksi_5' => fake()->sentence(),
        ];
    }

    public function incomplete(): static
    {
        return $this->state(fn () => [
            'link_video' => null,
            'refleksi_1' => null,
            'refleksi_2' => null,
            'refleksi_3' => null,
            'refleksi_4' => null,
            'refleksi_5' => null,
        ]);
    }
}
