<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error | Tradalyze</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-red-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 text-center">
            <!-- Error Code -->
            <div class="mb-8">
                <h1 class="text-9xl font-bold text-red-600 mb-4">500</h1>
                <div class="w-24 h-1 bg-red-600 mx-auto mb-6"></div>
            </div>
            
            <!-- Icon -->
            <div class="mb-6">
                <svg class="w-32 h-32 mx-auto text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <!-- Message -->
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Something Went Wrong</h2>
            <p class="text-gray-600 text-lg mb-8">
                We're experiencing technical difficulties. Our team has been notified and is working on it.
            </p>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Go to Dashboard
                </a>
                <button onclick="location.reload()" class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg shadow-md transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Try Again
                </button>
            </div>
            
            <!-- Help Text -->
            <p class="mt-8 text-sm text-gray-500">
                If the problem persists, please contact support.
            </p>
        </div>
    </div>
</body>
</html>
