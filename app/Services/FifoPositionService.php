<?php

namespace App\Services;

use App\Models\Fill;
use App\Models\Instrument;
use App\Models\Position;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

class FifoPositionService
{
    /**
     * Process all fills for an instrument and create/update positions using FIFO
     */
    public function processInstrument(Instrument $instrument): void
    {
        DB::beginTransaction();
        
        try {
            // Delete existing positions for this instrument (we'll recalculate)
            $instrument->positions()->delete();
            
            // Get all fills for this instrument, ordered by datetime
            $fills = $instrument->fills()->orderBy('datetime')->get();
            
            // FIFO queue to track BUY fills
            $buyQueue = collect();
            
            foreach ($fills as $fill) {
                if ($fill->isBuy()) {
                    // Add to FIFO queue
                    $buyQueue->push([
                        'fill' => $fill,
                        'remaining_quantity' => $fill->quantity,
                    ]);
                } else {
                    // SELL - consume from buy queue
                    $this->processSell($fill, $buyQueue, $instrument);
                }
            }
            
            // Create open positions for remaining buy fills in queue
            foreach ($buyQueue as $queueItem) {
                if ($queueItem['remaining_quantity'] > 0) {
                    Position::create([
                        'instrument_id' => $instrument->id,
                        'open_datetime' => $queueItem['fill']->datetime,
                        'close_datetime' => null,
                        'quantity' => $queueItem['remaining_quantity'],
                        'cost_basis' => $queueItem['fill']->price,
                        'realized_pnl' => null,
                    ]);
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process a SELL fill against the FIFO buy queue
     */
    private function processSell(Fill $sellFill, $buyQueue, Instrument $instrument): void
    {
        $remainingSellQty = $sellFill->quantity;
        
        while ($remainingSellQty > 0 && $buyQueue->isNotEmpty()) {
            $firstBuy = $buyQueue->first();
            
            if ($firstBuy['remaining_quantity'] <= 0) {
                $buyQueue->shift();
                continue;
            }
            
            // Calculate how much to match
            $matchQty = min($remainingSellQty, $firstBuy['remaining_quantity']);
            
            // Calculate PnL for this leg
            $buyPrice = $firstBuy['fill']->price;
            $sellPrice = $sellFill->price;
            $multiplier = $instrument->multiplier;
            
            // PnL = (sell_price - buy_price) * multiplier * qty - fees
            $buyFees = ($firstBuy['fill']->fees / $firstBuy['fill']->quantity) * $matchQty;
            $sellFees = ($sellFill->fees / $sellFill->quantity) * $matchQty;
            $totalFees = $buyFees + $sellFees;
            
            $pnl = (($sellPrice - $buyPrice) * $multiplier * $matchQty) - $totalFees;
            
            // Create closed position
            Position::create([
                'instrument_id' => $instrument->id,
                'open_datetime' => $firstBuy['fill']->datetime,
                'close_datetime' => $sellFill->datetime,
                'quantity' => $matchQty,
                'cost_basis' => $buyPrice,
                'realized_pnl' => $pnl,
            ]);
            
            // Update remaining quantities
            $firstBuy['remaining_quantity'] -= $matchQty;
            $remainingSellQty -= $matchQty;
            
            // If buy is fully consumed, remove from queue
            if ($firstBuy['remaining_quantity'] <= 0) {
                $buyQueue->shift();
            }
        }
        
        // If there's still sell quantity remaining, it means we're selling short
        // For now, we'll just log this - you can enhance this later
        if ($remainingSellQty > 0) {
            \Log::warning("Short position detected for instrument {$instrument->symbol}: {$remainingSellQty} units");
        }
    }

    /**
     * Process all fills for multiple instruments
     */
    public function processAllInstruments(): void
    {
        $instruments = Instrument::all();
        
        foreach ($instruments as $instrument) {
            $this->processInstrument($instrument);
        }
    }
}
