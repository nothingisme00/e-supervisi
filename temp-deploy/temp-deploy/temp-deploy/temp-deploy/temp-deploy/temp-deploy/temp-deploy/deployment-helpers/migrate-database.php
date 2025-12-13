<?php
/**
 * Helper script untuk migrate database di shared hosting
 * Upload file ini ke public_html, akses via browser, lalu HAPUS setelah selesai!
 * 
 * URL: https://yourdomain.com/migrate-database.php
 */

// Sesuaikan path ke folder Laravel Anda
$laravelPath = __DIR__ . '/../e-supervisi';

// Load Laravel
require $laravelPath . '/vendor/autoload.php';
$app = require_once $laravelPath . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<html><head><title>Database Migration</title></head><body>";
echo "<h1>Database Migration & Seeding</h1>";
echo "<pre>";

// Test database connection
echo "Testing database connection...\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "✓ Database connection successful!\n\n";
} catch (\Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    echo "</pre></body></html>";
    exit;
}

// Run migrations
echo "Running migrations...\n";
echo str_repeat("-", 50) . "\n";
$status = $kernel->call('migrate', ['--force' => true]);

if ($status === 0) {
    echo "\n✓ Migrations completed successfully!\n\n";
} else {
    echo "\n✗ Migrations failed!\n\n";
}

// Run seeders
echo "Running database seeders...\n";
echo str_repeat("-", 50) . "\n";
$status = $kernel->call('db:seed', ['--force' => true]);

if ($status === 0) {
    echo "\n✓ Seeding completed successfully!\n\n";
} else {
    echo "\n✗ Seeding failed!\n\n";
}

echo str_repeat("=", 50) . "\n";
echo "MIGRATION & SEEDING COMPLETE!\n";
echo str_repeat("=", 50) . "\n\n";

echo "Default Login Credentials:\n";
echo "- Admin: admin@example.com / admin123\n";
echo "- Kepala Sekolah: kepala@example.com / kepala123\n";
echo "- Guru: guru@example.com / guru123\n\n";

echo "</pre>";
echo "<p style='color: red; font-weight: bold;'>⚠ PENTING: HAPUS file ini sekarang juga untuk keamanan!</p>";
echo "<hr>";
echo "<p><small>File: " . __FILE__ . "</small></p>";
echo "</body></html>";
?>
