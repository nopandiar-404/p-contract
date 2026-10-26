<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$router = $app->make('router');

foreach ($router->getRoutes() as $r) {
    echo $r->uri() . PHP_EOL;
}
