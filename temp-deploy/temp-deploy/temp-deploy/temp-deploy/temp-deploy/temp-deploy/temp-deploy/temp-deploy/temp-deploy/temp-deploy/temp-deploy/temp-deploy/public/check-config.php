<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Laravel Configuration Check</h2>";
echo "<hr>";

echo "<h3>Session Configuration:</h3>";
echo "<pre>";
echo "SESSION_DRIVER: " . config('session.driver') . "\n";
echo "SESSION_LIFETIME: " . config('session.lifetime') . "\n";
echo "SESSION_CONNECTION: " . config('session.connection') . "\n";
echo "SESSION_TABLE: " . config('session.table') . "\n";
echo "</pre>";

echo "<h3>Database Configuration:</h3>";
echo "<pre>";
echo "DB_CONNECTION: " . config('database.default') . "\n";
echo "DB_DATABASE: " . config('database.connections.mysql.database') . "\n";
echo "DB_USERNAME: " . config('database.connections.mysql.username') . "\n";
echo "</pre>";

echo "<h3>App Configuration:</h3>";
echo "<pre>";
echo "APP_ENV: " . config('app.env') . "\n";
echo "APP_DEBUG: " . (config('app.debug') ? 'true' : 'false') . "\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "</pre>";

// Test database connection
try {
    $pdo = DB::connection()->getPdo();
    echo "<h3>✅ Database Connection: SUCCESS</h3>";
    
    // Check if sessions table exists
    $tables = DB::select("SHOW TABLES LIKE 'sessions'");
    if (count($tables) > 0) {
        echo "<h3>✅ Sessions Table: EXISTS</h3>";
        
        // Count sessions
        $count = DB::table('sessions')->count();
        echo "<p>Total sessions in database: <strong>" . $count . "</strong></p>";
    } else {
        echo "<h3>❌ Sessions Table: NOT FOUND</h3>";
        echo "<p>Please create the sessions table!</p>";
    }
} catch (Exception $e) {
    echo "<h3>❌ Database Connection: FAILED</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='/'>← Back to Home</a></p>";
?>
