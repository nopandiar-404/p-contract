<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContractNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'contract_id',
        'type',
        'message',
        'is_read',
        'data',
        'sent_date',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public const TYPE_EXPIRY = 'expiry';
    public const TYPE_GRACE_PERIOD = 'grace_period';
    public const TYPE_STATUS_CHANGE = 'status_change';
    public const TYPE_RENEWAL = 'renewal';

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

        return self::create([
            'contract_id' => $contract->id,
            'type' => self::TYPE_EXPIRY,
            'message' => $message,
            'is_read' => false,
            'data' => ['days_before' => $daysBefore, 'uuid' => (string) Str::uuid()],
            'sent_date' => now(),
        ]);
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

        return self::create([
            'contract_id' => $contract->id,
            'type' => self::TYPE_GRACE_PERIOD,
            'message' => $message,
            'is_read' => false,
            'data' => ['remaining_days' => $remaining, 'uuid' => (string) Str::uuid()],
            'sent_date' => now(),
        ]);
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

        return self::create([
            'contract_id' => $contract->id,
            'type' => self::TYPE_STATUS_CHANGE,
            'message' => $message,
            'is_read' => false,
            'data' => [
                'from' => $fromStatus,
                'to' => $toStatus,
                'uuid' => (string) Str::uuid(),
            ],
            'sent_date' => now(),
        ]);
    }

    public static function createRenewalNotification(Contract $contract, $oldEndDate, $newEndDate, $note = null)
    {
        $old = $oldEndDate ? Carbon::parse($oldEndDate)->format('d/m/Y') : '-';
        $new = $newEndDate ? Carbon::parse($newEndDate)->format('d/m/Y') : '-';
        $message = sprintf(
            "Kontrak %s (%s) diperpanjang dari %s menjadi %s.",
            $contract->contract_name,
            $contract->contract_number,
            $old,
            $new
        );

        if (!empty($note)) {
            $message .= ' Catatan: ' . $note;
        }

        return self::create([
            'contract_id' => $contract->id,
            'type' => self::TYPE_RENEWAL,
            'message' => $message,
            'is_read' => false,
            'data' => [
                'old_end' => $old,
                'new_end' => $new,
                'note' => $note,
                'uuid' => (string) Str::uuid(),
            ],
            'sent_date' => now(),
        ]);
    }
}
