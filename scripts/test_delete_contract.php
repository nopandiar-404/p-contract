<?php
// Boot Laravel and delete the first contract to verify backend deletion works
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Contracts\Console\Kernel;

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Contract;

$contract = Contract::first();
if (!$contract) {
    echo "No contract found to delete.\n";
    exit(1);
}

$id = $contract->id;
$name = $contract->contract_name ?? $contract->name ?? 'N/A';
echo "Found contract: {$id} - {$name}\n";

try {
    $contract->delete();
    echo "Deleted contract {$id}.\n";
    exit(0);
} catch (\Exception $e) {
    echo "Delete failed: " . $e->getMessage() . "\n";
    exit(1);
}
