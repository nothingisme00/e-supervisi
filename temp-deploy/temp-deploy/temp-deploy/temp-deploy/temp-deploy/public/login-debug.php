<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Login Debug</h2>";
echo "<hr>";

// Check if user is authenticated
if (auth()->check()) {
    $user = auth()->user();
    
    echo "<h3>✅ USER IS AUTHENTICATED</h3>";
    echo "<pre>";
    echo "ID: " . $user->id . "\n";
    echo "NIK: " . $user->nik . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Tingkat: " . ($user->tingkat ?? 'NULL') . "\n";
    echo "Mata Pelajaran: " . ($user->mata_pelajaran ?? 'NULL') . "\n";
    echo "</pre>";
    
    echo "<h3>Role Check Results:</h3>";
    echo "<pre>";
    echo "isAdmin(): " . ($user->isAdmin() ? 'TRUE' : 'FALSE') . "\n";
    echo "isGuru(): " . ($user->isGuru() ? 'TRUE' : 'FALSE') . "\n";
    echo "isKepalaSekolah(): " . ($user->isKepalaSekolah() ? 'TRUE' : 'FALSE') . "\n";
    echo "</pre>";
    
    echo "<h3>Expected Routes:</h3>";
    echo "<ul>";
    if ($user->isAdmin()) {
        echo "<li><strong>Admin:</strong> <a href='" . route('admin.dashboard') . "'>" . route('admin.dashboard') . "</a></li>";
    }
    if ($user->isGuru()) {
        echo "<li><strong>Guru:</strong> <a href='" . route('guru.home') . "'>" . route('guru.home') . "</a></li>";
    }
    if ($user->isKepalaSekolah()) {
        echo "<li><strong>Kepala Sekolah:</strong> <a href='" . route('kepala.dashboard') . "'>" . route('kepala.dashboard') . "</a></li>";
    }
    echo "</ul>";
    
    echo "<h3>Test Access:</h3>";
    echo "<p>Click the link above to test if you can access the correct dashboard.</p>";
    
} else {
    echo "<h3>❌ NO USER AUTHENTICATED</h3>";
    echo "<p>Please <a href='/login'>login</a> first.</p>";
}

echo "<hr>";
echo "<h3>Session Info:</h3>";
echo "<pre>";
echo "Session ID: " . session()->getId() . "\n";
echo "Session Driver: " . config('session.driver') . "\n";
echo "</pre>";

echo "<p><a href='/'>← Back to Home</a></p>";
?>
