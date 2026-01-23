<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = [
        'broker',
        'trade_id',
        'symbol',
        'asset_class',
        'underlying_symbol',
        'description',
        'open_close_indicator',
        'buy_sell',
        'quantity',
        'trade_price',
        'trade_date',
        'date_time',
        'fifo_pnl_realized',
        'net_cash',
        'currency_primary',
        'put_call',
        'strike',
        'expiry',
        'multiplier',
        'ib_commission',
        'ib_commission_currency',
        'trade_money',
        'proceeds',
        'exchange',
        'cost_basis',
    ];

    protected $casts = [
        'trade_date' => 'date',
        'date_time' => 'datetime',
        'expiry' => 'date',
        'quantity' => 'decimal:4',
        'trade_price' => 'decimal:4',
        'fifo_pnl_realized' => 'decimal:2',
        'net_cash' => 'decimal:2',
        'strike' => 'decimal:2',
        'ib_commission' => 'decimal:4',
        'trade_money' => 'decimal:2',
        'proceeds' => 'decimal:2',
        'cost_basis' => 'decimal:2',
    ];

    /**
     * Check if the trade is closed
     */
    public function isClosed(): bool
    {
        return $this->open_close_indicator === 'C';
    }

    /**
     * Check if the trade is an option
     */
    public function isOption(): bool
    {
        return $this->asset_class === 'OPT';
    }

    /**
     * Check if the trade is profitable
     */
    public function isProfitable(): bool
    {
        return $this->isClosed() && $this->fifo_pnl_realized > 0;
    }
}
