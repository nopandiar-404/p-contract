<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/contracts/export', 'GET', ['type' => 'csv']);
$response = $kernel->handle($request);

file_put_contents(__DIR__ . '/../storage/contracts_export_test.csv', $response->getContent());
file_put_contents(__DIR__ . '/../storage/contracts_export_test.json', $response->getContent());

// Now try PDF
$requestPdf = Illuminate\Http\Request::create('/contracts/export', 'GET', ['type' => 'pdf']);
$responsePdf = $kernel->handle($requestPdf);
file_put_contents(__DIR__ . '/../storage/contracts_export_test.pdf', $responsePdf->getContent());

// Excel (xlsx)
$requestXls = Illuminate\Http\Request::create('/contracts/export', 'GET', ['type' => 'excel']);
$responseXls = $kernel->handle($requestXls);
file_put_contents(__DIR__ . '/../storage/contracts_export_test.xlsx', $responseXls->getContent());

echo "done\n";
