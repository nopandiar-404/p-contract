<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/contracts/export', 'GET');

/** @var \Illuminate\Routing\Router $router */
$router = $app->make('router');
$route = $router->getRoutes()->match($request);

file_put_contents(__DIR__ . '/../storage/matched_route.txt', print_r([
    'uri' => $route->uri(),
    'action' => $route->getActionName(),
    'methods' => $route->methods(),
    'name' => $route->getName(),
], true));

echo "matched\n";
