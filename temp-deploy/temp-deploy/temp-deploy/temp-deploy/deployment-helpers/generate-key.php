<?php
/**
 * Helper script untuk generate APP_KEY di shared hosting
 * Upload file ini ke public_html, akses via browser, lalu HAPUS setelah selesai!
 * 
 * URL: https://yourdomain.com/generate-key.php
 */

// Sesuaikan path ke folder Laravel Anda
$laravelPath = __DIR__ . '/../e-supervisi';

// Load Laravel
require $laravelPath . '/vendor/autoload.php';
$app = require_once $laravelPath . '/bootstrap/app.php';

// Generate key
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->call('key:generate', ['--force' => true]);

echo "<html><head><title>Generate APP_KEY</title></head><body>";
echo "<h1>Generate APP_KEY</h1>";

if ($status === 0) {
    echo "<p style='color: green; font-weight: bold;'>✓ APP_KEY berhasil di-generate!</p>";
    
    // Baca .env untuk menampilkan key
    $envPath = $laravelPath . '/.env';
    if (file_exists($envPath)) {
        $envContent = file_get_contents($envPath);
        if (preg_match('/APP_KEY=(.+)/', $envContent, $matches)) {
            echo "<p>APP_KEY Anda: <code>" . htmlspecialchars($matches[1]) . "</code></p>";
        }
    }
    
    echo "<p style='color: red; font-weight: bold;'>⚠ PENTING: HAPUS file ini sekarang juga untuk keamanan!</p>";
} else {
    echo "<p style='color: red;'>✗ Gagal generate APP_KEY. Cek error log.</p>";
}

echo "<hr>";
echo "<p><small>File: " . __FILE__ . "</small></p>";
echo "</body></html>";
?>
