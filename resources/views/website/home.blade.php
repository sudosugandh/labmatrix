@extends('website.layout')

@section('content')
@php
    $banners = $settings->banner_images ?? [];
    $hasBanners = count($banners) > 0;
@endphp
    {{-- Hero Section --}}
    <section class="relative w-full overflow-hidden bg-slate-800"
             x-data="{ current: 0, total: {{ count($banners) }} }"
             x-init="if (total > 1) { setInterval(() => { current = (current + 1) % total }, 5000) }">
        @if($hasBanners)
            <div class="relative aspect-[21/9] min-h-[20rem] md:min-h-[28rem] lg:min-h-[32rem]">
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
                            <video src="{{ $bannerUrl }}" class="w-full h-full object-cover" autoplay muted loop playsinline aria-label="Banner {{ $index + 1 }}"></video>
                        @else
                            <img src="{{ $bannerUrl }}" alt="Banner {{ $index + 1 }}" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/30 to-transparent"></div>
                    </div>
                @endforeach
                <div class="absolute inset-0 flex items-center justify-center z-10 px-4">
                    <div class="text-center text-white max-w-4xl">
                        <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold mb-5 tracking-tight drop-shadow-lg">
                            {{ $settings->hero_title ?? 'Welcome to ' . (currentLab()?->name ?? 'HealthMatrix') }}
                        </h1>
                        <p class="text-lg md:text-xl lg:text-2xl text-slate-200 drop-shadow-md font-medium">
                            {{ $settings->hero_subtitle ?? 'Your trusted diagnostic center' }}
                        </p>
                        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                            <a href="{{ route('public.appointments.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold text-white shadow-xl transition-all hover:scale-[1.02] hover:shadow-2xl" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 80%, #1e1b4b) 100%); box-shadow: 0 4px 20px color-mix(in srgb, var(--primary-color) 40%, transparent);">
                                Book Appointment
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ route('public.reports.search') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold bg-white/15 backdrop-blur-sm border border-white/30 text-white hover:bg-white/25 transition-all">
                                View Reports
                            </a>
                        </div>
                    </div>
                </div>
                @if(count($banners) > 1)
                    <button @click="current = (current - 1 + total) % total"
                            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 flex items-center justify-center rounded-full bg-white/15 backdrop-blur-sm hover:bg-white/25 text-white transition-all border border-white/20"
                            aria-label="Previous slide">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="current = (current + 1) % total"
                            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 flex items-center justify-center rounded-full bg-white/15 backdrop-blur-sm hover:bg-white/25 text-white transition-all border border-white/20"
                            aria-label="Next slide">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @endif
            </div>
        @else
            <div class="min-h-[20rem] md:min-h-[28rem] flex items-center justify-center bg-gradient-to-br from-slate-800 to-slate-900">
                <div class="text-center text-white px-4">
                    <h1 class="font-display text-4xl md:text-5xl font-bold mb-4 tracking-tight">{{ $settings->hero_title ?? 'Welcome to ' . (currentLab()?->name ?? 'HealthMatrix') }}</h1>
                    <p class="text-xl text-slate-300">{{ $settings->hero_subtitle ?? 'Your trusted diagnostic center' }}</p>
                    <div class="mt-8">
                        <a href="{{ route('public.appointments.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold text-white" style="background-color: var(--primary-color);">
                            Book Appointment
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </section>

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

    {{-- Our Services --}}
    @if(($settings->show_services_section ?? true) && $tests->count() > 0)
    <section class="py-20 md:py-24 bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-slate-900 tracking-tight">{{ $settings->services_heading ?? 'Our Services' }}</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">{{ $settings->services_subtitle ?? 'Comprehensive diagnostic tests with accurate results and fast turnaround.' }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($tests as $test)
                    <article class="group bg-white rounded-2xl shadow-sm shadow-slate-200/60 border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/80 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                        <div class="aspect-[16/10] bg-slate-100 relative overflow-hidden">
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
                        <div class="p-6 lg:p-7 flex flex-col flex-1">
                            <h3 class="font-display text-xl font-semibold text-slate-900 mb-2">{{ $test->name }}</h3>
                            @if($test->description)
                                <p class="text-slate-600 text-sm leading-relaxed mb-5 flex-1 line-clamp-4">{{ $test->description }}</p>
                            @else
                                <p class="text-slate-500 text-sm mb-5 flex-1">Professional lab test with detailed reporting.</p>
                            @endif
                            <div class="flex items-center justify-between gap-4 pt-5 border-t border-slate-100">
                                <p class="text-xl font-bold" style="color: var(--primary-color);">₹{{ number_format($test->price, 2) }}</p>
                                <a href="{{ route('public.appointments.create') }}?test={{ urlencode($test->name) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl text-white transition-all hover:shadow-lg" style="background-color: var(--primary-color); box-shadow: 0 2px 10px color-mix(in srgb, var(--primary-color) 35%, transparent);">
                                    Book test
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
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
                            <cite class="font-display font-semibold text-slate-900 not-italic">{{ $t->name }}</cite>
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

    {{-- Offers / Promos --}}
    @if(($settings->show_promos_section ?? true) && isset($promos) && $promos->count() > 0)
    <section class="py-20 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-display text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-slate-900 tracking-tight">{{ $settings->promos_heading ?? 'Special Offers' }}</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">{{ $settings->promos_subtitle ?? 'Save on health checkups and diagnostic packages.' }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($promos as $promo)
                    @php
                        $shortText = $promo->short_text ?? '';
                        $isPlaceholder = strlen($shortText) > 0 && (strlen($shortText) < 15 || preg_match('/^[a-z;]+$/i', $shortText) || str_contains($shortText, 'lorem'));
                    @endphp
                    <article class="group relative bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm flex flex-col overflow-hidden hover:shadow-xl hover:shadow-slate-200/60 hover:border-slate-300/80 transition-all duration-300 border-l-4" style="border-left-color: var(--primary-color);">
                        <h3 class="font-display text-xl font-semibold text-slate-900 mb-3">{{ $promo->title }}</h3>
                        @if($shortText && !$isPlaceholder)
                            <p class="text-slate-600 text-sm flex-1 mb-5 leading-relaxed">{{ $shortText }}</p>
                        @elseif($shortText && $isPlaceholder)
                            <p class="text-slate-500 text-sm flex-1 mb-5 leading-relaxed italic">Get comprehensive health insights with our full body check package.</p>
                        @endif
                        @if($promo->valid_until)
                            <p class="text-xs text-slate-500 mb-4 font-medium">Valid until {{ $promo->valid_until->format('M d, Y') }}</p>
                        @endif
                        <a href="{{ ($promo->link ? (str_starts_with($promo->link, 'http') ? $promo->link : url($promo->link)) : route('public.appointments.create')) }}"
                           target="{{ ($promo->link && str_starts_with($promo->link, 'http')) ? '_blank' : '_self' }}"
                           rel="noopener"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold mt-auto w-fit transition-all group-hover:gap-3 bg-slate-100 hover:bg-slate-200/80" style="color: var(--primary-color);">
                            Book Now
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </article>
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
