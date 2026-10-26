<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

$filePath = 'contracts/pdf/21_1776913643_REKAPITULASI TERMIN 4 2024-2025.pdf';

echo "Checking file: $filePath\n";

if (Storage::disk('public')->exists($filePath)) {
    echo "File exists\n";
    try {
        $size = Storage::disk('public')->size($filePath);
        echo "Size: $size bytes\n";
    } catch (Exception $e) {
        echo "Error getting size: " . $e->getMessage() . "\n";
    }
} else {
    echo "File does not exist\n";
}

$fullPath = storage_path('app/public/' . $filePath);
echo "Full path: $fullPath\n";
if (file_exists($fullPath)) {
    echo "File exists via direct path\n";
    $size = filesize($fullPath);
    echo "Direct size: $size bytes\n";
} else {
    echo "File does not exist via direct path\n";
}