<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RESET ADMIN PASSWORD ===\n\n";

$admin = \App\Models\User::where('role', 'admin')->first();

if ($admin) {
    $newPassword = 'admin123'; // Password baru
    $admin->password = bcrypt($newPassword);
    $admin->must_change_password = false;
    $admin->save();
    
    echo "✅ Password admin berhasil direset!\n\n";
    echo "Login Credentials:\n";
    echo "NIK: {$admin->nik}\n";
    echo "Password: {$newPassword}\n";
    echo "Email: {$admin->email}\n";
    echo "Nama: {$admin->name}\n";
} else {
    echo "❌ Admin tidak ditemukan!\n";
}
