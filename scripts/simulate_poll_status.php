<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if ($argc < 2) {
    echo json_encode(['error' => 'missing_id']);
    exit(1);
}

$id = (int) $argv[1];
$notification = App\Models\ContractNotification::find($id);
if (!$notification) {
    echo json_encode(['error' => 'not_found']);
    exit(1);
}

// Normalize date fields like other scripts (sent_date may be string)
if ($notification->sent_date instanceof \Carbon\Carbon === false && is_string($notification->sent_date) && !empty($notification->sent_date)) {
    try {
        $notification->sent_date = \Carbon\Carbon::parse($notification->sent_date);
    } catch (Exception $e) {
        // leave as-is if parse fails
    }
}
if ($notification->created_at instanceof \Carbon\Carbon === false && is_string($notification->created_at) && !empty($notification->created_at)) {
    try {
        $notification->created_at = \Carbon\Carbon::parse($notification->created_at);
    } catch (Exception $e) {
        // leave as-is
    }
}

$controller = new App\Http\Controllers\NotificationController();
$response = $controller->status($notification);
// JsonResponse -> getContent()
echo $response->getContent();
