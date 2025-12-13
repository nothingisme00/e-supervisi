<?php
/**
 * Helper script untuk optimize Laravel di shared hosting
 * Upload file ini ke public_html, akses via browser, lalu HAPUS setelah selesai!
 * 
 * URL: https://yourdomain.com/optimize.php
 */

// Sesuaikan path ke folder Laravel Anda
$laravelPath = __DIR__ . '/../e-supervisi';

// Load Laravel
require $laravelPath . '/vendor/autoload.php';
$app = require_once $laravelPath . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<html><head><title>Laravel Optimization</title></head><body>";
echo "<h1>Laravel Optimization</h1>";
echo "<pre>";

echo "Optimizing Laravel for production...\n";
echo str_repeat("=", 50) . "\n\n";

// Clear all caches first
echo "1. Clearing all caches...\n";
$kernel->call('config:clear');
$kernel->call('cache:clear');
$kernel->call('route:clear');
$kernel->call('view:clear');
echo "   ✓ All caches cleared\n\n";

// Cache config
echo "2. Caching configuration...\n";
$kernel->call('config:cache');
echo "   ✓ Configuration cached\n\n";

// Cache routes
echo "3. Caching routes...\n";
$kernel->call('route:cache');
echo "   ✓ Routes cached\n\n";

// Cache views
echo "4. Caching views...\n";
$kernel->call('view:cache');
echo "   ✓ Views cached\n\n";

echo str_repeat("=", 50) . "\n";
echo "OPTIMIZATION COMPLETE!\n";
echo str_repeat("=", 50) . "\n\n";

echo "Your Laravel application is now optimized for production.\n\n";

echo "</pre>";
echo "<p style='color: red; font-weight: bold;'>⚠ PENTING: HAPUS file ini sekarang juga untuk keamanan!</p>";
echo "<hr>";
echo "<p><small>File: " . __FILE__ . "</small></p>";
echo "</body></html>";
?>
