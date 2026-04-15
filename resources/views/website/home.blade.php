@extends('website.layout')

@section('content')
@php
    $banners = $settings->banner_images ?? [];
    $hasBanners = count($banners) > 0;
    $brandDisplay = trim((string) ($settings->brand_name ?? '')) ?: (currentLab()?->name ?? 'Our lab');
    $heroTitle = trim((string) ($settings->hero_title ?? '')) ?: 'Accurate Lab Tests. Fast Reports. Trusted Care.';
    $heroSubtitle = trim((string) ($settings->hero_subtitle ?? '')) ?: 'Book your test online and get reliable reports quickly.';
    $homeHowSteps = $settings->resolvedHomeHowSteps();
    $homeWhyCards = $settings->resolvedHomeWhyCards();
    $homeStats = ($settings->home_show_stats_strip ?? false) ? $settings->aboutStatsDisplay() : null;
    $ldMedical = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalClinic',
        'name' => $settings->brand_name ?? currentLab()?->name ?? 'Lab',
        'url' => url('/'),
    ];
    $ldPhone = trim((string) ($settings->contact_phone ?? $settings->phone ?? ''));
    if ($ldPhone !== '') {
        $ldMedical['telephone'] = $ldPhone;
    }
    $ldAddr = trim((string) ($settings->address ?? ''));
    if ($ldAddr !== '') {
        $ldMedical['address'] = ['@type' => 'PostalAddress', 'streetAddress' => $ldAddr];
    }
    if (! empty($settings->og_image)) {
        $ldMedical['image'] = url(\Illuminate\Support\Facades\Storage::url($settings->og_image));
    }
    $hoursText = trim((string) ($settings->contact_working_hours ?? ''));
    if ($hoursText === '') {
        $hoursText = trim((string) ($settings->about_working_hours ?? ''));
    }
    if ($hoursText !== '') {
        $ldMedical['openingHours'] = $hoursText;
    }
    $sameAs = array_values(array_filter([
        trim((string) ($settings->facebook_url ?? '')),
        trim((string) ($settings->twitter_url ?? '')),
        trim((string) ($settings->instagram_url ?? '')),
        trim((string) ($settings->linkedin_url ?? '')),
        trim((string) ($settings->youtube_url ?? '')),
    ]));
    if (count($sameAs) > 0) {
        $ldMedical['sameAs'] = $sameAs;
    }
    $ldGraph = [
        '@context' => 'https://schema.org',
        '@graph' => [
            $ldMedical,
            [
                '@type' => 'WebSite',
                'name' => $settings->brand_name ?? currentLab()?->name ?? 'Lab',
                'url' => url('/'),
            ],
        ],
    ];
@endphp
@push('meta')
<link rel="canonical" href="{{ url('/') }}" />
<script type="application/ld+json">{!! json_encode($ldGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) !!}</script>
@endpush
    {{-- Hero Section --}}
    <section class="relative w-full overflow-hidden bg-slate-800"
             x-data="{ current: 0, total: {{ count($banners) }} }"
             x-init="if (total > 1) { setInterval(() => { current = (current + 1) % total }, 5000) }">
        @if($hasBanners)
            {{-- Mobile: tall viewport-based frame (svh) — avoids 21:9 squashing; md+: cinematic ratio --}}
            <div class="relative w-full min-h-[19rem] h-[62svh] max-h-[40rem] sm:min-h-[21rem] sm:h-[58svh] md:h-auto md:max-h-none md:min-h-[24rem] md:aspect-[21/9] lg:min-h-[28rem] xl:min-h-[32rem] isolate">
                @foreach($banners as $index => $banner)
                    @php
                        $bannerUrl = \Illuminate\Support\Facades\Storage::url($banner);
                        $isVideo = preg_match('/\.(mp4|webm|ogg|mov|avi)(\?|$)/i', $banner);
                    @endphp
                    <div x-show="current === {{ $index }}"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0">
                        @if($isVideo)
                            <video src="{{ $bannerUrl }}" class="w-full h-full object-cover object-center max-md:object-[center_25%]" autoplay muted loop playsinline aria-label="Banner {{ $index + 1 }}"></video>
                        @else
                            <img src="{{ $bannerUrl }}" alt="" role="presentation" class="w-full h-full object-cover object-center max-md:object-[center_25%]" @if($index === 0) fetchpriority="high" @else loading="lazy" @endif decoding="async">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/45 to-slate-900/20 max-md:from-slate-950/95 max-md:via-slate-900/55"></div>
                    </div>
                @endforeach
                {{-- Copy + CTAs: bottom-weighted on small screens so faces/hero art stay visible; centered on md+ --}}
                <div class="absolute inset-0 z-10 flex flex-col justify-end pt-14 pb-[max(2rem,env(safe-area-inset-bottom,0px))] px-4 sm:pt-16 sm:pb-10 sm:px-5 md:justify-center md:py-12 md:px-6">
                    <div class="text-center text-white max-w-4xl mx-auto w-full">
                        <p class="text-xs sm:text-sm font-semibold uppercase tracking-[0.2em] text-white/85 mb-3 md:mb-4">{{ $brandDisplay }}</p>
                        <h1 class="font-display text-[1.75rem] leading-tight sm:text-3xl sm:leading-tight md:text-5xl lg:text-[3.25rem] font-bold mb-3 sm:mb-4 md:mb-5 tracking-tight drop-shadow-lg [text-wrap:balance]">
                            {{ $heroTitle }}
                        </h1>
                        <p class="text-base sm:text-lg md:text-xl text-slate-100/95 drop-shadow-md font-medium max-w-2xl mx-auto leading-relaxed [text-wrap:balance]">
                            {{ $heroSubtitle }}
                        </p>
                        <div class="mt-7 sm:mt-9 flex flex-wrap items-stretch sm:items-center justify-center gap-3 sm:gap-4 max-w-lg mx-auto sm:max-w-none">
                            <a href="{{ $settings->heroPrimaryUrl() }}" @if(str_starts_with($settings->heroPrimaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex flex-1 sm:flex-initial min-h-[50px] items-center justify-center gap-2 px-6 sm:px-8 py-3.5 rounded-2xl text-sm sm:text-base font-bold text-white shadow-xl transition-all active:scale-[0.98] sm:hover:scale-[1.02] sm:hover:shadow-2xl touch-manipulation" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #1e1b4b) 100%); box-shadow: 0 4px 24px color-mix(in srgb, var(--primary-color) 45%, transparent);">
                                {{ $settings->heroPrimaryLabel() }}
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ $settings->heroSecondaryUrl() }}" @if(str_starts_with($settings->heroSecondaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex flex-1 sm:flex-initial min-h-[50px] items-center justify-center gap-2 px-6 sm:px-8 py-3.5 rounded-2xl text-sm sm:text-base font-semibold bg-white/12 backdrop-blur-md border-2 border-white/35 text-white hover:bg-white/20 transition-all touch-manipulation">
                                {{ $settings->heroSecondaryLabel() }}
                            </a>
                            @if($settings->heroTertiaryLabel() && $settings->heroTertiaryUrl())
                                <a href="{{ $settings->heroTertiaryUrl() }}" @if(str_starts_with($settings->heroTertiaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex flex-[1_1_100%] sm:flex-initial min-h-[50px] sm:min-h-0 items-center justify-center gap-2 px-6 sm:px-8 py-3.5 rounded-2xl text-sm sm:text-base font-semibold bg-white/12 backdrop-blur-md border-2 border-white/35 text-white hover:bg-white/20 transition-all touch-manipulation">
                                    {{ $settings->heroTertiaryLabel() }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @if(count($banners) > 1)
                    <button type="button" @click="current = (current - 1 + total) % total"
                            class="absolute z-20 w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center rounded-full bg-white/15 backdrop-blur-sm hover:bg-white/25 active:bg-white/30 text-white transition-all border border-white/20 touch-manipulation max-md:top-3 max-md:left-3 max-md:translate-y-0 md:left-4 md:top-1/2 md:-translate-y-1/2"
                            aria-label="Previous slide">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button type="button" @click="current = (current + 1) % total"
                            class="absolute z-20 w-11 h-11 sm:w-12 sm:h-12 flex items-center justify-center rounded-full bg-white/15 backdrop-blur-sm hover:bg-white/25 active:bg-white/30 text-white transition-all border border-white/20 touch-manipulation max-md:top-3 max-md:right-3 max-md:translate-y-0 md:right-4 md:top-1/2 md:-translate-y-1/2"
                            aria-label="Next slide">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @endif
            </div>
        @else
            <div class="min-h-[22rem] md:min-h-[30rem] flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 px-4">
                <div class="text-center text-white max-w-3xl mx-auto">
                    <p class="text-xs sm:text-sm font-semibold uppercase tracking-[0.2em] text-white/80 mb-3">{{ $brandDisplay }}</p>
                    <h1 class="font-display text-3xl md:text-5xl font-bold mb-4 tracking-tight leading-tight [text-wrap:balance]">{{ $heroTitle }}</h1>
                    <p class="text-lg md:text-xl text-slate-200 max-w-2xl mx-auto leading-relaxed">{{ $heroSubtitle }}</p>
                    <div class="mt-9 flex flex-wrap items-center justify-center gap-4">
                        <a href="{{ $settings->heroPrimaryUrl() }}" @if(str_starts_with($settings->heroPrimaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex min-h-[50px] items-center gap-2 px-8 py-3.5 rounded-2xl font-bold text-white shadow-lg" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e1b4b) 100%);">
                            {{ $settings->heroPrimaryLabel() }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ $settings->heroSecondaryUrl() }}" @if(str_starts_with($settings->heroSecondaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex min-h-[50px] items-center gap-2 px-8 py-3.5 rounded-2xl font-semibold border-2 border-white/35 text-white hover:bg-white/10 transition-all">
                            {{ $settings->heroSecondaryLabel() }}
                        </a>
                        @if($settings->heroTertiaryLabel() && $settings->heroTertiaryUrl())
                            <a href="{{ $settings->heroTertiaryUrl() }}" @if(str_starts_with($settings->heroTertiaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex min-h-[50px] items-center gap-2 px-8 py-3.5 rounded-2xl font-semibold border-2 border-white/35 text-white hover:bg-white/10 transition-all">
                                {{ $settings->heroTertiaryLabel() }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </section>

    @include('website.partials.trust-strip', ['settings' => $settings])

    @if($homeStats)
    <section class="py-12 md:py-16 bg-slate-950 text-white relative overflow-hidden border-b border-slate-800/90">
        <div class="absolute inset-0 opacity-30 pointer-events-none" style="background: radial-gradient(ellipse 70% 90% at 50% 100%, color-mix(in srgb, var(--primary-color) 50%, transparent), transparent);"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-0 text-center md:divide-x md:divide-white/10">
                <div class="px-2 md:px-6">
                    <p class="font-display text-4xl md:text-[2.75rem] font-bold tabular-nums leading-none" style="color: color-mix(in srgb, var(--primary-color) 88%, white);">{{ $homeStats['tests'] }}</p>
                    <p class="mt-3 text-slate-300 text-sm font-medium">Tests processed</p>
                </div>
                <div class="px-2 md:px-6">
                    <p class="font-display text-4xl md:text-[2.75rem] font-bold tabular-nums leading-none" style="color: color-mix(in srgb, var(--primary-color) 88%, white);">{{ $homeStats['patients'] }}</p>
                    <p class="mt-3 text-slate-300 text-sm font-medium">Patients served</p>
                </div>
                <div class="px-2 md:px-6">
                    <p class="font-display text-4xl md:text-[2.75rem] font-bold tabular-nums leading-none" style="color: color-mix(in srgb, var(--primary-color) 88%, white);">{{ $homeStats['years'] }}</p>
                    <p class="mt-3 text-slate-300 text-sm font-medium">Years of experience</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- How it works --}}
    <section class="py-14 md:py-20 bg-white border-b border-slate-100 overflow-x-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 px-1">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] mb-3" style="color: var(--primary-color);">Our process</p>
                <h2 class="font-display text-2xl md:text-[2rem] font-bold text-slate-900 tracking-tight [text-wrap:balance] break-words">{{ $settings->resolvedHomeHowHeading() }}</h2>
                <p class="mt-4 text-slate-600 max-w-xl mx-auto text-base leading-relaxed [text-wrap:balance] break-words">{{ $settings->resolvedHomeHowSubtitle() }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                @foreach($homeHowSteps as $step)
                <div class="relative text-center p-7 md:p-8 rounded-3xl border border-slate-200/80 bg-white shadow-md shadow-slate-200/40 ring-1 ring-slate-100 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    <div class="mx-auto w-16 h-16 rounded-2xl flex items-center justify-center text-white font-display font-bold text-xl mb-5 shadow-lg" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 70%, #1e1b4b) 100%);">{{ $loop->iteration }}</div>
                    <h3 class="font-display font-bold text-lg text-slate-900 mb-2">{{ $step['title'] }}</h3>
                    @if(!empty($step['body']))
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $step['body'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Popular health packages (strip OR full grid — never both) --}}
    @if(isset($promos) && $promos->isNotEmpty())
    @if($settings->promos_today_strip_enabled ?? false)
    <section class="py-8 md:py-10 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-display text-xl md:text-2xl font-bold text-slate-900 tracking-tight">{{ $settings->promos_today_heading ?? 'Popular health packages' }}</h2>
                    @if(!empty($settings->promos_today_subtitle))
                        <p class="mt-1 text-sm text-slate-600">{{ $settings->promos_today_subtitle }}</p>
                    @endif
                    <p class="mt-2 text-sm text-slate-500 max-w-xl">Tap a card to book with this offer — full details at checkout.</p>
                </div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide shrink-0">Live offers · dates apply automatically</p>
            </div>
            <div class="flex gap-4 overflow-x-auto pb-2 snap-x snap-mandatory scrollbar-thin -mx-4 px-4 md:mx-0 md:px-0">
                @foreach($promos->take(8) as $promo)
                    @php
                        $promoHref = $promo->link ? (str_starts_with($promo->link, 'http') ? $promo->link : url($promo->link)) : route('public.appointments.create');
                        $promoExternal = str_starts_with($promo->link ?? '', 'http');
                    @endphp
                    @include('website.partials.promo-offer-card', ['promo' => $promo, 'promoHref' => $promoHref, 'promoExternal' => $promoExternal, 'articleClass' => 'snap-start shrink-0 w-[min(100%,300px)]'])
                @endforeach
            </div>
        </div>
    </section>
    @elseif($settings->show_promos_section ?? true)
    <section id="offers" class="py-16 md:py-20 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100 scroll-mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ $settings->promos_heading ?? 'Popular health packages' }}</h2>
                <p class="mt-3 text-slate-600 max-w-2xl mx-auto">{{ $settings->promos_subtitle ?? 'Save on checkups and diagnostic bundles.' }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($promos->take(6) as $promo)
                    @php
                        $promoHref = $promo->link ? (str_starts_with($promo->link, 'http') ? $promo->link : url($promo->link)) : route('public.appointments.create');
                        $promoExternal = str_starts_with($promo->link ?? '', 'http');
                    @endphp
                    @include('website.partials.promo-offer-card', ['promo' => $promo, 'promoHref' => $promoHref, 'promoExternal' => $promoExternal, 'articleClass' => 'h-full'])
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endif

    {{-- Content After Banner (Trust/Intro from CMS) --}}
    @if(!empty($settings->content_after_banner))
    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="cms-content text-slate-600 leading-relaxed text-lg prose prose-lg prose-slate max-w-none">
                {!! $settings->content_after_banner !!}
            </div>
        </div>
    </section>
    @endif

    @include('website.partials.spotlight-section', ['settings' => $settings, 'compact' => false])

    @if(isset($homePackages) && $homePackages->isNotEmpty())
    <section class="py-14 md:py-20 bg-gradient-to-b from-slate-50/80 to-white border-b border-slate-100" id="packages-teaser">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10 md:mb-12">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] mb-2" style="color: var(--primary-color);">Health packages</p>
                    <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Our packages</h2>
                    <p class="mt-2 text-slate-600 max-w-xl text-base">Curated bundles with transparent pricing — tap to view details or book.</p>
                </div>
                <a href="{{ route('public.packages.index') }}" class="inline-flex items-center justify-center gap-2 min-h-[44px] px-4 py-2 text-sm font-bold shrink-0 rounded-xl border-2 border-slate-200 text-slate-800 hover:border-slate-300 hover:bg-white transition-colors">
                    View all packages
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($homePackages as $pkg)
                    <div class="flex flex-col rounded-3xl border border-slate-200/90 bg-white p-6 md:p-7 shadow-md shadow-slate-200/50 ring-1 ring-slate-100 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                        <h3 class="font-display font-bold text-lg text-slate-900 [text-wrap:balance]">{{ $pkg->name }}</h3>
                        <p class="mt-3 font-display text-3xl font-bold tabular-nums" style="color: var(--primary-color);">₹{{ number_format((float) ($pkg->final_price ?? $pkg->price), 0) }}</p>
                        @if($pkg->description)
                            <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $pkg->description }}</p>
                        @endif
                        <ul class="mt-4 space-y-2 text-sm text-slate-700 flex-1">
                            @if(($pkg->tests_count ?? 0) > 0 && $pkg->relationLoaded('tests') && $pkg->tests->isNotEmpty())
                                @foreach($pkg->tests->take(5) as $t)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-5 h-5 shrink-0 mt-0.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="leading-snug">{{ $t->name }}</span>
                                    </li>
                                @endforeach
                                @if($pkg->tests_count > 5)
                                    <li class="text-slate-500 pl-7">+ {{ $pkg->tests_count - 5 }} more</li>
                                @endif
                            @else
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 shrink-0 mt-0.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $pkg->tests_count ?? 0 }} tests included</span>
                                </li>
                            @endif
                        </ul>
                        <div class="mt-6 flex flex-col gap-2">
                            <a href="{{ route('public.appointments.create') }}?package={{ $pkg->id }}" class="inline-flex min-h-[48px] items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-bold text-white shadow-md transition hover:opacity-95" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 78%, #1e1b4b) 100%);">
                                Book now
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('public.packages.show', $pkg) }}" class="inline-flex min-h-[44px] items-center justify-center rounded-2xl px-5 py-2.5 text-sm font-semibold border-2 border-slate-200 text-slate-800 hover:bg-slate-50 transition-colors">
                                View details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Why choose us --}}
    <section class="py-16 md:py-24 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 max-w-3xl mx-auto">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] mb-3" style="color: var(--primary-color);">Why patients choose us</p>
                <h2 class="font-display text-2xl md:text-[2rem] font-bold text-slate-900 tracking-tight">{{ $settings->resolvedHomeWhyHeading() }}</h2>
                <p class="mt-4 text-slate-600 text-base md:text-lg leading-relaxed">{{ $settings->resolvedHomeWhySubtitle() }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                @foreach($homeWhyCards as $card)
                <article class="rounded-3xl border border-slate-200/80 bg-gradient-to-b from-slate-50/50 to-white p-7 md:p-8 shadow-sm hover:shadow-lg hover:border-slate-300/90 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-5 shadow-inner border border-white/80" style="background: color-mix(in srgb, var(--primary-color) 14%, white); color: var(--primary-color);">
                        @include('website.partials.home-why-icon', ['i' => $loop->index])
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-900 mb-3">{{ $card['title'] }}</h3>
                    @if(!empty($card['body']))
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $card['body'] }}</p>
                    @endif
                </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Popular specialties (quick entry points) --}}
    @php
        $specialtyLinks = [
            ['label' => 'Blood tests', 'href' => route('public.appointments.create'), 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Thyroid', 'href' => route('public.appointments.create'), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Diabetes', 'href' => route('public.appointments.create'), 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label' => 'Heart health', 'href' => route('public.appointments.create'), 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
            ['label' => 'Liver & kidney', 'href' => route('public.appointments.create'), 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517L6 18.089V5a2 2 0 012-2h6a2 2 0 012 2v10z'],
            ['label' => 'Full body', 'href' => route('public.packages.index'), 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
    @endphp
    <section class="py-12 md:py-16 bg-slate-50/90 border-b border-slate-100" aria-labelledby="specialties-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 id="specialties-heading" class="font-display text-xl md:text-2xl font-bold text-slate-900 tracking-tight">Popular specialties</h2>
                <p class="mt-2 text-sm text-slate-600 max-w-xl mx-auto">Jump straight to booking or browse packages.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach($specialtyLinks as $spec)
                    <a href="{{ $spec['href'] }}" class="group flex flex-col items-center text-center rounded-2xl border border-slate-200/90 bg-white p-4 sm:p-5 shadow-sm hover:shadow-md hover:border-slate-300 hover:-translate-y-0.5 transition-all duration-200">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl mb-3 text-white shadow-md" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e1b4b) 100%);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $spec['icon'] }}"/></svg>
                        </span>
                        <span class="text-sm font-bold text-slate-800 leading-tight group-hover:underline decoration-2 underline-offset-2">{{ $spec['label'] }}</span>
                        <span class="mt-2 text-xs font-semibold" style="color: var(--primary-color);">Book</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Our Services (carousel: 3 cards per slide on lg, same card design) --}}
    @if($settings->show_services_section ?? true)
    @if($tests->count() > 0)
    @php
        $serviceSlides = $tests->chunk(3);
        $serviceSlideCount = $serviceSlides->count();
        $serviceImgGlobalIndex = 0;
    @endphp
    <section class="py-20 md:py-28 bg-gradient-to-b from-white via-slate-50/50 to-white" aria-labelledby="services-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 max-w-3xl mx-auto">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] mb-3" style="color: var(--primary-color);">Diagnostics</p>
                <h2 id="services-heading" class="font-display text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">{{ $settings->services_heading ?? 'Our services' }}</h2>
                <p class="mt-4 text-lg text-slate-600 leading-relaxed">{{ $settings->services_subtitle ?? 'Comprehensive tests with accurate results and fast turnaround.' }}</p>
            </div>

            <div class="relative"
                 x-data="{
                    slide: 0,
                    total: {{ (int) $serviceSlideCount }},
                    hoverPause: false,
                    focusInside: false,
                    reduceMotion: false,
                    _apid: null,
                    go(n) { this.slide = (n + this.total) % this.total; },
                    tick() {
                        if (this.total <= 1 || this.reduceMotion) return;
                        if (this.hoverPause || this.focusInside) return;
                        this.go(this.slide + 1);
                    },
                    init() {
                        this.reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        if (this.total <= 1 || this.reduceMotion) return;
                        this._apid = setInterval(() => this.tick(), 5500);
                    },
                    destroy() {
                        if (this._apid) { clearInterval(this._apid); this._apid = null; }
                    },
                    onFocusOut() {
                        setTimeout(() => {
                            if (! this.$el.contains(document.activeElement)) this.focusInside = false;
                        }, 100);
                    },
                 }"
                 @mouseenter="hoverPause = true"
                 @mouseleave="hoverPause = false"
                 @focusin="focusInside = true"
                 @focusout="onFocusOut()">
                <div class="overflow-hidden rounded-2xl">
                    <div class="flex transition-transform duration-500 ease-out motion-reduce:transition-none"
                         :style="'transform: translateX(-' + (slide * 100) + '%)'">
                        @foreach($serviceSlides as $chunk)
                            <div class="w-full shrink-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" role="group" aria-roledescription="slide">
                                @foreach($chunk as $test)
                                    <article class="group bg-white rounded-3xl shadow-md shadow-slate-200/50 border border-slate-200/80 ring-1 ring-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                                        <div class="aspect-[16/10] bg-slate-100 relative overflow-hidden shrink-0">
                                            @if($test->image)
                                                @php $isFirstServiceImg = ($serviceImgGlobalIndex === 0); $serviceImgGlobalIndex++; @endphp
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($test->image) }}" alt="{{ $test->name }}"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                     @unless($isFirstServiceImg) loading="lazy" decoding="async" @endunless>
                                            @else
                                                <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);">
                                                    <svg class="w-20 h-20 text-slate-400 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517L6 18.089V5a2 2 0 012-2h6a2 2 0 012 2v10z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        </div>
                                        <div class="p-6 lg:p-7 flex flex-col flex-1 min-h-0">
                                            <h3 class="font-display text-xl font-bold text-slate-900 mb-2">{{ $test->name }}</h3>
                                            @if($test->description)
                                                <p class="text-slate-600 text-sm leading-relaxed mb-5 flex-1 line-clamp-4">{{ $test->description }}</p>
                                            @else
                                                <p class="text-slate-500 text-sm mb-5 flex-1">Professional lab test with detailed reporting.</p>
                                            @endif
                                            <p class="text-2xl font-bold tabular-nums mb-5" style="color: var(--primary-color);">₹{{ number_format($test->price, 0) }}</p>
                                            <div class="flex flex-col sm:flex-row gap-2 pt-5 mt-auto border-t border-slate-100">
                                                <a href="{{ route('public.appointments.create') }}?test={{ urlencode($test->name) }}" class="inline-flex flex-1 items-center justify-center gap-2 min-h-[46px] px-4 py-2.5 text-sm font-bold rounded-2xl text-white transition-all hover:opacity-95 shadow-md" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 78%, #1e1b4b) 100%);">
                                                    Book now
                                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                                <a href="{{ route('public.packages.index') }}" class="inline-flex flex-1 items-center justify-center min-h-[46px] px-4 py-2.5 text-sm font-semibold rounded-2xl border-2 border-slate-200 text-slate-800 hover:bg-slate-50 transition-colors">
                                                    View packages
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                                @if($chunk->count() < 3)
                                    @for($i = $chunk->count(); $i < 3; $i++)
                                        <div class="hidden lg:block" aria-hidden="true"></div>
                                    @endfor
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($serviceSlideCount > 1)
                    <div class="flex items-center justify-center mt-10">
                        <div class="flex items-center gap-1 sm:gap-2" role="tablist" aria-label="Test slides">
                            @for($d = 0; $d < $serviceSlideCount; $d++)
                                <button type="button"
                                        @click="slide = {{ $d }}"
                                        class="p-2 sm:p-3 rounded-full focus:outline-none focus:ring-2 focus:ring-[color:var(--primary-color)] focus:ring-offset-2"
                                        aria-label="Go to slide {{ $d + 1 }} of {{ $serviceSlideCount }}">
                                    <span class="block h-2.5 rounded-full transition-all duration-300"
                                          :class="slide === {{ $d }} ? 'w-8 bg-[color:var(--primary-color)]' : 'w-2 bg-slate-300 hover:bg-slate-400'"></span>
                                </button>
                            @endfor
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @else
    <section class="py-16 md:py-20 bg-gradient-to-b from-white via-slate-50/50 to-white border-b border-slate-100" aria-labelledby="services-heading-empty">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 id="services-heading-empty" class="font-display text-2xl md:text-3xl font-bold text-slate-900">{{ $settings->services_heading ?? 'Our services' }}</h2>
            <p class="mt-3 text-slate-600">Tests will appear here once they are added in your lab catalog. You can still book a general appointment.</p>
            <a href="{{ route('public.appointments.create') }}" class="mt-6 inline-flex min-h-[48px] items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-bold text-white shadow-md" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 78%, #1e1b4b) 100%);">
                Book an appointment
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </section>
    @endif
    @endif

    {{-- Report download --}}
    @if($settings->online_reports_enabled ?? true)
    <section class="py-16 md:py-20 bg-slate-50/50 border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-slate-200/90 bg-white p-8 md:p-12 shadow-lg shadow-slate-200/40 flex flex-col md:flex-row md:items-center md:justify-between gap-10">
                <div class="max-w-xl">
                    <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Download your reports anytime</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">Enter the phone number you used when booking or registering. Verify with OTP if prompted, then view or download your report instantly — no need to visit the lab.</p>
                    <ul class="mt-6 space-y-2 text-sm text-slate-700">
                        <li class="flex items-start gap-2"><span class="mt-0.5 w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-white text-xs font-bold" style="background: var(--primary-color);">1</span> Open the patient reports page</li>
                        <li class="flex items-start gap-2"><span class="mt-0.5 w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-white text-xs font-bold" style="background: var(--primary-color);">2</span> Enter your mobile number</li>
                        <li class="flex items-start gap-2"><span class="mt-0.5 w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-white text-xs font-bold" style="background: var(--primary-color);">3</span> Download or view your report instantly</li>
                    </ul>
                </div>
                <div class="shrink-0 flex flex-col sm:flex-row md:flex-col gap-3 w-full md:w-auto">
                    <a href="{{ $settings->heroSecondaryUrl() }}" @if(str_starts_with($settings->heroSecondaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-base font-bold text-white shadow-lg hover:shadow-xl transition-all text-center" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e1b4b) 100%);">
                        {{ $settings->heroSecondaryLabel() }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </a>
                    <a href="{{ route('public.appointments.create') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-base font-semibold border-2 border-slate-300 text-slate-800 hover:bg-slate-50 transition-colors text-center">Book a new test</a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Testimonials --}}
    @if(($settings->show_testimonials_section ?? true) && isset($testimonials) && $testimonials->count() > 0)
    <section class="py-20 md:py-28 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 md:mb-16 max-w-2xl mx-auto">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] mb-3" style="color: var(--primary-color);">Testimonials</p>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">{{ $settings->testimonials_heading ?? 'What our patients say' }}</h2>
                <p class="mt-4 text-lg text-slate-600">{{ $settings->testimonials_subtitle ?? 'Real feedback from people who trust us.' }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($testimonials as $t)
                    <article class="group relative bg-gradient-to-b from-slate-50/80 to-white rounded-3xl p-8 border border-slate-200/70 shadow-md flex flex-col hover:shadow-lg hover:border-slate-300/80 transition-all duration-300">
                        <div class="absolute top-6 right-6 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24" style="color: var(--primary-color);"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>
                        @if($t->rating)
                            <p class="text-amber-500 text-xl mb-4" aria-label="{{ $t->rating }} out of 5 stars">{{ str_repeat('★', (int)$t->rating) }}{{ str_repeat('☆', 5 - (int)$t->rating) }}</p>
                        @endif
                        <blockquote class="text-slate-700 flex-1 mb-6 text-base leading-relaxed pr-8">"{{ $t->quote }}"</blockquote>
                        <div class="flex items-center gap-4 pt-5 border-t border-slate-100">
                            @if($t->photo)
                                <img src="{{ asset('storage/' . $t->photo) }}" alt="{{ $t->name }}" class="h-14 w-14 rounded-full object-cover ring-2 ring-white shadow-md">
                            @else
                                <div class="h-14 w-14 rounded-full flex items-center justify-center text-white font-display font-bold text-lg shadow-md" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #1e1b4b) 100%);">{{ strtoupper(substr($t->name, 0, 1)) }}</div>
                            @endif
                            <div class="min-w-0 text-left">
                                <cite class="font-display font-semibold text-slate-900 not-italic block">{{ $t->name }}</cite>
                                @if(!empty($t->city))
                                    <span class="text-sm text-slate-500 mt-0.5 block">{{ $t->city }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- FAQ --}}
    @if(($settings->show_faq_section ?? true) && isset($faqs) && $faqs->count() > 0)
    <section class="py-20 md:py-28 bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-3xl lg:max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 md:mb-16">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] mb-3" style="color: var(--primary-color);">FAQ</p>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">{{ $settings->faq_heading ?? 'Frequently asked questions' }}</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-xl mx-auto">{{ $settings->faq_subtitle ?? 'Quick answers to common questions.' }}</p>
            </div>
            <div class="space-y-4" x-data="{ open: null }">
                @foreach($faqs as $i => $faq)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden hover:border-slate-300/90 transition-all duration-300"
                         :class="open === {{ $i }} && 'shadow-md ring-1 ring-slate-200/80 border-l-[3px]'"
                         :style="open === {{ $i }} ? { borderLeftColor: 'var(--primary-color)' } : {}">
                        <button type="button"
                                id="faq-button-{{ $i }}"
                                @click="open = open === {{ $i }} ? null : {{ $i }}"
                                :aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                                aria-controls="faq-panel-{{ $i }}"
                                class="w-full flex items-center justify-between gap-4 px-6 py-5 md:py-6 text-left font-bold text-slate-900 text-base md:text-lg hover:bg-slate-50/60 transition-colors">
                            <span class="pr-4 leading-snug">{{ $faq->question }}</span>
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl transition-all duration-200" :class="open === {{ $i }} ? 'text-white shadow-md' : 'bg-slate-100 text-slate-600'" :style="open === {{ $i }} ? { background: 'var(--primary-color)' } : {}">
                                <svg class="w-5 h-5 transition-transform" :class="open === {{ $i }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </button>
                        <div x-show="open === {{ $i }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             id="faq-panel-{{ $i }}"
                             role="region"
                             aria-labelledby="faq-button-{{ $i }}"
                             class="border-t border-slate-100">
                            <div class="px-6 py-5 md:py-6 text-slate-600 leading-relaxed text-[15px] md:text-base prose prose-slate max-w-none">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Contact / find us --}}
    @php
        $mapUrl = trim((string) ($settings->home_map_url ?? ''));
        $cPhone = trim((string) ($settings->contact_phone ?? $settings->phone ?? ''));
        $cPhoneHref = $cPhone !== '' ? 'tel:'.preg_replace('/\s+/', '', $cPhone) : '';
    @endphp
    <section class="py-14 md:py-20 bg-gradient-to-b from-white to-slate-50 border-t border-slate-200/60">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-slate-200/90 bg-white p-8 md:p-10 lg:p-12 shadow-lg shadow-slate-200/30 text-center md:text-left">
                <div class="grid md:grid-cols-2 gap-10 md:gap-12 items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] mb-2" style="color: var(--primary-color);">We are here to help</p>
                        <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Questions or ready to book?</h2>
                        <p class="mt-3 text-slate-600 leading-relaxed">Reach us by phone, visit the contact page, or open directions in Maps.</p>
                        <div class="mt-6 flex flex-col sm:flex-row flex-wrap gap-3 justify-center md:justify-start">
                            @if($cPhoneHref !== '')
                                <a href="{{ $cPhoneHref }}" class="inline-flex min-h-[48px] items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-bold text-white shadow-md transition hover:opacity-95" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 78%, #1e1b4b) 100%);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $cPhone }}
                                </a>
                            @endif
                            <a href="{{ route('public.appointments.create') }}" class="inline-flex min-h-[48px] items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-bold border-2 border-slate-200 text-slate-900 hover:bg-slate-50 transition-colors">
                                Book appointment
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 items-center md:items-stretch">
                        <a href="{{ url('/contact') }}" class="inline-flex w-full min-h-[48px] items-center justify-center gap-2 rounded-2xl border-2 border-slate-200 px-5 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-50 transition-colors">
                            <svg class="w-5 h-5 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Contact &amp; hours
                        </a>
                        @if($mapUrl !== '')
                            <a href="{{ str_starts_with($mapUrl, 'http') ? $mapUrl : url($mapUrl) }}" @if(str_starts_with($mapUrl, 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex w-full min-h-[48px] items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-bold text-white shadow-md transition hover:opacity-95" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Find us on map
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
