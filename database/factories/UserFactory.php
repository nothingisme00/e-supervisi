<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => fake()->unique()->numerify('################'), // 16 digit NIK
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => fake()->randomElement(['guru', 'kepala_sekolah', 'admin']),
            'tingkat' => fake()->randomElement(['SD', 'SMP', 'SMA']),
            'mata_pelajaran' => fake()->randomElement(['Matematika', 'Bahasa Indonesia', 'IPA', 'IPS']),
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'tingkat' => null,
            'mata_pelajaran' => null,
        ]);
    }

    /**
     * Create a kepala sekolah user.
     */
    public function kepalaSekolah(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'kepala_sekolah',
        ]);
    }

    /**
     * Create a guru user.
     */
    public function guru(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'guru',
        ]);
    }
}
