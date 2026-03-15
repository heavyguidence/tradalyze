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
     * Process all fills for an instrument and create/update positions using FIFO.
     * Handles both long (buy-first) and short (sell-first) trades.
     */
    public function processInstrument(Instrument $instrument): void
    {
        DB::beginTransaction();

        try {
            $instrument->positions()->delete();

            $fills = $instrument->fills()->orderBy('datetime')->get();

            $buyQueue  = collect(); // open long fills
            $sellQueue = collect(); // open short fills

            foreach ($fills as $fill) {
                if ($fill->isBuy()) {
                    $remaining = $fill->quantity;

                    // Close any open short positions first
                    if ($sellQueue->isNotEmpty()) {
                        $remaining = $this->processShortClose($fill, $sellQueue, $instrument);
                    }

                    // Whatever is left opens a new long position
                    if ($remaining > 0) {
                        $buyQueue->push([
                            'fill'               => $fill,
                            'remaining_quantity' => $remaining,
                        ]);
                    }
                } else {
                    $remaining = $fill->quantity;

                    // Close any open long positions first
                    if ($buyQueue->isNotEmpty()) {
                        $remaining = $this->processSell($fill, $buyQueue, $instrument);
                    }

                    // Whatever is left opens a new short position
                    if ($remaining > 0) {
                        $sellQueue->push([
                            'fill'               => $fill,
                            'remaining_quantity' => $remaining,
                        ]);
                    }
                }
            }

            // Create open long positions for remaining buy fills
            foreach ($buyQueue as $queueItem) {
                if ($queueItem['remaining_quantity'] > 0) {
                    $openFill = $queueItem['fill'];
                    $openQty  = $queueItem['remaining_quantity'];
                    $openFees = $openFill->quantity > 0
                        ? ($openFill->fees / $openFill->quantity) * $openQty
                        : 0;

                    Position::create([
                        'instrument_id' => $instrument->id,
                        'open_datetime' => $openFill->datetime,
                        'close_datetime' => null,
                        'quantity'       => $openQty,
                        'cost_basis'     => ($openFill->price * $instrument->multiplier * $openQty) + $openFees,
                        'realized_pnl'   => null,
                    ]);
                }
            }

            // Create open short positions for remaining sell fills
            foreach ($sellQueue as $queueItem) {
                if ($queueItem['remaining_quantity'] > 0) {
                    $openFill = $queueItem['fill'];
                    $openQty  = $queueItem['remaining_quantity'];
                    $openFees = $openFill->quantity > 0
                        ? ($openFill->fees / $openFill->quantity) * $openQty
                        : 0;

                    Position::create([
                        'instrument_id' => $instrument->id,
                        'open_datetime' => $openFill->datetime,
                        'close_datetime' => null,
                        'quantity'       => -$openQty, // negative indicates short
                        'cost_basis'     => ($openFill->price * $instrument->multiplier * $openQty) - $openFees,
                        'realized_pnl'   => null,
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
     * Process a SELL fill closing long positions from the buy queue.
     * Returns any unmatched sell quantity (overflow becomes a short open).
     */
    private function processSell(Fill $sellFill, $buyQueue, Instrument $instrument): float
    {
        $remainingSellQty = $sellFill->quantity;

        while ($remainingSellQty > 0 && $buyQueue->isNotEmpty()) {
            $firstBuy = $buyQueue->first();

            if ($firstBuy['remaining_quantity'] <= 0) {
                $buyQueue->shift();
                continue;
            }

            $matchQty  = min($remainingSellQty, $firstBuy['remaining_quantity']);
            $buyPrice  = $firstBuy['fill']->price;
            $sellPrice = $sellFill->price;
            $multiplier = $instrument->multiplier;

            $buyFees  = $firstBuy['fill']->quantity > 0
                ? ($firstBuy['fill']->fees / $firstBuy['fill']->quantity) * $matchQty
                : 0;
            $sellFees = $sellFill->quantity > 0
                ? ($sellFill->fees / $sellFill->quantity) * $matchQty
                : 0;
            $totalFees = $buyFees + $sellFees;

            $pnl = (($sellPrice - $buyPrice) * $multiplier * $matchQty) - $totalFees;

            Position::create([
                'instrument_id'  => $instrument->id,
                'open_datetime'  => $firstBuy['fill']->datetime,
                'close_datetime' => $sellFill->datetime,
                'quantity'       => $matchQty,
                'cost_basis'     => ($buyPrice * $multiplier * $matchQty) + $buyFees,
                'realized_pnl'   => $pnl,
            ]);

            $firstBuy['remaining_quantity'] -= $matchQty;
            $remainingSellQty -= $matchQty;

            if ($firstBuy['remaining_quantity'] <= 0) {
                $buyQueue->shift();
            } else {
                $buyQueue[0] = $firstBuy;
            }
        }

        return $remainingSellQty;
    }

    /**
     * Process a BUY fill closing short positions from the sell queue.
     * Returns any unmatched buy quantity (overflow becomes a long open).
     */
    private function processShortClose(Fill $buyFill, $sellQueue, Instrument $instrument): float
    {
        $remainingBuyQty = $buyFill->quantity;

        while ($remainingBuyQty > 0 && $sellQueue->isNotEmpty()) {
            $firstSell = $sellQueue->first();

            if ($firstSell['remaining_quantity'] <= 0) {
                $sellQueue->shift();
                continue;
            }

            $matchQty   = min($remainingBuyQty, $firstSell['remaining_quantity']);
            $sellPrice  = $firstSell['fill']->price;
            $buyPrice   = $buyFill->price;
            $multiplier = $instrument->multiplier;

            $sellFees = $firstSell['fill']->quantity > 0
                ? ($firstSell['fill']->fees / $firstSell['fill']->quantity) * $matchQty
                : 0;
            $buyFees = $buyFill->quantity > 0
                ? ($buyFill->fees / $buyFill->quantity) * $matchQty
                : 0;
            $totalFees = $sellFees + $buyFees;

            // Short P&L: profit when sell price > buy price
            $pnl = (($sellPrice - $buyPrice) * $multiplier * $matchQty) - $totalFees;

            Position::create([
                'instrument_id'  => $instrument->id,
                'open_datetime'  => $firstSell['fill']->datetime,
                'close_datetime' => $buyFill->datetime,
                'quantity'       => -$matchQty, // negative indicates short
                'cost_basis'     => ($sellPrice * $multiplier * $matchQty) - $sellFees,
                'realized_pnl'   => $pnl,
            ]);

            $firstSell['remaining_quantity'] -= $matchQty;
            $remainingBuyQty -= $matchQty;

            if ($firstSell['remaining_quantity'] <= 0) {
                $sellQueue->shift();
            } else {
                $sellQueue[0] = $firstSell;
            }
        }

        return $remainingBuyQty;
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
