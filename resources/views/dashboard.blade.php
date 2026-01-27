@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h2>
    <p class="text-gray-600">Welcome to your trading dashboard</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    <div class="group relative bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 p-6 overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-200 rounded-full -mr-16 -mt-16 opacity-30"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-blue-600 text-xs font-semibold uppercase tracking-wide mb-2">Total Trades</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalTrades) }}</h3>
                    <div class="mt-2 flex items-center text-xs text-blue-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="font-medium">All time</span>
                    </div>
                </div>
                <div class="rounded-2xl p-4 shadow-lg" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="group relative bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 p-6 overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-green-200 rounded-full -mr-16 -mt-16 opacity-30"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-green-600 text-xs font-semibold uppercase tracking-wide mb-2">Winning Trades</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($winningTrades) }}</h3>
                    <div class="mt-2 flex items-center text-xs text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Profitable</span>
                    </div>
                </div>
                <div class="rounded-2xl p-4 shadow-lg" style="background: linear-gradient(135deg, #22c55e 0%, #4ade80 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="group relative bg-gradient-to-br from-red-50 to-rose-100 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 p-6 overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-red-200 rounded-full -mr-16 -mt-16 opacity-30"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-red-600 text-xs font-semibold uppercase tracking-wide mb-2">Losing Trades</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($losingTrades) }}</h3>
                    <div class="mt-2 flex items-center text-xs text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="font-medium">Closed losses</span>
                    </div>
                </div>
                <div class="rounded-2xl p-4 shadow-lg" style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="group relative bg-gradient-to-br from-orange-50 to-amber-100 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 p-6 overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-orange-200 rounded-full -mr-16 -mt-16 opacity-30"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-orange-600 text-xs font-semibold uppercase tracking-wide mb-2">Win Rate</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $winRate }}%</h3>
                    <div class="mt-2 flex items-center text-xs text-orange-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        </svg>
                        <span class="font-medium">Success ratio</span>
                    </div>
                </div>
                <div class="rounded-2xl p-4 shadow-lg" style="background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="group relative bg-gradient-to-br from-emerald-50 to-teal-100 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 p-6 overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-200 rounded-full -mr-16 -mt-16 opacity-30"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-emerald-600 text-xs font-semibold uppercase tracking-wide mb-2">Net Profit</p>
                    <h3 class="text-3xl font-bold text-emerald-600">${{ number_format($netProfit, 2) }}</h3>
                    <div class="mt-2 flex items-center text-xs text-emerald-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="font-medium">Total gains</span>
                    </div>
                </div>
                <div class="rounded-2xl p-4 shadow-lg" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="group relative bg-gradient-to-br from-rose-50 to-pink-100 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 p-6 overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-rose-200 rounded-full -mr-16 -mt-16 opacity-30"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-rose-600 text-xs font-semibold uppercase tracking-wide mb-2">Net Loss</p>
                    <h3 class="text-3xl font-bold text-rose-600">${{ number_format($netLoss, 2) }}</h3>
                    <div class="mt-2 flex items-center text-xs text-rose-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                        <span class="font-medium">Total losses</span>
                    </div>
                </div>
                <div class="rounded-2xl p-4 shadow-lg" style="background: linear-gradient(135deg, #f43f5e 0%, #fb7185 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="group relative bg-gradient-to-br from-purple-50 to-violet-100 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 p-6 overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-200 rounded-full -mr-16 -mt-16 opacity-30"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-purple-600 text-xs font-semibold uppercase tracking-wide mb-2">Total Commissions</p>
                    <h3 class="text-3xl font-bold text-purple-600">${{ number_format($totalCommissions, 2) }}</h3>
                    <div class="mt-2 flex items-center text-xs text-purple-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium">Fees paid</span>
                    </div>
                </div>
                <div class="rounded-2xl p-4 shadow-lg" style="background: linear-gradient(135deg, #a855f7 0%, #c084fc 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="group relative bg-gradient-to-br from-cyan-50 to-sky-100 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 p-6 overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-200 rounded-full -mr-16 -mt-16 opacity-30"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-cyan-600 text-xs font-semibold uppercase tracking-wide mb-2">Account Balance</p>
                    <h3 class="text-3xl font-bold {{ $accountBalance >= 0 ? 'text-cyan-600' : 'text-red-600' }}">${{ number_format($accountBalance, 2) }}</h3>
                    <div class="mt-2 flex items-center text-xs text-cyan-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span class="font-medium">Current equity</span>
                    </div>
                </div>
                <div class="rounded-2xl p-4 shadow-lg" style="background: linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Account Balance & P&L History Charts (50/50 Split) -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    <!-- Account Balance History Chart -->
    <div class="bg-gradient-to-br from-indigo-50 to-blue-100 rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Account Balance History</h3>
                <p class="text-xs text-gray-600 mt-1">Track your account growth over time</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Current</p>
                <p class="text-2xl font-bold {{ $accountBalance >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    ${{ number_format($accountBalance, 2) }}
                </p>
            </div>
        </div>
        
        <!-- Line Chart Container -->
        <div class="relative bg-white rounded-lg p-4" style="height: 350px;">
            <canvas id="accountBalanceChart"></canvas>
        </div>
        
        <!-- Legend -->
        <div class="mt-4 flex justify-center gap-4 text-xs">
            <div class="flex items-center">
                <div class="w-3 h-3 rounded mr-2" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);"></div>
                <span class="text-gray-700 font-medium">Balance</span>
            </div>
            @if($balanceHistory['startingBalance'] > 0)
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-gray-400 rounded mr-2"></div>
                    <span class="text-gray-700">Start: ${{ number_format($balanceHistory['startingBalance'], 2) }}</span>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Net P&L History Chart -->
    <div class="bg-gradient-to-br from-emerald-50 to-teal-100 rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Net Profit & Loss</h3>
                <p class="text-xs text-gray-600 mt-1">Cumulative profit vs loss from trades</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-600 uppercase tracking-wide mb-1">Net</p>
                <p class="text-2xl font-bold {{ ($netProfit - $netLoss) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    ${{ number_format($netProfit - $netLoss, 2) }}
                </p>
            </div>
        </div>
        
        <!-- Line Chart Container -->
        <div class="relative bg-white rounded-lg p-4" style="height: 350px;">
            <canvas id="netPnLChart"></canvas>
        </div>
        
        <!-- Legend -->
        <div class="mt-4 flex justify-center gap-4 text-xs">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-emerald-500 rounded mr-2"></div>
                <span class="text-gray-700 font-medium">Profit: ${{ number_format($netProfit, 2) }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-red-500 rounded mr-2"></div>
                <span class="text-gray-700 font-medium">Loss: ${{ number_format($netLoss, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Daily P&L Bar Chart for Current Month -->
    <div class="bg-gradient-to-br from-slate-50 to-gray-100 rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Daily P&L - {{ now()->format('F Y') }}</h3>
        </div>
        
        <!-- Bar Chart Container -->
        <div class="relative" style="height: 300px;">
            <canvas id="dailyPnlChart"></canvas>
        </div>
        
        <!-- Legend -->
        <div class="mt-4 flex justify-center gap-6 text-sm">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                <span class="text-gray-600">Profit</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span class="text-gray-600">Loss</span>
            </div>
        </div>
    </div>

    <!-- Calendar Widget -->
    <div class="bg-gradient-to-br from-slate-50 to-gray-100 rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Daily P&L Calendar</h3>
        </div>
        
        <!-- FullCalendar Container -->
        <div id="pnl-calendar"></div>
        
        <!-- Legend -->
        <div class="mt-4 flex items-center justify-center space-x-4 text-xs">
            <div class="flex items-center space-x-1">
                <div class="w-3 h-3 rounded" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);"></div>
                <span class="text-gray-600">Profit</span>
            </div>
            <div class="flex items-center space-x-1">
                <div class="w-3 h-3 rounded" style="background: linear-gradient(135deg, #f43f5e 0%, #fb7185 100%);"></div>
                <span class="text-gray-600">Loss</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h3>
        @if($recentTrades->count() > 0)
            <div class="space-y-4">
                @foreach($recentTrades as $trade)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-bold text-gray-900">{{ $trade->instrument->symbol }}</span>
                                <span class="text-xs px-2 py-1 rounded-full {{ $trade->direction === 'long' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ strtoupper($trade->direction) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $trade->quantity }} @ ${{ number_format($trade->average_price, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($trade->close_datetime)->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold {{ $trade->realized_pnl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trade->realized_pnl >= 0 ? '+' : '' }}${{ number_format($trade->realized_pnl, 2) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $trade->realized_pnl >= 0 ? 'Profit' : 'Loss' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="text-gray-500 text-lg">No data available yet</p>
                <p class="text-gray-400 text-sm mt-2">Start by adding your first trade</p>
            </div>
        @endif
    </div>
</div>

<!-- FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Account Balance History Chart
    const balanceHistory = @json($balanceHistory);
    
    if (balanceHistory && balanceHistory.labels.length > 0) {
        const balanceCtx = document.getElementById('accountBalanceChart').getContext('2d');
        
        // Create gradient for the line
        const balanceGradient = balanceCtx.createLinearGradient(0, 0, 0, 400);
        balanceGradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
        balanceGradient.addColorStop(1, 'rgba(99, 102, 241, 0.05)');
        
        const accountBalanceChart = new Chart(balanceCtx, {
            type: 'line',
            data: {
                labels: balanceHistory.labels,
                datasets: [{
                    label: 'Account Balance',
                    data: balanceHistory.data,
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: balanceGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: 'rgb(99, 102, 241)',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: balanceHistory.startingBalance === 0,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            },
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#6b7280',
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 20,
                            callback: function(value, index) {
                                const date = this.getLabelForValue(value);
                                // Show only month/day for cleaner labels
                                const parts = date.split('-');
                                return parts[1] + '/' + parts[2];
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                const prevValue = context.dataIndex > 0 ? context.dataset.data[context.dataIndex - 1] : balanceHistory.startingBalance;
                                const change = value - prevValue;
                                const changeStr = change >= 0 ? '+$' + change.toFixed(2) : '-$' + Math.abs(change).toFixed(2);
                                
                                return [
                                    'Balance: $' + value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}),
                                    'Change: ' + changeStr
                                ];
                            },
                            title: function(context) {
                                return context[0].label;
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        displayColors: false,
                        bodyFont: {
                            size: 13
                        },
                        titleFont: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
    
    // Net P&L History Chart
    const pnlHistory = @json($pnlHistory);
    
    if (pnlHistory && pnlHistory.labels.length > 0) {
        const pnlCtx = document.getElementById('netPnLChart').getContext('2d');
        
        // Create gradients
        const profitGradient = pnlCtx.createLinearGradient(0, 0, 0, 350);
        profitGradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        profitGradient.addColorStop(1, 'rgba(16, 185, 129, 0.05)');
        
        const lossGradient = pnlCtx.createLinearGradient(0, 0, 0, 350);
        lossGradient.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
        lossGradient.addColorStop(1, 'rgba(239, 68, 68, 0.05)');
        
        const netPnLChart = new Chart(pnlCtx, {
            type: 'line',
            data: {
                labels: pnlHistory.labels,
                datasets: [
                    {
                        label: 'Cumulative Profit',
                        data: pnlHistory.profitData,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: profitGradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: 'rgb(16, 185, 129)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                    },
                    {
                        label: 'Cumulative Loss',
                        data: pnlHistory.lossData,
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: lossGradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: 'rgb(239, 68, 68)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            },
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#6b7280',
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 20,
                            callback: function(value, index) {
                                const date = this.getLabelForValue(value);
                                const parts = date.split('-');
                                return parts[1] + '/' + parts[2];
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                const label = context.dataset.label;
                                return label + ': $' + value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            },
                            title: function(context) {
                                return context[0].label;
                            },
                            footer: function(tooltipItems) {
                                const profit = tooltipItems[0].parsed.y;
                                const loss = tooltipItems[1] ? tooltipItems[1].parsed.y : 0;
                                const net = profit - loss;
                                return 'Net P&L: ' + (net >= 0 ? '+' : '') + '$' + net.toFixed(2);
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        displayColors: true,
                        bodyFont: {
                            size: 13
                        },
                        titleFont: {
                            size: 12,
                            weight: 'bold'
                        },
                        footerFont: {
                            size: 13,
                            weight: 'bold'
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
    
    // Daily P&L Bar Chart
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);
    
    const ctx = document.getElementById('dailyPnlChart').getContext('2d');
    const dailyPnlChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Daily P&L',
                data: chartData,
                backgroundColor: chartData.map(value => value >= 0 ? 'rgba(34, 197, 94, 0.8)' : 'rgba(239, 68, 68, 0.8)'),
                borderColor: chartData.map(value => value >= 0 ? 'rgb(34, 197, 94)' : 'rgb(239, 68, 68)'),
                borderWidth: 1,
                hoverBackgroundColor: chartData.map(value => value >= 0 ? 'rgba(34, 197, 94, 1)' : 'rgba(239, 68, 68, 1)'),
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        },
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 15
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            const value = context.parsed.y;
                            label += (value >= 0 ? '+' : '') + '$' + value.toFixed(2);
                            return label;
                        },
                        title: function(context) {
                            return '{{ now()->format("F") }} ' + context[0].label + ', {{ now()->year }}';
                        }
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    displayColors: false,
                    bodyFont: {
                        size: 13
                    },
                    titleFont: {
                        size: 12,
                        weight: 'normal'
                    }
                }
            },
            interaction: {
                mode: 'index',
                intersect: false
            },
            animation: {
                duration: 750,
                easing: 'easeInOutQuart'
            }
        }
    });
    
    // FullCalendar
    const dailyPnL = @json($dailyPnL);
    
    // Convert dailyPnL to FullCalendar events
    const events = Object.entries(dailyPnL).map(([date, data]) => {
        const pnl = parseFloat(data.total_pnl);
        return {
            start: date,
            allDay: true,
            display: 'background',
            backgroundColor: pnl > 0 ? '#10b981' : pnl < 0 ? '#f43f5e' : '#e5e7eb',
            extendedProps: { pnl: pnl }
        };
    });
    
    const calendarEl = document.getElementById('pnl-calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        height: 'auto',
        events: events,
        dayCellDidMount: function(info) {
            const dateStr = info.date.toISOString().split('T')[0];
            const dayData = dailyPnL[dateStr];
            
            if (dayData) {
                const pnl = parseFloat(dayData.total_pnl);
                const pnlDiv = document.createElement('div');
                pnlDiv.className = 'pnl-amount';
                pnlDiv.style.color = pnl > 0 ? '#10b981' : pnl < 0 ? '#f43f5e' : '#6b7280';
                pnlDiv.textContent = (pnl >= 0 ? '+' : '') + '$' + pnl.toFixed(2);
                info.el.querySelector('.fc-daygrid-day-frame').appendChild(pnlDiv);
            }
        }
    });
    calendar.render();
});
</script>

<style>
    #pnl-calendar {
        background: white;
        border-radius: 0.5rem;
        padding: 1rem;
    }
    #pnl-calendar .fc-toolbar-title {
        font-size: 1.25rem !important;
        font-weight: 600;
        color: #1f2937;
    }
    #pnl-calendar .fc-button {
        background: linear-gradient(135deg, #f97316 0%, #fb923c 100%) !important;
        border: none !important;
        font-size: 0.75rem !important;
        padding: 0.25rem 0.5rem !important;
    }
    #pnl-calendar .fc-button:hover {
        opacity: 0.9;
    }
    #pnl-calendar .fc-col-header-cell-cushion {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
    }
    #pnl-calendar .fc-daygrid-day-number {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        padding: 0.25rem;
    }
    #pnl-calendar .fc-day-today {
        background: rgba(59, 130, 246, 0.1) !important;
    }
    #pnl-calendar .fc-day-today .fc-daygrid-day-number {
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #pnl-calendar .fc-daygrid-day-frame {
        min-height: 80px;
        display: flex;
        flex-direction: column;
    }
    #pnl-calendar .fc-daygrid-day-events {
        flex: 1;
    }
    #pnl-calendar .pnl-amount {
        text-align: center;
        font-size: 1rem;
        font-weight: 800;
        margin-top: auto;
        padding: 0.5rem 0;
    }
    #pnl-calendar .fc-theme-standard td, 
    #pnl-calendar .fc-theme-standard th {
        border-color: #e5e7eb;
    }
</style>
@endsection
