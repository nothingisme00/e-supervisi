<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Debug User Session</h2>";
echo "<hr>";

if (auth()->check()) {
    $user = auth()->user();
    echo "<h3>✅ User Logged In:</h3>";
    echo "<pre>";
    echo "ID: " . $user->id . "\n";
    echo "NIK: " . $user->nik . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Tingkat: " . $user->tingkat . "\n";
    echo "Mata Pelajaran: " . $user->mata_pelajaran . "\n";
    echo "Is Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
    echo "Must Change Password: " . ($user->must_change_password ? 'Yes' : 'No') . "\n";
    echo "</pre>";
    
    echo "<h3>Role Checks:</h3>";
    echo "<pre>";
    echo "isAdmin(): " . ($user->isAdmin() ? 'TRUE' : 'FALSE') . "\n";
    echo "isGuru(): " . ($user->isGuru() ? 'TRUE' : 'FALSE') . "\n";
    echo "isKepalaSekolah(): " . ($user->isKepalaSekolah() ? 'TRUE' : 'FALSE') . "\n";
    echo "</pre>";
    
    echo "<h3>Expected Redirect:</h3>";
    if ($user->isAdmin()) {
        echo "<p>Should redirect to: <strong>/admin/dashboard</strong></p>";
    } elseif ($user->isGuru()) {
        echo "<p>Should redirect to: <strong>/guru/home</strong></p>";
    } elseif ($user->isKepalaSekolah()) {
        echo "<p>Should redirect to: <strong>/kepala/dashboard</strong></p>";
    }
} else {
    echo "<h3>❌ No User Logged In</h3>";
    echo "<p>Please <a href='/login'>login</a> first.</p>";
}

echo "<hr>";
echo "<p><a href='/'>← Back to Home</a></p>";
?>
