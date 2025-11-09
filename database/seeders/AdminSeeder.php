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
        // Create default admin user
        User::create([
            'nik' => '1234567890123456',
            'name' => 'Administrator',
            'email' => 'admin@esupervisi.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->command->info('Default admin user created successfully!');
        $this->command->info('NIK: 1234567890123456');
        $this->command->info('Password: admin123');
        $this->command->warn('⚠️  IMPORTANT: Please change the default password after first login!');
    }
}
