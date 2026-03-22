<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin - HealthMatrix')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-indigo-600 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden transition-opacity"></div>

        <div class="flex">
            <!-- Modern Sidebar -->
            <aside id="sidebar" class="fixed lg:sticky top-0 left-0 z-40 w-64 h-screen bg-gradient-to-b from-indigo-900 via-indigo-800 to-indigo-900 shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center mb-8">
                        <div class="bg-white rounded-lg p-2 mr-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Super Admin</h2>
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('super-admin.dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('super-admin.dashboard') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                        <a href="{{ route('super-admin.labs.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('super-admin.labs.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-medium">Labs</span>
                        </a>
                    </nav>
                </div>
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-indigo-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-white rounded-xl hover:bg-white hover:bg-opacity-10 transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col lg:ml-0">
                <!-- Page Heading -->
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="px-4 sm:px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">@yield('page-title', 'Super Admin')</h1>
                                <p class="text-sm text-gray-500 mt-1">@yield('page-subtitle', '')</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center px-4 py-2 bg-indigo-100 rounded-lg">
                                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="font-medium text-indigo-900">{{ Auth::user()->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6 bg-gray-50">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
        
        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });

            // Close sidebar when clicking overlay
            document.getElementById('sidebar-overlay')?.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        </script>
    </div>
</body>
</html>
