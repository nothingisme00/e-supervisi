<?php

namespace Database\Factories;

use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupervisiFactory extends Factory
{
    protected $model = Supervisi::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->guru(),
            'status' => Supervisi::STATUS_DRAFT,
            'tanggal_supervisi' => null,
            'catatan' => fake()->optional()->sentence(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => Supervisi::STATUS_DRAFT]);
    }

    public function submitted(): static
    {
        return $this->state(fn () => [
            'status' => Supervisi::STATUS_SUBMITTED,
            'tanggal_supervisi' => now(),
        ]);
    }

    public function underReview(): static
    {
        return $this->state(fn () => [
            'status' => Supervisi::STATUS_UNDER_REVIEW,
            'tanggal_supervisi' => now(),
            'reviewed_by' => User::factory()->admin(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => Supervisi::STATUS_COMPLETED,
            'tanggal_supervisi' => now(),
            'reviewed_by' => User::factory()->admin(),
            'reviewed_at' => now(),
        ]);
    }

    public function revision(): static
    {
        return $this->state(fn () => [
            'status' => Supervisi::STATUS_REVISION,
            'tanggal_supervisi' => now(),
            'revision_notes' => fake()->sentence(),
            'needs_revision' => true,
        ]);
    }
}
