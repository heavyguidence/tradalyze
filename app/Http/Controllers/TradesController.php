<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiaryEntry;
use App\Models\Position;
use App\Models\PositionScreenshot;
use App\Models\Instrument;
use App\Models\Fill;
use App\Models\TradeTag;
use App\Services\FifoPositionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TradesController extends Controller
{
    protected $fifoService;

    public function __construct(FifoPositionService $fifoService)
    {
        $this->fifoService = $fifoService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 30);
        
        // Build query with filters
        $query = Position::with(['instrument', 'tags'])
            ->whereHas('instrument', function($q) {
                $q->where('user_id', auth()->id());
            });

        // Filter by symbol keyword
        if ($request->filled('symbol')) {
            $query->whereHas('instrument', function($q) use ($request) {
                $q->where('symbol', 'LIKE', '%' . $request->symbol . '%');
            });
        }

        // Filter by trade state (open/closed)
        if ($request->filled('state')) {
            if ($request->state === 'open') {
                $query->whereNull('close_datetime');
            } elseif ($request->state === 'closed') {
                $query->whereNotNull('close_datetime');
            }
        }

        // Filter by asset type
        if ($request->filled('asset_type')) {
            $query->whereHas('instrument', function($q) use ($request) {
                $q->where('asset_type', $request->asset_type);
            });
        }

        // Filter by option type (CALL/PUT)
        if ($request->filled('put_call')) {
            $query->whereHas('instrument', function($q) use ($request) {
                $q->where('put_call', $request->put_call);
            });
        }

        // Filter by opened date range
        if ($request->filled('opened_from')) {
            $query->whereDate('open_datetime', '>=', $request->opened_from);
        }
        if ($request->filled('opened_to')) {
            $query->whereDate('open_datetime', '<=', $request->opened_to);
        }

        // Filter by closed date range
        if ($request->filled('closed_from')) {
            $query->whereDate('close_datetime', '>=', $request->closed_from);
        }
        if ($request->filled('closed_to')) {
            $query->whereDate('close_datetime', '<=', $request->closed_to);
        }

        // Filter by tags
        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('trade_tags.id', $request->tag);
            });
        }

        // Filter by P&L (Winner/Loser)
        if ($request->filled('pnl_filter')) {
            if ($request->pnl_filter === 'winner') {
                $query->where('realized_pnl', '>', 0);
            } elseif ($request->pnl_filter === 'loser') {
                $query->where('realized_pnl', '<', 0);
            } elseif ($request->pnl_filter === 'breakeven') {
                $query->where('realized_pnl', '=', 0);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'close_datetime');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'symbol') {
            $query->join('instruments', 'positions.instrument_id', '=', 'instruments.id')
                ->select('positions.*')
                ->orderBy('instruments.symbol', $sortOrder);
        } elseif ($sortBy === 'pnl') {
            $query->orderBy('realized_pnl', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        if ($perPage === 'all') {
            $positions = $query->get();
            // Create a custom paginator for "all" results
            $positions = new \Illuminate\Pagination\LengthAwarePaginator(
                $positions,
                $positions->count(),
                $positions->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $positions = $query->paginate((int)$perPage)->appends($request->except('page'));
        }
        
        // Get user's tags for filter
        $userTags = TradeTag::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        // Map of date-string => DiaryEntry for day-header indicators
        $diaryEntryByDate = DiaryEntry::where('user_id', auth()->id())
            ->whereNotNull('entry_date')
            ->get()
            ->keyBy(fn($e) => $e->entry_date->format('Y-m-d'));

        return view('trades', compact('positions', 'userTags', 'diaryEntryByDate'));
    }

    public function create()
    {
        // Get all tags for the current user
        $userTags = TradeTag::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
        
        // Find the "Untagged" tag ID for default selection
        $defaultTagId = $userTags->where('name', 'Untagged')->first()?->id;
        
        // Get user's broker credentials
        $user = auth()->user();
        
        return view('trades.create', compact('userTags', 'defaultTagId', 'user'));
    }

    public function show(Position $position)
    {
        // Ensure user owns this position
        if ($position->instrument->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load the instrument, fills, tags, and screenshots
        $position->load(['instrument.fills' => function($query) use ($position) {
            $query->orderBy('datetime', 'asc');
        }, 'tags', 'screenshots']);
        
        // Get all available tags for the user
        $availableTags = TradeTag::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('trades.show', compact('position', 'availableTags'));
    }

    public function update(Request $request, Position $position)
    {
        // Ensure user owns this position
        if ($position->instrument->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $position->update([
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'Notes updated successfully.');
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'asset_type' => 'required|in:stock,option,future',
            'symbol' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0.01',
            'opened_date' => 'required|date',
            'closed_date' => 'nullable|date|after_or_equal:opened_date',
            'entry_price' => 'required|numeric|min:0',
            'exit_price' => 'nullable|numeric|min:0',
            'fees' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:5000',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:trade_tags,id',
            // Option/Future specific fields
            'option_type' => 'required_if:asset_type,option,future|in:CALL,PUT,null',
            'strike_price' => 'nullable|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'multiplier' => 'nullable|integer|min:1',
        ]);

        // Validate that if exit_price is provided, closed_date must also be provided
        if ($request->filled('exit_price') && !$request->filled('closed_date')) {
            return response()->json([
                'success' => false,
                'message' => 'Closed Date is required when Exit Price is provided.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Map asset type to database format
            $assetTypeMap = [
                'stock' => 'STK',
                'option' => 'OPT',
                'future' => 'FUT',
            ];

            // Create or find instrument
            $instrumentData = [
                'user_id' => auth()->id(),
                'symbol' => strtoupper($validated['symbol']),
                'underlying_symbol' => strtoupper($validated['symbol']),
                'asset_type' => $assetTypeMap[$validated['asset_type']],
                'currency' => 'USD',
            ];

            // Add derivative-specific fields
            if ($validated['asset_type'] !== 'stock') {
                $instrumentData['put_call'] = $validated['option_type'] ?? null;
                $instrumentData['strike'] = $validated['strike_price'] ?? null;
                $instrumentData['expiry'] = $validated['expiration_date'] ?? null;
                $instrumentData['multiplier'] = $validated['multiplier'] ?? 100;
            } else {
                $instrumentData['multiplier'] = 1;
            }

            $instrument = Instrument::create($instrumentData);

            // Create opening fill (BUY)
            $entryFees = $request->filled('exit_price') ? ($validated['fees'] ?? 0) / 2 : ($validated['fees'] ?? 0);
            $openFill = Fill::create([
                'instrument_id' => $instrument->id,
                'datetime' => $validated['opened_date'],
                'side' => 'BUY',
                'quantity' => $validated['quantity'],
                'price' => $validated['entry_price'],
                'fees' => $entryFees,
            ]);

            // Calculate cost basis
            $multiplier = $instrumentData['multiplier'];
            if ($validated['asset_type'] === 'stock') {
                $costBasis = ($validated['entry_price'] * $validated['quantity']) + $entryFees;
            } else {
                $costBasis = ($validated['entry_price'] * $validated['quantity'] * $multiplier) + $entryFees;
            }

            // Create position
            $positionData = [
                'instrument_id' => $instrument->id,
                'open_datetime' => $validated['opened_date'],
                'quantity' => $validated['quantity'],
                'cost_basis' => $costBasis,
                'notes' => $validated['notes'] ?? null,
            ];

            // If position is closed, create closing fill and calculate P&L
            if ($request->filled('exit_price') && $request->filled('closed_date')) {
                $exitFees = ($validated['fees'] ?? 0) / 2;
                $closeFill = Fill::create([
                    'instrument_id' => $instrument->id,
                    'datetime' => $validated['closed_date'],
                    'side' => 'SELL',
                    'quantity' => $validated['quantity'],
                    'price' => $validated['exit_price'],
                    'fees' => $exitFees,
                ]);

                // Calculate realized P&L
                if ($validated['asset_type'] === 'stock') {
                    $proceeds = ($validated['exit_price'] * $validated['quantity']) - $exitFees;
                    $realizedPnl = $proceeds - $costBasis;
                } else {
                    $proceeds = ($validated['exit_price'] * $validated['quantity'] * $multiplier) - $exitFees;
                    $realizedPnl = $proceeds - $costBasis;
                }

                $positionData['close_datetime'] = $validated['closed_date'];
                $positionData['realized_pnl'] = $realizedPnl;
            }

            $position = Position::create($positionData);

            // Attach selected tags to new position (or Untagged if none selected)
            if ($request->filled('tag_ids') && is_array($validated['tag_ids']) && count($validated['tag_ids']) > 0) {
                // Verify all tags belong to the current user before attaching
                $userTagIds = TradeTag::where('user_id', auth()->id())
                    ->whereIn('id', $validated['tag_ids'])
                    ->pluck('id')
                    ->toArray();
                
                if (!empty($userTagIds)) {
                    $position->tags()->attach($userTagIds);
                }
            } else {
                // Default to Untagged if no tags selected
                $untaggedTag = TradeTag::where('user_id', auth()->id())
                    ->where('name', 'Untagged')
                    ->first();
                
                if ($untaggedTag) {
                    $position->tags()->attach($untaggedTag->id);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trade saved successfully!',
                'position_id' => $position->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save trade: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'broker' => 'required|string',
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        try {
            $broker = $request->input('broker');
            $file = $request->file('csv_file');
            
            \Log::info('CSV Upload started', ['broker' => $broker, 'file' => $file->getClientOriginalName()]);
            
            // Parse CSV based on broker
            if ($broker === 'interactive_broker') {
                $result = $this->parseInteractiveBrokerCSV($file);
                
                \Log::info('CSV Upload completed', $result);
                
                return redirect()->route('trades')->with('success', 
                    "Successfully imported {$result['fills']} fills for {$result['instruments']} instruments. Created {$result['positions']} positions.");
            }
            
            return back()->with('error', 'Unsupported broker format.');
            
        } catch (\Exception $e) {
            \Log::error('CSV Upload failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Error processing CSV: ' . $e->getMessage());
        }
    }

    private function parseInteractiveBrokerCSV($file)
    {
        $csvData = array_map('str_getcsv', file($file->getPathname()));
        $headers = array_shift($csvData); // Remove header row
        
        $fillsCount = 0;
        $instrumentsCreated = 0;
        $processedInstruments = collect();

        DB::beginTransaction();
        
        try {
            foreach ($csvData as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Create associative array from CSV row
                $data = array_combine($headers, $row);
                
                // Skip if Symbol is empty
                if (empty($data['Symbol'])) {
                    continue;
                }
                
                // Sanitize and prepare instrument data
                $ibAssetClass = trim($data['AssetClass'] ?? '');
                if ($ibAssetClass === 'STK') {
                    $assetType = 'STK';
                } elseif ($ibAssetClass === 'FUT') {
                    $assetType = 'FUT';
                } else {
                    $assetType = 'OPT'; // OPT, FOP, WAR, etc.
                }

                // Clean symbol - remove extra spaces
                $cleanSymbol = preg_replace('/\s+/', '', trim($data['Symbol']));

                $instrumentData = [
                    'user_id' => auth()->id(),
                    'symbol' => $cleanSymbol,
                    'underlying_symbol' => !empty($data['UnderlyingSymbol']) ? trim($data['UnderlyingSymbol']) : null,
                    'asset_type' => $assetType,
                    'currency' => isset($data['CurrencyPrimary']) ? trim($data['CurrencyPrimary']) : 'USD',
                ];

                // Parse expiry date (shared by OPT and FUT)
                $expiryDate = null;
                if (!empty($data['Expiry'])) {
                    try {
                        $expiryDate = date('Y-m-d', strtotime($data['Expiry']));
                    } catch (\Exception $e) {
                        \Log::warning('Invalid expiry date', ['expiry' => $data['Expiry'], 'symbol' => $cleanSymbol]);
                    }
                }

                if ($assetType === 'OPT') {
                    $instrumentData['expiry'] = $expiryDate;
                    $instrumentData['strike'] = !empty($data['Strike']) ? (float)$data['Strike'] : null;
                    $instrumentData['put_call'] = !empty($data['Put/Call']) ? strtoupper(trim($data['Put/Call'])) : null;
                    $instrumentData['multiplier'] = !empty($data['Multiplier']) ? (int)$data['Multiplier'] : 100;
                } elseif ($assetType === 'FUT') {
                    $instrumentData['expiry'] = $expiryDate;
                    $instrumentData['strike'] = null;
                    $instrumentData['put_call'] = null;
                    $instrumentData['multiplier'] = !empty($data['Multiplier']) ? (int)$data['Multiplier'] : 1;
                } else {
                    $instrumentData['expiry'] = null;
                    $instrumentData['strike'] = null;
                    $instrumentData['put_call'] = null;
                    $instrumentData['multiplier'] = 1;
                }
                
                // Find or create instrument - use whereDate for proper date comparison
                $instrument = Instrument::where('user_id', auth()->id())
                    ->where('symbol', $instrumentData['symbol'])
                    ->where('asset_type', $instrumentData['asset_type'])
                    ->where(function($query) use ($instrumentData) {
                        if ($instrumentData['expiry'] === null) {
                            $query->whereNull('expiry');
                        } else {
                            $query->whereDate('expiry', $instrumentData['expiry']);
                        }
                    })
                    ->where(function($query) use ($instrumentData) {
                        if ($instrumentData['strike'] === null) {
                            $query->whereNull('strike');
                        } else {
                            $query->where('strike', $instrumentData['strike']);
                        }
                    })
                    ->where(function($query) use ($instrumentData) {
                        if ($instrumentData['put_call'] === null) {
                            $query->whereNull('put_call');
                        } else {
                            $query->where('put_call', $instrumentData['put_call']);
                        }
                    })
                    ->first();
                
                if (!$instrument) {
                    $instrument = Instrument::create($instrumentData);
                    $instrumentsCreated++;
                }
                if (!$instrument) {
                    $instrument = Instrument::create($instrumentData);
                    $instrumentsCreated++;
                }
                
                // Track this instrument for FIFO processing
                if (!$processedInstruments->contains($instrument->id)) {
                    $processedInstruments->push($instrument->id);
                }
                
                // Check for duplicate fills by exec_id
                $execId = trim($data['TradeID']);
                if (Fill::where('exec_id', $execId)->exists()) {
                    \Log::info('Skipping duplicate fill', ['exec_id' => $execId]);
                    continue; // Skip duplicate
                }
                
                // Parse datetime with error handling
                $fillDatetime = null;
                try {
                    $fillDatetime = date('Y-m-d H:i:s', strtotime($data['DateTime']));
                } catch (\Exception $e) {
                    \Log::warning('Invalid datetime', ['datetime' => $data['DateTime'], 'symbol' => $cleanSymbol]);
                    continue; // Skip this fill if datetime is invalid
                }
                
                // Create fill record
                Fill::create([
                    'instrument_id' => $instrument->id,
                    'datetime' => $fillDatetime,
                    'side' => strtoupper(trim($data['Buy/Sell'])) === 'BUY' ? 'BUY' : 'SELL',
                    'quantity' => abs((float)$data['Quantity']),
                    'price' => (float)$data['TradePrice'],
                    'fees' => abs((float)($data['IBCommission'] ?? 0)),
                    'order_id' => !empty($data['TradeID']) ? trim($data['TradeID']) : null,
                    'exec_id' => $execId,
                ]);
                
                $fillsCount++;
            }
            
            // Process FIFO for all affected instruments
            foreach ($processedInstruments as $instrumentId) {
                $instrument = Instrument::find($instrumentId);
                $this->fifoService->processInstrument($instrument);
            }
            
            $positionsCount = Position::count();
            
            DB::commit();
            
            return [
                'fills' => $fillsCount,
                'instruments' => $instrumentsCreated,
                'positions' => $positionsCount,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Position $position)
    {
        // Ensure user owns this position
        if ($position->instrument->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        try {
            DB::beginTransaction();
            
            $instrumentId = $position->instrument_id;
            
            // Delete the position
            $position->delete();
            
            // Check if this instrument has any remaining positions or fills
            $instrument = Instrument::find($instrumentId);
            if ($instrument) {
                $hasPositions = $instrument->positions()->exists();
                $hasFills = $instrument->fills()->exists();
                
                // If no positions or fills remain, delete the instrument (cascade deletes all)
                if (!$hasPositions && !$hasFills) {
                    $instrument->delete();
                }
            }
            
            DB::commit();
            
            return redirect()->route('trades')->with('success', 'Position deleted successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting position: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'position_ids' => 'required|array',
            'position_ids.*' => 'exists:positions,id',
        ]);
        
        // Ensure user owns all selected positions
        $invalidPositions = Position::whereIn('id', $request->position_ids)
            ->whereHas('instrument', function($query) {
                $query->where('user_id', '!=', auth()->id());
            })
            ->exists();
            
        if ($invalidPositions) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            
            $positionIds = $request->input('position_ids');
            
            // Get all affected instruments before deleting positions
            $affectedInstruments = Position::whereIn('id', $positionIds)
                ->pluck('instrument_id')
                ->unique();
            
            // Delete all selected positions
            $deletedCount = Position::whereIn('id', $positionIds)->delete();
            
            // Clean up orphaned instruments
            foreach ($affectedInstruments as $instrumentId) {
                $instrument = Instrument::find($instrumentId);
                if ($instrument) {
                    $hasPositions = $instrument->positions()->exists();
                    $hasFills = $instrument->fills()->exists();
                    
                    if (!$hasPositions && !$hasFills) {
                        $instrument->delete();
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('trades')->with('success', "Successfully deleted {$deletedCount} position(s).");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting positions: ' . $e->getMessage());
        }
    }

    public function attachTag(Position $position, TradeTag $tag)
    {
        // Ensure user owns this position and tag
        if ($position->instrument->user_id !== auth()->id() || $tag->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        // Check if tag is already attached
        if ($position->tags()->where('trade_tag_id', $tag->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tag already attached to this trade.'
            ], 422);
        }

        $position->tags()->attach($tag->id);

        return response()->json([
            'success' => true,
            'message' => 'Tag attached successfully.',
            'tag' => $tag,
        ]);
    }

    public function detachTag(Position $position, TradeTag $tag)
    {
        // Ensure user owns this position and tag
        if ($position->instrument->user_id !== auth()->id() || $tag->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        $position->tags()->detach($tag->id);

        return response()->json([
            'success' => true,
            'message' => 'Tag removed successfully.'
        ]);
    }

    public function saveBrokerCredentials(Request $request)
    {
        $request->validate([
            'flex_token' => 'required|string',
            'query_id' => 'required|string',
        ]);

        $user = auth()->user();
        $user->ib_flex_token = $request->flex_token;
        $user->ib_query_id = $request->query_id;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Broker credentials saved successfully.'
        ]);
    }

    public function autoImport(Request $request)
    {
        // Get credentials from user if not provided in request
        $user = auth()->user();
        $flexToken = $request->input('flex_token', $user->ib_flex_token);
        $queryId = $request->input('query_id', $user->ib_query_id);

        // Validate that we have credentials
        if (empty($flexToken) || empty($queryId)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide both Flex Token and Query ID in settings first.'
            ], 422);
        }

        try {
            Log::info('Auto Import: Starting', [
                'user_id' => $user->id,
                'query_id' => $queryId
            ]);

            // Create or find the "Imported" tag for this user
            $importedTag = TradeTag::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'name' => 'Imported',
                ],
                [
                    'color' => '#6366F1', // Indigo color
                ]
            );

            Log::info('Auto Import: Imported tag ready', [
                'tag_id' => $importedTag->id
            ]);

            // Get all existing positions before import to identify new ones
            $existingPositionIds = Position::whereHas('instrument', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->pluck('id')->toArray();

            // Use the Interactive Broker Flex Service
            $flexService = new \App\Services\InteractiveBrokerFlexService();
            
            // Import the report (this handles both request and retrieval with retries)
            $importResult = $flexService->importReport($flexToken, $queryId);
            
            if (!$importResult['success']) {
                Log::error('Auto Import: Failed to retrieve report', [
                    'error' => $importResult['error']
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => $importResult['message'] ?? $importResult['error']
                ], 500);
            }

            // Now we have the CSV data, let's process it
            $csvData = $importResult['csvData'];
            
            // Save CSV to a temporary file for processing
            $tempFile = tmpfile();
            $tempFilePath = stream_get_meta_data($tempFile)['uri'];
            fwrite($tempFile, $csvData);
            
            // Create a mock UploadedFile object
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $tempFilePath,
                'ib_auto_import.csv',
                'text/csv',
                null,
                true
            );
            
            Log::info('Auto Import: Processing CSV data', [
                'csv_size' => strlen($csvData)
            ]);

            // Parse the CSV using existing logic
            $result = $this->parseInteractiveBrokerCSV($uploadedFile);
            
            // Close and delete temp file
            fclose($tempFile);

            // Get all newly created positions
            $newPositions = Position::whereHas('instrument', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereNotIn('id', $existingPositionIds)->get();

            // Tag all new positions with "Imported" tag
            $taggedCount = 0;
            foreach ($newPositions as $position) {
                // Check if position doesn't already have this tag
                if (!$position->tags()->where('trade_tag_id', $importedTag->id)->exists()) {
                    $position->tags()->attach($importedTag->id);
                    $taggedCount++;
                }
            }
            
            Log::info('Auto Import: Completed successfully', array_merge($result, [
                'tagged_positions' => $taggedCount
            ]));

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$result['fills']} fills for {$result['instruments']} instruments. Created {$result['positions']} positions.",
                'data' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Auto Import: Exception occurred', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error during auto import: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeScreenshot(Request $request, Position $position)
    {
        if ($position->instrument->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate(['screenshot' => 'required|image|max:10240']);

        $path       = $request->file('screenshot')->store("position-screenshots/{$position->id}", 'public');
        $screenshot = $position->screenshots()->create([
            'path'          => $path,
            'original_name' => $request->file('screenshot')->getClientOriginalName(),
        ]);

        return response()->json([
            'success'    => true,
            'screenshot' => [
                'id'   => $screenshot->id,
                'url'  => Storage::disk('public')->url($path),
                'name' => $screenshot->original_name,
            ],
        ]);
    }

    public function destroyScreenshot(Position $position, PositionScreenshot $screenshot)
    {
        if ($position->instrument->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($screenshot->position_id !== $position->id) {
            abort(403, 'Unauthorized action.');
        }

        Storage::disk('public')->delete($screenshot->path);
        $screenshot->delete();

        return response()->json(['success' => true]);
    }
}
