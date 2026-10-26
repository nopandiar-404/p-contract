<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractBoqItem extends Model
{
    protected $fillable = [
        'description',
        'unit',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function getFormattedQuantityAttribute(): string
    {
        $formatted = number_format((float) $this->quantity, 3, ',', '.');

        return rtrim(rtrim($formatted, '0'), ',');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}