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
        
        return view('dashboard', compact('totalTrades', 'winningTrades', 'losingTrades', 'winRate', 'netProfit', 'netLoss', 'totalCommissions', 'accountBalance', 'dailyPnL', 'recentTrades', 'chartLabels', 'chartData'));
    }
}
