<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'nik' => '1234567890',
            'nip' => '198701012010011001',
            'name' => 'Administrator',
            'email' => 'admin@e-supervisi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Kepala Sekolah
        User::create([
            'nik' => '1234567891',
            'nip' => '198801012010011002',
            'name' => 'Kepala Sekolah',
            'email' => 'kepala@e-supervisi.com',
            'password' => Hash::make('password'),
            'role' => 'kepala',
            'jabatan' => 'Kepala Sekolah',
        ]);

        // Guru
        User::create([
            'nik' => '1234567892',
            'nip' => '199001012015011003',
            'name' => 'Guru Matematika',
            'email' => 'guru1@e-supervisi.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'mata_pelajaran' => 'Matematika',
            'tingkatan' => 'SMA',
        ]);

        User::create([
            'nik' => '1234567893',
            'nip' => '199101012015021004',
            'name' => 'Guru Bahasa Indonesia',
            'email' => 'guru2@e-supervisi.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'mata_pelajaran' => 'Bahasa Indonesia',
            'tingkatan' => 'SMA',
        ]);
    }
}