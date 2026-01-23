<?php

namespace App\Http\Controllers;

use App\Models\DiaryEntry;
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
            ->latest()
            ->paginate($perPage)
            ->appends(['per_page' => $perPage]);
        
        return view('diary', compact('entries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);

        $entry = auth()->user()->diaryEntries()->create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Diary entry saved successfully!',
            'entry' => $entry,
        ]);
    }

    public function show(DiaryEntry $entry)
    {
        // Ensure the user owns this entry
        if ($entry->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('diary-show', compact('entry'));
    }

    public function update(Request $request, DiaryEntry $entry)
    {
        // Ensure the user owns this entry
        if ($entry->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);

        $entry->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Diary entry updated successfully!',
            'entry' => $entry,
        ]);
    }

    public function destroy(DiaryEntry $entry)
    {
        // Ensure the user owns this entry
        if ($entry->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $entry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Diary entry deleted successfully!',
        ]);
    }
}
