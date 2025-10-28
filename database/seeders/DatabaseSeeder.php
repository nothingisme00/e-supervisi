<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guru;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'nik' => '111111',
            'name' => 'Admin E-Supervisi',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Guru + detail guru
        $guruUser = User::create([
            'nik' => '222222',
            'name' => 'Guru Contoh',
            'password' => bcrypt('password'),
            'role' => 'guru'
        ]);

        Guru::create([
            'user_id' => $guruUser->id,
            'mata_pelajaran' => 'Matematika',
            'tingkat' => 'VIII',
            'sekolah' => 'SMP Negeri Contoh'
        ]);

        // Kepala
        User::create([
            'nik' => '333333',
            'name' => 'Kepala Sekolah Contoh',
            'password' => bcrypt('password'),
            'role' => 'kepala'
        ]);

        // (Optional) create default refleksi questions (we can seed them when a supervisi is created)
    }
}
