<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TradeTag extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'color',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'position_trade_tag');
    }

    public function getTradeCountAttribute(): int
    {
        return $this->positions()->count();
    }
}
