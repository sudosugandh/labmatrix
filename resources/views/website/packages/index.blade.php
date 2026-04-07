@extends('website.layout')

@section('content')
{{-- Hero — aligned with Book Appointment page --}}
<section class="relative overflow-hidden bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
    <div class="absolute top-1/2 -right-32 w-96 h-96 rounded-full blur-3xl opacity-30 pointer-events-none" style="background: color-mix(in srgb, var(--primary-color) 40%, transparent);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20 md:pt-14 md:pb-24">
        <nav class="mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-white/70 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Home
            </a>
        </nav>

        <div class="max-w-3xl">
            <p class="text-sm font-semibold tracking-widest uppercase text-white/50 mb-3">Health &amp; wellness</p>
            <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight mb-4">Test Packages</h1>
            <p class="text-lg md:text-xl text-slate-300 leading-relaxed">Curated bundles at special prices — same quality as single tests, with clear savings and one simple booking flow.</p>
        </div>
    </div>

    {{-- Wave: soft transition into content (matches premium landing feel) --}}
    <div class="relative w-full overflow-hidden leading-[0] text-slate-50">
        <svg class="relative block w-full h-[44px] sm:h-[56px] md:h-[72px]" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill="currentColor" d="M0,96L60,88C120,80,240,64,360,58.7C480,53,600,59,720,64C840,69,960,75,1080,69.3C1200,64,1320,48,1380,40L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"/>
        </svg>
    </div>
</section>

{{-- Packages + sidebar (layout mirrors Book Appointment: 2/3 + 1/3) --}}
<section class="relative bg-gradient-to-b from-slate-50 to-white -mt-px pt-2 pb-16 md:pb-24">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-100/50 via-transparent to-transparent pointer-events-none"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    @if($packages->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-14">
            {{-- Cards column --}}
            <div class="lg:col-span-2">
                <div class="mb-8 md:mb-10">
                    <h2 class="font-display text-xl md:text-2xl font-bold text-slate-900 tracking-tight">Choose your package</h2>
                    <p class="mt-2 text-slate-600 text-sm md:text-base max-w-xl">Tap a card for full test lists and to book with this bundle pre-selected.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-7">
                    @foreach($packages as $package)
                        @php
                            $rawDesc = trim(strip_tags((string) ($package->description ?? '')));
                            $shortText = $rawDesc !== '' ? Str::limit($rawDesc, 110) : '';
                            $isPlaceholder = $shortText !== '' && (
                                strlen($shortText) < 18
                                || preg_match('/^[a-z\s;]+$/i', $shortText)
                                || preg_match('/hjkl|asdf|test@test|lorem/i', $shortText)
                            );
                        @endphp
                        <article class="group relative flex flex-col bg-white rounded-2xl border border-slate-200/90 shadow-md shadow-slate-200/50 overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/40 hover:-translate-y-0.5 hover:border-slate-300/80 ring-1 ring-slate-100/80">
                            {{-- Header: flex row keeps badge + bookmark in-flow so nothing overlaps the title below --}}
                            <div class="relative shrink-0 bg-gradient-to-r from-slate-100 via-sky-50/90 to-slate-100 border-b border-slate-200/60 px-4 pt-3 pb-3 sm:px-5">
                                <div class="flex items-start justify-between gap-3 min-h-[2.25rem]">
                                    <div class="flex-1 min-w-0 flex items-start">
                                        @if($package->discount > 0)
                                            <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-[11px] font-bold tracking-wide text-white shadow-sm" style="background-color: color-mix(in srgb, var(--primary-color) 88%, #0f172a);">
                                                {{ number_format($package->discount, 0) }}% off
                                            </span>
                                        @endif
                                    </div>
                                    <div class="w-9 h-9 shrink-0 rounded-full bg-white border border-slate-200/90 shadow-sm flex items-center justify-center text-slate-400 group-hover:text-slate-600 transition-colors" title="Save for later" aria-hidden="true">
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="relative z-10 flex flex-col flex-1 px-5 pt-4 pb-5 bg-white">
                                <h3 class="font-display text-lg font-bold text-slate-900 leading-snug mb-2 group-hover:text-slate-800 transition-colors break-words">
                                    {{ $package->name }}
                                </h3>

                                @if($shortText !== '' && ! $isPlaceholder)
                                    <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4">{{ $shortText }}</p>
                                @else
                                    <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4">Curated tests in one bundle — clear pricing, single visit, and digital reports when ready.</p>
                                @endif

                                <div class="mt-auto pt-4 border-t border-slate-100">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] mb-3" style="color: var(--primary-color);">
                                        {{ $package->tests_count }} {{ Str::plural('test', $package->tests_count) }} included
                                    </p>
                                    <div class="flex items-baseline gap-2 flex-wrap mb-4">
                                        @if($package->discount > 0)
                                            <span class="text-sm text-slate-400 line-through tabular-nums">₹{{ number_format($package->price, 2) }}</span>
                                        @endif
                                        <span class="text-xl sm:text-2xl font-bold tabular-nums tracking-tight" style="color: var(--primary-color);">₹{{ number_format($package->final_price, 2) }}</span>
                                    </div>
                                    <a href="{{ route('public.packages.show', $package) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl py-3 px-4 text-sm font-semibold text-white transition-all duration-200 hover:brightness-110 active:brightness-95 shadow-md" style="background-color: var(--primary-color); box-shadow: 0 4px 14px color-mix(in srgb, var(--primary-color) 28%, transparent);">
                                        View details
                                        <svg class="w-4 h-4 opacity-95 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            {{-- Sticky sidebar — same role as Appointment booking page --}}
            <aside class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <div class="rounded-2xl bg-white p-6 shadow-xl shadow-slate-200/50 border border-slate-200/70">
                        <h3 class="font-display text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl text-white text-sm font-bold shadow-md" style="background: var(--primary-color); box-shadow: 0 4px 12px color-mix(in srgb, var(--primary-color) 35%, transparent);">✓</span>
                            Why bundles?
                        </h3>
                        <ul class="space-y-3 text-slate-600 text-sm leading-relaxed">
                            <li class="flex gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--primary-color);" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>One visit, multiple tests — coordinated scheduling.</span>
                            </li>
                            <li class="flex gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--primary-color);" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Package pricing vs booking tests separately.</span>
                            </li>
                            <li class="flex gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--primary-color);" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Digital reports when ready — same as single tests.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 p-6 text-white border border-slate-700/50 shadow-lg">
                        <h3 class="font-display text-lg font-semibold mb-2">Ready to book?</h3>
                        <p class="text-slate-400 text-sm mb-5 leading-relaxed">Pick a package above, or schedule a general visit and add tests later.</p>
                        <a href="{{ route('public.appointments.create') }}" class="flex w-full items-center justify-center gap-2 rounded-xl py-3.5 px-4 text-sm font-semibold text-white shadow-lg transition-all hover:shadow-xl hover:-translate-y-0.5" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 82%, #1e1b4b) 100%); box-shadow: 0 4px 20px color-mix(in srgb, var(--primary-color) 40%, transparent);">
                            Book an appointment
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/80 p-5 text-center">
                        <p class="text-sm text-slate-600 mb-3">Not sure which package fits you?</p>
                        <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 text-sm font-semibold" style="color: var(--primary-color);">
                            Talk to us
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    @else
        <div class="relative max-w-lg mx-auto text-center py-16 px-6 rounded-2xl border border-dashed border-slate-200 bg-white/80 shadow-sm">
            <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h2 class="font-display text-xl font-semibold text-slate-900 mb-2">No packages yet</h2>
            <p class="text-slate-600 mb-6">Check back soon or contact us for available tests and appointments.</p>
            <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 text-sm font-semibold" style="color: var(--primary-color);">
                Contact us
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @endif
    </div>
</section>
@endsection
