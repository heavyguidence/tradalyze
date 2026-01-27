<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Balance;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all closed positions for the current user
        $closedPositions = Position::whereHas('instrument', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->whereNotNull('close_datetime')
        ->whereNotNull('realized_pnl')
        ->get();
        
        // Calculate statistics
        $totalTrades = $closedPositions->count();
        $winningTrades = $closedPositions->where('realized_pnl', '>', 0)->count();
        $losingTrades = $closedPositions->where('realized_pnl', '<', 0)->count();
        $winRate = $totalTrades > 0 ? round(($winningTrades / $totalTrades) * 100, 2) : 0;
        
        // Calculate profit/loss totals
        $netProfit = $closedPositions->where('realized_pnl', '>', 0)->sum('realized_pnl');
        $netLoss = abs($closedPositions->where('realized_pnl', '<', 0)->sum('realized_pnl'));
        
        // Calculate total commissions from all fills for user's instruments
        $totalCommissions = \App\Models\Fill::whereHas('instrument', function($query) {
            $query->where('user_id', auth()->id());
        })->sum('fees');
        
        // Calculate account balance
        $initialBalance = Balance::where('user_id', auth()->id())
            ->where('type', 'initial')
            ->sum('amount');
        
        $deposits = Balance::where('user_id', auth()->id())
            ->where('type', 'deposit')
            ->sum('amount');
        
        $withdrawals = Balance::where('user_id', auth()->id())
            ->where('type', 'withdrawal')
            ->sum('amount');
        
        // Account Balance = Initial + Deposits + Net Profit - (Net Loss + Commissions + Withdrawals)
        $accountBalance = $initialBalance + $deposits + $netProfit - ($netLoss + $totalCommissions + $withdrawals);
        
        // Calculate daily P&L for calendar widget
        $dailyPnL = Position::whereHas('instrument', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->whereNotNull('close_datetime')
        ->whereNotNull('realized_pnl')
        ->selectRaw('DATE(close_datetime) as date, SUM(realized_pnl) as total_pnl')
        ->groupBy('date')
        ->get()
        ->keyBy('date');
        
        // Calculate daily P&L for current month bar chart
        $currentMonth = now()->format('Y-m');
        $daysInMonth = now()->daysInMonth;
        
        $monthlyDailyPnL = Position::whereHas('instrument', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->whereNotNull('close_datetime')
        ->whereNotNull('realized_pnl')
        ->whereYear('close_datetime', now()->year)
        ->whereMonth('close_datetime', now()->month)
        ->selectRaw("CAST(strftime('%d', close_datetime) AS INTEGER) as day, SUM(realized_pnl) as total_pnl")
        ->groupBy('day')
        ->get()
        ->keyBy('day');
        
        // Prepare data for bar chart (all days of current month)
        $chartLabels = [];
        $chartData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $chartLabels[] = $day;
            $chartData[] = isset($monthlyDailyPnL[$day]) ? round($monthlyDailyPnL[$day]->total_pnl, 2) : 0;
        }
        
        // Get 5 most recent trades
        $recentTrades = Position::whereHas('instrument', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->with('instrument')
        ->whereNotNull('close_datetime')
        ->whereNotNull('realized_pnl')
        ->orderBy('close_datetime', 'desc')
        ->limit(5)
        ->get();
        
        // Calculate Account Balance History
        $balanceHistory = $this->calculateBalanceHistory();
        
        // Calculate Net P&L History
        $pnlHistory = $this->calculatePnLHistory();
        
        return view('dashboard', compact('totalTrades', 'winningTrades', 'losingTrades', 'winRate', 'netProfit', 'netLoss', 'totalCommissions', 'accountBalance', 'dailyPnL', 'recentTrades', 'chartLabels', 'chartData', 'balanceHistory', 'pnlHistory'));
    }
    
    /**
     * Calculate daily account balance history
     */
    private function calculateBalanceHistory()
    {
        // Get initial balance with date
        $initialBalance = Balance::where('user_id', auth()->id())
            ->where('type', 'initial')
            ->first();
        
        // Get all deposits and withdrawals
        $balanceTransactions = Balance::where('user_id', auth()->id())
            ->whereIn('type', ['deposit', 'withdrawal'])
            ->orderBy('date')
            ->get();
        
        // Get all closed positions with their P&L
        $closedPositions = Position::whereHas('instrument', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->with('instrument')
        ->whereNotNull('close_datetime')
        ->whereNotNull('realized_pnl')
        ->orderBy('close_datetime')
        ->get();
        
        // Get total fees per instrument for closed positions
        $instrumentIds = $closedPositions->pluck('instrument_id')->unique();
        $feesByInstrument = \App\Models\Fill::whereIn('instrument_id', $instrumentIds)
            ->selectRaw('instrument_id, SUM(fees) as total_fees')
            ->groupBy('instrument_id')
            ->pluck('total_fees', 'instrument_id');
        
        // Combine all transactions into a timeline
        $timeline = collect();
        
        // Starting point
        $startDate = null;
        $startingBalance = 0;
        
        if ($initialBalance) {
            $startDate = $initialBalance->date;
            $startingBalance = $initialBalance->amount;
            $timeline->push([
                'date' => $initialBalance->date->format('Y-m-d'),
                'type' => 'initial',
                'amount' => $initialBalance->amount,
                'description' => 'Initial Balance'
            ]);
        } else {
            // If no initial balance, start from first transaction
            $firstPosition = $closedPositions->first();
            $firstTransaction = $balanceTransactions->first();
            
            if ($firstPosition && $firstTransaction) {
                $startDate = $firstPosition->close_datetime < $firstTransaction->date 
                    ? $firstPosition->close_datetime 
                    : $firstTransaction->date;
            } elseif ($firstPosition) {
                $startDate = $firstPosition->close_datetime;
            } elseif ($firstTransaction) {
                $startDate = $firstTransaction->date;
            } else {
                $startDate = now();
            }
            
            $startingBalance = 0;
        }
        
        // Add deposits and withdrawals
        foreach ($balanceTransactions as $transaction) {
            $amount = $transaction->type === 'withdrawal' ? -$transaction->amount : $transaction->amount;
            $timeline->push([
                'date' => $transaction->date->format('Y-m-d'),
                'type' => $transaction->type,
                'amount' => $amount,
                'description' => $transaction->description ?? ucfirst($transaction->type)
            ]);
        }
        
        // Add P&L from closed positions (already includes fees in realized_pnl calculation)
        foreach ($closedPositions as $position) {
            $timeline->push([
                'date' => $position->close_datetime->format('Y-m-d'),
                'type' => 'trade',
                'amount' => $position->realized_pnl,
                'description' => $position->instrument->symbol . ' Trade'
            ]);
        }
        
        // Sort timeline by date
        $timeline = $timeline->sortBy('date')->values();
        
        // Calculate running balance for each day
        $balanceByDay = collect();
        $runningBalance = $startingBalance;
        $currentDate = \Carbon\Carbon::parse($startDate);
        $today = now();
        
        // Group timeline by date
        $transactionsByDate = $timeline->groupBy('date');
        
        // Generate daily balances
        while ($currentDate->lte($today)) {
            $dateStr = $currentDate->format('Y-m-d');
            
            // Add transactions for this date
            if (isset($transactionsByDate[$dateStr])) {
                foreach ($transactionsByDate[$dateStr] as $transaction) {
                    if ($transaction['type'] !== 'initial') {
                        $runningBalance += $transaction['amount'];
                    }
                }
            }
            
            $balanceByDay->push([
                'date' => $dateStr,
                'balance' => round($runningBalance, 2)
            ]);
            
            $currentDate->addDay();
        }
        
        return [
            'labels' => $balanceByDay->pluck('date')->toArray(),
            'data' => $balanceByDay->pluck('balance')->toArray(),
            'startingBalance' => $startingBalance
        ];
    }
    
    /**
     * Calculate cumulative Net P&L history (Profit and Loss only from trades)
     */
    private function calculatePnLHistory()
    {
        // Get all closed positions with their P&L
        $closedPositions = Position::whereHas('instrument', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->with('instrument')
        ->whereNotNull('close_datetime')
        ->whereNotNull('realized_pnl')
        ->orderBy('close_datetime')
        ->get();
        
        if ($closedPositions->isEmpty()) {
            return [
                'labels' => [],
                'profitData' => [],
                'lossData' => []
            ];
        }
        
        // Group P&L by date
        $pnlByDate = collect();
        $cumulativeProfit = 0;
        $cumulativeLoss = 0;
        
        $startDate = $closedPositions->first()->close_datetime;
        $today = now();
        $currentDate = \Carbon\Carbon::parse($startDate);
        
        // Group positions by date
        $positionsByDate = $closedPositions->groupBy(function($position) {
            return $position->close_datetime->format('Y-m-d');
        });
        
        // Calculate cumulative profit and loss for each day
        while ($currentDate->lte($today)) {
            $dateStr = $currentDate->format('Y-m-d');
            
            // Add P&L from positions closed on this date
            if (isset($positionsByDate[$dateStr])) {
                foreach ($positionsByDate[$dateStr] as $position) {
                    $pnl = $position->realized_pnl;
                    if ($pnl > 0) {
                        $cumulativeProfit += $pnl;
                    } elseif ($pnl < 0) {
                        $cumulativeLoss += abs($pnl);
                    }
                }
            }
            
            $pnlByDate->push([
                'date' => $dateStr,
                'profit' => round($cumulativeProfit, 2),
                'loss' => round($cumulativeLoss, 2)
            ]);
            
            $currentDate->addDay();
        }
        
        return [
            'labels' => $pnlByDate->pluck('date')->toArray(),
            'profitData' => $pnlByDate->pluck('profit')->toArray(),
            'lossData' => $pnlByDate->pluck('loss')->toArray()
        ];
    }
}
