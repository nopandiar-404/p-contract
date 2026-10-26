<?php
// Script to call /contracts/export via the HTTP kernel and save the response to storage/app/exports
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

// Accept type as argument
$type = $argv[1] ?? 'csv';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Request::create('/contracts/export', 'GET', ['type' => $type]);

try {
    $response = $kernel->handle($request);

    $storageDir = __DIR__ . '/../storage/app/exports';
    if (!file_exists($storageDir)) {
        mkdir($storageDir, 0755, true);
    }

    $cd = $response->headers->get('Content-Disposition');
    $contentType = $response->headers->get('Content-Type');
    $status = $response->getStatusCode();

    $filename = null;
    if ($cd && preg_match('/filename="?([^";]+)"?/', $cd, $m)) {
        $filename = $m[1];
    }

    if (!$filename) {
        $filename = 'export_' . $type . '_' . date('Ymd_His');
        if ($type === 'csv') $filename .= '.csv';
        if ($type === 'pdf') $filename .= '.pdf';
        if ($type === 'excel') $filename .= '.xlsx';
    }

    $dest = $storageDir . DIRECTORY_SEPARATOR . $filename;

    // Diagnostic logging: record status and headers for inspection
    file_put_contents(__DIR__ . '/../storage/logs/export-cli-debug.log', '[' . date('c') . "] type={$type} status={$status} content-type={$contentType} content-disposition=" . ($cd ?: 'null') . "\n", FILE_APPEND);

    if ($response instanceof BinaryFileResponse) {
        $file = $response->getFile();
        copy($file->getRealPath(), $dest);
        echo "Saved (binary): {$dest}\n";
        exit(0);
    }

    if ($response instanceof StreamedResponse) {
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        file_put_contents($dest, $content);
        echo "Saved (streamed): {$dest}\n";
        exit(0);
    }

    $content = method_exists($response, 'getContent') ? $response->getContent() : null;
    file_put_contents($dest, $content ?? '');
    echo "Saved (default): {$dest}\n";
    exit(0);

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    file_put_contents(__DIR__ . '/../storage/logs/export-cli-error.log', $e->getMessage() . "\n" . $e->getTraceAsString(), FILE_APPEND);
    exit(1);
}
