<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fill extends Model
{
    protected $fillable = [
        'instrument_id',
        'datetime',
        'side',
        'quantity',
        'price',
        'fees',
        'order_id',
        'exec_id',
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'quantity' => 'decimal:2',
        'price' => 'decimal:4',
        'fees' => 'decimal:2',
    ];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function isBuy(): bool
    {
        return $this->side === 'BUY';
    }

    public function isSell(): bool
    {
        return $this->side === 'SELL';
    }
}
