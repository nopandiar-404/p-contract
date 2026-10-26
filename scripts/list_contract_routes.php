<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$router = $app->make('router');
$routes = $router->getRoutes();
$out = [];
foreach ($routes as $r) {
    $uri = $r->uri();
    if (str_contains($uri, 'contracts')) {
        $out[] = sprintf("%s %s -> %s\n", implode('|', $r->methods()), $uri, (string)$r->getActionName());
    }
}
$file = __DIR__ . '/../storage/logs/routes-contracts.log';
file_put_contents($file, implode("", $out));
echo "Wrote to: $file\n";
