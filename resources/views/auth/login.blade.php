<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Tradalyze') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900">Tradalyze</h1>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Sign in to your account</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Or 
                    <a href="{{ route('register') }}" class="font-medium text-orange-600 hover:text-orange-500">
                        create a new account
                    </a>
                </p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                There were errors with your submission
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="{{ old('email') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                            placeholder="you@example.com"
                        >
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        id="remember" 
                        name="remember" 
                        type="checkbox" 
                        class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors"
                    >
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
