<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Contract;

$contract = Contract::find(21);

if ($contract) {
    echo "Contract ID: " . $contract->id . "\n";
    echo "pdf_files: " . print_r($contract->pdf_files, true) . "\n";
    echo "pdf_files type: " . gettype($contract->pdf_files) . "\n";
    if (is_array($contract->pdf_files)) {
        echo "Is array, count: " . count($contract->pdf_files) . "\n";
        echo "First element: " . ($contract->pdf_files[0] ?? 'none') . "\n";
    }
    echo "Raw attributes pdf_files: " . print_r($contract->getAttributes()['pdf_files'], true) . "\n";
    echo "Manual decode: " . print_r(json_decode($contract->getAttributes()['pdf_files'], true), true) . "\n";
    if (is_array($contract->pdf_files)) {
        echo "Array count: " . count($contract->pdf_files) . "\n";
        foreach ($contract->pdf_files as $key => $value) {
            echo "[$key] => " . print_r($value, true) . " (type: " . gettype($value) . ")\n";
        }
    }
} else {
    echo "Contract not found\n";
}