<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_number',
        'nomor_surat',
        'contract_name',
        'contract_type',
        'client_id',
        'vendor_name',
        'coa_id',
        'description',
        'dpp',
        'start_date',
        'end_date',
        'status',
        'contract_term',
        'tahun_1',
        'tahun_2',
        'jumlah',
        'ppn',
        'total',
        'pr',
        'po',
        'ttb',
        'berita_acara',
        'payment_status',
        'payment_date',
        'keterangan',
        'grace_period_days',
        'actual_end_date',
        'termination_reason',
        'pdf_files',
        'created_by',
        'account_name',
        'account_position'
    ];

    // Gunakan $casts untuk date fields (lebih modern dari $dates)
    protected $casts = [
        'dpp' => 'decimal:2',
        'tahun_1' => 'decimal:2',
        'tahun_2' => 'decimal:2',
        'jumlah' => 'decimal:2',
        'ppn' => 'decimal:2',
        'total' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_end_date' => 'date',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'grace_period_days' => 'integer',
        'pdf_files' => 'array',
    ];

    protected $appends = [
        'days_remaining',
        'is_in_grace_period',
        'grace_period_end_date',
        'remaining_grace_period_days',
        'status_badge',
        'priority',
        'duration_in_days'
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(ContractNotification::class);
    }

    public function boqItems()
    {
        return $this->hasMany(ContractBoqItem::class);
    }

    // Accessors
    public function getJumlahAttribute($value)
    {
        if ($value !== null) {
            return (float) $value;
        }
        if ($this->tahun_1 !== null || $this->tahun_2 !== null) {
            return (float) ($this->tahun_1 ?? 0) + (float) ($this->tahun_2 ?? 0);
        }
        return null;
    }

    public function getPpnAttribute($value)
    {
        if ($value !== null) {
            return (float) $value;
        }
        $jumlah = $this->jumlah;
        if ($jumlah !== null) {
            return round((float)$jumlah * 0.11, 2);
        }
        return null;
    }

    public function getTotalAttribute($value)
    {
        if ($value !== null) {
            return (float) $value;
        }
        $jumlah = $this->jumlah;
        if ($jumlah !== null) {
            $ppn = $this->ppn ?? round((float)$jumlah * 0.11, 2);
            return round((float)$jumlah + (float)$ppn, 2);
        }
        return null;
    }

    public function getDaysRemainingAttribute()
    {
        if (in_array($this->status, ['expired', 'terminated'])) {
            return 0;
        }

        try {
            $endDate = $this->actual_end_date ?? $this->end_date;
            if (!$endDate) return 0;

            $days = Carbon::now()->diffInDays(Carbon::parse($endDate), false);
            return max(0, $days);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getIsInGracePeriodAttribute()
    {
        if ($this->status !== 'grace_period') {
            return false;
        }

        try {
            $gracePeriodEnd = Carbon::parse($this->end_date)->addDays($this->grace_period_days);
            return Carbon::now()->lte($gracePeriodEnd);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getGracePeriodEndDateAttribute()
    {
        try {
            if (!$this->end_date) return null;
            return Carbon::parse($this->end_date)->addDays($this->grace_period_days ?? 30);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getRemainingGracePeriodDaysAttribute()
    {
        if ($this->status !== 'grace_period') {
            return 0;
        }

        try {
            if (!$this->end_date) return 0;
            $gracePeriodEnd = Carbon::parse($this->end_date)->addDays($this->grace_period_days ?? 30);
            $remainingDays = Carbon::now()->diffInDays($gracePeriodEnd, false);
            return max(0, $remainingDays);
        } catch (\Exception $e) {
            return 0;
        }
    }

    // Tambahkan accessor untuk durasi kontrak
    public function getDurationInDaysAttribute()
    {
        try {
            if (!$this->start_date || !$this->end_date) return 0;
            return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date));
        } catch (\Exception $e) {
            return 0;
        }
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeInGracePeriod(Builder $query): Builder
    {
        return $query->where('status', 'grace_period');
    }

    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->where('status', 'active')
            ->whereDate('end_date', '<=', Carbon::now()->addDays($days))
            ->whereDate('end_date', '>=', Carbon::now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', 'expired');
    }

    public function scopeTerminated(Builder $query): Builder
    {
        return $query->where('status', 'terminated');
    }

    // Scope untuk pencarian
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('contract_number', 'like', "%{$search}%")
                ->orWhere('contract_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('client', function (Builder $clientQuery) use ($search) {
                    $clientQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        });
    }

    // Business Logic Methods
    public function canRenew()
    {
        return in_array($this->status, ['active', 'grace_period'])
            && $this->days_remaining <= 30;
    }

    public function shouldNotify()
    {
        if ($this->status === 'active') {
            return $this->days_remaining <= 30;
        } elseif ($this->status === 'grace_period') {
            return $this->remaining_grace_period_days <= 7;
        }
        return false;
    }

    // Status Badge dengan HTML escape
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => ['color' => 'success', 'text' => 'Aktif', 'icon' => 'check-circle'],
            'grace_period' => ['color' => 'warning', 'text' => 'Masa Tenggang', 'icon' => 'clock'],
            'expired' => ['color' => 'danger', 'text' => 'Kadaluarsa', 'icon' => 'times-circle'],
            'terminated' => ['color' => 'secondary', 'text' => 'Dihentikan', 'icon' => 'ban']
        ];

        $status = $this->status;
        $badge = $badges[$status] ?? ['color' => 'secondary', 'text' => ucfirst($status), 'icon' => 'question-circle'];

        $html = '<span class="badge bg-' . e($badge['color']) . '">';
        $html .= '<i class="fas fa-' . e($badge['icon']) . ' me-1"></i>';
        $html .= e($badge['text']) . '</span>';

        return $html;
    }

    // Priority dengan status yang lebih detail
    public function getPriorityAttribute()
    {
        if ($this->status === 'grace_period') {
            $remainingDays = $this->remaining_grace_period_days;
            if ($remainingDays < 3) {
                return ['level' => 'high', 'text' => 'Tinggi', 'color' => 'danger'];
            } elseif ($remainingDays < 7) {
                return ['level' => 'medium', 'text' => 'Sedang', 'color' => 'warning'];
            } else {
                return ['level' => 'low', 'text' => 'Rendah', 'color' => 'info'];
            }
        }

        if ($this->status === 'active') {
            $daysRemaining = $this->days_remaining;
            if ($daysRemaining < 7) {
                return ['level' => 'high', 'text' => 'Tinggi', 'color' => 'danger'];
            } elseif ($daysRemaining < 14) {
                return ['level' => 'medium', 'text' => 'Sedang', 'color' => 'warning'];
            } elseif ($daysRemaining < 30) {
                return ['level' => 'low', 'text' => 'Rendah', 'color' => 'info'];
            }
        }

        return ['level' => 'none', 'text' => 'Normal', 'color' => 'secondary'];
    }

    // Boot method untuk event handlers
    protected static function boot()
    {
        parent::boot();

        // Auto-generate contract number jika kosong
        static::creating(function ($contract) {
            if (empty($contract->contract_number)) {
                $contract->contract_number = self::generateContractNumber();
            }

            // Set created_by jika kosong
            if (empty($contract->created_by) && Auth::check()) {
                $contract->created_by = Auth::id();
            }
        });

        // Auto-update status berdasarkan tanggal dan hitung jumlah tahun, PPN (11% * Jumlah), dan Total (Jumlah + PPN)
        static::saving(function ($contract) {
            $contract->updateStatusBasedOnDate();
            if ($contract->tahun_1 !== null || $contract->tahun_2 !== null) {
                $contract->jumlah = (float)($contract->tahun_1 ?? 0) + (float)($contract->tahun_2 ?? 0);
            }
            if ($contract->jumlah !== null) {
                $contract->ppn = round((float)$contract->jumlah * 0.11, 2);
                $contract->total = round((float)$contract->jumlah + (float)$contract->ppn, 2);
            } else {
                $contract->ppn = null;
                $contract->total = null;
            }
        });
    }

    // Method untuk generate contract number
    public static function generateContractNumber()
    {
        $prefix = 'CTR';
        $year = date('Y');
        $month = date('m');
        $sequence = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count() + 1;

        return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $sequence);
    }

    // Method untuk update status otomatis berdasarkan tanggal
    public function updateStatusBasedOnDate()
    {
        if ($this->status === 'terminated') {
            return;
        }

        $now = Carbon::now();
        $endDate = Carbon::parse($this->end_date);
        $actualEndDate = $this->actual_end_date ? Carbon::parse($this->actual_end_date) : null;

        if ($actualEndDate && $actualEndDate->lt($now)) {
            $this->status = 'terminated';
            return;
        }

        if ($endDate->lt($now)) {
            $gracePeriodEnd = $endDate->copy()->addDays($this->grace_period_days ?? 30);

            if ($gracePeriodEnd->gte($now)) {
                $this->status = 'grace_period';
            } else {
                $this->status = 'expired';
            }
        } else {
            $this->status = 'active';
        }
    }

    // Formatted attributes
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date ? $this->start_date->format('d/m/Y') : '-';
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('d/m/Y') : '-';
    }

    public function getFormattedActualEndDateAttribute()
    {
        return $this->actual_end_date ? $this->actual_end_date->format('d/m/Y') : '-';
    }
}
