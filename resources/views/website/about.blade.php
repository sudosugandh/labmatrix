@extends('website.layout')

@section('content')
    @php
        $brand = trim((string) ($settings->brand_name ?? '')) ?: (currentLab()?->name ?? '');
        $heroSubtitle = $brand !== ''
            ? "{$brand} — precision diagnostics, dependable results, and care that puts patients first."
            : 'Precision diagnostics, dependable results, and care that puts patients first.';
        $whyCards = $settings->aboutWhyCardsForDisplay();
        $stats = $settings->aboutStatsDisplay();
        $cPhone = trim((string) ($settings->contact_phone ?? '')) ?: trim((string) ($settings->phone ?? ''));
        $cAddress = trim((string) ($settings->address ?? ''));
        $hoursRaw = trim((string) ($settings->about_working_hours ?? ''));
        $introHtml = trim((string) ($settings->about_intro ?? ''));
    @endphp

    @include('website.partials.inner-page-hero', [
        'settings' => $settings,
        'title' => $page->title,
        'subtitle' => $heroSubtitle,
        'minimalCtas' => true,
        'overline' => 'About us',
    ])

    {{-- Section 1: Strong intro --}}
    <section class="relative -mt-px py-14 md:py-18 bg-white border-b border-slate-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left">
            <p class="text-sm font-semibold uppercase tracking-wider mb-3" style="color: var(--primary-color);">Why patients choose us</p>
            <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-900 tracking-tight leading-snug mb-6">
                Clinical accuracy you can build treatment plans on
            </h2>
            @if($introHtml !== '')
                <div class="cms-content about-intro prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed text-left">
                    {!! $introHtml !!}
                </div>
            @else
                <div class="space-y-4 text-slate-600 text-lg leading-relaxed">
                    <p>We combine <strong class="text-slate-800">rigorous quality systems</strong>, <strong class="text-slate-800">modern instrumentation</strong>, and an experienced clinical team so every report reflects what is true in the sample — not guesswork.</p>
                    <p>From routine wellness panels to specialised investigations, we focus on <strong class="text-slate-800">reliability</strong>, <strong class="text-slate-800">traceability</strong>, and <strong class="text-slate-800">clear communication</strong> with referring doctors and patients.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Section 2: Why choose us --}}
    <section class="py-16 md:py-20 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 md:mb-14">
                <h2 class="font-display text-3xl md:text-[2rem] font-bold text-slate-900 tracking-tight">Why choose us</h2>
                <p class="mt-3 text-slate-600">Everything we do is designed to earn your trust — before, during, and after your test.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($whyCards as $idx => $card)
                    @php
                        $icons = [
                            'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                            'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                            'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517L6 18.089V5a2 2 0 012-2h6a2 2 0 012 2v10z',
                            'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                            'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                        ];
                        $d = $icons[$idx % count($icons)];
                    @endphp
                    <article class="group rounded-2xl border border-slate-200/90 bg-white p-6 md:p-7 shadow-sm hover:shadow-lg hover:border-slate-300/80 transition-all duration-300 flex flex-col">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl text-white mb-4 shadow-md" style="background: linear-gradient(135deg, var(--primary-color), color-mix(in srgb, var(--primary-color) 72%, #1e1b4b));">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $d }}"></path></svg>
                        </span>
                        <h3 class="font-display text-lg font-bold text-slate-900 mb-2">{{ $card['title'] }}</h3>
                        @if($card['body'] !== '')
                            <p class="text-slate-600 text-sm leading-relaxed flex-1">{{ $card['body'] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Section 3: Stats --}}
    <section class="py-14 md:py-18 bg-slate-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-25 pointer-events-none" style="background: radial-gradient(ellipse 60% 80% at 50% 100%, color-mix(in srgb, var(--primary-color) 55%, transparent), transparent);"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm font-semibold uppercase tracking-widest text-slate-400 mb-10">Numbers that reflect real trust</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8 text-center">
                <div>
                    <p class="font-display text-4xl md:text-5xl font-bold tabular-nums" style="color: color-mix(in srgb, var(--primary-color) 92%, white);">{{ $stats['tests'] }}</p>
                    <p class="mt-2 text-slate-400 text-sm font-medium uppercase tracking-wide">Tests processed</p>
                </div>
                <div>
                    <p class="font-display text-4xl md:text-5xl font-bold tabular-nums" style="color: color-mix(in srgb, var(--primary-color) 92%, white);">{{ $stats['patients'] }}</p>
                    <p class="mt-2 text-slate-400 text-sm font-medium uppercase tracking-wide">Patients served</p>
                </div>
                <div>
                    <p class="font-display text-4xl md:text-5xl font-bold tabular-nums" style="color: color-mix(in srgb, var(--primary-color) 92%, white);">{{ $stats['years'] }}</p>
                    <p class="mt-2 text-slate-400 text-sm font-medium uppercase tracking-wide">Years of experience</p>
                </div>
            </div>
            <p class="text-center text-xs text-slate-500 mt-10 max-w-xl mx-auto">Replace headline figures anytime under <strong class="text-slate-400">Settings → CMS → About page</strong>.</p>
        </div>
    </section>

    {{-- Section 4: Team --}}
    @if(($settings->spotlight_enabled ?? false) && trim((string)($settings->spotlight_name ?? '')) !== '')
    <section class="py-16 md:py-20 bg-white border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <p class="text-sm font-semibold uppercase tracking-wider mb-2" style="color: var(--primary-color);">Leadership &amp; clinical oversight</p>
                <h2 class="font-display text-3xl font-bold text-slate-900 tracking-tight">Our team</h2>
            </div>
            <div class="rounded-3xl border border-slate-200/80 bg-gradient-to-b from-slate-50/80 to-white p-8 md:p-12 shadow-sm">
                <div class="flex flex-col md:flex-row items-center gap-10 md:gap-14">
                    <div class="shrink-0">
                        @if($settings->spotlight_image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($settings->spotlight_image) }}" alt="{{ $settings->spotlight_name }}" class="w-44 h-44 md:w-52 md:h-52 rounded-2xl object-cover shadow-xl ring-4 ring-white" loading="lazy">
                        @else
                            <div class="w-44 h-44 md:w-52 md:h-52 rounded-2xl flex items-center justify-center text-white text-4xl font-display font-bold shadow-xl" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e1b4b) 100%);">
                                {{ strtoupper(substr($settings->spotlight_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-center md:text-left flex-1">
                        <h3 class="font-display text-2xl md:text-3xl font-bold text-slate-900">{{ $settings->spotlight_name }}</h3>
                        @if(!empty($settings->spotlight_credentials))
                            <p class="mt-2 text-lg text-slate-600 font-medium">{{ $settings->spotlight_credentials }}</p>
                        @endif
                        @if(!empty($settings->spotlight_experience))
                            <p class="mt-3 inline-flex items-center gap-2 text-sm font-semibold px-3 py-1.5 rounded-lg bg-slate-100 text-slate-800">
                                <svg class="w-4 h-4 shrink-0" style="color: var(--primary-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $settings->spotlight_experience }}
                            </p>
                        @endif
                        @if(!empty($settings->spotlight_tagline))
                            <p class="mt-5 text-slate-600 leading-relaxed text-lg">{{ $settings->spotlight_tagline }}</p>
                        @endif
                        @php $teamTrust = trim((string) ($settings->about_team_trust_line ?? '')); @endphp
                        @if($teamTrust !== '')
                            <p class="mt-6 text-slate-700 leading-relaxed border-l-4 pl-4 py-1" style="border-color: var(--primary-color);">{{ $teamTrust }}</p>
                        @else
                            <p class="mt-6 text-slate-600 leading-relaxed">Our pathologists and lab staff follow strict protocols so you receive results you can rely on for follow-up care.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="py-14 md:py-16 bg-white border-b border-slate-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-2xl font-bold text-slate-900">Clinical team</h2>
            <p class="mt-4 text-slate-600 leading-relaxed">Our pathologists and laboratory professionals work to the same standard on every sample: accurate results, documented quality checks, and respect for your time. Enable <strong class="text-slate-800">Doctor / centre spotlight</strong> in Settings → CMS to feature a lead clinician on this page.</p>
        </div>
    </section>
    @endif

    {{-- Section 5: Visit us --}}
    @if($cPhone !== '' || $cAddress !== '' || $hoursRaw !== '')
    <section class="py-14 md:py-18 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-900 text-center mb-10">Visit &amp; hours</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if($cPhone !== '')
                    <a href="tel:{{ preg_replace('/\s+/', '', $cPhone) }}" class="flex flex-col p-6 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all">
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Phone</span>
                        <span class="mt-2 text-xl font-semibold text-slate-900" style="color: var(--primary-color);">{{ $cPhone }}</span>
                        <span class="mt-2 text-sm text-slate-500">Call for appointments &amp; enquiries</span>
                    </a>
                @endif
                @if($cAddress !== '')
                    @php $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($cAddress); @endphp
                    <div class="flex flex-col p-6 rounded-2xl bg-white border border-slate-200 shadow-sm md:col-span-1">
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Address</span>
                        <p class="mt-2 text-slate-700 leading-relaxed flex-1">{{ $cAddress }}</p>
                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="mt-4 text-sm font-semibold inline-flex items-center gap-1" style="color: var(--primary-color);">
                            Get directions
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                @endif
                @if($hoursRaw !== '')
                    <div class="flex flex-col p-6 rounded-2xl bg-white border border-slate-200 shadow-sm">
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Working hours</span>
                        <div class="mt-2 text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $hoursRaw }}</div>
                    </div>
                @elseif($cPhone !== '' || $cAddress !== '')
                    <div class="flex flex-col p-6 rounded-2xl bg-slate-100/80 border border-dashed border-slate-200 justify-center">
                        <p class="text-sm text-slate-500">Add working hours in <strong class="text-slate-700">Settings → CMS → About page</strong> so patients know when to visit or call.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Section 6: CTA --}}
    <section class="py-16 md:py-20 relative overflow-hidden" style="background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 92%, #0f172a) 0%, var(--primary-color) 45%, color-mix(in srgb, var(--primary-color) 70%, #1e1b4b) 100%);">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_30%_20%,white,transparent_45%)] pointer-events-none"></div>
        <div class="relative max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-display text-3xl md:text-4xl font-bold text-white tracking-tight">Book your test today</h2>
            <p class="mt-4 text-lg text-white/85">Same trusted lab — simple online booking. Walk-ins welcome where available.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ $settings->heroPrimaryUrl() }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl text-base font-bold bg-white shadow-xl hover:scale-[1.02] transition-transform" style="color: var(--primary-color);">
                    Book appointment
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 px-6 py-4 rounded-xl text-base font-semibold text-white border-2 border-white/40 hover:bg-white/10 transition-colors">
                    Contact us
                </a>
            </div>
        </div>
    </section>
@endsection
