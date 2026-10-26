<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$router = $app->make('router');
$routes = $router->getRoutes();
$out = [];
foreach ($routes as $r) {
    if ($r->uri() === 'contracts/export') {
        $out[] = [
            'uri' => $r->uri(),
            'methods' => $r->methods(),
            'action' => (string)$r->getActionName(),
            'middleware' => $r->gatherMiddleware(),
        ];
    }
}
file_put_contents(__DIR__.'/../storage/logs/inspect-route.log', json_encode($out, JSON_PRETTY_PRINT));
echo "Wrote inspect log\n";