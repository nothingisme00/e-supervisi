<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Support\Facades\Artisan;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Clearing Laravel Cache...</h2>";
echo "<hr>";

try {
    Artisan::call('config:clear');
    echo "✅ Config cache cleared<br>";
    
    Artisan::call('cache:clear');
    echo "✅ Application cache cleared<br>";
    
    Artisan::call('view:clear');
    echo "✅ View cache cleared<br>";
    
    Artisan::call('route:clear');
    echo "✅ Route cache cleared<br>";
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ All cache cleared successfully!</h3>";
    echo "<p><a href='/'>← Back to Home</a></p>";
} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
