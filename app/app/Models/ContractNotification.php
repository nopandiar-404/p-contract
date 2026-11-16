<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Jobs\SendWhatsAppMessage;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Contract;

class ContractNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications'; // Tetap menggunakan tabel notifications

    protected $fillable = [
        'contract_id',
        'type',
        'message',
        'is_read',
        'data',
        'sent_date'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    const TYPE_EXPIRY = 'expiry';
    const TYPE_GRACE_PERIOD = 'grace_period';
    const TYPE_STATUS_CHANGE = 'status_change';
    const TYPE_RENEWAL = 'renewal';

    // Helper factory methods that persist a notification and attempt WhatsApp delivery
    public static function createExpiryNotification(Contract $contract, $daysBefore)
    {
        $endDate = $contract->end_date ? Carbon::parse($contract->end_date)->format('d/m/Y') : '-';
        $message = sprintf(
            "Kontrak %s (%s) akan berakhir dalam %d hari pada %s.",
            $contract->contract_name,
            $contract->contract_number,
            $daysBefore,
            $endDate
        );

        $uuid = (string) Str::uuid();

        $notification = self::create([
            'contract_id' => $contract->id,
            'type' => self::TYPE_EXPIRY,
            'message' => $message,
            'is_read' => false,
            'data' => ['days_before' => $daysBefore, 'uuid' => $uuid],
            'sent_date' => now()
        ]);

        // Dispatch WhatsApp send job to queue (non-blocking)
        if ($contract->client && !empty($contract->client->phone)) {
            $waConfigured = config('services.whatsapp.access_token') && (config('services.whatsapp.phone_number_id') || env('WHATSAPP_API_URL'));

            if (!$waConfigured) {
                Log::warning('WhatsApp not configured - skipping dispatch', [
                    'contract_id' => $contract->id,
                    'to' => $contract->client->phone,
                ]);
            } else {
                try {
                    // Prefer per-day template mapping if configured
                    $templateName = config('services.whatsapp.templates.per_day.' . $daysBefore);
                    if (!$templateName) {
                        // support common typo alias
                        $templateName = config('services.whatsapp.templates.per_dau.' . $daysBefore);
                    }

                    // Also allow config like services.whatsapp.templates.expiry_{days}
                    if (!$templateName) {
                        $expiryKey = 'services.whatsapp.templates.expiry_' . $daysBefore;
                        $templateName = config($expiryKey);
                    }

                    if (!$templateName) {
                        if ((int)$daysBefore === 1) {
                            $templateName = 'contract_reminder_1_day';
                        } else {
                            $templateName = "contract_reminder_{$daysBefore}_days";
                        }
                    }

                    if ($templateName) {
                        $components = [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $contract->client->name ?? ''],
                                    ['type' => 'text', 'text' => $contract->contract_name],
                                    ['type' => 'text', 'text' => $contract->contract_number],
                                    ['type' => 'text', 'text' => (string) $daysBefore],
                                    ['type' => 'text', 'text' => $endDate],
                                ]
                            ]
                        ];

                        $payload = ['template_name' => $templateName, 'language' => 'id_ID', 'components' => $components];
                        Log::info('Dispatching WhatsApp template job', [
                            'to' => $contract->client->phone,
                            'mode' => 'template',
                            'payload' => $payload,
                            'contract_id' => $contract->id,
                            'queue' => 'whatsapp'
                        ]);

                        SendWhatsAppMessage::dispatch($contract->client->phone, 'template', $payload, $contract->id, $uuid)->onQueue('whatsapp');
                    } else {
                        $textPayload = ['text' => $message];
                        Log::info('Dispatching WhatsApp text job', [
                            'to' => $contract->client->phone,
                            'mode' => 'text',
                            'payload' => $textPayload,
                            'contract_id' => $contract->id,
                            'queue' => 'whatsapp'
                        ]);
                        SendWhatsAppMessage::dispatch($contract->client->phone, 'text', $textPayload, $contract->id, $uuid)->onQueue('whatsapp');
                    }
                } catch (Exception $e) {
                    Log::error('Failed to dispatch WhatsApp job', ['error' => $e->getMessage(), 'contract_id' => $contract->id]);
                }
            }
        }

        return $notification;
    }

    public static function createGracePeriodNotification(Contract $contract)
    {
        $remaining = $contract->remaining_grace_period_days;
        $message = sprintf(
            "Kontrak %s (%s) sedang dalam masa tenggang. Sisa %d hari.",
            $contract->contract_name,
            $contract->contract_number,
            $remaining
        );

        $uuid = (string) Str::uuid();

        $notification = self::create([
            'contract_id' => $contract->id,
            'type' => self::TYPE_GRACE_PERIOD,
            'message' => $message,
            'is_read' => false,
            'data' => ['remaining_days' => $remaining, 'uuid' => $uuid],
            'sent_date' => now()
        ]);

        if ($contract->client && !empty($contract->client->phone)) {
            $waConfigured = config('services.whatsapp.access_token') && (config('services.whatsapp.phone_number_id') || env('WHATSAPP_API_URL'));
            if (!$waConfigured) {
                Log::warning('WhatsApp not configured - skipping dispatch (grace period)', [
                    'contract_id' => $contract->id,
                    'to' => $contract->client->phone,
                ]);
            } else {
                try {
                    $textPayload = ['text' => $message];
                    Log::info('Dispatching WhatsApp text job (grace period)', [
                        'to' => $contract->client->phone,
                        'mode' => 'text',
                        'payload' => $textPayload,
                        'contract_id' => $contract->id,
                        'queue' => 'whatsapp'
                    ]);
                    SendWhatsAppMessage::dispatch($contract->client->phone, 'text', $textPayload, $contract->id, $uuid)->onQueue('whatsapp');
                } catch (Exception $e) {
                    Log::error('Failed to dispatch WhatsApp job', ['error' => $e->getMessage(), 'contract_id' => $contract->id]);
                }
            }
        }

        return $notification;
    }

    public static function createStatusChangeNotification(Contract $contract, $fromStatus, $toStatus)
    {
        $message = sprintf(
            "Status kontrak %s (%s) berubah dari %s menjadi %s.",
            $contract->contract_name,
            $contract->contract_number,
            $fromStatus,
            $toStatus
        );

        $uuid = (string) Str::uuid();

        $notification = self::create([
            'contract_id' => $contract->id,
            'type' => self::TYPE_STATUS_CHANGE,
            'message' => $message,
            'is_read' => false,
            'data' => ['from' => $fromStatus, 'to' => $toStatus, 'uuid' => $uuid],
            'sent_date' => now()
        ]);

        if ($contract->client && !empty($contract->client->phone)) {
            $waConfigured = config('services.whatsapp.access_token') && (config('services.whatsapp.phone_number_id') || env('WHATSAPP_API_URL'));
            if (!$waConfigured) {
                Log::warning('WhatsApp not configured - skipping dispatch (status change)', [
                    'contract_id' => $contract->id,
                    'to' => $contract->client->phone,
                    'from' => $fromStatus,
                    'to_status' => $toStatus,
                ]);
            } else {
                try {
                    $textPayload = ['text' => $message];
                    Log::info('Dispatching WhatsApp text job (status change)', [
                        'to' => $contract->client->phone,
                        'mode' => 'text',
                        'payload' => $textPayload,
                        'contract_id' => $contract->id,
                        'queue' => 'whatsapp',
                        'from' => $fromStatus,
                        'to_status' => $toStatus
                    ]);
                    SendWhatsAppMessage::dispatch($contract->client->phone, 'text', $textPayload, $contract->id, $uuid)->onQueue('whatsapp');
                } catch (Exception $e) {
                    Log::error('Failed to dispatch WhatsApp job', ['error' => $e->getMessage(), 'contract_id' => $contract->id]);
                }
            }
        }

        return $notification;
    }

    public static function createRenewalNotification(Contract $contract, $oldEndDate, $newEndDate, $note = null)
    {
        $old = $oldEndDate ? \Carbon\Carbon::parse($oldEndDate)->format('d/m/Y') : '-';
        $new = $newEndDate ? \Carbon\Carbon::parse($newEndDate)->format('d/m/Y') : '-';

        $message = sprintf(
            "Kontrak %s (%s) diperpanjang dari %s menjadi %s.",
            $contract->contract_name,
            $contract->contract_number,
            $old,
            $new
        );

        if (!empty($note)) {
            $message .= " Catatan: " . $note;
        }

        $uuid = (string) \Illuminate\Support\Str::uuid();

        $notification = self::create([
            'contract_id' => $contract->id,
            'type' => self::TYPE_RENEWAL,
            'message' => $message,
            'is_read' => false,
            'data' => ['old_end' => $old, 'new_end' => $new, 'note' => $note, 'uuid' => $uuid],
            'sent_date' => now()
        ]);

        // dispatch WhatsApp text message if available
        if ($contract->client && !empty($contract->client->phone)) {
            $waConfigured = config('services.whatsapp.access_token') && (config('services.whatsapp.phone_number_id') || env('WHATSAPP_API_URL'));
            if (!$waConfigured) {
                Log::warning('WhatsApp not configured - skipping renewal dispatch', [
                    'contract_id' => $contract->id,
                    'to' => $contract->client->phone,
                ]);
            } else {
                try {
                    $textPayload = ['text' => $message];
                    SendWhatsAppMessage::dispatch($contract->client->phone, 'text', $textPayload, $contract->id, $uuid)->onQueue('whatsapp');
                } catch (\Exception $e) {
                    Log::error('Failed to dispatch WhatsApp job (renewal)', ['error' => $e->getMessage(), 'contract_id' => $contract->id]);
                }
            }
        }

        return $notification;
    }
}
