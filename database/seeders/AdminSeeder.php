<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'nik' => '1234567890123456',
            'name' => 'Administrator',
            'email' => 'admin@esupervisi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create kepala sekolah user
        User::create([
            'nik' => '2234567890123456',
            'name' => 'Kepala Sekolah',
            'email' => 'kepala@esupervisi.com',
            'password' => Hash::make('kepala123'),
            'role' => 'kepala_sekolah',
            'is_active' => true,
        ]);

        // Create guru user
        User::create([
            'nik' => '3234567890123456',
            'name' => 'Guru Demo',
            'email' => 'guru@esupervisi.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'tingkat' => 'SMA',
            'mata_pelajaran' => 'Matematika',
            'is_active' => true,
        ]);
    }
}
