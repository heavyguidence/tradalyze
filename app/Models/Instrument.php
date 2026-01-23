<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instrument extends Model
{
    protected $fillable = [
        'user_id',
        'symbol',
        'underlying_symbol',
        'asset_type',
        'expiry',
        'strike',
        'put_call',
        'multiplier',
        'currency',
    ];

    protected $casts = [
        'expiry' => 'date',
        'strike' => 'decimal:2',
        'multiplier' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fills(): HasMany
    {
        return $this->hasMany(Fill::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function ledger(): HasMany
    {
        return $this->hasMany(Ledger::class);
    }

    public function isOption(): bool
    {
        return $this->asset_type === 'OPT';
    }

    public function isStock(): bool
    {
        return $this->asset_type === 'STK';
    }
}
