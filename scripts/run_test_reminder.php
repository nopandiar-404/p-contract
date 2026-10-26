<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = App\Models\Contract::first();
if (!$c) {
    echo "NO_CONTRACTS\n";
    exit(1);
}

App\Models\ContractNotification::createExpiryNotification($c, 30);

echo "DONE\n";
