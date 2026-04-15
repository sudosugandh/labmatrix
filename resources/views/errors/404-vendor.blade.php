@php
    $lab = $lab ?? null;
    $settings = $settings ?? null;
    if ($settings === null && $lab) {
        $settings = new \App\Models\WebsiteSetting([
            'brand_name' => $lab->name,
            'primary_color' => '#6366f1',
            'theme_preset' => 'violet',
        ]);
    }
    if ($settings === null) {
        $settings = new \App\Models\WebsiteSetting([
            'brand_name' => config('app.name'),
            'primary_color' => '#6366f1',
            'theme_preset' => 'violet',
        ]);
    }
    $brandName = $settings->brand_name ?? $lab?->name ?? config('app.name');
    $primary = $settings->primary_color ?? '#6366f1';
    $presetKey = $settings->theme_preset ?? 'violet';
    $theme = \App\Support\WebsiteTheme::preset(in_array($presetKey, \App\Support\WebsiteTheme::PRESET_KEYS, true) ? $presetKey : 'violet');
    $secondary = $settings?->secondary_color ?: $theme['secondary_default'];
    $logoUrl = $settings->logo ? \Illuminate\Support\Facades\Storage::url($settings->logo) : null;
    $heroImageUrl = $settings->hero_image ? \Illuminate\Support\Facades\Storage::url($settings->hero_image) : null;

    $hasSocial = ! empty($settings->facebook_url) || ! empty($settings->twitter_url) || ! empty($settings->instagram_url)
        || ! empty($settings->linkedin_url) || ! empty($settings->youtube_url);

    $menuItems = collect();
    if ($lab) {
        $mainMenu = \App\Models\Menu::where('lab_id', $lab->id)->where('name', 'Main Menu')->first();
        $menuItems = $mainMenu ? $mainMenu->items : collect();
    }
    $currentPath = '/' . trim(request()->path(), '/');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Page not found') }} — {{ $brandName }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="{{ $theme['fonts_href'] }}" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: {{ $primary }};
            --secondary-color: {{ $secondary }};
            --bg-start: {{ $theme['page_bg_start'] }};
            --bg-end: {{ $theme['page_bg_end'] }};
            --font-display: {{ $theme['font_display'] }};
            --font-body: {{ $theme['font_body'] }};
        }
        body { font-family: var(--font-body); }
        .font-display { font-family: var(--font-display); }
        [x-cloak] { display: none !important; }
        @media (max-width: 767px) {
            html:has(.site-nav-links.open) {
                overflow: hidden;
            }
        }
        .site-nav-links a:focus-visible,
        .nav-cta:focus-visible,
        .nav-cta-secondary:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
        .site-nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        @media (min-width: 768px) {
            .site-nav-links { gap: 2rem; }
        }
        .site-nav-links a {
            display: inline-block;
            padding: 0.5rem 0.875rem;
            font-size: 0.9375rem;
            font-weight: 500;
            color: #334155;
            text-decoration: none;
            white-space: nowrap;
            border-radius: 0.5rem;
            transition: color 0.2s, background 0.2s, box-shadow 0.2s;
        }
        .site-nav-links a:hover {
            color: #0f172a;
            background-color: #f8fafc;
        }
        .site-nav-links a.active {
            color: var(--primary-color);
            font-weight: 600;
        }
        .site-nav-links a.active:hover {
            background-color: color-mix(in srgb, var(--primary-color) 10%, transparent);
        }
        .site-nav-links a.nav-cta {
            background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 85%, #1e1b4b) 100%);
            color: white !important;
            padding: 0.5rem 1.25rem;
            border-radius: 0.625rem;
            font-weight: 600;
            box-shadow: 0 2px 8px color-mix(in srgb, var(--primary-color) 35%, transparent);
        }
        .site-nav-links a.nav-cta:hover {
            color: white !important;
            box-shadow: 0 4px 14px color-mix(in srgb, var(--primary-color) 45%, transparent);
            transform: translateY(-1px);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .site-nav-links a.nav-cta-secondary {
            border: 2px solid #0f172a;
            color: #0f172a !important;
            padding: 0.4rem 1.125rem;
            border-radius: 0.625rem;
            font-weight: 600;
            background-color: #fff;
        }
        .site-nav-links a.nav-cta-secondary:hover {
            color: #0f172a !important;
            background-color: #f8fafc;
        }
        .site-nav-links a.nav-cta-secondary.active {
            color: #0f172a !important;
            background-color: #f1f5f9;
        }
        .site-nav-links a.nav-cta-secondary.active:hover {
            background-color: #e2e8f0;
        }
        @media (max-width: 767px) {
            .site-nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                flex-direction: column;
                gap: 0;
                padding: 1rem;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
                border-top: 1px solid #e5e7eb;
            }
            .site-nav-links.open {
                display: flex;
            }
            .site-nav-links a {
                width: 100%;
                padding: 0.75rem 1rem;
                border-radius: 0.375rem;
            }
            .site-nav-links a.nav-cta,
            .site-nav-links a.nav-cta-secondary {
                text-align: center;
                justify-content: center;
            }
            .nav-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 2.5rem;
                height: 2.5rem;
                padding: 0;
                border: 1px solid #e5e7eb;
                border-radius: 0.375rem;
                background: #fff;
                color: #374151;
            }
            .nav-toggle:hover {
                background: #f9fafb;
            }
            .nav-toggle:focus-visible {
                outline: 2px solid var(--primary-color);
                outline-offset: 2px;
            }
        }
        @media (min-width: 768px) {
            .nav-toggle { display: none; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased text-slate-800 bg-white" x-data="{ navOpen: false }">
    <nav class="sticky top-0 z-50 border-b border-slate-200/90 bg-white/95 shadow-[0_1px_0_0_rgba(0,0,0,0.05)] backdrop-blur-md" aria-label="{{ __('Main navigation') }}">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="relative flex min-h-[4rem] items-center justify-between py-2 md:h-16 md:min-h-0 md:py-0">
                <div class="flex min-w-0 shrink-0 items-center pr-2">
                    <a href="{{ route('home') }}" class="flex items-center rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--primary-color)] focus-visible:ring-offset-2">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $brandName }} — {{ __('Home') }}" class="h-10 w-auto max-w-[min(100vw-8rem,20rem)] object-contain md:h-12" width="160" height="48" decoding="async">
                        @else
                            <span class="font-display max-w-[min(100vw-8rem,20rem)] truncate text-lg font-bold tracking-tight text-slate-900 md:text-xl" style="color: var(--primary-color);">{{ $brandName }}</span>
                        @endif
                    </a>
                </div>
                <button type="button" class="nav-toggle min-h-[44px] min-w-[44px] shrink-0" @click="navOpen = !navOpen" :aria-expanded="navOpen ? 'true' : 'false'" aria-controls="primary-navigation" :aria-label="navOpen ? {{ json_encode(__('Close menu')) }} : {{ json_encode(__('Open menu')) }}">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!navOpen" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="navOpen" x-cloak aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div id="primary-navigation" class="site-nav-links" :class="{ 'open': navOpen }" @click="navOpen = false">
                    @if($menuItems->isNotEmpty())
                        @foreach($menuItems as $item)
                            @php
                                $itemPath = '/' . trim(parse_url($item->url, PHP_URL_PATH) ?? $item->url, '/');
                                $itemUrl = $item->url ?? '';
                                $isActive = $currentPath === $itemPath || ($itemPath === '/' && $currentPath === '');
                                $isBookCta = str_contains($itemUrl, 'book-appointment') || (str_contains($itemUrl, 'appointments') && ! str_contains($itemUrl, 'my-appointments')) || str_contains(strtolower($item->title ?? ''), 'book');
                                $isReportsCta = str_contains($itemUrl, 'patient-reports') || (str_contains($itemUrl, 'reports') && ! str_contains($itemUrl, 'appointments')) || str_contains(strtolower($item->title ?? ''), 'report');
                            @endphp
                            <a href="{{ $item->url }}" class="{{ $isActive ? 'active' : '' }} {{ $isBookCta ? 'nav-cta' : '' }} {{ $isReportsCta && ! $isBookCta ? 'nav-cta-secondary' : '' }}">{{ $item->title }}</a>
                        @endforeach
                    @else
                        <a href="{{ route('home') }}" class="active">{{ __('Home') }}</a>
                        <a href="{{ url('/about-us') }}">{{ __('About Us') }}</a>
                        <a href="{{ url('/contact') }}">{{ __('Contact') }}</a>
                        @if(Route::has('public.packages.index'))
                            <a href="{{ route('public.packages.index') }}">{{ __('Packages') }}</a>
                        @endif
                        @if(Route::has('public.appointments.create') && ($settings->online_booking_enabled ?? true))
                            <a href="{{ route('public.appointments.create') }}" class="nav-cta">{{ __('Book Appointment') }}</a>
                        @endif
                        @if(Route::has('public.reports.search') && ($settings->online_reports_enabled ?? true))
                            <a href="{{ route('public.reports.search') }}" class="nav-cta-secondary">{{ __('Reports') }}</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero: illustration + message (split layout like reference) --}}
    <main class="flex flex-1 flex-col justify-center">
        <div class="mx-auto grid w-full max-w-6xl flex-1 grid-cols-1 items-center gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:gap-12 lg:py-16 lg:px-8">
            <div class="order-2 flex justify-center lg:order-1 lg:justify-start" aria-hidden="true">
                @if($heroImageUrl)
                    <div class="relative w-full max-w-md">
                        <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-[color:var(--primary-color)]/15 via-transparent to-[color:var(--secondary-color)]/20 blur-2xl"></div>
                        <img src="{{ $heroImageUrl }}" alt="" class="relative mx-auto max-h-[min(52vh,420px)] w-auto object-contain drop-shadow-xl" loading="eager">
                    </div>
                @else
                    {{-- Friendly lab illustration (generic SVG, brand-tinted) --}}
                    <div class="relative w-full max-w-sm">
                        <div class="absolute -inset-6 rounded-full bg-gradient-to-br from-[color:var(--primary-color)]/20 to-[color:var(--secondary-color)]/15 blur-3xl"></div>
                        <svg viewBox="0 0 400 360" class="relative h-auto w-full text-[color:var(--primary-color)]" role="img" aria-label="">
                            <defs>
                                <linearGradient id="flask404" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:currentColor;stop-opacity:0.95" />
                                    <stop offset="100%" style="stop-color:currentColor;stop-opacity:0.45" />
                                </linearGradient>
                            </defs>
                            {{-- Flask body --}}
                            <ellipse cx="200" cy="300" rx="95" ry="28" fill="currentColor" opacity="0.12"/>
                            <path d="M130 120 L130 240 Q130 280 200 295 Q270 280 270 240 L270 120 L250 100 L250 70 Q250 50 230 45 L200 40 L170 45 Q150 50 150 70 L150 100 Z" fill="url(#flask404)" opacity="0.85"/>
                            <path d="M155 115 L155 235 Q155 265 200 278 Q245 265 245 235 L245 115" fill="none" stroke="white" stroke-width="3" opacity="0.35"/>
                            {{-- Bubbles --}}
                            <circle cx="175" cy="200" r="12" fill="white" opacity="0.5"/>
                            <circle cx="220" cy="175" r="8" fill="white" opacity="0.4"/>
                            <circle cx="195" cy="150" r="6" fill="white" opacity="0.35"/>
                            {{-- Face (surprised friendly) --}}
                            <ellipse cx="185" cy="118" rx="10" ry="12" fill="#0f172a"/>
                            <ellipse cx="215" cy="118" rx="10" ry="12" fill="#0f172a"/>
                            <ellipse cx="186" cy="116" rx="3" ry="4" fill="white"/>
                            <ellipse cx="216" cy="116" rx="3" ry="4" fill="white"/>
                            <ellipse cx="200" cy="142" rx="18" ry="12" fill="#0f172a" opacity="0.9"/>
                            <path d="M188 138 Q200 132 212 138" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" opacity="0.6"/>
                            {{-- Magnifying glass "searching" --}}
                            <g opacity="0.9">
                                <circle cx="310" cy="95" r="38" fill="none" stroke="#0f172a" stroke-width="10" opacity="0.25"/>
                                <circle cx="310" cy="95" r="32" fill="none" stroke="currentColor" stroke-width="6" opacity="0.5"/>
                                <line x1="338" y1="123" x2="365" y2="150" stroke="currentColor" stroke-width="10" stroke-linecap="round" opacity="0.55"/>
                            </g>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="order-1 text-center lg:order-2 lg:text-left">
                <p class="font-display text-4xl font-extrabold uppercase leading-tight tracking-tight text-slate-900 sm:text-5xl md:text-[2.75rem] md:leading-[1.1]">
                    {{ __('Oops! Page not found.') }}
                </p>
                <p class="mt-5 max-w-lg text-base leading-relaxed text-slate-600 sm:text-lg lg:mx-0 mx-auto">
                    {{ __('We looked through every tray and report, but this address is not in our system. The link may be wrong, or the page may have moved.') }}
                </p>
                <div class="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center lg:justify-start">
                    <a href="{{ route('home') }}"
                       class="inline-flex min-h-[52px] w-full max-w-xs items-center justify-center rounded-full px-10 text-sm font-bold uppercase tracking-[0.2em] text-white shadow-lg transition hover:opacity-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 sm:w-auto"
                       style="background: linear-gradient(135deg, var(--primary-color), color-mix(in srgb, var(--primary-color) 72%, var(--secondary-color))); box-shadow: 0 10px 30px color-mix(in srgb, var(--primary-color) 35%, transparent);">
                        {{ __('Back to home') }}
                    </a>
                    @if(Route::has('public.appointments.create') && ($settings->online_booking_enabled ?? true))
                        <a href="{{ route('public.appointments.create') }}"
                           class="inline-flex min-h-[52px] w-full max-w-xs items-center justify-center rounded-full border-2 border-slate-900/90 bg-white px-8 text-sm font-bold uppercase tracking-wider text-slate-900 transition hover:bg-slate-900 hover:text-white sm:w-auto">
                            {{ __('Book a test') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </main>

    {{-- Footer: follow + social + logo strip (reference-style) --}}
    <footer class="mt-auto border-t border-slate-200 bg-slate-950 text-white">
        <div class="h-1 w-full bg-gradient-to-r from-[color:var(--primary-color)] via-[color:var(--secondary-color)] to-[color:var(--primary-color)] opacity-90" aria-hidden="true"></div>
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            @if($hasSocial)
                <div class="flex flex-col items-center gap-6 sm:flex-row sm:justify-between">
                    <p class="text-center text-sm font-semibold tracking-wide text-slate-400 sm:text-left">
                        {{ __('Follow :brand on', ['brand' => $brandName]) }}
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        @if(! empty($settings->facebook_url))
                            <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                               class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 text-slate-400 transition hover:scale-110 hover:border-[color:var(--primary-color)] hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                        @if(! empty($settings->instagram_url))
                            <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                               class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 text-slate-400 transition hover:scale-110 hover:border-[color:var(--primary-color)] hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        @endif
                        @if(! empty($settings->twitter_url))
                            <a href="{{ $settings->twitter_url }}" target="_blank" rel="noopener noreferrer" aria-label="X"
                               class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 text-slate-400 transition hover:scale-110 hover:border-[color:var(--primary-color)] hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        @endif
                        @if(! empty($settings->linkedin_url))
                            <a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
                               class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 text-slate-400 transition hover:scale-110 hover:border-[color:var(--primary-color)] hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        @endif
                        @if(! empty($settings->youtube_url))
                            <a href="{{ $settings->youtube_url }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"
                               class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-700 bg-slate-900 text-slate-400 transition hover:scale-110 hover:border-[color:var(--primary-color)] hover:text-white">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <div class="mt-8 flex flex-col items-center gap-4 border-t border-slate-800/90 pt-8">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center opacity-90 transition hover:opacity-100">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $brandName }}" class="h-8 w-auto max-w-[140px] object-contain brightness-0 invert opacity-90" width="140" height="32" decoding="async">
                    @else
                        <span class="font-display text-lg font-bold text-white">{{ $brandName }}</span>
                    @endif
                </a>
                <p class="text-center text-xs text-slate-500">&copy; {{ date('Y') }} {{ $brandName }}. {{ __('All rights reserved.') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>
