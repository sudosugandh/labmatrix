<!DOCTYPE html>
@php
    if (!isset($settings) || $settings === null) {
        $settings = \App\Models\WebsiteSetting::getForCurrentLab();
    }
    if ($settings === null) {
        $settings = new \App\Models\WebsiteSetting([
            'primary_color' => '#6366f1',
            'brand_name' => currentLab()?->name ?? 'HealthMatrix',
            'hero_title' => 'Welcome to ' . (currentLab()?->name ?? 'HealthMatrix'),
            'hero_subtitle' => 'Your trusted diagnostic center',
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
        body { font-family: var(--font-body); }
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
            border: 2px solid var(--primary-color);
            color: var(--primary-color) !important;
            padding: 0.4rem 1.125rem;
            border-radius: 0.625rem;
            font-weight: 600;
        }
        .site-nav-links a.nav-cta-secondary:hover {
            background-color: color-mix(in srgb, var(--primary-color) 8%, transparent);
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
<body class="font-sans antialiased min-h-screen" style="background: linear-gradient(180deg, var(--page-bg-start) 0%, var(--page-bg-end) 55%, #ffffff 100%);" x-data="{ navOpen: false }">
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

    @if($settings->home_collection_popup_enabled ?? true)
    <!-- Home visit / home collection popup -->
    <div x-data="homeVisitPopup({{ (int)($settings->home_collection_popup_delay_seconds ?? 2) }}, '{{ $settings->home_collection_popup_frequency ?? 'session' }}')" x-init="init()" x-show="open" x-cloak x-transition class="home-visit-overlay" @keydown.escape.window="close()">
        <div class="home-visit-modal" @click.outside="close()">
            <button type="button" class="close-btn" aria-label="Close" @click="close()">&times;</button>
            <div class="p-6 pt-10 text-center">
                <div class="mb-4 flex justify-center">
                    <svg class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a4 4 0 0 1 4 4v1h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1V6a4 4 0 0 1 4-4zm2 6V6a2 2 0 1 0-4 0v2h4z"/></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-1">Need Help in Booking A Home Collection?</h2>
                <p class="text-sm text-gray-600 mb-6">Drop your Details and we will guide you over call</p>
                <template x-if="!submitted">
                    <form @submit.prevent="submit()" class="text-left space-y-4">
                        <input type="text" name="name" x-model="name" placeholder="Name" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <input type="tel" name="phone" x-model="phone" placeholder="Enter Phone Number" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <select name="city" x-model="city"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
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
                            class="w-full rounded py-2.5 text-sm font-medium text-white disabled:opacity-70"
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
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-[0_1px_0_0_rgba(0,0,0,0.05)] border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center">
                        @if($settings->logo)
                            <img src="{{ Storage::url($settings->logo) }}" alt="Logo" class="h-14 w-auto max-h-14 object-contain">
                        @else
                            <span class="text-xl font-display font-bold tracking-tight" style="color: var(--primary-color);">{{ currentLab()?->name ?? 'HealthMatrix' }}</span>
                        @endif
                    </a>
                </div>
                <button type="button" class="nav-toggle" @click="navOpen = !navOpen" aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!navOpen"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="navOpen" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="site-nav-links" :class="{ 'open': navOpen }" @click="navOpen = false">
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
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @php
        $footerMenu = \App\Models\Menu::where('lab_id', currentLabId())->where('name', 'Footer Menu')->first();
        $footerMenuItems = $footerMenu ? $footerMenu->items : collect();
    @endphp
    <!-- Footer -->
    <footer class="bg-slate-900 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <div>
                    <h3 class="text-lg font-display font-semibold mb-4 tracking-tight">{{ currentLab()?->name ?? 'HealthMatrix' }}</h3>
                    @if($settings->address)
                        <p class="text-slate-400 leading-relaxed">{{ $settings->address }}</p>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-display font-semibold mb-4 tracking-tight">Contact</h3>
                    @if($settings->phone)
                        <p class="text-slate-400">Phone: {{ $settings->phone }}</p>
                    @endif
                    @if($settings->email)
                        <p class="text-slate-400">Email: {{ $settings->email }}</p>
                    @endif
                </div>
                @if($footerMenuItems->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-display font-semibold mb-4 tracking-tight">Quick Links</h3>
                        <ul class="space-y-3">
                            @foreach($footerMenuItems as $item)
                                <li><a href="{{ $item->url }}" class="text-slate-400 hover:text-white transition-colors inline-block">{{ $item->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    @if($settings->footer_text)
                        <p class="text-slate-400 leading-relaxed">{{ $settings->footer_text }}</p>
                    @endif
                    @if(!empty($settings->footer_content))
                        <div class="footer-cms mt-2 text-slate-400 prose prose-invert prose-sm max-w-none">
                            {!! $settings->footer_content !!}
                        </div>
                    @endif
                </div>
            </div>
            @php
                $hasSocial = !empty($settings->facebook_url) || !empty($settings->twitter_url) || !empty($settings->instagram_url) || !empty($settings->linkedin_url) || !empty($settings->youtube_url);
            @endphp
            @if($hasSocial)
            <div class="mt-10 pt-8 border-t border-slate-700/80 flex flex-wrap items-center justify-center gap-5">
                <span class="text-slate-500 text-sm font-medium">Follow us</span>
                @if(!empty($settings->facebook_url))
                    <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-white transition-colors p-2 rounded-lg hover:bg-slate-800" aria-label="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                @endif
                @if(!empty($settings->twitter_url))
                    <a href="{{ $settings->twitter_url }}" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-white transition-colors p-2 rounded-lg hover:bg-slate-800" aria-label="X (Twitter)">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                @endif
                @if(!empty($settings->instagram_url))
                    <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-white transition-colors p-2 rounded-lg hover:bg-slate-800" aria-label="Instagram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                @endif
                @if(!empty($settings->linkedin_url))
                    <a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-white transition-colors p-2 rounded-lg hover:bg-slate-800" aria-label="LinkedIn">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                @endif
                @if(!empty($settings->youtube_url))
                    <a href="{{ $settings->youtube_url }}" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-white transition-colors p-2 rounded-lg hover:bg-slate-800" aria-label="YouTube">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                @endif
            </div>
            @endif
            <div class="mt-10 pt-8 border-t border-slate-700/80 text-center text-slate-500 text-sm">
                <p>&copy; {{ date('Y') }} {{ currentLab()?->name ?? 'HealthMatrix' }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

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
