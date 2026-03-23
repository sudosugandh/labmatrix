<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $tenantName = currentLab()?->name ?? config('app.name', 'HealthMatrix');
        @endphp
        <title>{{ $tenantName }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700|sora:600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --auth-accent: #7c3aed;
                --auth-accent-dark: #6d28d9;
                --auth-accent-ring: rgb(124 58 237 / 0.25);
            }
            .font-display { font-family: 'Sora', ui-sans-serif, system-ui, sans-serif; }
            .auth-body { font-family: 'Outfit', ui-sans-serif, system-ui, sans-serif; }
            /* Premium inputs for all auth forms in the card */
            #auth-panel input[type="email"],
            #auth-panel input[type="password"],
            #auth-panel input[type="text"] {
                border-radius: 0.75rem !important;
                border: 1px solid rgb(226 232 240) !important;
                padding: 0.625rem 0.875rem !important;
                transition: border-color 0.15s, box-shadow 0.15s !important;
            }
            #auth-panel input[type="email"]:focus,
            #auth-panel input[type="password"]:focus,
            #auth-panel input[type="text"]:focus {
                border-color: var(--auth-accent) !important;
                box-shadow: 0 0 0 3px var(--auth-accent-ring) !important;
                outline: none !important;
            }
        </style>
        @stack('styles')
    </head>
    <body class="auth-body min-h-screen antialiased text-slate-900">
        <div class="min-h-screen lg:grid lg:grid-cols-2">
            {{-- Brand panel (desktop) --}}
            <aside class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-gradient-to-br from-slate-800 via-slate-900 to-violet-950 px-12 py-14 xl:px-16">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.04\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-80"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-violet-600/20 blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <a href="/" class="inline-flex items-center gap-3 text-white/90 hover:text-white transition-colors group">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20 shadow-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517L6 18.089V5a2 2 0 012-2h6a2 2 0 012 2v10z"/>
                            </svg>
                        </span>
                        <span class="font-display text-xl font-bold tracking-tight">{{ $tenantName }}</span>
                    </a>
                </div>

                <div class="relative z-10 max-w-md">
                    <p class="text-sm font-semibold tracking-widest uppercase text-violet-300/90 mb-4">Admin portal</p>
                    <h1 class="font-display text-4xl xl:text-5xl font-bold text-white leading-tight tracking-tight mb-5">
                        Diagnostics, simplified.
                    </h1>
                    <p class="text-lg text-slate-300 leading-relaxed">
                        Secure access to your lab dashboard, orders, reports, and patient workflows.
                    </p>
                    <ul class="mt-10 space-y-4 text-slate-300 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-violet-500/30 text-violet-200">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </span>
                            Role-based access &amp; audit-friendly sessions
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-violet-500/30 text-violet-200">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </span>
                            Encrypted sign-in with optional “remember me”
                        </li>
                    </ul>
                </div>

                <p class="relative z-10 text-xs text-slate-500">
                    © {{ date('Y') }} {{ $tenantName }}. All rights reserved.
                </p>
            </aside>

            {{-- Form column --}}
            <main class="relative flex min-h-screen flex-col justify-center bg-gradient-to-b from-slate-100 to-white px-4 py-10 sm:px-8 lg:px-12 xl:px-16">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-10%,rgba(124,58,237,0.06),transparent)] pointer-events-none lg:hidden"></div>

                {{-- Mobile brand strip --}}
                <div class="mb-8 flex items-center justify-center gap-3 lg:hidden relative z-10">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-600 shadow-lg shadow-violet-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517L6 18.089V5a2 2 0 012-2h6a2 2 0 012 2v10z"/></svg>
                    </span>
                    <span class="font-display text-lg font-bold text-slate-900">{{ $tenantName }}</span>
                </div>

                <div class="relative z-10 mx-auto w-full max-w-md">
                    <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-8 shadow-2xl shadow-slate-300/40 backdrop-blur-sm sm:p-10">
                        {{ $slot }}
                    </div>
                    <p class="mt-8 text-center text-xs text-slate-500">
                        Protected area. Only authorized staff should sign in.
                    </p>
                </div>
            </main>
        </div>
    </body>
</html>
