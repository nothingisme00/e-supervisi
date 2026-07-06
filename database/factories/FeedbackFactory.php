<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition(): array
    {
        return [
            'supervisi_id' => Supervisi::factory(),
            'user_id' => User::factory(),
            'komentar' => fake()->paragraph(),
            'is_revision_request' => false,
        ];
    }

    public function revisionRequest(): static
    {
        return $this->state(fn () => [
            'is_revision_request' => true,
        ]);
    }

    public function reply(Feedback $parent): static
    {
        return $this->state(fn () => [
            'parent_id' => $parent->id,
            'supervisi_id' => $parent->supervisi_id,
        ]);
    }
}
