@extends('layouts.app')

@section('title', 'Add Trade')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Loading Overlay -->
<div id="loading-overlay" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center">
        <div class="flex justify-center mb-4">
            <svg class="animate-spin h-12 w-12 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Processing Upload...</h3>
        <p class="text-gray-600 text-sm">Please wait while we import your trades. This may take a moment.</p>
    </div>
</div>

<!-- Success Modal -->
<div id="success-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 text-center mb-2">Import Successful!</h3>
            <p class="text-gray-600 text-center mb-6" id="success-modal-message">Your trades have been imported successfully.</p>
            <div class="flex flex-col space-y-3">
                <button onclick="viewTrades()" class="w-full px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
                    View My Trades
                </button>
                <button onclick="closeSuccessModal()" class="w-full px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors">
                    Stay Here
                </button>
            </div>
        </div>
    </div>
</div>

<div class="mb-8">
    <div class="flex items-center mb-4">
        <a href="{{ route('trades') }}" class="mr-4 text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Add Trade</h2>
            <p class="text-gray-600 mt-1">Add a new trade manually or import from CSV</p>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
            <button onclick="switchTab('manual')" id="tab-manual" class="tab-button py-4 px-8 text-center border-b-2 font-medium text-sm focus:outline-none transition-colors border-orange-600 text-orange-600">
                Manual Entry
            </button>
            <button onclick="switchTab('csv')" id="tab-csv" class="tab-button py-4 px-8 text-center border-b-2 font-medium text-sm focus:outline-none transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Import CSV
            </button>
            <button onclick="switchTab('auto-import')" id="tab-auto-import" class="tab-button py-4 px-8 text-center border-b-2 font-medium text-sm focus:outline-none transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Auto Import Trades
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="p-8">
        <!-- Manual Entry Tab -->
        <div id="content-manual" class="tab-content">
            <form id="manual-trade-form" class="max-w-6xl mx-auto">
                @csrf
                
                <!-- Asset Type Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asset Type</label>
                    <div class="inline-flex rounded-lg border border-gray-300 p-1 bg-gray-50">
                        <button type="button" onclick="selectAssetType('stock')" class="asset-type-btn px-6 py-2 rounded-md text-sm font-medium transition-all hover:bg-gray-100">
                            Stock
                        </button>
                        <button type="button" onclick="selectAssetType('option')" class="asset-type-btn px-6 py-2 rounded-md text-sm font-medium bg-orange-600 text-white transition-all">
                            Option
                        </button>
                        <button type="button" onclick="selectAssetType('future')" class="asset-type-btn px-6 py-2 rounded-md text-sm font-medium transition-all hover:bg-gray-100">
                            Future
                        </button>
                    </div>
                    <input type="hidden" name="asset_type" id="asset_type" value="option">
                </div>

                <!-- Common Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Symbol -->
                    <div>
                        <label for="symbol" class="block text-xs font-medium text-gray-700 mb-1">Symbol *</label>
                        <input 
                            type="text" 
                            id="symbol" 
                            name="symbol" 
                            required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 uppercase"
                            placeholder="SPX"
                            oninput="this.value = this.value.toUpperCase()"
                        >
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-xs font-medium text-gray-700 mb-1">
                            <span id="quantity-label">Contracts *</span>
                        </label>
                        <input 
                            type="number" 
                            id="quantity" 
                            name="quantity" 
                            required
                            value="1"
                            step="1"
                            min="1"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            placeholder="1"
                            oninput="calculatePnL()"
                        >
                    </div>

                    <!-- Opened Date -->
                    <div>
                        <label for="opened_date" class="block text-xs font-medium text-gray-700 mb-1">Opened Date *</label>
                        <input 
                            type="datetime-local" 
                            id="opened_date" 
                            name="opened_date" 
                            required
                            value="{{ date('Y-m-d\TH:i') }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        >
                    </div>

                    <!-- Closed Date -->
                    <div>
                        <label for="closed_date" class="block text-xs font-medium text-gray-700 mb-1">Closed Date</label>
                        <input 
                            type="datetime-local" 
                            id="closed_date" 
                            name="closed_date"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            oninput="calculatePnL()"
                        >
                    </div>
                </div>

                <!-- Option/Future Specific Fields -->
                <div id="derivative-fields" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Option Type -->
                        <div>
                            <label for="option_type" class="block text-xs font-medium text-gray-700 mb-1">
                                <span id="option-type-label">Option Type *</span>
                            </label>
                            <select 
                                id="option_type" 
                                name="option_type"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            >
                                <option value="CALL">CALL</option>
                                <option value="PUT">PUT</option>
                            </select>
                        </div>

                        <!-- Strike Price -->
                        <div>
                            <label for="strike_price" class="block text-xs font-medium text-gray-700 mb-1">Strike Price *</label>
                            <input 
                                type="number" 
                                id="strike_price" 
                                name="strike_price"
                                step="0.01"
                                min="0"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                placeholder="100.00"
                            >
                        </div>

                        <!-- Expiration Date -->
                        <div>
                            <label for="expiration_date" class="block text-xs font-medium text-gray-700 mb-1">Expiration Date</label>
                            <input 
                                type="date" 
                                id="expiration_date" 
                                name="expiration_date"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            >
                        </div>

                        <!-- Multiplier -->
                        <div>
                            <label for="multiplier" class="block text-xs font-medium text-gray-700 mb-1">Multiplier</label>
                            <input 
                                type="number" 
                                id="multiplier" 
                                name="multiplier"
                                value="100"
                                min="1"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                oninput="calculatePnL()"
                            >
                        </div>
                    </div>
                </div>

                <!-- Price Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Entry Price -->
                    <div>
                        <label for="entry_price" class="block text-xs font-medium text-gray-700 mb-1">
                            <span id="entry-price-label">Entry Price *</span>
                        </label>
                        <input 
                            type="number" 
                            id="entry_price" 
                            name="entry_price" 
                            required
                            step="0.01"
                            min="0"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            placeholder="10.50"
                            oninput="calculatePnL()"
                        >
                    </div>

                    <!-- Exit Price -->
                    <div>
                        <label for="exit_price" class="block text-xs font-medium text-gray-700 mb-1">
                            <span id="exit-price-label">Exit Price</span>
                        </label>
                        <input 
                            type="number" 
                            id="exit_price" 
                            name="exit_price"
                            step="0.01"
                            min="0"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            placeholder="12.00"
                            oninput="calculatePnL()"
                        >
                    </div>

                    <!-- Fees -->
                    <div>
                        <label for="fees" class="block text-xs font-medium text-gray-700 mb-1">Total Fees</label>
                        <input 
                            type="number" 
                            id="fees" 
                            name="fees"
                            value="0"
                            step="0.01"
                            min="0"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            placeholder="0.00"
                            oninput="calculatePnL()"
                        >
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea 
                        id="notes" 
                        name="notes"
                        rows="4"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-vertical"
                        placeholder="Add any additional notes about this trade..."
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">Optional: Add context, strategy, or other information about this trade</p>
                </div>

                <!-- Tag Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trade Tags</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($userTags as $tag)
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input 
                                    type="checkbox" 
                                    name="tag_ids[]" 
                                    value="{{ $tag->id }}"
                                    {{ $tag->id == $defaultTagId ? 'checked' : '' }}
                                    class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500 focus:ring-2"
                                >
                                <span class="ml-2 flex items-center">
                                    <span 
                                        class="inline-block w-3 h-3 rounded-full mr-2" 
                                        style="background-color: {{ $tag->color }};"
                                    ></span>
                                    <span class="text-sm text-gray-700">{{ $tag->name }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Select one or more tags to categorize this trade. "Untagged" is selected by default.</p>
                </div>

                <!-- P&L Display -->
                <div id="pnl-display" class="hidden border-2 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Trade Calculation</h3>
                    
                    <!-- Calculation Breakdown -->
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Entry Price:</span>
                            <span id="calc-entry" class="text-sm font-medium text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Exit Price:</span>
                            <span id="calc-exit" class="text-sm font-medium text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Price Difference:</span>
                            <span id="calc-diff" class="text-sm font-medium"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Quantity × Multiplier:</span>
                            <span id="calc-multiplier" class="text-sm font-medium text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Gross P&L:</span>
                            <span id="calc-gross" class="text-sm font-semibold"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-600">Total Fees:</span>
                            <span id="calc-fees" class="text-sm font-medium text-red-600"></span>
                        </div>
                    </div>

                    <!-- Final P&L -->
                    <div class="bg-gradient-to-r rounded-lg p-4 border-2" id="pnl-container">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Net Profit/Loss</p>
                                <p id="pnl-value" class="text-3xl font-bold"></p>
                            </div>
                            <div id="pnl-icon"></div>
                        </div>
                    </div>
                    
                    <p id="pnl-formula" class="text-xs text-gray-500 mt-3 text-center"></p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a 
                        href="{{ route('trades') }}"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit"
                        id="submit-trade-btn"
                        class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors font-medium"
                    >
                        Save Trade
                    </button>
                </div>
            </form>
        </div>

        <!-- CSV Import Tab -->
        <div id="content-csv" class="tab-content hidden">
            <form action="{{ route('trades.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto">
                @csrf
                
                <!-- Broker Selection -->
                <div class="mb-6">
                    <label for="broker" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Broker
                    </label>
                    <select id="broker" name="broker" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                        <option value="interactive_broker" selected>Interactive Broker</option>
                    </select>
                    <p class="mt-2 text-xs text-gray-500">
                        Choose your broker to ensure proper CSV format handling
                    </p>
                </div>

                <!-- File Upload Area -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload CSV File
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="csv_file" type="file" accept=".csv" class="sr-only" onchange="handleFileSelect(event)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                CSV files only, up to 10MB
                            </p>
                        </div>
                    </div>
                    
                    <!-- Selected File Display -->
                    <div id="file-info" class="hidden mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900" id="file-name"></p>
                                    <p class="text-xs text-gray-500" id="file-size"></p>
                                </div>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CSV Format Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        CSV Format Guidelines
                    </h4>
                    <p class="text-xs text-blue-800 mb-2">Your CSV file should contain the following structure:</p>
                    <ul class="text-xs text-blue-700 list-disc list-inside space-y-1">
                        <li>File format details will be defined soon</li>
                        <li>Make sure your CSV is properly formatted</li>
                        <li>First row should contain column headers</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('trades') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" id="upload-btn" disabled class="px-6 py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors flex items-center">
                        <span id="upload-btn-text">Upload & Import</span>
                        <svg id="upload-btn-spinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Auto Import Trades Tab -->
        <div id="content-auto-import" class="tab-content hidden">
            <form id="auto-import-form" onsubmit="handleAutoImport(event)" class="max-w-2xl mx-auto">
                @csrf
                
                <!-- Broker Selection -->
                <div class="mb-6">
                    <label for="broker-select" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Broker
                    </label>
                    <select id="broker-select" name="broker" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" onchange="handleBrokerChange()">
                        <option value="interactive_broker" selected>Interactive Broker</option>
                    </select>
                    <p class="mt-2 text-xs text-gray-500">
                        Select your broker to configure automatic trade imports
                    </p>
                </div>

                <!-- Broker-specific Fields (Initially Visible) -->
                <div id="broker-fields" class="space-y-6">
                    <!-- Flex Token -->
                    <div>
                        <label for="flex-token" class="block text-sm font-medium text-gray-700 mb-2">
                            Flex Token *
                        </label>
                        <input 
                            type="text" 
                            id="flex-token" 
                            name="flex_token"
                            value="{{ old('flex_token', $user->ib_flex_token) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                            placeholder="Enter your Flex Token"
                            oninput="validateBrokerFields()"
                        >
                        <p class="mt-2 text-xs text-gray-500">
                            Your Interactive Broker Flex Token for API access
                        </p>
                    </div>

                    <!-- Query ID -->
                    <div>
                        <label for="query-id" class="block text-sm font-medium text-gray-700 mb-2">
                            Query ID *
                        </label>
                        <input 
                            type="text" 
                            id="query-id" 
                            name="query_id"
                            value="{{ old('query_id', $user->ib_query_id) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                            placeholder="Enter your Query ID"
                            oninput="validateBrokerFields()"
                        >
                        <p class="mt-2 text-xs text-gray-500">
                            Your Interactive Broker Query ID for trade data retrieval
                        </p>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-800 mb-1">How to get your credentials:</h4>
                                <ul class="text-xs text-blue-700 list-disc list-inside space-y-1">
                                    <li>Log in to your Interactive Broker account</li>
                                    <li>Navigate to Account Management → Reports → Flex Queries</li>
                                    <li>Create or use an existing Flex Query and copy the Query ID</li>
                                    <li>Generate a Flex Token from the security settings</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-4">
                        <a href="{{ route('trades') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <div class="flex space-x-3">
                            <button type="button" id="save-credentials-btn" onclick="saveBrokerCredentials()" disabled class="px-6 py-3 border border-gray-300 bg-white text-gray-700 rounded-lg font-medium hover:bg-gray-50 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                <span id="save-btn-text">Save Credentials</span>
                            </button>
                            <button type="submit" id="import-trades-btn" disabled class="px-6 py-3 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <span id="import-btn-text">Import Trades</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        // Update tab buttons
        const tabs = ['manual', 'csv', 'auto-import'];
        tabs.forEach(t => {
            const button = document.getElementById('tab-' + t);
            const content = document.getElementById('content-' + t);
            
            if (t === tab) {
                button.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                button.classList.add('border-orange-600', 'text-orange-600');
                content.classList.remove('hidden');
            } else {
                button.classList.remove('border-orange-600', 'text-orange-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                content.classList.add('hidden');
            }
        });
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('file-name').textContent = file.name;
            document.getElementById('file-size').textContent = formatFileSize(file.size);
            document.getElementById('file-info').classList.remove('hidden');
            document.getElementById('upload-btn').disabled = false;
        }
    }

    function clearFile() {
        document.getElementById('file-upload').value = '';
        document.getElementById('file-info').classList.add('hidden');
        document.getElementById('upload-btn').disabled = true;
    }

    function handleBrokerChange() {
        const brokerSelect = document.getElementById('broker-select');
        const brokerFields = document.getElementById('broker-fields');
        
        if (brokerSelect.value) {
            brokerFields.classList.remove('hidden');
            // Validate fields after showing them
            validateBrokerFields();
        } else {
            brokerFields.classList.add('hidden');
        }
    }

    function validateBrokerFields() {
        const flexToken = document.getElementById('flex-token').value.trim();
        const queryId = document.getElementById('query-id').value.trim();
        const saveBtn = document.getElementById('save-credentials-btn');
        const importBtn = document.getElementById('import-trades-btn');
        
        // Enable buttons only if both fields are filled
        const isValid = flexToken.length > 0 && queryId.length > 0;
        saveBtn.disabled = !isValid;
        importBtn.disabled = !isValid;
    }

    function showSuccessModal(message) {
        const modal = document.getElementById('success-modal');
        const messageEl = document.getElementById('success-modal-message');
        
        if (messageEl) {
            messageEl.textContent = message;
        }
        
        modal.classList.remove('hidden');
    }

    function closeSuccessModal() {
        const modal = document.getElementById('success-modal');
        modal.classList.add('hidden');
        
        // Reset import button
        const importBtn = document.getElementById('import-trades-btn');
        const importBtnText = document.getElementById('import-btn-text');
        if (importBtn && importBtnText) {
            importBtnText.textContent = 'Import Trades';
            importBtn.disabled = false;
        }
    }

    function viewTrades() {
        window.location.href = '{{ route("trades") }}';
    }

    async function saveBrokerCredentials() {
        const saveBtn = document.getElementById('save-credentials-btn');
        const saveBtnText = document.getElementById('save-btn-text');
        const originalText = saveBtnText.textContent;
        
        // Disable button and show loading state
        saveBtn.disabled = true;
        saveBtnText.textContent = 'Saving...';
        
        const flexToken = document.getElementById('flex-token').value.trim();
        const queryId = document.getElementById('query-id').value.trim();
        
        try {
            const response = await fetch('{{ route("trades.save-broker-credentials") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    flex_token: flexToken,
                    query_id: queryId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                saveBtnText.textContent = 'Saved ✓';
                setTimeout(() => {
                    saveBtnText.textContent = originalText;
                    saveBtn.disabled = false;
                }, 2000);
                
                // Show success message
                showMessage('success', data.message);
            } else {
                throw new Error(data.message || 'Failed to save credentials');
            }
        } catch (error) {
            saveBtnText.textContent = originalText;
            saveBtn.disabled = false;
            showMessage('error', error.message || 'An error occurred while saving credentials');
        }
    }

    async function handleAutoImport(event) {
        event.preventDefault();
        
        const importBtn = document.getElementById('import-trades-btn');
        const importBtnText = document.getElementById('import-btn-text');
        const originalText = importBtnText.textContent;
        const loadingOverlay = document.getElementById('loading-overlay');
        
        // Disable button and show loading state
        importBtn.disabled = true;
        importBtnText.textContent = 'Requesting Report...';
        
        // Show loading overlay with custom message
        if (loadingOverlay) {
            loadingOverlay.querySelector('h3').textContent = 'Importing Trades from Interactive Brokers';
            loadingOverlay.querySelector('p').textContent = 'This may take several minutes. Please do not close this window.';
            loadingOverlay.classList.remove('hidden');
        }
        
        const flexToken = document.getElementById('flex-token').value.trim();
        const queryId = document.getElementById('query-id').value.trim();
        
        try {
            // Set a long timeout for this request (10 minutes) as IB API can be slow
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 600000); // 10 minutes
            
            // Update progress message
            setTimeout(() => {
                if (importBtn.disabled) {
                    importBtnText.textContent = 'Retrieving Report...';
                    if (loadingOverlay && !loadingOverlay.classList.contains('hidden')) {
                        loadingOverlay.querySelector('p').textContent = 'Waiting for report generation. This can take up to 5 minutes...';
                    }
                }
            }, 3000);
            
            const response = await fetch('{{ route("trades.auto-import") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    flex_token: flexToken,
                    query_id: queryId
                }),
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            const data = await response.json();
            
            // Hide loading overlay
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            if (data.success) {
                importBtnText.textContent = '✓ Import Complete';
                importBtn.disabled = false;
                
                // Show success modal with message
                showSuccessModal(data.message);
            } else {
                throw new Error(data.message || 'Failed to import trades');
            }
        } catch (error) {
            // Hide loading overlay
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            importBtnText.textContent = originalText;
            importBtn.disabled = false;
            
            let errorMessage = 'An error occurred while importing trades';
            
            if (error.name === 'AbortError') {
                errorMessage = 'Import timeout. The request took too long. Please try again.';
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            showMessage('error', errorMessage);
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Handle form submission with loading indicator
    document.addEventListener('DOMContentLoaded', function() {
        // Manual form submission handler
        const manualForm = document.getElementById('manual-trade-form');
        if (manualForm) {
            manualForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('submit-trade-btn');
                const originalText = submitBtn.textContent;
                
                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
                
                try {
                    const formData = new FormData(manualForm);
                    
                    const response = await fetch('{{ route("trades.store.manual") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Show success message
                        showMessage('success', data.message);
                        
                        // Clear form
                        manualForm.reset();
                        
                        // Reset to default values
                        document.getElementById('quantity').value = '1';
                        document.getElementById('fees').value = '0';
                        document.getElementById('opened_date').value = '{{ date("Y-m-d\\TH:i") }}';
                        document.getElementById('multiplier').value = '100';
                        
                        // Reset tag checkboxes to only Untagged checked
                        const tagCheckboxes = document.querySelectorAll('input[name="tag_ids[]"]');
                        tagCheckboxes.forEach(checkbox => {
                            checkbox.checked = (checkbox.value === '{{ $defaultTagId }}');
                        });
                        
                        // Hide P&L display
                        document.getElementById('pnl-display').classList.add('hidden');
                        
                        // Reset validation states
                        document.getElementById('exit_price').setCustomValidity('');
                        document.getElementById('closed_date').setCustomValidity('');
                        document.getElementById('closed_date').classList.remove('border-red-500');
                    } else {
                        showMessage('error', data.message);
                    }
                } catch (error) {
                    showMessage('error', 'An error occurred while saving the trade. Please try again.');
                    console.error('Error:', error);
                } finally {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            });
        }
        
        // CSV form submission handler
        const form = document.querySelector('form[enctype="multipart/form-data"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                const uploadBtn = document.getElementById('upload-btn');
                const btnText = document.getElementById('upload-btn-text');
                const btnSpinner = document.getElementById('upload-btn-spinner');
                const loadingOverlay = document.getElementById('loading-overlay');
                
                if (loadingOverlay && uploadBtn && btnText && btnSpinner) {
                    // Show loading overlay
                    loadingOverlay.classList.remove('hidden');
                    
                    // Update button state
                    uploadBtn.disabled = true;
                    btnText.textContent = 'Uploading...';
                    btnSpinner.classList.remove('hidden');
                }
            });
        }
    });
    
    // Show message helper function
    function showMessage(type, message) {
        // Remove any existing messages
        const existingMessages = document.querySelectorAll('.alert-message');
        existingMessages.forEach(msg => msg.remove());
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert-message mb-6 border rounded-lg px-4 py-3 flex items-center ${
            type === 'success' 
                ? 'bg-green-50 border-green-200 text-green-800' 
                : 'bg-red-50 border-red-200 text-red-800'
        }`;
        
        alertDiv.innerHTML = `
            <svg class="w-5 h-5 mr-3 ${type === 'success' ? 'text-green-600' : 'text-red-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                }
            </svg>
            <span>${message}</span>
        `;
        
        // Insert at the top of the page
        const container = document.querySelector('.mb-8');
        container.parentNode.insertBefore(alertDiv, container.nextSibling);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Asset Type Selection
    function selectAssetType(type) {
        const assetTypeInput = document.getElementById('asset_type');
        const buttons = document.querySelectorAll('.asset-type-btn');
        const derivativeFields = document.getElementById('derivative-fields');
        const quantityLabel = document.getElementById('quantity-label');
        const optionTypeLabel = document.getElementById('option-type-label');
        const entryPriceLabel = document.getElementById('entry-price-label');
        const exitPriceLabel = document.getElementById('exit-price-label');
        
        // Update hidden input
        assetTypeInput.value = type;
        
        // Update button styles
        buttons.forEach(btn => {
            btn.classList.remove('bg-orange-600', 'text-white');
            btn.classList.add('text-gray-700');
        });
        
        event.target.classList.remove('text-gray-700');
        event.target.classList.add('bg-orange-600', 'text-white');
        
        // Show/hide derivative fields and update labels
        if (type === 'stock') {
            derivativeFields.style.display = 'none';
            quantityLabel.textContent = 'Shares *';
            entryPriceLabel.textContent = 'Entry Price *';
            exitPriceLabel.textContent = 'Exit Price';
            document.getElementById('multiplier').value = '1';
        } else if (type === 'option') {
            derivativeFields.style.display = 'block';
            quantityLabel.textContent = 'Contracts *';
            optionTypeLabel.textContent = 'Option Type *';
            entryPriceLabel.textContent = 'Entry Price *';
            exitPriceLabel.textContent = 'Exit Price';
            document.getElementById('multiplier').value = '100';
        } else if (type === 'future') {
            derivativeFields.style.display = 'block';
            quantityLabel.textContent = 'Contracts *';
            optionTypeLabel.textContent = 'Contract Type *';
            entryPriceLabel.textContent = 'Entry Price *';
            exitPriceLabel.textContent = 'Exit Price';
            document.getElementById('multiplier').value = '1';
        }
        
        calculatePnL();
    }

    // Calculate P&L with detailed breakdown
    function calculatePnL() {
        const assetType = document.getElementById('asset_type').value;
        const quantity = parseFloat(document.getElementById('quantity').value) || 0;
        const entryPrice = parseFloat(document.getElementById('entry_price').value) || 0;
        const exitPrice = parseFloat(document.getElementById('exit_price').value) || 0;
        const fees = parseFloat(document.getElementById('fees').value) || 0;
        const closedDate = document.getElementById('closed_date').value;
        const multiplier = parseFloat(document.getElementById('multiplier').value) || 1;
        
        const pnlDisplay = document.getElementById('pnl-display');
        const exitPriceInput = document.getElementById('exit_price');
        const closedDateInput = document.getElementById('closed_date');
        
        // Validation: If exit price is entered, closed date must be selected
        if (exitPrice > 0 && !closedDate) {
            exitPriceInput.setCustomValidity('If you enter an Exit Price, you must select a Closed Date');
            closedDateInput.setCustomValidity('Required when Exit Price is provided');
            closedDateInput.classList.add('border-red-500');
        } else {
            exitPriceInput.setCustomValidity('');
            closedDateInput.setCustomValidity('');
            closedDateInput.classList.remove('border-red-500');
        }
        
        // Only show calculations if we have exit price
        if (!exitPrice || !entryPrice || !quantity) {
            pnlDisplay.classList.add('hidden');
            return;
        }
        
        // Calculate components based on asset type
        let grossPnL, netPnL;
        
        if (assetType === 'stock') {
            // Stock calculation: (Exit - Entry) × Shares
            const priceDiff = exitPrice - entryPrice;
            grossPnL = priceDiff * quantity;
            netPnL = grossPnL - fees;
            
            // Update breakdown for stocks
            document.getElementById('calc-entry').textContent = `$${entryPrice.toFixed(2)}`;
            document.getElementById('calc-exit').textContent = `$${exitPrice.toFixed(2)}`;
            
            const calcDiffEl = document.getElementById('calc-diff');
            calcDiffEl.textContent = `$${priceDiff.toFixed(2)}`;
            calcDiffEl.className = `text-sm font-medium ${priceDiff >= 0 ? 'text-green-600' : 'text-red-600'}`;
            
            document.getElementById('calc-multiplier').textContent = `${quantity} shares`;
        } else {
            // Options/Futures calculation: (Exit × Multiplier × Contracts) - (Entry × Multiplier × Contracts)
            const entryValue = entryPrice * multiplier * quantity;
            const exitValue = exitPrice * multiplier * quantity;
            grossPnL = exitValue - entryValue;
            netPnL = grossPnL - fees;
            
            // Update breakdown for options/futures
            document.getElementById('calc-entry').textContent = `$${entryPrice.toFixed(2)} × ${multiplier} × ${quantity} = $${entryValue.toFixed(2)}`;
            document.getElementById('calc-exit').textContent = `$${exitPrice.toFixed(2)} × ${multiplier} × ${quantity} = $${exitValue.toFixed(2)}`;
            
            const calcDiffEl = document.getElementById('calc-diff');
            calcDiffEl.textContent = `$${exitValue.toFixed(2)} - $${entryValue.toFixed(2)}`;
            calcDiffEl.className = `text-sm font-medium ${grossPnL >= 0 ? 'text-green-600' : 'text-red-600'}`;
            
            document.getElementById('calc-multiplier').textContent = `${quantity} contracts × ${multiplier} multiplier`;
        }
        
        const calcGrossEl = document.getElementById('calc-gross');
        calcGrossEl.textContent = `${grossPnL >= 0 ? '+' : ''}$${grossPnL.toFixed(2)}`;
        calcGrossEl.className = `text-sm font-semibold ${grossPnL >= 0 ? 'text-green-600' : 'text-red-600'}`;
        
        document.getElementById('calc-fees').textContent = `-$${fees.toFixed(2)}`;
        
        // Update final P&L display
        const pnlValue = document.getElementById('pnl-value');
        const pnlIcon = document.getElementById('pnl-icon');
        const pnlContainer = document.getElementById('pnl-container');
        
        pnlDisplay.classList.remove('hidden');
        pnlContainer.classList.remove('from-green-50', 'to-green-100', 'from-red-50', 'to-red-100', 'from-gray-50', 'to-gray-100', 'border-green-300', 'border-red-300', 'border-gray-300');
        
        if (netPnL > 0) {
            pnlValue.textContent = `+$${netPnL.toFixed(2)}`;
            pnlValue.className = 'text-3xl font-bold text-green-600';
            pnlContainer.classList.add('from-green-50', 'to-green-100', 'border-green-300');
            pnlIcon.innerHTML = `
                <svg class="w-16 h-16 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            `;
        } else if (netPnL < 0) {
            pnlValue.textContent = `-$${Math.abs(netPnL).toFixed(2)}`;
            pnlValue.className = 'text-3xl font-bold text-red-600';
            pnlContainer.classList.add('from-red-50', 'to-red-100', 'border-red-300');
            pnlIcon.innerHTML = `
                <svg class="w-16 h-16 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            `;
        } else {
            pnlValue.textContent = `$0.00`;
            pnlValue.className = 'text-3xl font-bold text-gray-600';
            pnlContainer.classList.add('from-gray-50', 'to-gray-100', 'border-gray-300');
            pnlIcon.innerHTML = `
                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                </svg>
            `;
        }
        
        // Update formula display
        let formulaText;
        if (assetType === 'stock') {
            formulaText = `Formula: (Exit - Entry) × Shares - Fees = ($${exitPrice.toFixed(2)} - $${entryPrice.toFixed(2)}) × ${quantity} - $${fees.toFixed(2)}`;
        } else {
            formulaText = `Formula: (Exit - Entry) × Contracts × Multiplier - Fees = ($${exitPrice.toFixed(2)} - $${entryPrice.toFixed(2)}) × ${quantity} × ${multiplier} - $${fees.toFixed(2)}`;
        }
        document.getElementById('pnl-formula').textContent = formulaText;
    }

    // Validate broker fields on page load to enable buttons if credentials are already saved
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we're on auto-import tab and validate fields
        const flexToken = document.getElementById('flex-token');
        const queryId = document.getElementById('query-id');
        
        if (flexToken && queryId && (flexToken.value || queryId.value)) {
            validateBrokerFields();
        }
    });
</script>
@endsection
