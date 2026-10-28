<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking DomPDF facade presence and runtime\n";
echo "GD enabled: " . (extension_loaded('gd') ? 'yes' : 'no') . "\n";

if (class_exists('\\Barryvdh\\DomPDF\\Facade\\Pdf')) {
    echo "Facade exists\n";
    try {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('<h1>Test PDF</h1><p>Hello</p>');
        $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test_dompdf.pdf';
        $pdf->save($tmp);
        echo "Saved PDF to: $tmp\n";
        echo "Size: " . filesize($tmp) . " bytes\n";
    } catch (\Throwable $e) {
        echo "DomPDF runtime error: " . $e->getMessage() . "\n";
        file_put_contents(__DIR__.'/../storage/logs/dompdf-runtime.log', $e->getMessage() . "\n" . $e->getTraceAsString(), FILE_APPEND);
    }
} else {
    echo "Facade NOT found\n";
}
