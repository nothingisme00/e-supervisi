<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ModulKategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->words(2, true),
            'is_active' => true,
        ];
    }
}
