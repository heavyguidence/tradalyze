<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ledger extends Model
{
    protected $table = 'ledger';

    protected $fillable = [
        'instrument_id',
        'datetime',
        'type',
        'amount',
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
