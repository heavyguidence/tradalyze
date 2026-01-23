<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Tradalyze') }} - @yield('title')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-gray-900 text-white fixed h-screen overflow-y-auto flex flex-col transition-all duration-300 z-40 md:w-64" style="width: 16rem;">
            <!-- Sidebar Header -->
            <div class="px-6 py-8 border-b border-gray-800">
                <h1 id="sidebar-title" class="text-2xl font-bold text-white">Tradalyze</h1>
                <p id="sidebar-user" class="text-xs text-gray-400 mt-1">{{ auth()->user()->name }}</p>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="px-3 py-6 flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-orange-600 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                           title="Dashboard">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="sidebar-text ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('trades') }}" 
                           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('trades') ? 'bg-orange-600 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                           title="Trades">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="sidebar-text ml-3">Trades</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('diary') }}" 
                           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('diary') ? 'bg-orange-600 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                           title="Diary">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="sidebar-text ml-3">Diary</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings') }}" 
                           class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('settings') ? 'bg-orange-600 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                           title="Settings">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="sidebar-text ml-3">Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- Logout Button -->
            <div class="px-3 py-4 border-t border-gray-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link flex items-center w-full px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-200" title="Logout">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="sidebar-text ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Toggle Button -->
        <button 
            id="sidebar-toggle" 
            class="fixed top-2 z-50 text-white p-2 rounded-r-md shadow-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500"
            style="left: 16rem; background-color: #1f2937;"
            onmouseover="this.style.backgroundColor='#374151'" 
            onmouseout="this.style.backgroundColor='#1f2937'"
            onclick="toggleSidebar()"
            aria-label="Toggle Sidebar"
        >
            <svg id="toggle-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
        </button>
        
        <!-- Main Content -->
        <main id="main-content" class="flex-1 p-4 md:p-6 lg:p-8 transition-all duration-300 ml-20 md:ml-64 overflow-x-hidden" style="margin-left: 16rem;">
            <div class="w-full max-w-full overflow-x-hidden">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const toggleIcon = document.getElementById('toggle-icon');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const sidebarTitle = document.getElementById('sidebar-title');
            const sidebarUser = document.getElementById('sidebar-user');
            
            // Check if sidebar is currently expanded (width is 16rem or 256px)
            const currentWidth = sidebar.offsetWidth;
            const isExpanded = currentWidth > 100; // If width > 100px, it's expanded
            
            if (isExpanded) {
                // Collapse sidebar
                sidebar.style.width = '5rem';
                mainContent.style.marginLeft = '5rem';
                toggleBtn.style.left = '5rem';
                
                // Hide text elements
                sidebarTexts.forEach(text => text.classList.add('hidden'));
                sidebarTitle.classList.add('hidden');
                sidebarUser.classList.add('hidden');
                
                // Change icon to expand
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>';
                
                // Store state
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                // Expand sidebar
                sidebar.style.width = '16rem';
                mainContent.style.marginLeft = '16rem';
                toggleBtn.style.left = '16rem';
                
                // Show text elements
                sidebarTexts.forEach(text => text.classList.remove('hidden'));
                sidebarTitle.classList.remove('hidden');
                sidebarUser.classList.remove('hidden');
                
                // Change icon to collapse
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>';
                
                // Store state
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        }
        
        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('main-content');
                const toggleBtn = document.getElementById('sidebar-toggle');
                const toggleIcon = document.getElementById('toggle-icon');
                const sidebarTexts = document.querySelectorAll('.sidebar-text');
                const sidebarTitle = document.getElementById('sidebar-title');
                const sidebarUser = document.getElementById('sidebar-user');
                
                // Set collapsed state
                sidebar.style.width = '5rem';
                mainContent.style.marginLeft = '5rem';
                toggleBtn.style.left = '5rem';
                
                // Hide text elements
                sidebarTexts.forEach(text => text.classList.add('hidden'));
                sidebarTitle.classList.add('hidden');
                sidebarUser.classList.add('hidden');
                
                // Change icon to expand
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>';
            }
        });
    </script>
</body>
</html>
