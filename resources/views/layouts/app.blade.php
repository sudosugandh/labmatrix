<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @php
            $adminBranding = (function_exists('currentLabId') && currentLabId())
                ? \App\Models\WebsiteSetting::getForCurrentLab()
                : null;
            $adminPrimary = $adminBranding?->primary_color ?? '#4f46e5';
            $adminSecondary = $adminBranding?->secondary_color ?? '#6366f1';
        @endphp
        <style>
            :root {
                --admin-primary: {{ $adminPrimary }};
                --admin-secondary: {{ $adminSecondary }};
            }
            .admin-sidebar-panel {
                background: linear-gradient(180deg, color-mix(in srgb, var(--admin-primary) 78%, #0f172a) 0%, color-mix(in srgb, var(--admin-primary) 55%, #020617) 100%);
            }
            .admin-mobile-menu-btn {
                background-color: var(--admin-primary);
            }
            .admin-mobile-menu-btn:hover {
                filter: brightness(0.93);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" type="button" class="admin-mobile-menu-btn lg:hidden fixed top-4 left-4 z-50 p-2 text-white rounded-lg shadow-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Sidebar Overlay (Mobile) -->
            <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden transition-opacity"></div>

            <div class="flex min-h-0 w-full">
                <!-- Modern Sidebar -->
                <aside id="sidebar" class="admin-sidebar-panel fixed lg:sticky top-0 left-0 z-40 flex-shrink-0 w-64 h-screen shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto overscroll-contain">
                    <div class="p-6 pt-6">
                        {{-- Drawer: no logo, text only --}}
                        <p class="text-white font-semibold text-sm px-4 mb-4">{{ currentLab()?->name ?? config('app.name') }}</p>
                        <nav class="space-y-1">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="font-medium">Dashboard</span>
                            </a>
                            <a href="{{ route('patients.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('patients.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="font-medium">Patients</span>
                            </a>
                            <a href="{{ route('tests.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('tests.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="font-medium">Tests</span>
                            </a>
                            <a href="{{ route('doctors.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('doctors.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium">Doctors</span>
                            </a>
                            <a href="{{ route('corporate-accounts.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('corporate-accounts.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="font-medium">Corporate accounts</span>
                            </a>
                            <a href="{{ route('health-camps.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('health-camps.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="font-medium">Health camps</span>
                            </a>
                            <a href="{{ route('test-packages.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('test-packages.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="font-medium">Test Packages</span>
                            </a>
                            @if(isLargeLab())
                            <a href="{{ route('technicians.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('technicians.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">Technicians</span>
                            </a>
                            @endif
                            <a href="{{ route('appointments.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('appointments.*') && !request()->routeIs('appointment-slots.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">Appointments</span>
                            </a>
                            <a href="{{ route('appointment-slots.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('appointment-slots.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">Booking slots</span>
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('orders.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-medium">Test Orders</span>
                            </a>
                            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-medium">Reports</span>
                            </a>
                            <a href="{{ route('report-templates.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('report-templates.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                </svg>
                                <span class="font-medium">Report templates</span>
                            </a>
                            <a href="{{ route('report-builder.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('report-builder.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-medium">Report Builder</span>
                            </a>
                            <a href="{{ route('bills.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('bills.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                                </svg>
                                <span class="font-medium">Bills & Invoices</span>
                            </a>
                            <a href="{{ route('analytics.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('analytics.*') ? 'bg-white bg-opacity-20 shadow-lg transform scale-105' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="font-medium">Analytics</span>
                            </a>
                            <div class="border-t border-white/10 my-4 pt-4"></div>

                            <p class="text-xs text-white/60 uppercase tracking-wider px-4 mb-2">Inventory</p>
                            <a href="{{ route('inventory.dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('inventory.dashboard') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="font-medium">Inventory Dashboard</span>
                            </a>
                            <a href="{{ route('inventory.items.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('inventory.items.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="font-medium">Inventory Items</span>
                            </a>
                            <a href="{{ route('inventory.adjustments.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('inventory.adjustments.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                <span class="font-medium">Stock Adjustments</span>
                            </a>
                            <a href="{{ route('inventory.reports.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('inventory.reports.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="font-medium">Reports & Analytics</span>
                            </a>

                            <div class="border-t border-white/10 my-4 pt-4"></div>

                            <p class="text-xs text-white/60 uppercase tracking-wider px-4 mb-2">Admin & Settings</p>
                            <a href="{{ route('admin.settings.index', ['tab' => 'branding']) }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.website-settings.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium">Settings</span>
                            </a>
                                <a href="{{ route('admin.pages.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('admin.pages.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-medium">Pages</span>
                                </a>
                                <a href="{{ route('admin.menus.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('admin.menus.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                    <span class="font-medium">Menus</span>
                                </a>
                                <a href="{{ route('testimonials.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('testimonials.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    <span class="font-medium">Testimonials</span>
                                </a>
                                <a href="{{ route('faqs.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('faqs.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">FAQ</span>
                                </a>
                                <a href="{{ route('promos.index') }}" class="flex items-center px-4 py-3 text-white rounded-xl transition-all duration-200 {{ request()->routeIs('promos.*') ? 'bg-white bg-opacity-20 shadow-lg' : 'hover:bg-white hover:bg-opacity-10' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7a2 2 0 010-2.828l7-7A1.994 1.994 0 0112 3h5"></path>
                                    </svg>
                                    <span class="font-medium">Offers</span>
                                </a>
                        </nav>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="flex-1 flex flex-col min-w-0 lg:ml-0 overflow-x-hidden">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white shadow-sm border-b border-gray-200">
                            <div class="px-4 sm:px-6 py-4">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="flex-1 p-4 sm:p-6 bg-gray-50">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        @stack('scripts')

        <x-flash-toasts />
        
        <script>
            // Enable console logging for debugging
            console.log('HealthMatrix App Loaded');
            console.log('Current Route:', '{{ request()->route()->getName() ?? "N/A" }}');
            console.log('Current URL:', window.location.href);
            
            // Log all errors to console
            window.addEventListener('error', function(e) {
                console.error('JavaScript Error:', e.message, e.filename, e.lineno);
            });
            
            // Log unhandled promise rejections
            window.addEventListener('unhandledrejection', function(e) {
                console.error('Unhandled Promise Rejection:', e.reason);
            });
        </script>
    </body>
</html>
