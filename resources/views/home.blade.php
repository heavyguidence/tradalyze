<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Primary Meta Tags -->
    <title>{{ config('app.name', 'Tradalyze') }} - Transform Your Trading Journey | Advanced Trading Analytics & Journal</title>
    <meta name="title" content="Tradalyze - Transform Your Trading Journey | Advanced Trading Analytics & Journal">
    <meta name="description" content="Master your trades with intelligent analytics, automated tracking, and insightful journaling. Track P&L, analyze performance, and improve your trading with Tradalyze - the complete trading journal and analytics platform.">
    <meta name="keywords" content="trading journal, trade analytics, trading tracker, P&L tracker, trade diary, stock trading journal, options trading, day trading, swing trading, trading performance, FIFO position tracking, Interactive Brokers, trading statistics">
    <meta name="author" content="Tradalyze">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Tradalyze - Transform Your Trading Journey">
    <meta property="og:description" content="Master your trades with intelligent analytics, automated tracking, and insightful journaling. Elevate your trading game today.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:site_name" content="Tradalyze">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="Tradalyze - Transform Your Trading Journey">
    <meta property="twitter:description" content="Master your trades with intelligent analytics, automated tracking, and insightful journaling. Elevate your trading game today.">
    <meta property="twitter:image" content="{{ asset('images/twitter-image.jpg') }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url('/') }}">
    
    <!-- Additional SEO -->
    <meta name="theme-color" content="#ea580c">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Tradalyze">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .gradient-bg {
            background: linear-gradient(-45deg, #1e293b, #0f172a, #7c2d12, #ea580c);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(234, 88, 12, 0.5);
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        
        .shimmer {
            background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.3) 50%, transparent 100%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }
        
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(234, 88, 12, 0.2);
        }
        
        .stat-number {
            background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-gray-900/80 backdrop-blur-md border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span class="text-2xl font-bold text-white">Tradalyze</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-300 hover:text-white transition-colors duration-200">Features</a>
                    <a href="#how-it-works" class="text-gray-300 hover:text-white transition-colors duration-200">How It Works</a>
                    <a href="#testimonials" class="text-gray-300 hover:text-white transition-colors duration-200">Testimonials</a>
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Login</a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-orange-600 to-orange-500 text-white rounded-lg hover:from-orange-700 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-orange-500/50">
                        Get Started
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-300 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-gray-900 border-t border-gray-800">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" class="block text-gray-300 hover:text-white transition-colors duration-200">Features</a>
                <a href="#how-it-works" class="block text-gray-300 hover:text-white transition-colors duration-200">How It Works</a>
                <a href="#testimonials" class="block text-gray-300 hover:text-white transition-colors duration-200">Testimonials</a>
                <a href="{{ route('login') }}" class="block text-gray-300 hover:text-white transition-colors duration-200">Login</a>
                <a href="{{ route('register') }}" class="block text-center px-6 py-2 bg-gradient-to-r from-orange-600 to-orange-500 text-white rounded-lg">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg relative min-h-screen flex items-center justify-center overflow-hidden pt-16">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-72 h-72 bg-orange-500/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-float delay-300"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Text -->
                <div class="text-white space-y-8">
                    <h1 class="text-5xl md:text-7xl font-bold leading-tight opacity-0 animate-fadeInUp">
                        Transform Your 
                        <span class="block bg-gradient-to-r from-orange-400 to-orange-600 bg-clip-text text-transparent">
                            Trading Journey
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-gray-300 opacity-0 animate-fadeInUp delay-200">
                        Master your trades with intelligent analytics, automated tracking, and insightful journaling. 
                        <span class="text-orange-400 font-semibold">Elevate your trading game today.</span>
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 opacity-0 animate-fadeInUp delay-300">
                        <a href="{{ route('register') }}" class="group relative px-8 py-4 bg-gradient-to-r from-orange-600 to-orange-500 text-white rounded-lg font-semibold text-lg shadow-2xl hover:shadow-orange-500/50 hover-glow overflow-hidden">
                            <span class="relative z-10">Start Journaling</span>
                            <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100"></div>
                        </a>
                        <a href="#features" class="px-8 py-4 glass-morphism text-white rounded-lg font-semibold text-lg hover:bg-white/10 transition-all duration-200 border border-white/20">
                            Learn More
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 pt-8 opacity-0 animate-fadeInUp delay-400">
                        <div class="text-center">
                            <div class="text-4xl font-bold stat-number">10K+</div>
                            <div class="text-gray-400 text-sm mt-1">Active Traders</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold stat-number">1M+</div>
                            <div class="text-gray-400 text-sm mt-1">Trades Tracked</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold stat-number">95%</div>
                            <div class="text-gray-400 text-sm mt-1">Satisfaction</div>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Image/Illustration -->
                <div class="relative opacity-0 animate-fadeInUp delay-500">
                    <div class="glass-morphism rounded-2xl p-8 shadow-2xl">
                        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg p-6 space-y-4">
                            <!-- Mock Dashboard Preview -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                </div>
                                <div class="text-xs text-gray-400">Dashboard</div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="h-24 bg-gradient-to-r from-orange-500/20 to-orange-600/20 rounded-lg border border-orange-500/30 p-4">
                                    <div class="text-orange-400 text-sm font-semibold">Total P&L</div>
                                    <div class="text-2xl font-bold text-white mt-1">$45,290.50</div>
                                    <div class="text-green-400 text-xs mt-1">↑ 23.5%</div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="h-20 bg-gray-800/50 rounded-lg border border-gray-700 p-3">
                                        <div class="text-gray-400 text-xs">Win Rate</div>
                                        <div class="text-lg font-bold text-white mt-1">68.5%</div>
                                    </div>
                                    <div class="h-20 bg-gray-800/50 rounded-lg border border-gray-700 p-3">
                                        <div class="text-gray-400 text-xs">Trades</div>
                                        <div class="text-lg font-bold text-white mt-1">1,247</div>
                                    </div>
                                </div>
                                
                                <div class="h-32 bg-gray-800/50 rounded-lg border border-gray-700 p-4">
                                    <div class="flex justify-between items-end h-full">
                                        <div class="w-2 bg-orange-500 rounded-t" style="height: 40%"></div>
                                        <div class="w-2 bg-orange-500 rounded-t" style="height: 65%"></div>
                                        <div class="w-2 bg-orange-500 rounded-t" style="height: 45%"></div>
                                        <div class="w-2 bg-orange-500 rounded-t" style="height: 80%"></div>
                                        <div class="w-2 bg-orange-500 rounded-t" style="height: 55%"></div>
                                        <div class="w-2 bg-orange-500 rounded-t" style="height: 90%"></div>
                                        <div class="w-2 bg-orange-500 rounded-t" style="height: 70%"></div>
                                        <div class="w-2 bg-orange-500 rounded-t" style="height: 60%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Everything You Need to 
                    <span class="bg-gradient-to-r from-orange-600 to-orange-500 bg-clip-text text-transparent">Succeed</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Powerful features designed to help you analyze, track, and improve your trading performance
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Advanced Analytics</h3>
                    <p class="text-gray-600">
                        Deep insights into your trading patterns with comprehensive P&L analysis, win rates, and performance metrics.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Auto Import</h3>
                    <p class="text-gray-600">
                        Seamlessly import trades from your broker with Interactive Brokers Flex Query integration.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Trading Journal</h3>
                    <p class="text-gray-600">
                        Document your trading journey with detailed entries, screenshots, and reflections to improve over time.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Tagging</h3>
                    <p class="text-gray-600">
                        Organize trades with custom tags and categories to identify patterns and strategies that work.
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Real-time Tracking</h3>
                    <p class="text-gray-600">
                        Monitor your positions and P&L in real-time with FIFO position management and accurate calculations.
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Secure & Private</h3>
                    <p class="text-gray-600">
                        Your trading data is encrypted and secure. We never share your information with third parties.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Simple <span class="bg-gradient-to-r from-orange-600 to-orange-500 bg-clip-text text-transparent">Three-Step</span> Process
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Get started in minutes and transform your trading journey
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="relative inline-block mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center shadow-xl">
                            <span class="text-4xl font-bold text-white">1</span>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-orange-400 rounded-full animate-ping opacity-75"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Sign Up</h3>
                    <p class="text-gray-600">
                        Create your free account in seconds. No credit card required to get started.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="relative inline-block mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-xl">
                            <span class="text-4xl font-bold text-white">2</span>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-400 rounded-full animate-ping opacity-75"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Import Trades</h3>
                    <p class="text-gray-600">
                        Connect your broker or manually add trades. CSV import and auto-sync supported.
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="relative inline-block mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-xl">
                            <span class="text-4xl font-bold text-white">3</span>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-400 rounded-full animate-ping opacity-75"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Analyze & Improve</h3>
                    <p class="text-gray-600">
                        Get insights, track performance, and make data-driven decisions to become a better trader.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Loved by <span class="bg-gradient-to-r from-orange-600 to-orange-500 bg-clip-text text-transparent">Traders</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    See what our community has to say about their experience
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="flex text-orange-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">
                        "Tradalyze completely transformed how I track my trades. The analytics are incredibly detailed and the auto-import saves me hours every week!"
                    </p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            JS
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">John Smith</div>
                            <div class="text-sm text-gray-500">Day Trader</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="flex text-orange-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">
                        "The trading journal feature is a game-changer. Being able to review my trades with notes and screenshots has improved my discipline significantly."
                    </p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            SK
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Sarah Kim</div>
                            <div class="text-sm text-gray-500">Swing Trader</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="flex text-orange-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">
                        "As a professional trader, I need accurate P&L tracking. Tradalyze's FIFO position management is spot-on and the UI is beautiful!"
                    </p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            MR
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">Michael Rodriguez</div>
                            <div class="text-sm text-gray-500">Options Trader</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg py-20 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-96 h-96 bg-orange-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Level Up Your Trading?
            </h2>
            <p class="text-xl text-gray-300 mb-8">
                Join thousands of traders who are already improving their performance with Tradalyze
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group relative px-8 py-4 bg-white text-gray-900 rounded-lg font-semibold text-lg shadow-2xl hover:shadow-white/50 hover-glow overflow-hidden">
                    <span class="relative z-10">Get Started</span>
                    <div class="absolute inset-0 shimmer opacity-0 group-hover:opacity-100"></div>
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 glass-morphism text-white rounded-lg font-semibold text-lg hover:bg-white/10 transition-all duration-200 border border-white/20">
                    Sign In
                </a>
            </div>
            <p class="text-sm text-gray-400 mt-6">No credit card required • Self Host • Be in control of your data</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="text-2xl font-bold text-white">Tradalyze</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Transform your trading journey with intelligent analytics, automated tracking, and insightful journaling.
                    </p>
                    <div class="flex space-x-4">
                        <!-- <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-orange-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a> -->
                        <!-- <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-orange-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a> -->
                        <a href="https://github.com/heavyguidence/tradalyze" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-orange-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- <div>
                    <h4 class="text-white font-semibold mb-4">Product</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="hover:text-orange-500 transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">API</a></li>
                    </ul>
                </div> -->
                
                <!-- <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-orange-500 transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Privacy</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition-colors">Terms</a></li>
                    </ul>
                </div>
            </div> -->
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-sm">
                <p>&copy; 2026 Tradalyze. All rights reserved. Built with ❤️ for traders.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    document.getElementById('mobile-menu').classList.add('hidden');
                }
            });
        });
        
        // Add scroll reveal animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        // Observe feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease-out';
            observer.observe(card);
        });
    </script>
</body>
</html>
