<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin - HealthMatrix')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sa-shell { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .sa-display { font-family: 'DM Sans', 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .sa-sidebar { background: linear-gradient(180deg, #0a0f1a 0%, #0c1222 50%, #0a0f1a 100%); }
        .sa-nav-active { background: rgb(124 58 237 / 0.95); box-shadow: 0 1px 0 rgb(255 255 255 / 0.06) inset; }
        /* Icon-only controls: use with title="" + aria-label for accessibility */
        .sa-icon-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 2.5rem; height: 2.5rem; flex-shrink: 0; border-radius: 0.75rem;
            transition: background-color .15s ease, color .15s ease, border-color .15s ease, box-shadow .15s ease;
        }
        .sa-icon-btn:focus-visible { outline: 2px solid rgb(139 92 246); outline-offset: 2px; }
        .sa-icon-btn > svg { width: 1.25rem; height: 1.25rem; }
        .sa-icon-btn-lg { width: 3rem; height: 3rem; border-radius: 0.75rem; }
        .sa-icon-btn-lg > svg { width: 1.5rem; height: 1.5rem; }
    </style>
</head>
<body class="sa-shell antialiased bg-slate-100 text-slate-900">
    <div class="min-h-screen flex">
        <button type="button" id="mobile-menu-button" class="lg:hidden fixed top-4 left-4 z-[60] flex h-11 w-11 items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg ring-1 ring-white/10 hover:bg-slate-800 transition-colors" aria-label="Open menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div id="sidebar-overlay" class="hidden fixed inset-0 z-[45] bg-slate-950/60 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

        <aside id="sidebar" class="sa-sidebar fixed lg:sticky top-0 z-50 flex h-screen w-[260px] shrink-0 flex-col border-r border-slate-800/80 shadow-2xl shadow-black/20 transition-transform duration-300 ease-out -translate-x-full lg:translate-x-0">
            <div class="flex flex-col h-full min-h-0">
                <div class="p-6 pb-5 border-b border-slate-800/80">
                    <a href="{{ route('super-admin.dashboard') }}" class="flex items-start gap-3 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-violet-500 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-violet-500 to-violet-700 shadow-lg shadow-violet-900/40 ring-1 ring-white/10" aria-hidden="true">
                            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.2 6.8H21l-5.5 4 2.1 6.7L12 15.9 6.4 19.5l2.1-6.7L3 8.8h6.8L12 2z"/></svg>
                        </span>
                        <span class="min-w-0 pt-0.5">
                            <span class="sa-display block text-base font-bold tracking-tight text-white leading-tight">Super Admin</span>
                            <span class="mt-0.5 block text-xs font-medium text-slate-500">Platform control</span>
                        </span>
                    </a>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5" aria-label="Super admin">
                    <a href="{{ route('super-admin.dashboard') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.dashboard') ? 'sa-nav-active text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('super-admin.activity.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.activity.*') ? 'sa-nav-active text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Activity
                    </a>
                    <a href="{{ route('super-admin.domains.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.domains.*') ? 'sa-nav-active text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Domains
                    </a>
                    <a href="{{ route('super-admin.users.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.users.*') ? 'sa-nav-active text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Users
                    </a>
                    <a href="{{ route('super-admin.labs.index') }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('super-admin.labs.*') ? 'sa-nav-active text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Labs
                    </a>
                </nav>

                <div class="mt-auto border-t border-slate-800/80 p-3">
                    <form method="POST" action="{{ route('logout') }}" class="flex w-full justify-center px-3">
                        @csrf
                        <button type="submit" class="sa-icon-btn text-slate-400 hover:bg-slate-800/60 hover:text-white" title="Logout" aria-label="Logout">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col lg:ml-0">
            <header class="sticky top-0 z-30 border-b border-slate-200/90 bg-white/90 backdrop-blur-md">
                <div class="px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0 pt-10 lg:pt-0">
                            <h1 class="sa-display text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">@yield('page-title', 'Super Admin')</h1>
                            <p class="mt-1 text-sm text-slate-500">@yield('page-subtitle', '')</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-700">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-violet-100 text-violet-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </span>
                                <span class="max-w-[10rem] truncate">{{ Auth::user()->name }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                @if(session('success'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200/80 bg-emerald-50/90 px-4 py-3 text-sm text-emerald-900 shadow-sm" role="alert">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200/80 bg-red-50/90 px-4 py-3 text-sm text-red-900 shadow-sm" role="alert">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function () {
            document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay')?.classList.toggle('hidden');
        });
        document.getElementById('sidebar-overlay')?.addEventListener('click', function () {
            document.getElementById('sidebar')?.classList.add('-translate-x-full');
            document.getElementById('sidebar-overlay')?.classList.add('hidden');
        });
    </script>
</body>
</html>
