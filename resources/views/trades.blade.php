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
        <form method="GET" action="{{ route('trades') }}" id="filter-form">
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
            <input type="hidden" name="per_page" value="{{ request('per_page', 30) }}">
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
                    <option value="30" {{ request('per_page', 30) == 30 ? 'selected' : '' }}>30</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                </select>
                <span class="text-xs sm:text-sm text-gray-600 hidden sm:inline whitespace-nowrap">entries</span>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opened Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Closed Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P&L</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($positions as $position)
                <tr class="group hover:bg-gray-50 transition-colors position-row cursor-pointer" onclick="window.location='{{ route('trades.show', $position) }}'">
                    <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                        <input type="checkbox" name="position_ids[]" value="{{ $position->id }}" onchange="updateBulkDeleteButton()" class="position-checkbox w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $position->open_datetime->format('M d, Y') }}
                        <span class="block text-xs text-gray-500">{{ $position->open_datetime->format('H:i:s') }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($position->isClosed())
                            {{ $position->close_datetime->format('M d, Y') }}
                            <span class="block text-xs text-gray-500">{{ $position->close_datetime->format('H:i:s') }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $position->instrument->symbol }}</div>
                        @if($position->instrument->underlying_symbol)
                            <div class="text-xs text-gray-500">{{ $position->instrument->underlying_symbol }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $position->instrument->asset_type === 'STK' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                            {{ $position->instrument->asset_type }}
                        </span>
                        @if($position->instrument->isOption())
                            <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $position->instrument->put_call === 'C' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $position->instrument->put_call === 'C' ? 'CALL' : 'PUT' }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${{ number_format($position->cost_basis, 2) }}
                        <span class="block text-xs text-gray-500">Qty: {{ number_format($position->quantity, 0) }}</span>
                        @if($position->instrument->isOption())
                            <span class="block text-xs text-gray-500">Strike: ${{ number_format($position->instrument->strike, 2) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($position->isClosed())
                            <span class="text-gray-700">Avg Exit</span>
                            <span class="block text-xs text-gray-500">FIFO Matched</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if($position->isClosed())
                            @if($position->isProfitable())
                                <span class="text-green-600">+${{ number_format($position->realized_pnl, 2) }}</span>
                            @elseif($position->isLoss())
                                <span class="text-red-600">-${{ number_format(abs($position->realized_pnl), 2) }}</span>
                            @else
                                <span class="text-gray-600">$0.00</span>
                            @endif
                        @else
                            <span class="text-gray-400">Open</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-wrap gap-1">
                            @foreach($position->tags as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-white" style="background-color: {{ $tag->color }}">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($position->isClosed())
                            @if($position->isProfitable())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Win
                                </span>
                            @else
                                <span class="text-gray-600">$0.00</span>
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($position->isClosed())
                            @if($position->isProfitable())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Win
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Loss
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Open
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition-colors inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-12 text-center">
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
            Showing {{ $positions->firstItem() ?? 0 }} to {{ $positions->lastItem() ?? 0 }} of {{ $positions->total() }} entries
        </div>
        
        <div class="flex items-center space-x-4">
            @if($positions->hasPages() && request('per_page') !== 'all')
                <div class="text-sm text-gray-600">
                    Page {{ $positions->currentPage() }} of {{ $positions->lastPage() }}
                </div>
                
                <div class="flex space-x-1">
                    {{-- Previous Button --}}
                    @if($positions->onFirstPage())
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg text-sm cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <a href="{{ $positions->previousPageUrl() }}&per_page={{ request('per_page', 30) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                            Previous
                        </a>
                    @endif
                    
                    {{-- Page Numbers --}}
                    @php
                        $currentPage = $positions->currentPage();
                        $lastPage = $positions->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp
                    
                    @if($start > 1)
                        <a href="{{ $positions->url(1) }}&per_page={{ request('per_page', 30) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                            1
                        </a>
                        @if($start > 2)
                            <span class="px-3 py-1.5 text-gray-400">...</span>
                        @endif
                    @endif
                    
                    @for($page = $start; $page <= $end; $page++)
                        @if($page == $currentPage)
                            <span class="px-3 py-1.5 bg-orange-600 text-white rounded-lg text-sm font-medium">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $positions->url($page) }}&per_page={{ request('per_page', 30) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor
                    
                    @if($end < $lastPage)
                        @if($end < $lastPage - 1)
                            <span class="px-3 py-1.5 text-gray-400">...</span>
                        @endif
                        <a href="{{ $positions->url($lastPage) }}&per_page={{ request('per_page', 30) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                            {{ $lastPage }}
                        </a>
                    @endif
                    
                    {{-- Next Button --}}
                    @if($positions->hasMorePages())
                        <a href="{{ $positions->nextPageUrl() }}&per_page={{ request('per_page', 30) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                            Next
                        </a>
                    @else
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-400 rounded-lg text-sm cursor-not-allowed">
                            Next
                        </span>
                    @endif
                </div>
            @elseif(request('per_page') === 'all')
                <div class="text-sm text-gray-600">
                    Showing all entries
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
});

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

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.position-checkbox');
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
