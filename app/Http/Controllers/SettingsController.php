<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\TradeTag;
use App\Models\Balance;

class SettingsController extends Controller
{
    public function index()
    {
        $tags = TradeTag::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
        
        $balances = Balance::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $hasInitialBalance = Balance::where('user_id', auth()->id())
            ->where('type', 'initial')
            ->exists();
            
        return view('settings.index', compact('tags', 'balances', 'hasInitialBalance'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => strip_tags($validated['name']),
            'email' => $validated['email'],
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function storeTag(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Check for duplicate tag name for this user
        $exists = TradeTag::where('user_id', auth()->id())
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'A tag with this name already exists.'
            ], 422);
        }

        $tag = TradeTag::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'color' => $validated['color'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully.',
            'tag' => $tag,
        ]);
    }

    public function updateTag(Request $request, TradeTag $tag)
    {
        // Ensure user owns this tag
        if ($tag->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Check for duplicate tag name (excluding current tag)
        $exists = TradeTag::where('user_id', auth()->id())
            ->where('name', $validated['name'])
            ->where('id', '!=', $tag->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'A tag with this name already exists.'
            ], 422);
        }

        $tag->update([
            'name' => $validated['name'],
            'color' => $validated['color'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully.',
            'tag' => $tag,
        ]);
    }

    public function destroyTag(TradeTag $tag)
    {
        // Ensure user owns this tag
        if ($tag->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully.'
        ]);
    }

    public function storeBalance(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:initial,deposit,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // Check if trying to create initial balance when one already exists
        if ($validated['type'] === 'initial') {
            $exists = Balance::where('user_id', auth()->id())
                ->where('type', 'initial')
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Initial balance already exists.'
                ], 422);
            }
        }

        $balance = Balance::create([
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'description' => $validated['description'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Balance entry created successfully.',
            'balance' => $balance,
        ]);
    }

    public function destroyBalance(Balance $balance)
    {
        // Ensure user owns this balance
        if ($balance->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        // Prevent deletion of initial balance
        if ($balance->type === 'initial') {
            return response()->json([
                'success' => false,
                'message' => 'Initial balance cannot be deleted.'
            ], 422);
        }

        $balance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Balance entry deleted successfully.'
        ]);
    }
}
