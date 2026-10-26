<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/login', 'GET');
$kernel->handle($request);

$user = App\Models\User::first();
if ($user) {
    Illuminate\Support\Facades\Auth::login($user);
}

$controller = $app->make(App\Http\Controllers\DashboardController::class);

$reqMonthly = new Illuminate\Http\Request(['period' => 'monthly']);
$respMonthly = $controller->distribution($reqMonthly);
echo "MONTHLY: " . $respMonthly->getContent() . "\n";

$reqQuarterly = new Illuminate\Http\Request(['period' => 'quarterly']);
$respQuarterly = $controller->distribution($reqQuarterly);
echo "QUARTERLY: " . $respQuarterly->getContent() . "\n";
