<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$last = App\Models\ContractNotification::orderBy('created_at', 'desc')->first();
if (!$last) {
    echo json_encode(['error' => 'no_notification']);
    exit(1);
}

// Normalize date fields (sent_date may be string or Carbon)
$sent = null;
if ($last->sent_date instanceof \Carbon\Carbon) {
    $sent = $last->sent_date->toDateTimeString();
} elseif (is_string($last->sent_date) && !empty($last->sent_date)) {
    $sent = $last->sent_date;
}

$created = null;
if ($last->created_at instanceof \Carbon\Carbon) {
    $created = $last->created_at->toDateTimeString();
} elseif (is_string($last->created_at) && !empty($last->created_at)) {
    $created = $last->created_at;
}

$out = [
    'id' => $last->id,
    'contract_id' => $last->contract_id,
    'type' => $last->type,
    'message' => $last->message,
    'data' => $last->data,
    'sent_date' => $sent,
    'created_at' => $created,
];

echo json_encode($out, JSON_PRETTY_PRINT);
