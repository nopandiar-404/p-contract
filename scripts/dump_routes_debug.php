<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Support\Facades\Route;

$routes = Route::getRoutes();
$out = [];
foreach ($routes as $r) {
    $uri = $r->uri();
    if (str_contains($uri, '_debug')) {
        $out[] = sprintf("%s %s -> %s\n", implode('|', $r->methods()), $uri, (string)$r->getActionName());
    }
}
$file = __DIR__ . '/../storage/logs/routes-debug.log';
file_put_contents($file, implode("", $out));
echo "Wrote to: $file\n";
