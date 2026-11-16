<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'contract_id',
        'type',
        'message',
        'is_read',
        'data'
    ];
    
    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array'
    ];
    
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}