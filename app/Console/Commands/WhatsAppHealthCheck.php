<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class WhatsAppHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:health-check {--v|verbose : Show full response body} {--o|output= : Output format (text|json)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick health check for WhatsApp configuration and connectivity';

    public function handle()
    {
        $this->info('Starting WhatsApp health check...');

        $apiUrl = config('services.whatsapp.api_url');
        $phoneId = config('services.whatsapp.phone_number_id');
        $accessToken = config('services.whatsapp.access_token');

        if (empty($apiUrl) && empty($phoneId) && empty($accessToken)) {
            $this->error('WhatsApp is not configured. Set WHATSAPP_API_URL or WHATSAPP_PHONE_NUMBER_ID and WHATSAPP_ACCESS_TOKEN in your .env.');
            return 1;
        }

        // If Meta Graph API detected and phone id + token provided, perform a deeper Meta-specific check
        $outputFormat = $this->option('output') ?: 'text';
        if (stripos($apiUrl ?? '', 'graph.facebook.com') !== false && $phoneId && $accessToken) {
            $this->info('Detected Meta Graph API. Performing Meta-specific verification...');
            try {
                $version = 'v15.0';
                $url = rtrim($apiUrl, '/') . '/' . $version . '/' . $phoneId . '?fields=display_phone_number,quality_rating,owner_business_info';
                /** @var Response $response */
                $response = Http::withHeaders(['Authorization' => 'Bearer ' . $accessToken])
                    ->timeout(8)
                    ->get($url);

                $result = ['provider' => 'meta', 'url' => $url, 'status' => $response->status(), 'ok' => $response->successful()];

                if ($response->successful()) {
                    $payload = $response->json();
                    $result['data'] = $payload;

                    // Basic checks
                    $result['checks'] = [
                        'has_display_phone' => !empty($payload['display_phone_number']),
                        'has_owner_info' => !empty($payload['owner_business_info']) || !empty($payload['quality_rating']),
                    ];

                    if ($outputFormat === 'json') {
                        $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    } else {
                        $this->info('Meta Graph API reachable and phone_number_id appears valid.');
                        $this->line('Display phone: ' . ($payload['display_phone_number'] ?? '-'));
                        $this->line('Quality rating: ' . ($payload['quality_rating'] ?? '-'));
                    }

                    return 0;
                }

                $result['body'] = $response->body();
                if ($outputFormat === 'json') {
                    $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                } else {
                    $this->error('Meta Graph API request failed: HTTP ' . $response->status());
                    if ($this->option('verbose')) {
                        $this->line($response->body());
                    }
                }

                return 2;
            } catch (\Exception $e) {
                Log::error('WhatsApp health check failed', ['error' => $e->getMessage()]);
                if ($outputFormat === 'json') {
                    $this->line(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_SLASHES));
                } else {
                    $this->error('Request error: ' . $e->getMessage());
                }
                return 3;
            }
        }

        // Generic provider check: try a simple GET to the API root or provided URL
        if ($apiUrl) {
            $this->info('Performing connectivity check to WHATSAPP_API_URL: ' . $apiUrl);
            try {
                $client = Http::withOptions(['verify' => true])->timeout(6);
                if ($accessToken) {
                    $client = $client->withToken($accessToken);
                }

                /** @var Response $response */
                $response = $client->get($apiUrl);

                if ($response->successful()) {
                    $this->info('Provider reachable (HTTP ' . $response->status() . ').');
                    if ($this->option('verbose')) {
                        $this->line($response->body());
                    }
                    return 0;
                }

                $this->error('Provider responded with HTTP ' . $response->status());
                if ($this->option('verbose')) {
                    $this->line($response->body());
                }
                return 4;
            } catch (\Exception $e) {
                Log::error('WhatsApp provider connectivity error', ['error' => $e->getMessage()]);
                $this->error('Connectivity error: ' . $e->getMessage());
                return 5;
            }
        }

        $this->error('Insufficient configuration to perform health check.');
        return 6;
    }
}
