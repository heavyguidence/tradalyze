<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request with rate limiting
     */
    public function login(Request $request)
    {
        // Rate limiting - 5 attempts per minute
        $key = 'login.' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => ['Too many login attempts. Please try again in ' . $seconds . ' seconds.'],
            ]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($key, 60);

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request with XSS protection
     */
    public function register(Request $request)
    {
        // Validate input with strict rules
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create user with hashed password (automatic via User model cast)
        $user = User::create([
            'name' => strip_tags($validated['name']), // Additional XSS protection
            'email' => $validated['email'],
            'password' => $validated['password'], // Auto-hashed by model
        ]);

        // Log the user in
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}

