@extends('website.layout')

@section('content')
@php
    $banners = $settings->banner_images ?? [];
    $hasBanners = count($banners) > 0;
    $heroTitle = trim((string) ($settings->hero_title ?? '')) ?: 'Accurate Lab Tests. Fast Reports. Trusted Care.';
    $heroSubtitle = trim((string) ($settings->hero_subtitle ?? '')) ?: 'Book your test online and get reliable reports quickly.';
@endphp
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
                            <img src="{{ $bannerUrl }}" alt="" role="presentation" class="w-full h-full object-cover object-center max-md:object-[center_25%]" @if($index === 0) fetchpriority="high" @endif decoding="async">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/45 to-slate-900/20 max-md:from-slate-950/95 max-md:via-slate-900/55"></div>
                    </div>
                @endforeach
                {{-- Copy + CTAs: bottom-weighted on small screens so faces/hero art stay visible; centered on md+ --}}
                <div class="absolute inset-0 z-10 flex flex-col justify-end pt-14 pb-[max(2rem,env(safe-area-inset-bottom,0px))] px-4 sm:pt-16 sm:pb-10 sm:px-5 md:justify-center md:py-12 md:px-6">
                    <div class="text-center text-white max-w-4xl mx-auto w-full">
                        <h1 class="font-display text-[1.7rem] leading-snug sm:text-3xl sm:leading-tight md:text-5xl lg:text-6xl font-bold mb-3 sm:mb-4 md:mb-5 tracking-tight drop-shadow-lg [text-wrap:balance]">
                            {{ $heroTitle }}
                        </h1>
                        <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-slate-200 drop-shadow-md font-medium max-w-3xl mx-auto leading-snug [text-wrap:balance]">
                            {{ $heroSubtitle }}
                        </p>
                        <div class="mt-6 sm:mt-8 flex flex-wrap items-stretch sm:items-center justify-center gap-3 sm:gap-4 max-w-lg mx-auto sm:max-w-none">
                            <a href="{{ $settings->heroPrimaryUrl() }}" @if(str_starts_with($settings->heroPrimaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex flex-1 sm:flex-initial min-h-[48px] items-center justify-center gap-2 px-5 sm:px-6 py-3.5 rounded-xl text-sm sm:text-base font-semibold text-white shadow-xl transition-all active:scale-[0.98] sm:hover:scale-[1.02] sm:hover:shadow-2xl touch-manipulation" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #1e1b4b) 100%); box-shadow: 0 4px 20px color-mix(in srgb, var(--primary-color) 40%, transparent);">
                                {{ $settings->heroPrimaryLabel() }}
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ $settings->heroSecondaryUrl() }}" @if(str_starts_with($settings->heroSecondaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex flex-1 sm:flex-initial min-h-[48px] items-center justify-center gap-2 px-5 sm:px-6 py-3.5 rounded-xl text-sm sm:text-base font-semibold bg-white/15 backdrop-blur-sm border border-white/30 text-white hover:bg-white/25 transition-all touch-manipulation">
                                {{ $settings->heroSecondaryLabel() }}
                            </a>
                            @if($settings->heroTertiaryLabel() && $settings->heroTertiaryUrl())
                                <a href="{{ $settings->heroTertiaryUrl() }}" @if(str_starts_with($settings->heroTertiaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex flex-[1_1_100%] sm:flex-initial min-h-[48px] sm:min-h-0 items-center justify-center gap-2 px-5 sm:px-6 py-3.5 rounded-xl text-sm sm:text-base font-semibold bg-white/15 backdrop-blur-sm border border-white/30 text-white hover:bg-white/25 transition-all touch-manipulation">
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
            <div class="min-h-[20rem] md:min-h-[28rem] flex items-center justify-center bg-gradient-to-br from-slate-800 to-slate-900">
                <div class="text-center text-white px-4">
                    <h1 class="font-display text-4xl md:text-5xl font-bold mb-4 tracking-tight">{{ $heroTitle }}</h1>
                    <p class="text-xl text-slate-300 max-w-2xl mx-auto">{{ $heroSubtitle }}</p>
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                        <a href="{{ $settings->heroPrimaryUrl() }}" @if(str_starts_with($settings->heroPrimaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold text-white" style="background-color: var(--primary-color);">
                            {{ $settings->heroPrimaryLabel() }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ $settings->heroSecondaryUrl() }}" @if(str_starts_with($settings->heroSecondaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold border-2 border-white/40 text-white hover:bg-white/10 transition-all">
                            {{ $settings->heroSecondaryLabel() }}
                        </a>
                        @if($settings->heroTertiaryLabel() && $settings->heroTertiaryUrl())
                            <a href="{{ $settings->heroTertiaryUrl() }}" @if(str_starts_with($settings->heroTertiaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold border-2 border-white/40 text-white hover:bg-white/10 transition-all">
                                {{ $settings->heroTertiaryLabel() }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </section>

    @include('website.partials.trust-strip', ['settings' => $settings])

    {{-- How it works --}}
    <section class="py-12 md:py-16 bg-white border-b border-slate-100 overflow-x-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 md:mb-14 px-1">
                <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-900 tracking-tight [text-wrap:balance] break-words">How it works</h2>
                <p class="mt-3 text-slate-600 max-w-xl mx-auto text-sm sm:text-base leading-relaxed [text-wrap:balance] break-words">Three simple steps from booking to your report.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-6">
                <div class="relative text-center p-6 rounded-2xl border border-slate-200/90 bg-gradient-to-b from-slate-50 to-white shadow-sm">
                    <div class="mx-auto w-14 h-14 rounded-2xl flex items-center justify-center text-white font-display font-bold text-xl mb-4 shadow-md" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 70%, #1e1b4b) 100%);">1</div>
                    <h3 class="font-display font-semibold text-lg text-slate-900 mb-2">Book test online</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Choose your tests or package and pick a slot that suits you — no long queues.</p>
                </div>
                <div class="relative text-center p-6 rounded-2xl border border-slate-200/90 bg-gradient-to-b from-slate-50 to-white shadow-sm">
                    <div class="mx-auto w-14 h-14 rounded-2xl flex items-center justify-center text-white font-display font-bold text-xl mb-4 shadow-md" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 70%, #1e1b4b) 100%);">2</div>
                    <h3 class="font-display font-semibold text-lg text-slate-900 mb-2">Visit lab or home collection</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Visit our centre for sample collection or request a trained phlebotomist at home.</p>
                </div>
                <div class="relative text-center p-6 rounded-2xl border border-slate-200/90 bg-gradient-to-b from-slate-50 to-white shadow-sm">
                    <div class="mx-auto w-14 h-14 rounded-2xl flex items-center justify-center text-white font-display font-bold text-xl mb-4 shadow-md" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 70%, #1e1b4b) 100%);">3</div>
                    <h3 class="font-display font-semibold text-lg text-slate-900 mb-2">Get report online</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Access your report securely as soon as it is ready — view or download anytime.</p>
                </div>
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

    {{-- Why choose us --}}
    <section class="py-16 md:py-20 bg-gradient-to-b from-white to-slate-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-14">
                <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Why choose us</h2>
                <p class="mt-3 text-slate-600 max-w-2xl mx-auto">Quality, speed, and care — built into every test we run.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <article class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background: color-mix(in srgb, var(--primary-color) 12%, white); color: var(--primary-color);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-display font-semibold text-lg text-slate-900 mb-2">Accurate reports</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Validated methods, internal QC, and clear reporting you and your doctor can rely on.</p>
                </article>
                <article class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background: color-mix(in srgb, var(--primary-color) 12%, white); color: var(--primary-color);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="font-display font-semibold text-lg text-slate-900 mb-2">Fast turnaround</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Efficient processing with same-day or next-day results for many routine tests.</p>
                </article>
                <article class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background: color-mix(in srgb, var(--primary-color) 12%, white); color: var(--primary-color);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h3 class="font-display font-semibold text-lg text-slate-900 mb-2">Home collection</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Trained phlebotomists visit you at home — convenient, hygienic, and on your schedule.</p>
                </article>
                <article class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background: color-mix(in srgb, var(--primary-color) 12%, white); color: var(--primary-color);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="font-display font-semibold text-lg text-slate-900 mb-2">Trusted lab</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Experienced team, modern equipment, and thousands of patients who choose us every year.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Our Services (carousel: 3 cards per slide on lg, same card design) --}}
    @if(($settings->show_services_section ?? true) && $tests->count() > 0)
    @php
        $serviceSlides = $tests->chunk(3);
        $serviceSlideCount = $serviceSlides->count();
    @endphp
    <section class="py-20 md:py-24 bg-gradient-to-b from-slate-50 to-white" aria-labelledby="services-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 md:mb-14">
                <h2 id="services-heading" class="font-display text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-slate-900 tracking-tight">{{ $settings->services_heading ?? 'Our Services' }}</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">{{ $settings->services_subtitle ?? 'Comprehensive diagnostic tests with accurate results and fast turnaround.' }}</p>
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
                                    <article class="group bg-white rounded-2xl shadow-sm shadow-slate-200/60 border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/80 hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                                        <div class="aspect-[16/10] bg-slate-100 relative overflow-hidden shrink-0">
                                            @if($test->image)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($test->image) }}" alt="{{ $test->name }}"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
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
                                            <h3 class="font-display text-xl font-semibold text-slate-900 mb-2">{{ $test->name }}</h3>
                                            @if($test->description)
                                                <p class="text-slate-600 text-sm leading-relaxed mb-5 flex-1 line-clamp-4">{{ $test->description }}</p>
                                            @else
                                                <p class="text-slate-500 text-sm mb-5 flex-1">Professional lab test with detailed reporting.</p>
                                            @endif
                                            <div class="flex items-center justify-between gap-4 pt-5 mt-auto border-t border-slate-100">
                                                <p class="text-xl font-bold" style="color: var(--primary-color);">₹{{ number_format($test->price, 2) }}</p>
                                                <a href="{{ route('public.appointments.create') }}?test={{ urlencode($test->name) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl text-white transition-all hover:shadow-lg shrink-0" style="background-color: var(--primary-color); box-shadow: 0 2px 10px color-mix(in srgb, var(--primary-color) 35%, transparent);">
                                                    Book test
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
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
    @endif

    {{-- Report download --}}
    @if($settings->online_reports_enabled ?? true)
    <section class="py-16 md:py-20 bg-white border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200/90 bg-gradient-to-br from-slate-50 via-white to-slate-50/80 p-8 md:p-12 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-8">
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
    <section class="py-20 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-slate-900 tracking-tight">{{ $settings->testimonials_heading ?? 'What Our Patients Say' }}</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">{{ $settings->testimonials_subtitle ?? 'Real feedback from people who trust us.' }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimonials as $t)
                    <article class="group relative bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm flex flex-col hover:shadow-xl hover:shadow-slate-200/60 hover:border-slate-300/80 transition-all duration-300">
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
    <section class="py-20 md:py-24 bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-slate-900 tracking-tight">{{ $settings->faq_heading ?? 'Frequently Asked Questions' }}</h2>
                <p class="mt-4 text-lg text-slate-600">{{ $settings->faq_subtitle ?? 'Quick answers to common questions.' }}</p>
            </div>
            <div class="space-y-3" x-data="{ open: null }">
                @foreach($faqs as $i => $faq)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden hover:border-slate-300/80 transition-all duration-300"
                         :class="open === {{ $i }} && 'shadow-lg border-l-4'"
                         :style="open === {{ $i }} ? { borderLeftColor: 'var(--primary-color)' } : {}">
                        <button type="button"
                                @click="open = open === {{ $i }} ? null : {{ $i }}"
                                class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left font-semibold text-slate-900 hover:bg-slate-50/50 transition-colors">
                            <span class="text-base pr-4">{{ $faq->question }}</span>
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl transition-all duration-200" :class="open === {{ $i }} ? 'bg-slate-900 text-white rotate-180 shadow-md' : 'bg-slate-100 text-slate-600'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </button>
                        <div x-show="open === {{ $i }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             class="border-t border-slate-100">
                            <div class="px-6 py-5 text-slate-600 leading-relaxed prose prose-slate max-w-none">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Contact CTA --}}
    <section class="py-12 md:py-16 bg-slate-50 border-t border-slate-200/60">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-slate-600 text-lg mb-2">Need help? Visit our <a href="{{ url('/contact') }}" class="font-semibold hover:underline" style="color: var(--primary-color);">Contact</a> page or <a href="{{ route('public.appointments.create') }}" class="font-semibold hover:underline" style="color: var(--primary-color);">book an appointment</a>.</p>
            <p class="text-sm text-slate-500 mt-2"><a href="{{ url('/contact') }}" class="hover:underline">View our help center</a></p>
        </div>
    </section>
@endsection
