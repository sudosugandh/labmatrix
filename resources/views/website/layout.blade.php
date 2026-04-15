<!DOCTYPE html>
@php
    if (!isset($settings) || $settings === null) {
        $settings = \App\Models\WebsiteSetting::getForCurrentLab();
    }
    if ($settings === null) {
        $settings = new \App\Models\WebsiteSetting([
            'primary_color' => '#6366f1',
            'brand_name' => currentLab()?->name ?? 'HealthMatrix',
            'hero_title' => 'Accurate Lab Tests. Fast Reports. Trusted Care.',
            'hero_subtitle' => 'Book your test online and get reliable reports quickly.',
            'theme_preset' => 'violet',
        ]);
    }
    $theme = \App\Support\WebsiteTheme::preset('violet');
    $secondaryResolved = $settings->secondary_color ?: $theme['secondary_default'];
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @if(isset($page))
        <title>{{ $page->meta_title ?: $page->title }} - {{ currentLab()?->name ?? 'HealthMatrix' }}</title>
        <meta name="description" content="{{ $page->meta_description ?: $page->title }}">
    @else
        <title>{{ $settings->meta_title ?? $settings->hero_title ?? (currentLab()?->name ?? 'HealthMatrix') }}</title>
        <meta name="description" content="{{ $settings->meta_description ?? $settings->hero_subtitle ?? 'Your trusted diagnostic center' }}">
    @endif
    <meta property="og:title" content="{{ isset($page) ? ($page->meta_title ?: $page->title) : ($settings->meta_title ?? $settings->hero_title ?? currentLab()?->name ?? 'HealthMatrix') }}">
    <meta property="og:description" content="{{ isset($page) ? ($page->meta_description ?: $page->title) : ($settings->meta_description ?? $settings->hero_subtitle ?? 'Your trusted diagnostic center') }}">
    @if($settings->og_image ?? null)
        <meta property="og:image" content="{{ config('app.url') }}/storage/{{ $settings->og_image }}">
    @endif
    @stack('meta')
    @include('website.partials.google-tag-manager', ['containerId' => $settings->publicGtmContainerIdForDisplay()])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="{{ $theme['fonts_href'] }}" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: {{ $settings->primary_color ?? '#6366f1' }};
            --primary-hover: {{ $settings->primary_color ?? '#4f46e5' }};
            --secondary-color: {{ $secondaryResolved }};
            --page-bg-start: {{ $theme['page_bg_start'] }};
            --page-bg-end: {{ $theme['page_bg_end'] }};
            --font-display: {{ $theme['font_display'] }};
            --font-body: {{ $theme['font_body'] }};
        }
        html { scroll-behavior: smooth; }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
        }
        body { font-family: var(--font-body); }
        @media (max-width: 767px) {
            html:has(.site-nav-links.open) {
                overflow: hidden;
            }
        }
        .font-display { font-family: var(--font-display); }
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
        /* Home visit popup */
        .home-visit-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .home-visit-modal {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            max-width: 420px;
            width: 100%;
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
            position: relative;
            z-index: 9999;
        }
        .home-visit-modal .close-btn {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: #6b7280;
            cursor: pointer;
            border-radius: 0.25rem;
        }
        .home-visit-modal .close-btn:hover {
            color: #111;
            background: #f3f4f6;
        }
        .home-visit-modal .close-btn:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
        /* Page load loader – lab-themed */
        .page-loader {
            position: fixed;
            inset: 0;
            background: linear-gradient(180deg, var(--page-bg-start) 0%, var(--page-bg-end) 100%);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        .page-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .page-loader__ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid color-mix(in srgb, var(--primary-color) 12%, var(--page-bg-end));
            border-top-color: var(--primary-color);
            border-right-color: var(--secondary-color);
            animation: loader-spin 0.9s linear infinite;
        }
        .page-loader__icons {
            position: absolute;
            width: 100px;
            height: 100px;
            animation: loader-orbit 8s linear infinite;
        }
        .page-loader__icon {
            position: absolute;
            width: 20px;
            height: 20px;
            opacity: 0.85;
            animation: loader-pulse 2s ease-in-out infinite;
        }
        .page-loader__icon--1 { top: 0; left: 50%; transform: translate(-50%, -50%); animation-delay: 0s; }
        .page-loader__icon--2 { top: 50%; right: 0; transform: translate(50%, -50%); animation-delay: 0.5s; }
        .page-loader__icon--3 { bottom: 0; left: 50%; transform: translate(-50%, 50%); animation-delay: 1s; }
        .page-loader__icon--4 { top: 50%; left: 0; transform: translate(-50%, -50%); animation-delay: 1.5s; }
        @keyframes loader-spin { to { transform: rotate(360deg); } }
        @keyframes loader-orbit { to { transform: rotate(-360deg); } }
        @keyframes loader-pulse { 0%, 100% { opacity: 0.6; } 50% { opacity: 1; } }
        .skip-to-main {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .skip-to-main:focus {
            position: fixed;
            left: 1rem;
            top: 1rem;
            width: auto;
            height: auto;
            margin: 0;
            padding: 0.75rem 1rem;
            clip: auto;
            overflow: visible;
            white-space: normal;
            z-index: 100;
            background: #fff;
            color: #0f172a;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.15);
            font-weight: 600;
            font-size: 0.875rem;
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
    </style>
    
    @php
        if (!isset($settings)) {
            $settings = \App\Models\WebsiteSetting::getForCurrentLab();
        }
        if (!isset($menuItems)) {
            $menu = \App\Models\Menu::where('lab_id', currentLabId())
                ->where('name', 'Main Menu')
                ->first();
            $menuItems = $menu ? $menu->items : collect();
        }
        $currentPath = '/' . trim(request()->path(), '/');
    @endphp
    @stack('styles')
</head>
<body class="site-public font-sans antialiased min-h-screen" style="background: linear-gradient(180deg, var(--page-bg-start) 0%, var(--page-bg-end) 55%, #ffffff 100%);" x-data="{ navOpen: false }">
    <a href="#main-content" class="skip-to-main" onclick="document.getElementById('main-content')?.focus({preventScroll:true})">Skip to main content</a>
    @include('website.partials.google-tag-manager-noscript', ['containerId' => $settings->publicGtmContainerIdForDisplay()])
    <!-- Page load loader (lab-themed) -->
    <div id="page-loader" class="page-loader" aria-hidden="true">
        <div class="relative flex flex-col items-center justify-center">
            <div class="relative w-[100px] h-[100px] flex items-center justify-center">
                <div class="page-loader__ring"></div>
                <div class="page-loader__icons">
                    <div class="page-loader__icon page-loader__icon--1" style="color: var(--primary-color);" title="Test tube">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M7 2v2h1v14c0 2.21 1.79 4 4 4s4-1.79 4-4V4h1V2H7zm4 18c-1.1 0-2-.9-2-2V4h4v14c0 1.1-.9 2-2 2z"/></svg>
                    </div>
                    <div class="page-loader__icon page-loader__icon--2" style="color: var(--secondary-color);" title="Microscope">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M19 8h-1V6h-2v2h-2V6h-2v2H5c-1.1 0-2 .9-2 2v1h2v7c0 1.1.9 2 2 2h2v1h2v-1h2c1.1 0 2-.9 2-2v-7h2v-1c0-1.1-.9-2-2-2zm-4 9H9v-2h6v2z"/></svg>
                    </div>
                    <div class="page-loader__icon page-loader__icon--3" style="color: var(--primary-color);" title="Beaker">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M7 2v2h1v12.27c-1.29.52-2.22 1.76-2.22 3.23 0 1.94 1.58 3.5 3.5 3.5s3.5-1.56 3.5-3.5c0-1.47-.93-2.71-2.22-3.23V4h1V2H7zm2 2h2v10.82c.55.22 1 .58 1.27 1.04.27-.46.72-.82 1.27-1.04V4h2V2H9v2z"/></svg>
                    </div>
                    <div class="page-loader__icon page-loader__icon--4" style="color: color-mix(in srgb, var(--primary-color) 70%, var(--secondary-color) 30%);" title="Lab">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 2L2 7v2h2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V9h2V7L12 2zm0 2.18l6 3.32v1.5H6v-1.5l6-3.32zM18 19H6V9h12v10z"/></svg>
                    </div>
                </div>
            </div>
            <p class="mt-6 text-lg font-bold text-gray-700">Almost there...</p>
            <p class="mt-1 text-sm text-gray-500">preparing your lab experience</p>
        </div>
    </div>

    @if(($settings->home_collection_popup_enabled ?? true) && ! request()->is('doctor-portal*'))
    <!-- Home visit / home collection popup -->
    <div x-data="homeVisitPopup({{ (int)($settings->home_collection_popup_delay_seconds ?? 2) }}, '{{ $settings->home_collection_popup_frequency ?? 'session' }}')" x-init="init()" x-show="open" x-cloak x-transition class="home-visit-overlay" @keydown.escape.window="close()" role="presentation">
        <div class="home-visit-modal" @click.outside="close()" role="dialog" aria-modal="true" aria-labelledby="home-visit-dialog-title">
            <button type="button" class="close-btn" aria-label="Close dialog" @click="close()">&times;</button>
            <div class="p-6 pt-10 text-center">
                <div class="mb-4 flex justify-center" aria-hidden="true">
                    <svg class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a4 4 0 0 1 4 4v1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1V6a4 4 0 0 1 4-4zm2 6V6a2 2 0 1 0-4 0v2h4z"/></svg>
                </div>
                <h2 id="home-visit-dialog-title" class="text-xl font-bold text-gray-900 mb-1">Need Help in Booking A Home Collection?</h2>
                <p class="text-sm text-gray-600 mb-6">Drop your Details and we will guide you over call</p>
                <template x-if="!submitted">
                    <form @submit.prevent="submit()" class="text-left space-y-4">
                        <input type="text" name="name" x-model="name" placeholder="Name" required
                            class="w-full min-h-[44px] rounded border border-gray-300 px-3 py-2.5 text-base sm:text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                        <input type="tel" name="phone" x-model="phone" placeholder="Enter Phone Number" required
                            class="w-full min-h-[44px] rounded border border-gray-300 px-3 py-2.5 text-base sm:text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                        <select name="city" x-model="city"
                            class="w-full min-h-[44px] rounded border border-gray-300 px-3 py-2.5 text-base sm:text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30">
                            <option value="">Select City</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Bangalore">Bangalore</option>
                            <option value="Hyderabad">Hyderabad</option>
                            <option value="Chennai">Chennai</option>
                            <option value="Kolkata">Kolkata</option>
                            <option value="Pune">Pune</option>
                            <option value="Ahmedabad">Ahmedabad</option>
                            <option value="Other">Other</option>
                        </select>
                        <p x-show="error" x-text="error" class="text-sm text-red-600"></p>
                        <button type="submit" :disabled="loading"
                            class="w-full min-h-[48px] rounded py-3 text-sm font-semibold text-white disabled:opacity-70"
                            style="background-color: var(--primary-color);">
                            <span x-show="!loading">Request Home Visit</span>
                            <span x-show="loading">Sending...</span>
                        </button>
                    </form>
                </template>
                <template x-if="submitted">
                    <p class="text-gray-700 font-medium" x-text="successMessage"></p>
                </template>
                <p class="mt-4 text-xs text-gray-500">
                    <a href="{{ route('page.show', ['slug' => 'privacy-policy']) }}" class="underline">Privacy Policy</a> and
                    <a href="{{ route('page.show', ['slug' => 'terms-and-conditions']) }}" class="underline">Terms &amp; Conditions</a>* Apply
                </p>
            </div>
        </div>
    </div>
    @endif
    @if(!empty($settings->announcement_enabled) && !empty($settings->announcement_text))
        <div class="text-white text-center py-2.5 px-4 text-sm font-medium" style="background: linear-gradient(90deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 85%, #312e81) 100%);">
            <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-center gap-2">
                <span>{{ $settings->announcement_text }}</span>
                @if(!empty($settings->announcement_link))
                    <a href="{{ str_starts_with($settings->announcement_link, 'http') ? $settings->announcement_link : url($settings->announcement_link) }}" class="underline font-medium hover:no-underline">{{ __('Learn more') }}</a>
                @endif
            </div>
        </div>
    @endif
    @if(!empty($settings->header_content))
        <div class="header-cms bg-gray-100 border-b border-gray-200 text-sm text-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                {!! $settings->header_content !!}
            </div>
        </div>
    @endif
    <!-- Navigation (sticky) -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-[0_1px_0_0_rgba(0,0,0,0.05)] border-b border-slate-200/60" aria-label="Main navigation">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center min-h-[4rem] py-2 md:py-0 md:h-16 md:min-h-0">
                <div class="flex items-center shrink-0 min-w-0 pr-2">
                    <a href="{{ route('home') }}" class="flex items-center rounded-lg">
                        @if($settings->logo)
                            <img src="{{ Storage::url($settings->logo) }}" alt="{{ currentLab()?->name ?? 'HealthMatrix' }} — Home" class="h-12 md:h-14 w-auto max-h-12 md:max-h-14 object-contain" width="160" height="56" decoding="async">
                        @else
                            <span class="text-lg md:text-xl font-display font-bold tracking-tight truncate max-w-[min(100vw-8rem,20rem)]" style="color: var(--primary-color);">{{ currentLab()?->name ?? 'HealthMatrix' }}</span>
                        @endif
                    </a>
                </div>
                <button type="button" class="nav-toggle min-h-[44px] min-w-[44px] shrink-0" @click="navOpen = !navOpen" :aria-expanded="navOpen ? 'true' : 'false'" aria-controls="primary-navigation" :aria-label="navOpen ? 'Close menu' : 'Open menu'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!navOpen" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="navOpen" x-cloak aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div id="primary-navigation" class="site-nav-links" :class="{ 'open': navOpen }" @click="navOpen = false">
                    @auth('doctor')
                        @include('website.partials.nav-doctor-portal')
                    @else
                        @foreach($menuItems as $item)
                            @php
                                $itemPath = '/' . trim(parse_url($item->url, PHP_URL_PATH) ?? $item->url, '/');
                                $itemUrl = $item->url ?? '';
                                $isActive = $currentPath === $itemPath || ($itemPath === '/' && $currentPath === '');
                                $isBookCta = str_contains($itemUrl, 'book-appointment') || (str_contains($itemUrl, 'appointments') && !str_contains($itemUrl, 'my-appointments')) || str_contains(strtolower($item->title ?? ''), 'book');
                                $isReportsCta = str_contains($itemUrl, 'patient-reports') || (str_contains($itemUrl, 'reports') && !str_contains($itemUrl, 'appointments')) || str_contains(strtolower($item->title ?? ''), 'report');
                            @endphp
                            <a href="{{ $item->url }}" class="{{ $isActive ? 'active' : '' }} {{ $isBookCta ? 'nav-cta' : '' }} {{ $isReportsCta && !$isBookCta ? 'nav-cta-secondary' : '' }}">{{ $item->title }}</a>
                        @endforeach
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content" class="pb-24 max-md:pb-28 md:pb-10" tabindex="-1">
        @yield('content')
    </main>

    @unless(Auth::guard('doctor')->check() && request()->is('doctor-portal*'))
        @include('website.partials.sticky-contact-fab')
    @endunless

    <div class="mt-20">
        @include('website.partials.site-footer', ['settings' => $settings])
    </div>

    <script>
        (function() {
            var loader = document.getElementById('page-loader');
            var minTime = 120;
            var start = Date.now();
            var scheduled = false;
            function hideLoader() {
                if (scheduled) return;
                scheduled = true;
                var elapsed = Date.now() - start;
                var delay = Math.max(0, minTime - elapsed);
                setTimeout(function() {
                    if (loader) loader.classList.add('hidden');
                }, delay);
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', hideLoader);
            } else {
                hideLoader();
            }
            window.addEventListener('load', hideLoader);
        })();
    </script>
    <script>
        function homeVisitPopup(delaySeconds, frequency) {
            delaySeconds = Math.max(0, parseInt(delaySeconds, 10) || 2);
            frequency = frequency === 'daily' ? 'daily' : 'session';
            const STORAGE_KEY = 'healthmatrix_home_visit_popup_shown';
            const STORAGE_KEY_DAILY = 'healthmatrix_home_visit_popup_date';
            return {
                open: false,
                name: '',
                phone: '',
                city: '',
                loading: false,
                submitted: false,
                successMessage: '',
                error: '',
                init() {
                    const delayMs = delaySeconds * 1000;
                    if (frequency === 'daily') {
                        const today = new Date().toDateString();
                        if (localStorage.getItem(STORAGE_KEY_DAILY) === today) return;
                        setTimeout(() => { this.open = true; localStorage.setItem(STORAGE_KEY_DAILY, today); }, delayMs);
                    } else {
                        if (sessionStorage.getItem(STORAGE_KEY)) return;
                        setTimeout(() => { this.open = true; sessionStorage.setItem(STORAGE_KEY, '1'); }, delayMs);
                    }
                },
                close() {
                    this.open = false;
                },
                async submit() {
                    this.error = '';
                    this.loading = true;
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    formData.append('name', this.name);
                    formData.append('phone', this.phone);
                    formData.append('city', this.city);
                    try {
                        const r = await fetch('{{ route("public.home-visit-request.store") }}', {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        });
                        const data = await r.json().catch(() => ({}));
                        if (r.ok && (data.success || data.message)) {
                            this.successMessage = data.message || 'Thank you! We will contact you shortly.';
                            this.submitted = true;
                        } else {
                            this.error = data.message || data.errors ? Object.values(data.errors).flat().join(' ') : 'Something went wrong. Please try again.';
                        }
                    } catch (e) {
                        this.error = 'Something went wrong. Please try again.';
                    }
                    this.loading = false;
                }
            };
        }
    </script>
</body>
</html>
