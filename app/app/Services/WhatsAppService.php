<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $client;
    protected $apiUrl;
    protected $token;

    protected $phoneNumberId;

    public function __construct()
    {
        $this->client = new Client(['timeout' => 10]);
        // Prefer Laravel config (config/services.php) but fall back to env vars
        $baseApi = config('services.whatsapp.api_url') ?? env('WHATSAPP_API_URL', 'https://graph.facebook.com');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id') ?? env('WHATSAPP_PHONE_NUMBER_ID');
        $this->token = config('services.whatsapp.access_token') ?? env('WHATSAPP_ACCESS_TOKEN');

        // If phone number id is provided, construct the Graph API messages endpoint
        if (!empty($this->phoneNumberId)) {
            // allow user to optionally include version in baseApi, otherwise default v15.0
            $this->apiUrl = rtrim($baseApi, '/') . '/v15.0/' . $this->phoneNumberId . '/messages';
        } else {
            // fallback to env-provided full endpoint
            $this->apiUrl = config('services.whatsapp.api_url') ?? env('WHATSAPP_API_URL');
        }
    }

    /**
     * Send a plain text WhatsApp message via configured API endpoint.
     * The endpoint and token are provided via config/services.php or env vars.
     * Expected payload: { to: phone, message: text }
     */
    public function sendMessage(string $to, string $message)
    {
        return $this->sendText($to, $message);
    }

    /**
     * Alias for sending plain text messages
     */
    public function sendText(string $to, string $message)
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $message]
        ];

        return $this->post($payload);
    }

    /**
     * Send a template message (pre-approved template) via WhatsApp Cloud API.
     * $components should follow WhatsApp Cloud API template components structure.
     */
    public function sendTemplate(string $to, string $templateName, string $language = 'en_US', array $components = [])
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => $language],
            ]
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        return $this->post($payload);
    }

    /**
     * Send media message. $media should be an array like ['link' => 'https://...'] or ['id' => 'MEDIA_ID']
     * type: one of 'image','document','audio','video'. Optionally include 'caption'.
     */
    public function sendMedia(string $to, string $type, array $media, string $caption = null)
    {
        $allowed = ['image', 'document', 'audio', 'video'];
        if (!in_array($type, $allowed)) {
            throw new \InvalidArgumentException('Unsupported media type: ' . $type);
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => $type,
        ];

        $payload[$type] = $media; // e.g. ['link' => 'https://...'] or ['id' => 'MEDIA_ID']
        if ($caption && in_array($type, ['image', 'video', 'document'])) {
            $payload[$type]['caption'] = $caption;
        }

        return $this->post($payload);
    }

    /**
     * Centralized POST helper for Graph API
     */
    protected function post(array $payload)
    {
        if (empty($this->apiUrl) || empty($this->token)) {
            // Instead of throwing exception, just log and return null
            Log::warning('WhatsApp API not configured - skipping send', [
                'api_url_configured' => !empty($this->apiUrl),
                'token_configured' => !empty($this->token)
            ]);
            return null;
        }

        $response = $this->client->post($this->apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => $payload
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}
