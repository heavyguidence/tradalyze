<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Position extends Model
{
    protected $fillable = [
        'instrument_id',
        'open_datetime',
        'close_datetime',
        'quantity',
        'cost_basis',
        'realized_pnl',
        'notes',
    ];

    protected $casts = [
        'open_datetime' => 'datetime',
        'close_datetime' => 'datetime',
        'quantity' => 'decimal:2',
        'cost_basis' => 'decimal:4',
        'realized_pnl' => 'decimal:2',
    ];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function fills(): HasManyThrough
    {
        return $this->hasManyThrough(Fill::class, Instrument::class, 'id', 'instrument_id', 'instrument_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TradeTag::class, 'position_trade_tag');
    }

    public function screenshots(): HasMany
    {
        return $this->hasMany(PositionScreenshot::class);
    }

    public function isOpen(): bool
    {
        return $this->close_datetime === null;
    }

    public function isClosed(): bool
    {
        return $this->close_datetime !== null;
    }

    public function isProfitable(): bool
    {
        return $this->realized_pnl !== null && $this->realized_pnl > 0;
    }

    public function isLoss(): bool
    {
        return $this->realized_pnl !== null && $this->realized_pnl < 0;
    }
}
