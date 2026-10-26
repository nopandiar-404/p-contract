<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Http\Request;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$logFile = __DIR__ . '/../storage/logs/debug-status.log';

try {
    $request = Request::create('/_debug/status', 'GET');
    $res = $kernel->handle($request);
    $content = $res->getContent();
    file_put_contents($logFile, "[status] code=" . $res->getStatusCode() . "\nheaders=" . json_encode($res->headers->all()) . "\nbody=" . $content . "\n\n", FILE_APPEND);

    // PDF export
    $requestPdf = Request::create('/contracts/export', 'GET', ['type' => 'pdf']);
    $resPdf = $kernel->handle($requestPdf);
    file_put_contents($logFile, "[export:pdf] code=".$resPdf->getStatusCode()."\nheaders=".json_encode($resPdf->headers->all())."\nbody=" . substr($resPdf->getContent(), 0, 1000) . "\n\n", FILE_APPEND);

    // Excel export
    $requestX = Request::create('/contracts/export', 'GET', ['type' => 'excel']);
    $resX = $kernel->handle($requestX);
    file_put_contents($logFile, "[export:excel] code=".$resX->getStatusCode()."\nheaders=".json_encode($resX->headers->all())."\nbody=" . substr($resX->getContent(), 0, 1000) . "\n\n", FILE_APPEND);

    echo "Wrote debug info to: $logFile\n";
} catch (\Throwable $e) {
    file_put_contents($logFile, "EXC: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
    echo "Exception written to $logFile\n";
}
