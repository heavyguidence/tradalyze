<?php

namespace App\Http\Controllers;

use App\Models\DiaryEntry;
use App\Models\Position;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        // Validate per_page value
        if (!in_array($perPage, [5, 10, 20])) {
            $perPage = 10;
        }
        
        $entries = auth()->user()->diaryEntries()
            ->orderByRaw('CASE WHEN entry_date IS NULL THEN 0 ELSE 1 END DESC')
            ->orderBy('entry_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage]);
        
        return view('diary', compact('entries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content'    => 'required|string',
            'title'      => 'nullable|string|max:255',
            'entry_date' => 'nullable|date',
        ]);

        $entryDate = $request->filled('entry_date') ? $request->entry_date : null;

        if ($entryDate) {
            // One entry per trading date — update if it already exists
            $entry = DiaryEntry::updateOrCreate(
                ['user_id' => auth()->id(), 'entry_date' => $entryDate],
                ['title' => $request->title, 'content' => $request->content]
            );
            $wasCreated = $entry->wasRecentlyCreated;
        } else {
            // Undated general note — always create a new entry
            $entry = auth()->user()->diaryEntries()->create([
                'entry_date' => null,
                'title'      => $request->title,
                'content'    => $request->content,
            ]);
            $wasCreated = true;
        }

        return response()->json([
            'success'     => true,
            'message'     => $wasCreated ? 'Diary entry saved!' : 'Existing entry for this date updated!',
            'entry'       => $entry,
            'was_created' => $wasCreated,
        ]);
    }

    public function show(DiaryEntry $entry)
    {
        if ($entry->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Load all positions closed on this trading date
        $tradingDayPositions = collect();
        if ($entry->entry_date) {
            $tradingDayPositions = Position::whereHas('instrument', fn($q) => $q->where('user_id', auth()->id()))
                ->whereDate('close_datetime', $entry->entry_date)
                ->with(['instrument', 'screenshots'])
                ->get();
        }

        return view('diary-show', compact('entry', 'tradingDayPositions'));
    }

    public function update(Request $request, DiaryEntry $entry)
    {
        if ($entry->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content'    => 'required|string',
            'title'      => 'nullable|string|max:255',
            'entry_date' => 'nullable|date',
        ]);

        $entry->update([
            'title'      => $request->title,
            'content'    => $request->content,
            'entry_date' => $request->filled('entry_date') ? $request->entry_date : $entry->entry_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Diary entry updated successfully!',
            'entry'   => $entry,
        ]);
    }

    public function destroy(DiaryEntry $entry)
    {
        if ($entry->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $entry->delete();

        return response()->json(['success' => true, 'message' => 'Diary entry deleted successfully!']);
    }

    /**
     * Upload a pasted image from the diary editor.
     * Saves to local public disk and returns the embeddable URL.
     */
    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:10240']);

        $path = $request->file('image')->store('diary-images', 'public');

        return response()->json(['url' => '/storage/' . $path]);
    }

    /**
     * Check if a diary entry already exists for the given date.
     * Used by the date picker to warn/pre-load existing content.
     */
    public function checkDate(Request $request)
    {
        $request->validate(['date' => 'required|date']);

        $entry = DiaryEntry::where('user_id', auth()->id())
            ->whereDate('entry_date', $request->date)
            ->first();

        if ($entry) {
            return response()->json([
                'exists'  => true,
                'id'      => $entry->id,
                'content' => $entry->content,
                'title'   => $entry->title ?? '',
            ]);
        }

        return response()->json(['exists' => false]);
    }
}
