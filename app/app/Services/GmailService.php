<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractAudit;
use App\Models\ContractNotification;
use App\Models\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GmailService
{
    /**
     * Retrieve Gmail OAuth credentials from env or fallback file.
     *
     * @return array
     */
    public static function getCredentials(): array
    {
        $clientId     = env('GMAIL_CLIENT_ID');
        $clientSecret = env('GMAIL_CLIENT_SECRET');
        $refreshToken = env('GMAIL_REFRESH_TOKEN');
        $fromEmail    = env('GMAIL_FROM_EMAIL') ?: env('MAIL_FROM_ADDRESS');
        $fromName     = env('GMAIL_FROM_NAME') ?: env('MAIL_FROM_NAME') ?: env('APP_NAME', 'Pelindo E-Contract');
        $topicName    = env('GMAIL_PUBSUB_TOPIC', 'projects/myproject/topics/mytopic');

        $credentialsPath = storage_path('app/gmail_credentials.json');
        if (file_exists($credentialsPath)) {
            try {
                $json = json_decode(file_get_contents($credentialsPath), true);
                $clientId     = $clientId ?: ($json['client_id'] ?? null);
                $clientSecret = $clientSecret ?: ($json['client_secret'] ?? null);
                $refreshToken = $refreshToken ?: ($json['refresh_token'] ?? null);
            } catch (\Throwable $e) {
                Log::warning('GmailService: Gagal membaca gmail_credentials.json: ' . $e->getMessage());
            }
        }

        return [
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'from_email'    => $fromEmail,
            'from_name'     => $fromName,
            'topic_name'    => $topicName,
        ];
    }

    /**
     * Obtain a valid OAuth2 access token, cached for its lifetime.
     *
     * @param bool $forceRefresh
     * @return string|null
     */
    public static function getAccessToken(bool $forceRefresh = false): ?string
    {
        $cacheKey = 'gmail_api_access_token';

        if (!$forceRefresh && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $creds = self::getCredentials();
        if (empty($creds['client_id']) || empty($creds['client_secret']) || empty($creds['refresh_token'])) {
            Log::error('GmailService: Konfigurasi GMAIL_CLIENT_ID, GMAIL_CLIENT_SECRET, atau GMAIL_REFRESH_TOKEN belum diset.');
            return null;
        }

        try {
            $response = Http::asForm()->timeout(15)->post('https://oauth2.googleapis.com/token', [
                'client_id'     => $creds['client_id'],
                'client_secret' => $creds['client_secret'],
                'refresh_token' => $creds['refresh_token'],
                'grant_type'    => 'refresh_token',
            ]);

            if (!$response->successful()) {
                Log::error('GmailService: Gagal me-refresh access token', ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            }

            $tokenData   = $response->json();
            $accessToken = $tokenData['access_token'] ?? null;
            $expiresIn   = (int) ($tokenData['expires_in'] ?? 3600);

            if ($accessToken) {
                // Simpan di cache, berikan buffer 5 menit sebelum kedaluwarsa
                $cacheTtlSeconds = max(60, $expiresIn - 300);
                Cache::put($cacheKey, $accessToken, $cacheTtlSeconds);
                return $accessToken;
            }

            return null;
        } catch (\Throwable $e) {
            Log::error('GmailService: Exception saat refresh token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Kirim email raw RFC 2822 menggunakan Gmail REST API.
     *
     * @param string $to
     * @param string $subject
     * @param string $htmlBody
     * @return bool
     */
    public static function send(string $to, string $subject, string $htmlBody): bool
    {
        $accessToken = self::getAccessToken();
        if (!$accessToken) {
            return false;
        }

        $creds       = self::getCredentials();
        $fromEmail   = $creds['from_email'] ?: 'noreply@example.com';
        $fromName    = $creds['from_name'] ?: 'Contract Notification';

        $boundary   = uniqid('gmail_bound_');
        $fromHeader = $fromName ? "{$fromName} <{$fromEmail}>" : $fromEmail;

        $raw  = "From: {$fromHeader}\r\n";
        $raw .= "To: {$to}\r\n";
        $raw .= "Subject: {$subject}\r\n";
        $raw .= "MIME-Version: 1.0\r\n";
        $raw .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n\r\n";

        $raw .= "--{$boundary}\r\n";
        $raw .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
        $raw .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $raw .= strip_tags($htmlBody) . "\r\n\r\n";

        $raw .= "--{$boundary}\r\n";
        $raw .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
        $raw .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $raw .= $htmlBody . "\r\n\r\n";
        $raw .= "--{$boundary}--";

        $rawEncoded = self::encodeBase64Url($raw);

        try {
            $response = Http::withToken($accessToken)
                ->timeout(20)
                ->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                    'raw' => $rawEncoded,
                ]);

            if ($response->successful()) {
                Log::info('GmailService: Email berhasil dikirim', ['to' => $to, 'message_id' => $response->json('id')]);
                return true;
            }

            Log::error('GmailService: Gagal kirim email', ['status' => $response->status(), 'response' => $response->body()]);
            return false;
        } catch (\Throwable $e) {
            Log::error('GmailService: Exception saat kirim email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Subscribes Gmail push notifications ke Cloud Pub/Sub topic via users.watch.
     *
     * POST https://www.googleapis.com/gmail/v1/users/me/watch
     *
     * @param string|null $topicName
     * @param array $labelIds
     * @param string $labelFilterBehavior
     * @return array
     */
    public static function watch(?string $topicName = null, array $labelIds = ['INBOX'], string $labelFilterBehavior = 'INCLUDE'): array
    {
        $accessToken = self::getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan Gmail access token. Periksa GMAIL_REFRESH_TOKEN dan credentials.',
            ];
        }

        $creds = self::getCredentials();
        $targetTopic = $topicName ?: $creds['topic_name'];

        if (empty($targetTopic)) {
            return [
                'success' => false,
                'message' => 'Cloud Pub/Sub topicName belum ditentukan. Atur GMAIL_PUBSUB_TOPIC di .env.',
            ];
        }

        $payload = [
            'topicName'           => $targetTopic,
            'labelIds'            => $labelIds,
            'labelFilterBehavior' => $labelFilterBehavior,
        ];

        try {
            $response = Http::withToken($accessToken)
                ->timeout(20)
                ->post('https://gmail.googleapis.com/gmail/v1/users/me/watch', $payload);

            if (!$response->successful()) {
                $err = $response->json();
                $msg = $err['error']['message'] ?? $response->body();
                Log::error('GmailService: watch() gagal', ['payload' => $payload, 'response' => $response->body()]);
                return [
                    'success' => false,
                    'message' => 'Gmail watch error: ' . $msg,
                    'error'   => $err,
                ];
            }

            $data = $response->json();
            // Data biasanya memuat: {"historyId": "...", "expiration": "1614369235749"}
            $historyId  = $data['historyId'] ?? null;
            $expiration = $data['expiration'] ?? null;

            // Simpan status watch ke cache / storage
            Cache::put('gmail_watch_active', true, now()->addDays(7));
            Cache::put('gmail_watch_topic', $targetTopic, now()->addDays(7));
            if ($historyId) {
                Cache::put('gmail_last_history_id', $historyId, now()->addDays(30));
            }
            if ($expiration) {
                Cache::put('gmail_watch_expiration', $expiration, now()->addDays(7));
            }

            Log::info('GmailService: watch() aktif', $data);

            return [
                'success'    => true,
                'message'    => 'Gmail watch berhasil diaktifkan.',
                'topicName'  => $targetTopic,
                'historyId'  => $historyId,
                'expiration' => $expiration,
                'expiration_formatted' => $expiration ? date('Y-m-d H:i:s', (int) ($expiration / 1000)) : null,
            ];
        } catch (\Throwable $e) {
            Log::error('GmailService: watch() exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memanggil Gmail watch: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Stop Gmail push notifications via users.stop.
     *
     * POST https://www.googleapis.com/gmail/v1/users/me/stop
     *
     * @return array
     */
    public static function stopWatch(): array
    {
        $accessToken = self::getAccessToken();
        if (!$accessToken) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan Gmail access token.',
            ];
        }

        try {
            $response = Http::withToken($accessToken)
                ->timeout(15)
                ->post('https://gmail.googleapis.com/gmail/v1/users/me/stop');

            Cache::forget('gmail_watch_active');
            Cache::forget('gmail_watch_expiration');

            if ($response->successful()) {
                Log::info('GmailService: watch() dihentikan.');
                return [
                    'success' => true,
                    'message' => 'Gmail push notifications berhasil dihentikan.',
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal menghentikan watch: ' . $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('GmailService: stopWatch() exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Mengambil riwayat perubahan (history) dari Gmail API.
     *
     * @param string $startHistoryId
     * @return array|null
     */
    public static function fetchHistory(string $startHistoryId): ?array
    {
        $accessToken = self::getAccessToken();
        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::withToken($accessToken)
                ->timeout(20)
                ->get('https://gmail.googleapis.com/gmail/v1/users/me/history', [
                    'startHistoryId' => $startHistoryId,
                    'historyTypes'   => 'messageAdded',
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Jika startHistoryId kadaluarsa (404), return null agar memicu fallback
            Log::warning('GmailService: fetchHistory non-200 response', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        } catch (\Throwable $e) {
            Log::error('GmailService: fetchHistory exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengambil detail satu email berdasarkan ID.
     *
     * @param string $messageId
     * @return array|null
     */
    public static function getMessage(string $messageId): ?array
    {
        $accessToken = self::getAccessToken();
        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::withToken($accessToken)
                ->timeout(15)
                ->get("https://gmail.googleapis.com/gmail/v1/users/me/messages/{$messageId}", [
                    'format' => 'full',
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Throwable $e) {
            Log::error('GmailService: getMessage exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengambil daftar pesan terbaru dari inbox (fallback).
     *
     * @param int $maxResults
     * @return array
     */
    public static function listRecentInboxMessages(int $maxResults = 5): array
    {
        $accessToken = self::getAccessToken();
        if (!$accessToken) {
            return [];
        }

        try {
            $response = Http::withToken($accessToken)
                ->timeout(15)
                ->get('https://gmail.googleapis.com/gmail/v1/users/me/messages', [
                    'maxResults' => $maxResults,
                    'q'          => 'label:INBOX',
                ]);

            if ($response->successful()) {
                return $response->json('messages') ?? [];
            }

            return [];
        } catch (\Throwable $e) {
            Log::error('GmailService: listRecentInboxMessages exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Parse detail pesan Gmail (headers, sender, subject, snippet, body).
     *
     * @param array $msgData
     * @return array
     */
    public static function parseMessage(array $msgData): array
    {
        $headers = [];
        foreach ($msgData['payload']['headers'] ?? [] as $header) {
            $headers[strtolower($header['name'])] = $header['value'];
        }

        $rawFrom    = $headers['from'] ?? '';
        $fromEmail  = '';
        $fromName   = '';

        if (preg_match('/(.*)<(.+@.+)>$/', $rawFrom, $matches)) {
            $fromName  = trim(trim($matches[1]), '"\'');
            $fromEmail = trim($matches[2]);
        } else {
            $fromEmail = trim($rawFrom);
            $fromName  = trim($rawFrom);
        }

        $subject = $headers['subject'] ?? '(Tanpa Subjek)';
        $date    = $headers['date'] ?? null;
        $snippet = $msgData['snippet'] ?? '';

        // Ekstrak plain text body
        $body = self::extractMessageBody($msgData['payload'] ?? []);

        return [
            'id'         => $msgData['id'] ?? null,
            'thread_id'  => $msgData['threadId'] ?? null,
            'from_email' => strtolower($fromEmail),
            'from_name'  => $fromName,
            'to'         => $headers['to'] ?? '',
            'subject'    => $subject,
            'date'       => $date,
            'snippet'    => $snippet,
            'body'       => $body,
            'headers'    => $headers,
        ];
    }

    /**
     * Rekursif mengekstrak body dari payload Gmail.
     *
     * @param array $payload
     * @return string
     */
    protected static function extractMessageBody(array $payload): string
    {
        if (isset($payload['body']['data'])) {
            return self::decodeBase64Url($payload['body']['data']);
        }

        if (isset($payload['parts']) && is_array($payload['parts'])) {
            // Prioritaskan text/plain terlebih dahulu, lalu text/html
            foreach ($payload['parts'] as $part) {
                if (($part['mimeType'] ?? '') === 'text/plain' && isset($part['body']['data'])) {
                    return self::decodeBase64Url($part['body']['data']);
                }
            }
            foreach ($payload['parts'] as $part) {
                if (isset($part['body']['data'])) {
                    return strip_tags(self::decodeBase64Url($part['body']['data']));
                }
                if (isset($part['parts'])) {
                    $sub = self::extractMessageBody($part);
                    if ($sub !== '') {
                        return $sub;
                    }
                }
            }
        }

        return '';
    }

    /**
     * Memproses notifikasi webhook Cloud Pub/Sub dari Gmail.
     *
     * Menerima payload:
     * {
     *   "message": {
     *     "data": "eyJlbWFpbEFkZHJlc3MiOiAidXNlckBleGFtcGxlLmNvbSIsICJoaXN0b3J5SWQiOiAiMTIzNDU2Nzg5MCJ9",
     *     "messageId": "...",
     *     "publishTime": "..."
     *   },
     *   "subscription": "..."
     * }
     *
     * @param array $payload
     * @param string|null $clientIp
     * @return array
     */
    public static function handleIncomingPubSubNotification(array $payload, ?string $clientIp = null): array
    {
        $messageData = $payload['message'] ?? null;
        if (!$messageData || empty($messageData['data'])) {
            return [
                'success' => false,
                'message' => 'Payload Pub/Sub tidak memiliki message.data yang valid.',
            ];
        }

        $pubsubMessageId = $messageData['messageId'] ?? null;
        $publishTime     = $messageData['publishTime'] ?? null;
        $subscription    = $payload['subscription'] ?? null;

        // Cegah pemrosesan duplikat pubsubMessageId
        if ($pubsubMessageId) {
            $dedupKey = 'pubsub_processed_msg_' . $pubsubMessageId;
            if (Cache::has($dedupKey)) {
                Log::info('GmailService: Pub/Sub message sudah pernah diproses (idempotent)', ['messageId' => $pubsubMessageId]);
                return [
                    'success'    => true,
                    'message'    => 'Pesan telah diproses sebelumnya.',
                    'message_id' => $pubsubMessageId,
                ];
            }
            Cache::put($dedupKey, true, now()->addHours(24));
        }

        // Decode Base64URL dari message.data
        $rawBase64 = $messageData['data'];
        $decodedJsonString = self::decodeBase64Url($rawBase64);
        $decodedData = json_decode($decodedJsonString, true);

        if (!$decodedData || !is_array($decodedData)) {
            Log::error('GmailService: Gagal decode data JSON dari Pub/Sub', ['raw' => $rawBase64, 'decoded' => $decodedJsonString]);
            return [
                'success' => false,
                'message' => 'Gagal men-decode JSON data dari Pub/Sub message.',
            ];
        }

        $emailAddress = $decodedData['emailAddress'] ?? null;
        $historyId    = $decodedData['historyId'] ?? null;

        Log::info('GmailService: Menerima notifikasi Gmail Push dari Cloud Pub/Sub', [
            'emailAddress' => $emailAddress,
            'historyId'    => $historyId,
            'messageId'    => $pubsubMessageId,
            'publishTime'  => $publishTime,
            'subscription' => $subscription,
        ]);

        $lastHistoryId = Cache::get('gmail_last_history_id');
        $startId       = $lastHistoryId ?: $historyId;

        // Ambil daftar pesan yang baru ditambahkan
        $newMessages = [];
        if ($startId) {
            $historyResponse = self::fetchHistory((string) $startId);
            if (!empty($historyResponse['history'])) {
                foreach ($historyResponse['history'] as $historyItem) {
                    if (!empty($historyItem['messagesAdded'])) {
                        foreach ($historyItem['messagesAdded'] as $added) {
                            if (!empty($added['message']['id'])) {
                                $newMessages[] = $added['message']['id'];
                            }
                        }
                    }
                }
            }
        }

        // Fallback: jika tidak ada pesan dari history, ambil pesan terbaru dari INBOX
        if (empty($newMessages)) {
            $recent = self::listRecentInboxMessages(3);
            foreach ($recent as $item) {
                if (!empty($item['id'])) {
                    $newMessages[] = $item['id'];
                }
            }
        }

        $newMessages = array_unique($newMessages);
        $processedContracts = [];

        foreach ($newMessages as $msgId) {
            $msgCacheKey = 'gmail_processed_email_' . $msgId;
            if (Cache::has($msgCacheKey)) {
                continue;
            }
            Cache::put($msgCacheKey, true, now()->addDays(7));

            $fullMessage = self::getMessage($msgId);
            if (!$fullMessage) {
                continue;
            }

            $parsed = self::parseMessage($fullMessage);
            $matchedContract = self::matchContract($parsed);

            if ($matchedContract) {
                $notification = self::createEmailNotification($matchedContract, $parsed, $historyId, $pubsubMessageId);
                $processedContracts[] = [
                    'contract_id'     => $matchedContract->id,
                    'contract_number' => $matchedContract->contract_number,
                    'from'            => $parsed['from_email'],
                    'subject'         => $parsed['subject'],
                    'notification_id' => $notification?->id,
                ];

                // Tambahkan audit trail
                try {
                    ContractAudit::create([
                        'contract_id' => $matchedContract->id,
                        'user_id'     => null,
                        'action'      => 'gmail_reply_received',
                        'note'        => "Balasan/email diterima via Gmail dari {$parsed['from_name']} ({$parsed['from_email']}): {$parsed['subject']}",
                        'ip'          => $clientIp ?? 'Pub/Sub Webhook',
                        'user_agent'  => 'Google-Cloud-PubSub/1.0',
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('GmailService: Gagal mencatat contract audit: ' . $e->getMessage());
                }
            } else {
                Log::info('GmailService: Email masuk tidak cocok dengan kontrak aktif manapun', [
                    'from'    => $parsed['from_email'],
                    'subject' => $parsed['subject'],
                    'snippet' => $parsed['snippet'],
                ]);
            }
        }

        // Simpan historyId terbaru
        if ($historyId) {
            Cache::put('gmail_last_history_id', $historyId, now()->addDays(30));
        }

        return [
            'success'             => true,
            'message'             => 'Notifikasi Gmail berhasil diproses.',
            'emailAddress'        => $emailAddress,
            'historyId'           => $historyId,
            'pubsubMessageId'     => $pubsubMessageId,
            'messages_checked'    => count($newMessages),
            'processed_contracts' => $processedContracts,
        ];
    }

    /**
     * Mencocokkan email masuk dengan Kontrak yang relevan.
     *
     * 1. Mencari nomor kontrak di Subject atau Body (misal 'CTR-...' atau nomor kontrak yang tersimpan)
     * 2. Jika tidak ada di teks, cari Client berdasarkan email pengirim (email / pic_email)
     *
     * @param array $parsed
     * @return Contract|null
     */
    protected static function matchContract(array $parsed): ?Contract
    {
        $searchSpace = ($parsed['subject'] ?? '') . ' ' . ($parsed['body'] ?? '') . ' ' . ($parsed['snippet'] ?? '');

        // 1. Cek nomor kontrak eksak dari database yang ada di searchSpace
        $activeContracts = Contract::select(['id', 'contract_number', 'contract_name', 'client_id', 'status'])
            ->whereIn('status', ['active', 'grace_period', 'expired'])
            ->get();

        foreach ($activeContracts as $c) {
            if ($c->contract_number && stripos($searchSpace, $c->contract_number) !== false) {
                return $c;
            }
        }

        // 2. Cek regex pattern nomor kontrak (cth CTR-001-2024 atau format nomor kontrak umum)
        if (preg_match('/[A-Z0-9\/\-_]{5,30}/i', $searchSpace, $m)) {
            $candidate = trim($m[0]);
            $found = Contract::where('contract_number', 'like', "%{$candidate}%")->first();
            if ($found) {
                return $found;
            }
        }

        // 3. Cari berdasarkan email vendor/client (email atau pic_email)
        $senderEmail = $parsed['from_email'] ?? '';
        if ($senderEmail) {
            $client = Client::where('email', $senderEmail)
                ->orWhere('pic_email', $senderEmail)
                ->first();

            if ($client) {
                // Pilih kontrak aktif terbaru milik client ini
                return $client->contracts()
                    ->whereIn('status', ['active', 'grace_period'])
                    ->latest()
                    ->first() ?: $client->contracts()->latest()->first();
            }
        }

        return null;
    }

    /**
     * Membuat notifikasi respon reminder / email masuk.
     *
     * @param Contract $contract
     * @param array $parsed
     * @param string|null $historyId
     * @param string|null $pubsubMsgId
     * @return ContractNotification|null
     */
    protected static function createEmailNotification(Contract $contract, array $parsed, ?string $historyId, ?string $pubsubMsgId): ?ContractNotification
    {
        $subjectSnippet = $parsed['subject'] ?: '(Tanpa subjek)';
        $message = sprintf(
            "Tanggapan/email diterima dari %s (%s) untuk Kontrak %s: \"%s\"",
            $parsed['from_name'] ?: 'Vendor',
            $parsed['from_email'],
            $contract->contract_number,
            \Illuminate\Support\Str::limit($subjectSnippet, 80)
        );

        try {
            return ContractNotification::create([
                'contract_id' => $contract->id,
                'type'        => 'gmail_reply',
                'message'     => $message,
                'is_read'     => false,
                'sent_date'   => now(),
                'data'        => [
                    'channel'           => 'gmail_push_pubsub',
                    'gmail_message_id'  => $parsed['id'],
                    'thread_id'         => $parsed['thread_id'],
                    'history_id'        => $historyId,
                    'pubsub_message_id' => $pubsubMsgId,
                    'from_email'        => $parsed['from_email'],
                    'from_name'         => $parsed['from_name'],
                    'subject'           => $parsed['subject'],
                    'snippet'           => $parsed['snippet'],
                    'received_at'       => now()->toIso8601String(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('GmailService: Gagal membuat ContractNotification: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update .env file dengan konfigurasi baru.
     *
     * @param array $values
     * @return bool
     */
    public static function updateEnv(array $values): bool
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return false;
        }

        $content = file_get_contents($envPath);
        foreach ($values as $key => $value) {
            $value = trim((string) $value);
            // Bungkus dalam tanda kutip jika memuat spasi atau karakter spesial
            if (preg_match('/\s/', $value) && !preg_match('/^".*"$/', $value)) {
                $formattedValue = '"' . addcslashes($value, '"\\') . '"';
            } else {
                $formattedValue = $value;
            }

            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$formattedValue}", $content);
            } else {
                $content .= "\n{$key}={$formattedValue}";
            }
        }

        $saved = file_put_contents($envPath, $content) !== false;

        // Simpan juga ke storage/app/gmail_credentials.json sebagai backup permanen
        self::saveCredentialsJson($values);

        // Hapus cache access token jika credentials berubah
        Cache::forget('gmail_api_access_token');

        return $saved;
    }

    /**
     * Menyimpan credentials ke storage/app/gmail_credentials.json
     *
     * @param array $values
     * @return bool
     */
    public static function saveCredentialsJson(array $values): bool
    {
        try {
            $dir = storage_path('app');
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            $path = storage_path('app/gmail_credentials.json');
            $existing = [];
            if (file_exists($path)) {
                $existing = json_decode(file_get_contents($path), true) ?: [];
            }

            $merged = array_merge($existing, [
                'client_id'     => $values['GMAIL_CLIENT_ID'] ?? ($existing['client_id'] ?? null),
                'client_secret' => $values['GMAIL_CLIENT_SECRET'] ?? ($existing['client_secret'] ?? null),
                'refresh_token' => $values['GMAIL_REFRESH_TOKEN'] ?? ($existing['refresh_token'] ?? null),
                'topic_name'    => $values['GMAIL_PUBSUB_TOPIC'] ?? ($existing['topic_name'] ?? null),
                'from_email'    => $values['GMAIL_FROM_EMAIL'] ?? ($existing['from_email'] ?? null),
                'from_name'     => $values['GMAIL_FROM_NAME'] ?? ($existing['from_name'] ?? null),
                'updated_at'    => now()->toIso8601String(),
            ]);

            return file_put_contents($path, json_encode($merged, JSON_PRETTY_PRINT)) !== false;
        } catch (\Throwable $e) {
            Log::warning('GmailService: Gagal menyimpan gmail_credentials.json: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper Encode URL-Safe Base64.
     *
     * @param string $data
     * @return string
     */
    public static function encodeBase64Url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Helper Decode URL-Safe Base64.
     *
     * @param string $data
     * @return string
     */
    public static function decodeBase64Url(string $data): string
    {
        $padding = strlen($data) % 4;
        if ($padding > 0) {
            $data .= str_repeat('=', 4 - $padding);
        }
        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }
}
