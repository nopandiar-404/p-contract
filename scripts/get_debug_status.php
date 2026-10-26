<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Http\Request;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Request::create('/_debug/status', 'GET');
$response = $kernel->handle($request);

echo "Status endpoint (/ _debug/status):\n";
echo $response->getContent() . "\n";

// Also try export pdf and show headers
$requestPdf = Request::create('/contracts/export', 'GET', ['type' => 'pdf']);
$responsePdf = $kernel->handle($requestPdf);

echo "\nExport PDF: status=" . $responsePdf->getStatusCode() . "\n";
echo "Content-Type: " . ($responsePdf->headers->get('Content-Type') ?: 'null') . "\n";
echo "Content-Disposition: " . ($responsePdf->headers->get('Content-Disposition') ?: 'null') . "\n";

// If response is not OK, dump content
if ($responsePdf->getStatusCode() !== 200) {
    echo "Response body:\n" . $responsePdf->getContent() . "\n";
}
