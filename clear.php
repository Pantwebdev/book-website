<?php

$commands = [
    'config:clear',
    'cache:clear',
    'route:clear',
    'view:clear',
];

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

foreach ($commands as $command) {
    $kernel->call($command);
    echo "✅ php artisan {$command} done<br>";
}
echo "<br><b>All cache cleared!</b>";
