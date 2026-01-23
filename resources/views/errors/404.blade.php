<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | Tradalyze</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 text-center">
            <!-- Error Code -->
            <div class="mb-8">
                <h1 class="text-9xl font-bold text-orange-600 mb-4">404</h1>
                <div class="w-24 h-1 bg-orange-600 mx-auto mb-6"></div>
            </div>
            
            <!-- Icon -->
            <div class="mb-6">
                <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <!-- Message -->
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Page Not Found</h2>
            <p class="text-gray-600 text-lg mb-8">
                The page you're looking for doesn't exist or has been moved.
            </p>
            
            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Go to Dashboard
                </a>
                <button onclick="history.back()" class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg shadow-md transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Go Back
                </button>
            </div>
            
            <!-- Help Text -->
            <p class="mt-8 text-sm text-gray-500">
                If you believe this is a mistake, please contact support.
            </p>
        </div>
    </div>
</body>
</html>
