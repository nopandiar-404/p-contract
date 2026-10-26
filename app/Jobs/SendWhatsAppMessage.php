<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\WhatsAppService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WhatsAppFailureAlert;
use App\Models\ContractNotification;
use Carbon\Carbon;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = [60, 300, 900];
    public $timeout = 30;

    public $to;
    public $mode; // 'text'|'template'|'media'
    public $payload; // array with mode-specific data
    public $contractId;
    public $notificationUuid;

    /**
     * Create a new job instance.
     */
    public function __construct(string $to, string $mode = 'text', array $payload = [], $contractId = null, $notificationUuid = null)
    {
        $this->to = $to;
        $this->mode = $mode;
        $this->payload = $payload;
        $this->contractId = $contractId;
        $this->notificationUuid = $notificationUuid;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $wa = new WhatsAppService();

        try {
            switch ($this->mode) {
                case 'template':
                    $template = $this->payload['template_name'] ?? null;
                    $language = $this->payload['language'] ?? 'id_ID';
                    $components = $this->payload['components'] ?? [];
                    if (!$template) {
                        throw new \InvalidArgumentException('Template name is required for template messages');
                    }
                    $res = $wa->sendTemplate($this->to, $template, $language, $components);
                    if ($res === null) {
                        // WhatsApp not configured, skip silently
                        Log::info('WhatsApp not configured - skipping template send', [
                            'to' => $this->to,
                            'contract_id' => $this->contractId,
                        ]);
                        return;
                    }
                    Log::channel('whatsapp')->info('WhatsApp template sent', [
                        'to' => $this->to,
                        'template' => $template,
                        'response' => $res,
                        'contract_id' => $this->contractId,
                    ]);

                    $this->markNotificationSent($res);
                    break;
                case 'media':
                    $mediaType = $this->payload['media_type'] ?? null;
                    $media = $this->payload['media'] ?? [];
                    $caption = $this->payload['caption'] ?? null;
                    if (!$mediaType || empty($media)) {
                        throw new \InvalidArgumentException('Media type and media data are required for media messages');
                    }
                    $res = $wa->sendMedia($this->to, $mediaType, $media, $caption);
                    if ($res === null) {
                        // WhatsApp not configured, skip silently
                        Log::info('WhatsApp not configured - skipping media send', [
                            'to' => $this->to,
                            'contract_id' => $this->contractId,
                        ]);
                        return;
                    }
                    Log::channel('whatsapp')->info('WhatsApp media sent', [
                        'to' => $this->to,
                        'media_type' => $mediaType,
                        'response' => $res,
                        'contract_id' => $this->contractId,
                    ]);

                    $this->markNotificationSent($res);
                    break;
                case 'text':
                default:
                    $text = $this->payload['text'] ?? '';
                    $res = $wa->sendText($this->to, $text);
                    if ($res === null) {
                        // WhatsApp not configured, skip silently
                        Log::info('WhatsApp not configured - skipping text send', [
                            'to' => $this->to,
                            'contract_id' => $this->contractId,
                        ]);
                        return;
                    }
                    Log::channel('whatsapp')->info('WhatsApp text sent', [
                        'to' => $this->to,
                        'response' => $res,
                        'contract_id' => $this->contractId,
                    ]);

                    $this->markNotificationSent($res);
                    break;
            }
        } catch (Exception $e) {
            Log::error('WhatsApp send failed', ['to' => $this->to, 'mode' => $this->mode, 'error' => $e->getMessage()]);

            // For all exceptions, rethrow so Laravel can retry according to $tries/backoff
            throw $e;
        }
    }

    /**
     * Mark related ContractNotification as sent (or attach response info).
     */
    protected function markNotificationSent($response = null)
    {
        if ($this->notificationUuid) {
            // Try to find by UUID first
            try {
                $candidate = ContractNotification::where('data->uuid', $this->notificationUuid)->first();
            } catch (\Throwable $t) {
                // Fallback to string match for DB without JSON operator
                $candidate = ContractNotification::where('data', 'like', '%"uuid":"' . $this->notificationUuid . '"%')->first();
            }
        }

        if (empty($candidate) && !$this->contractId) {
            return;
        }

        try {
            if (empty($candidate)) {
                $query = ContractNotification::where('contract_id', $this->contractId)
                    ->where('type', ContractNotification::TYPE_EXPIRY)
                    ->whereNull('sent_date')
                    ->orderBy('created_at', 'desc');

                // Prefer queued candidate when possible
                try {
                    $candidate = (clone $query)->where('data->queued', true)->first();
                } catch (\Throwable $t) {
                    $candidate = $query->first();
                }
            }

            if ($candidate) {
                $data = is_array($candidate->data) ? $candidate->data : (is_string($candidate->data) ? json_decode($candidate->data, true) ?? [] : []);
                $data['sent'] = true;
                $data['sent_at'] = Carbon::now()->toDateTimeString();

                // Add a compact response representation
                if ($response !== null) {
                    if (is_string($response)) {
                        $data['response'] = $response;
                    } elseif (is_array($response)) {
                        $data['response'] = $response;
                    } elseif (method_exists($response, 'body')) {
                        $data['response'] = $response->body();
                    } else {
                        $data['response'] = json_encode($response);
                    }
                }

                $candidate->data = $data;
                $candidate->sent_date = Carbon::now();
                $candidate->save();
            }
        } catch (\Exception $e) {
            Log::warning('Failed to mark ContractNotification as sent', ['error' => $e->getMessage(), 'contract_id' => $this->contractId, 'uuid' => $this->notificationUuid]);
        }
    }

    /**
     * Called when the job fails permanently.
     */
    public function failed(\Throwable $e)
    {
        $context = ['to' => $this->to, 'mode' => $this->mode, 'error' => $e->getMessage(), 'payload' => $this->payload];
        Log::channel('whatsapp')->error('WhatsApp job failed permanently', $context);

        // Mark related notification as failed
        try {
            $candidate = null;
            if ($this->notificationUuid) {
                try {
                    $candidate = ContractNotification::where('data->uuid', $this->notificationUuid)->first();
                } catch (\Throwable $t) {
                    $candidate = ContractNotification::where('data', 'like', '%"uuid":"' . $this->notificationUuid . '"%')->first();
                }
            }

            if (empty($candidate) && $this->contractId) {
                $candidate = ContractNotification::where('contract_id', $this->contractId)
                    ->where('type', ContractNotification::TYPE_EXPIRY)
                    ->whereNull('sent_date')
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            if ($candidate) {
                $data = is_array($candidate->data) ? $candidate->data : (is_string($candidate->data) ? json_decode($candidate->data, true) ?? [] : []);
                $data['sent'] = false;
                $data['error'] = $e->getMessage();
                $candidate->data = $data;
                $candidate->save();
            }
        } catch (\Exception $ex) {
            Log::channel('whatsapp')->error('Failed to mark ContractNotification as failed', ['error' => $ex->getMessage()]);
        }

        // Send Slack alert if webhook configured
        $webhook = env('WHATSAPP_ALERT_SLACK_WEBHOOK');
        if (!empty($webhook)) {
            try {
                Notification::route('slack', $webhook)
                    ->notify(new WhatsAppFailureAlert($this->to, $e->getMessage(), $this->contractId ?? null));
            } catch (Exception $notifyEx) {
                Log::channel('whatsapp')->error('Failed to send WhatsApp failure alert', ['error' => $notifyEx->getMessage()]);
            }
        }
    }
}
