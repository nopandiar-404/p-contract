<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Http\Request;

$controller = app(\App\Http\Controllers\ContractController::class);

// Test PDF with expiring
$r = Request::create('/contracts/export', 'GET', ['type' => 'pdf', 'expiring' => 1]);
$response = $controller->export($r);

if (method_exists($response, 'headers')) {
    echo "Status: " . $response->getStatusCode() . PHP_EOL;
    echo "Content-Type: " . $response->headers->get('Content-Type') . PHP_EOL;
    echo "Content-Disposition: " . $response->headers->get('Content-Disposition') . PHP_EOL;
}

if (method_exists($response, 'getContent')) {
    $content = $response->getContent();
    echo "Body length: " . strlen($content) . PHP_EOL;
}

// Save body for inspection
$storageDir = __DIR__ . '/../storage/app/exports';
if (!file_exists($storageDir)) mkdir($storageDir, 0755, true);
$filename = 'debug_export_pdf_' . date('Ymd_His');
if ($response instanceof Symfony\Component\HttpFoundation\BinaryFileResponse) {
    $file = $response->getFile();
    copy($file->getRealPath(), $storageDir . DIRECTORY_SEPARATOR . basename($file->getRealPath()));
    echo "Saved binary response to exports folder" . PHP_EOL;
} elseif (method_exists($response, 'getContent')) {
    // Choose extension by content type
    $ct = $response->headers->get('Content-Type') ?? '';
    $ext = strpos($ct, 'pdf') !== false ? '.pdf' : (strpos($ct, 'html') !== false ? '.html' : '.bin');
    $dest = $storageDir . DIRECTORY_SEPARATOR . $filename . $ext;
    file_put_contents($dest, $response->getContent());
    echo "Saved content to: " . $dest . PHP_EOL;
}
