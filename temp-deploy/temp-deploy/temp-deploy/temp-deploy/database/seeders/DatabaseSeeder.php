<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin, kepala sekolah, and guru users
        $this->call([
            AdminSeeder::class,
        ]);

        // Optionally create additional test users
        // User::factory(10)->guru()->create();
        // User::factory(5)->kepalaSekolah()->create();
    }
}
