<?php

namespace App\Http\Controllers;

use App\Services\GmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GmailWebhookController extends Controller
{
    /**
     * Endpoint Webhook untuk menerima Google Cloud Pub/Sub Push Notifications.
     *
     * Format payload:
     * POST /api/gmail/webhook
     * Content-Type: application/json
     *
     * {
     *   "message": {
     *     "data": "eyJlbWFpbEFkZHJlc3MiOiAicHJpb2tjYWJhbmdAZ21haWwuY29tIiwgImhpc3RvcnlJZCI6ICI5ODc2NTQzMjEwIn0=",
     *     "messageId": "2070443601311540",
     *     "publishTime": "2021-02-26T19:13:55.749Z"
     *   },
     *   "subscription": "projects/myproject/subscriptions/mysubscription"
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->all();

        // Validasi struktur Pub/Sub message
        if (empty($payload['message']) || empty($payload['message']['data'])) {
            Log::warning('GmailWebhook: Request tidak memiliki format Cloud Pub/Sub yang valid', [
                'ip'      => $request->ip(),
                'payload' => $payload,
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid Pub/Sub message payload.',
            ], 400);
        }

        try {
            $result = GmailService::handleIncomingPubSubNotification($payload, $request->ip());

            // Cloud Pub/Sub mewajibkan response HTTP 200/204 untuk meng-acknowledge pesan
            return response()->json([
                'status'     => 'acknowledged',
                'message_id' => $payload['message']['messageId'] ?? null,
                'result'     => $result,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('GmailWebhook: Exception saat memproses push notification: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // Tetap return 200 untuk mencegah infinite loop retry dari Pub/Sub jika data corrupt
            return response()->json([
                'status'  => 'error',
                'message' => 'Error processing notification: ' . $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Mengaktifkan Gmail Watch ke Cloud Pub/Sub topic.
     *
     * POST https://www.googleapis.com/gmail/v1/users/me/watch
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function watch(Request $request): JsonResponse
    {
        $topicName = $request->input('topicName') ?: env('GMAIL_PUBSUB_TOPIC');
        $labelIds  = (array) ($request->input('labelIds') ?: ['INBOX']);
        $behavior  = $request->input('labelFilterBehavior') ?: 'INCLUDE';

        if (empty($topicName)) {
            return response()->json([
                'success' => false,
                'message' => 'topicName wajib diisi atau konfigurasikan GMAIL_PUBSUB_TOPIC di file .env.',
            ], 422);
        }

        $result = GmailService::watch($topicName, $labelIds, $behavior);

        return response()->json($result, $result['success'] ? 200 : 502);
    }

    /**
     * Menghentikan Gmail Watch.
     *
     * POST https://www.googleapis.com/gmail/v1/users/me/stop
     *
     * @return JsonResponse
     */
    public function stop(): JsonResponse
    {
        $result = GmailService::stopWatch();

        return response()->json($result, $result['success'] ? 200 : 502);
    }

    /**
     * Cek status Gmail Watch saat ini.
     *
     * @return JsonResponse
     */
    public function status(): JsonResponse
    {
        $creds      = GmailService::getCredentials();
        $isActive   = (bool) Cache::get('gmail_watch_active', false);
        $topic      = Cache::get('gmail_watch_topic') ?: $creds['topic_name'];
        $expiration = Cache::get('gmail_watch_expiration');
        $historyId  = Cache::get('gmail_last_history_id');

        $isExpired = false;
        if ($expiration) {
            $isExpired = (int) ($expiration / 1000) < time();
        }

        return response()->json([
            'configured'            => !empty($creds['client_id']) && !empty($creds['client_secret']) && !empty($creds['refresh_token']),
            'watch_active'          => $isActive && !$isExpired,
            'topic_name'            => $topic,
            'last_history_id'       => $historyId,
            'expiration'            => $expiration,
            'expiration_formatted'  => $expiration ? date('Y-m-d H:i:s', (int) ($expiration / 1000)) : null,
            'is_expired'            => $isExpired,
            'webhook_url'           => url('/api/gmail/webhook'),
        ]);
    }

    /**
     * Endpoint untuk simulasi/pengujian Pub/Sub webhook secara lokal.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testWebhook(Request $request): JsonResponse
    {
        $emailAddress = $request->input('emailAddress', 'priokcabang@gmail.com');
        $historyId    = $request->input('historyId', '9876543210');

        $jsonData = json_encode([
            'emailAddress' => $emailAddress,
            'historyId'    => (string) $historyId,
        ]);

        $base64Data = GmailService::encodeBase64Url($jsonData);

        $simulatedPayload = [
            'message' => [
                'data'        => $base64Data,
                'messageId'   => (string) rand(1000000000000000, 9999999999999999),
                'publishTime' => now()->toIso8601String(),
            ],
            'subscription' => $request->input('subscription', 'projects/myproject/subscriptions/mysubscription'),
        ];

        $result = GmailService::handleIncomingPubSubNotification($simulatedPayload, $request->ip());

        return response()->json([
            'simulated_payload' => $simulatedPayload,
            'decoded_data'      => json_decode($jsonData, true),
            'result'            => $result,
        ]);
    }
}
