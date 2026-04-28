@extends('layouts.app')

@section('title', 'Trades')

@section('content')
<div class="max-w-full overflow-hidden">
<!-- Confirmation Modal -->
<div id="confirmation-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Confirm Deletion</h3>
                </div>
            </div>
            <p class="text-gray-600 mb-6" id="modal-message">Are you sure you want to delete this position? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors">
                    Cancel
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 md:mb-8">
    <div>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Trades</h2>
        <p class="text-sm md:text-base text-gray-600">View and manage your trading history</p>
    </div>
    <a href="{{ route('trades.create') }}" class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-semibold px-4 md:px-6 py-3 rounded-lg shadow-md transition-colors duration-200 flex items-center justify-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add Trade
    </a>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="flash-message mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="flash-message mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

<!-- Trades Table Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden w-full">
    <!-- Filters Section -->
    <div class="px-4 md:px-6 py-4 bg-gray-50 border-b border-gray-200 overflow-x-hidden">
        <!-- Filter Toggle Button -->
        <button 
            type="button" 
            onclick="toggleFilters()" 
            class="w-full flex items-center justify-between mb-3 px-4 py-2 bg-white hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors duration-200"
        >
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span class="font-semibold text-gray-700">Filters</span>
                @if(request()->except(['page', 'per_page']))
                    <span class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-full">
                        Active
                    </span>
                @endif
            </div>
            <svg id="filter-chevron" class="w-5 h-5 text-gray-600 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <form method="GET" action="{{ route('trades') }}" id="filter-form" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-3 md:gap-4">
                <!-- Symbol Search -->
                <div>
                    <label for="symbol" class="block text-xs font-medium text-gray-700 mb-1">Symbol</label>
                    <input 
                        type="text" 
                        name="symbol" 
                        id="symbol" 
                        value="{{ request('symbol') }}" 
                        placeholder="e.g. SPX, ORCL"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                </div>

                <!-- Trade State -->
                <div>
                    <label for="state" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select 
                        name="state" 
                        id="state"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="">All Positions</option>
                        <option value="open" {{ request('state') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ request('state') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <!-- Asset Type -->
                <div>
                    <label for="asset_type" class="block text-xs font-medium text-gray-700 mb-1">Asset Type</label>
                    <select 
                        name="asset_type" 
                        id="asset_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="">All Types</option>
                        <option value="OPT" {{ request('asset_type') == 'OPT' ? 'selected' : '' }}>Options</option>
                        <option value="STK" {{ request('asset_type') == 'STK' ? 'selected' : '' }}>Stocks</option>
                    </select>
                </div>

                <!-- Option Type (CALL/PUT) -->
                <div>
                    <label for="put_call" class="block text-xs font-medium text-gray-700 mb-1">Option Type</label>
                    <select 
                        name="put_call" 
                        id="put_call"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="">All Options</option>
                        <option value="CALL" {{ request('put_call') == 'CALL' ? 'selected' : '' }}>CALL</option>
                        <option value="PUT" {{ request('put_call') == 'PUT' ? 'selected' : '' }}>PUT</option>
                    </select>
                </div>

                <!-- P&L Filter -->
                <div>
                    <label for="pnl_filter" class="block text-xs font-medium text-gray-700 mb-1">P&L</label>
                    <select 
                        name="pnl_filter" 
                        id="pnl_filter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="">All Trades</option>
                        <option value="winner" {{ request('pnl_filter') == 'winner' ? 'selected' : '' }}>Winners</option>
                        <option value="loser" {{ request('pnl_filter') == 'loser' ? 'selected' : '' }}>Losers</option>
                        <option value="breakeven" {{ request('pnl_filter') == 'breakeven' ? 'selected' : '' }}>Break-even</option>
                    </select>
                </div>

                <!-- Tag Filter -->
                <div>
                    <label for="tag" class="block text-xs font-medium text-gray-700 mb-1">Tag</label>
                    <select 
                        name="tag" 
                        id="tag"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="">All Tags</option>
                        @foreach($userTags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Opened Date From -->
                <div>
                    <label for="opened_from" class="block text-xs font-medium text-gray-700 mb-1">Opened From</label>
                    <input 
                        type="date" 
                        name="opened_from" 
                        id="opened_from" 
                        value="{{ request('opened_from') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                </div>

                <!-- Opened Date To -->
                <div>
                    <label for="opened_to" class="block text-xs font-medium text-gray-700 mb-1">Opened To</label>
                    <input 
                        type="date" 
                        name="opened_to" 
                        id="opened_to" 
                        value="{{ request('opened_to') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                </div>

                <!-- Closed Date From -->
                <div>
                    <label for="closed_from" class="block text-xs font-medium text-gray-700 mb-1">Closed From</label>
                    <input 
                        type="date" 
                        name="closed_from" 
                        id="closed_from" 
                        value="{{ request('closed_from') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                </div>

                <!-- Closed Date To -->
                <div>
                    <label for="closed_to" class="block text-xs font-medium text-gray-700 mb-1">Closed To</label>
                    <input 
                        type="date" 
                        name="closed_to" 
                        id="closed_to" 
                        value="{{ request('closed_to') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort_by" class="block text-xs font-medium text-gray-700 mb-1">Sort By</label>
                    <select 
                        name="sort_by" 
                        id="sort_by"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="close_datetime" {{ request('sort_by', 'close_datetime') == 'close_datetime' ? 'selected' : '' }}>Closed Date</option>
                        <option value="open_datetime" {{ request('sort_by') == 'open_datetime' ? 'selected' : '' }}>Opened Date</option>
                        <option value="symbol" {{ request('sort_by') == 'symbol' ? 'selected' : '' }}>Symbol</option>
                        <option value="pnl" {{ request('sort_by') == 'pnl' ? 'selected' : '' }}>P&L</option>
                    </select>
                </div>

                <!-- Order -->
                <div>
                    <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">Order</label>
                    <select 
                        name="sort_order" 
                        id="sort_order"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2 col-span-1 sm:col-span-2">
                    <button 
                        type="submit"
                        class="flex-1 sm:flex-none px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition-colors whitespace-nowrap"
                    >
                        Apply Filters
                    </button>
                    <a 
                        href="{{ route('trades') }}"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-md transition-colors whitespace-nowrap"
                    >
                        Clear
                    </a>
                </div>
            </div>

            <!-- Preserve per_page in filters -->
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
        </form>
    </div>

    <div class="px-4 md:px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-semibold text-gray-900">Trade History</h3>
                <button id="bulk-delete-btn" onclick="deleteSelected()" class="hidden px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                    Delete Selected
                </button>
            </div>
            
            <!-- Per Page Selector -->
            <div class="flex items-center space-x-2">
                <label for="per-page" class="text-xs sm:text-sm text-gray-600 whitespace-nowrap">Show:</label>
                <select id="per-page" onchange="changePerPage(this.value)" class="px-2 sm:px-3 py-1.5 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="5"  {{ request('per_page', 10) == 5   ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page', 10) == 10  ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20  ? 'selected' : '' }}>20</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                </select>
                <span class="text-xs sm:text-sm text-gray-600 hidden sm:inline whitespace-nowrap">days</span>
            </div>
        </div>
    </div>
    
    <!-- Horizontally Scrollable Table Container -->
    <div class="overflow-x-auto w-full">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opened</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Closed</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Basis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P&L</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="bg-white" id="trades-tbody">
                @forelse($groupedPositions as $dateKey => $dayPositions)
                    @php
                        $isOpen     = $dateKey === 'open';
                        $groupId    = 'grp-' . str_replace('-', '', $dateKey);
                        $totalPnl   = $dayPositions->whereNotNull('realized_pnl')->sum('realized_pnl');
                        $winCount   = $dayPositions->filter(fn($p) => $p->realized_pnl > 0)->count();
                        $lossCount  = $dayPositions->filter(fn($p) => $p->realized_pnl < 0)->count();
                        $tradeCount = $dayPositions->count();
                    @endphp

                    {{-- ── Day / group header row ── --}}
                    @php
                        $headerBorderColor = $isOpen ? 'border-yellow-400' : ($totalPnl > 0 ? 'border-green-400' : ($totalPnl < 0 ? 'border-red-400' : 'border-gray-300'));
                    @endphp
                    <tr class="cursor-pointer select-none border-t-2 border-gray-200 bg-gray-100 hover:bg-gray-200 transition-all"
                        onclick="toggleDay('{{ $groupId }}')">
                        <td colspan="11" class="pl-0 pr-0 py-0">
                            <div class="flex items-center border-l-4 {{ $headerBorderColor }} pl-4 pr-8 py-5">
                                {{-- Left: chevron + date + counts --}}
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <svg data-chevron="{{ $groupId }}"
                                         class="w-4 h-4 text-gray-500 transition-transform duration-200 flex-shrink-0 rotate-90"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span class="font-bold text-sm text-gray-800 tracking-wide">
                                        @if($isOpen)
                                            Open Positions
                                        @else
                                            {{ \Carbon\Carbon::parse($dateKey)->format('l, F d, Y') }}
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-500 bg-white border border-gray-200 px-2 py-0.5 rounded-full shadow-sm">
                                        {{ $tradeCount }} {{ $tradeCount === 1 ? 'trade' : 'trades' }}
                                    </span>
                                    @if(!$isOpen && $winCount > 0)
                                        <span class="text-xs font-semibold text-green-700 bg-green-100 border border-green-200 px-2 py-0.5 rounded-full">{{ $winCount }}W</span>
                                    @endif
                                    @if(!$isOpen && $lossCount > 0)
                                        <span class="text-xs font-semibold text-red-700 bg-red-100 border border-red-200 px-2 py-0.5 rounded-full">{{ $lossCount }}L</span>
                                    @endif
                                </div>
                                {{-- Right: diary indicator + daily total P&L --}}
                                <div class="flex items-center gap-4 flex-shrink-0">
                                    @if(!$isOpen && isset($diaryEntryByDate[$dateKey]))
                                        @php $diaryEntry = $diaryEntryByDate[$dateKey]; @endphp
                                        <a href="{{ route('diary.show', $diaryEntry->id) }}"
                                           onclick="event.stopPropagation()"
                                           title="View diary entry for this day"
                                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-orange-500 text-white hover:bg-orange-600 transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            Journal
                                        </a>
                                    @elseif(!$isOpen)
                                        <a href="{{ route('diary') }}?date={{ $dateKey }}"
                                           onclick="event.stopPropagation()"
                                           title="Write a diary entry for this day"
                                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium text-gray-500 bg-white border border-gray-200 hover:border-orange-300 hover:text-orange-600 transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            Add Journal
                                        </a>
                                    @endif
                                    @if(!$isOpen)
                                        <span class="font-bold text-base tabular-nums
                                            {{ $totalPnl > 0 ? 'text-green-600' : ($totalPnl < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                            {{ $totalPnl >= 0 ? '+' : '-' }}${{ number_format(abs($totalPnl), 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- ── Individual trade rows (collapsed by default) ── --}}
                    @foreach($dayPositions as $position)
                    @php
                        $rowBg = $position->isClosed()
                            ? ($position->isProfitable() ? 'bg-green-50/40 hover:bg-green-50' : ($position->isLoss() ? 'bg-red-50/40 hover:bg-red-50' : 'hover:bg-gray-50'))
                            : 'hover:bg-yellow-50/50';
                    @endphp
                    <tr class="group transition-colors position-row cursor-pointer day-trade-row border-t border-gray-100 {{ $rowBg }}"
                        data-group="{{ $groupId }}"
                        onclick="window.location='{{ route('trades.show', $position) }}'">
                        <td class="px-4 py-3.5 whitespace-nowrap" onclick="event.stopPropagation()">
                            <input type="checkbox" name="position_ids[]" value="{{ $position->id }}" onchange="updateBulkDeleteButton()" class="position-checkbox w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        </td>
                        {{-- Symbol --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <div class="font-semibold text-sm text-gray-900">{{ $position->instrument->symbol }}</div>
                            @if($position->instrument->underlying_symbol && $position->instrument->underlying_symbol !== $position->instrument->symbol)
                                <div class="text-xs text-gray-400">{{ $position->instrument->underlying_symbol }}</div>
                            @endif
                        </td>
                        {{-- Type --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @if($position->instrument->asset_type === 'STK')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">Stock</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $position->instrument->put_call === 'C' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $position->instrument->put_call === 'C' ? 'Call' : 'Put' }}
                                </span>
                                @if($position->instrument->strike)
                                    <div class="text-xs text-gray-400 mt-0.5">${{ number_format($position->instrument->strike, 0) }}</div>
                                @endif
                            @endif
                        </td>
                        {{-- Opened --}}
                        <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-700">
                            @if($isOpen)
                                {{ $position->open_datetime->format('M d, Y') }}
                                <span class="block text-xs text-gray-400">{{ $position->open_datetime->format('H:i') }}</span>
                            @else
                                {{ $position->open_datetime->format('H:i') }}
                                @if($position->open_datetime->format('Y-m-d') !== $dateKey)
                                    <span class="block text-xs text-gray-400">{{ $position->open_datetime->format('M d') }}</span>
                                @endif
                            @endif
                        </td>
                        {{-- Closed --}}
                        <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-700">
                            @if($position->isClosed())
                                {{ $position->close_datetime->format('H:i') }}
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        {{-- Cost Basis --}}
                        <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-700 tabular-nums">
                            ${{ number_format($position->cost_basis, 2) }}
                        </td>
                        {{-- Qty --}}
                        <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-500 tabular-nums">
                            {{ number_format($position->quantity, 0) }}
                        </td>
                        {{-- P&L --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @if($position->isClosed())
                                <span class="font-bold text-sm tabular-nums {{ $position->isProfitable() ? 'text-green-600' : ($position->isLoss() ? 'text-red-600' : 'text-gray-500') }}">
                                    {{ $position->isProfitable() ? '+' : ($position->isLoss() ? '-' : '') }}${{ number_format(abs($position->realized_pnl), 2) }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">pending</span>
                            @endif
                        </td>
                        {{-- Tags --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                @foreach($position->tags as $tag)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: {{ $tag->color }}">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        {{-- Status --}}
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @if($position->isClosed())
                                @if($position->isProfitable())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Win
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Loss
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                    <svg class="w-2.5 h-2.5 animate-pulse" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                    Open
                                </span>
                            @endif
                        </td>
                        {{-- Arrow --}}
                        <td class="px-4 py-3.5 whitespace-nowrap text-right">
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-orange-500 transition-colors inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </td>
                    </tr>
                    @endforeach
                    {{-- Section bottom divider --}}
                    <tr class="day-trade-row border-0" data-group="{{ $groupId }}">
                        <td colspan="11" class="p-0"><div class="h-px bg-gray-300"></div></td>
                    </tr>

                @empty
                <tr>
                    <td colspan="11" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">No positions found</p>
                            <p class="text-gray-400 text-sm mt-2">Click "Add Trade" to import your first trades</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Custom Pagination Footer -->
    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
        <div class="text-sm text-gray-600">
            @if(request('per_page') === 'all')
                All {{ $totalDays }} trading {{ $totalDays === 1 ? 'day' : 'days' }}
            @else
                {{ $groupedPositions->count() }} of {{ $totalDays }} trading {{ $totalDays === 1 ? 'day' : 'days' }}
            @endif
        </div>

        <div class="flex items-center space-x-4">
            @if($daysPaginator->hasPages() && request('per_page') !== 'all')
                <div class="text-sm text-gray-600">
                    Page {{ $daysPaginator->currentPage() }} of {{ $daysPaginator->lastPage() }}
                </div>

                <div class="flex space-x-1">
                    {{-- Previous --}}
                    @if($daysPaginator->onFirstPage())
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg text-sm cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $daysPaginator->previousPageUrl() }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">Previous</a>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $currentPage = $daysPaginator->currentPage();
                        $lastPage    = $daysPaginator->lastPage();
                        $start       = max(1, $currentPage - 2);
                        $end         = min($lastPage, $currentPage + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $daysPaginator->url(1) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">1</a>
                        @if($start > 2)<span class="px-3 py-1.5 text-gray-400">...</span>@endif
                    @endif

                    @for($page = $start; $page <= $end; $page++)
                        @if($page == $currentPage)
                            <span class="px-3 py-1.5 bg-orange-600 text-white rounded-lg text-sm font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $daysPaginator->url($page) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">{{ $page }}</a>
                        @endif
                    @endfor

                    @if($end < $lastPage)
                        @if($end < $lastPage - 1)<span class="px-3 py-1.5 text-gray-400">...</span>@endif
                        <a href="{{ $daysPaginator->url($lastPage) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">{{ $lastPage }}</a>
                    @endif

                    {{-- Next --}}
                    @if($daysPaginator->hasMorePages())
                        <a href="{{ $daysPaginator->nextPageUrl() }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">Next</a>
                    @else
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg text-sm cursor-not-allowed">Next</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Auto-dismiss flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.5s ease-out';
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 500);
        }, 5000);
    });

    // Check if filters are active and show them automatically
    const hasActiveFilters = {{ request()->except(['page', 'per_page']) ? 'true' : 'false' }};
    if (hasActiveFilters) {
        const filterForm = document.getElementById('filter-form');
        if (filterForm) {
            filterForm.classList.remove('hidden');
            const chevron = document.getElementById('filter-chevron');
            if (chevron) {
                chevron.style.transform = 'rotate(180deg)';
            }
        }
    }
});

function toggleFilters() {
    const filterForm = document.getElementById('filter-form');
    const chevron = document.getElementById('filter-chevron');
    
    if (filterForm.classList.contains('hidden')) {
        filterForm.classList.remove('hidden');
        chevron.style.transform = 'rotate(180deg)';
    } else {
        filterForm.classList.add('hidden');
        chevron.style.transform = 'rotate(0deg)';
    }
}

function changePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    url.searchParams.delete('page'); // Reset to page 1 when changing per_page
    window.location.href = url.toString();
}

// Preserve filters when changing per_page
document.getElementById('per-page').addEventListener('change', function() {
    const form = document.getElementById('filter-form');
    const perPageInput = form.querySelector('input[name="per_page"]');
    perPageInput.value = this.value;
    form.submit();
});

function toggleDay(groupId) {
    const rows    = document.querySelectorAll(`.day-trade-row[data-group="${groupId}"]`);
    const chevron = document.querySelector(`[data-chevron="${groupId}"]`);
    // Rows are expanded by default (no hidden class), so "opening" means they are currently hidden
    const opening = rows.length > 0 && rows[0].classList.contains('hidden');

    rows.forEach(row => row.classList.toggle('hidden', !opening));

    if (chevron) {
        // 90° = expanded (pointing down), 0° = collapsed (pointing right)
        chevron.style.transform = opening ? 'rotate(90deg)' : 'rotate(0deg)';
    }

    // Uncheck hidden checkboxes when collapsing so the bulk-delete count stays accurate
    if (!opening) {
        rows.forEach(row => {
            const cb = row.querySelector('.position-checkbox');
            if (cb) cb.checked = false;
        });
        updateBulkDeleteButton();
    }
}

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.day-trade-row:not(.hidden) .position-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateBulkDeleteButton();
}

function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.position-checkbox:checked');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const selectAllCheckbox = document.getElementById('select-all');
    
    if (checkboxes.length > 0) {
        bulkDeleteBtn.classList.remove('hidden');
        bulkDeleteBtn.textContent = `Delete Selected (${checkboxes.length})`;
    } else {
        bulkDeleteBtn.classList.add('hidden');
        selectAllCheckbox.checked = false;
    }
}

let deleteCallback = null;

function showConfirmModal(title, message, callback) {
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-message').textContent = message;
    document.getElementById('confirmation-modal').classList.remove('hidden');
    deleteCallback = callback;
}

function closeConfirmModal() {
    document.getElementById('confirmation-modal').classList.add('hidden');
    deleteCallback = null;
}

function confirmDelete() {
    if (deleteCallback) {
        deleteCallback();
    }
    closeConfirmModal();
}

function deleteSelected() {
    const checkboxes = document.querySelectorAll('.position-checkbox:checked');
    if (checkboxes.length === 0) {
        return;
    }
    
    const count = checkboxes.length;
    showConfirmModal(
        'Confirm Bulk Deletion',
        `Are you sure you want to delete ${count} position${count > 1 ? 's' : ''}? This action cannot be undone.`,
        function() {
            const positionIds = Array.from(checkboxes).map(cb => cb.value);
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("trades.bulk-delete") }}';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Add position IDs
            positionIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'position_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
    );
}

function deletePosition(positionId) {
    showConfirmModal(
        'Confirm Deletion',
        'Are you sure you want to delete this position? This action cannot be undone.',
        function() {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/trades/${positionId}`;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Add DELETE method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    );
}
</script>
</div>
@endsection
