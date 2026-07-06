<?php

namespace Database\Factories;

use App\Models\CarouselSlide;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarouselSlideFactory extends Factory
{
    protected $model = CarouselSlide::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'image_path' => 'carousel/' . fake()->word() . '.webp',
            'order' => fake()->unique()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
