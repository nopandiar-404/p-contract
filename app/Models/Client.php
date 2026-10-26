<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'pic_name',
        'pic_phone',
        'pic_email',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // Accessors
    public function getAvatarInitialsAttribute()
    {
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->status == 'active') {
            return '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Aktif</span>';
        } else {
            return '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Non-Aktif</span>';
        }
    }

    public function getHasContractsAttribute()
    {
        return $this->contracts()->exists();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('pic_name', 'like', "%{$search}%");
        });
    }
}