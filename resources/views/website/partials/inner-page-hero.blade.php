{{--
  Inner page hero — matches public Test Packages hero (dark gradient, glow, wave).
  $settings, $title, optional $subtitle
  optional $minimalCtas (Book + text link)
  optional $showHeroCtas (default true) — set false for Contact-style hero (title + subtitle only)
  optional $overline — uppercase label; if empty, shows primary gradient bar instead
  optional $showBackLink (default true)
  optional $waveFillClass — Tailwind text-* for SVG wave fill (default text-white)
--}}
@php
    $sub = trim((string) ($subtitle ?? ''));
    $minimalCtas = $minimalCtas ?? false;
    $showHeroCtas = ($showHeroCtas ?? true) !== false;
    $overline = trim((string) ($overline ?? ''));
    $showBackLink = ($showBackLink ?? true) !== false;
    $waveFillClass = trim((string) ($waveFillClass ?? 'text-white'));
@endphp
<section class="relative overflow-hidden bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-60"></div>
    <div class="absolute top-1/2 -right-32 w-96 h-96 rounded-full blur-3xl opacity-30 pointer-events-none" style="background: color-mix(in srgb, var(--primary-color) 45%, transparent);"></div>
    <div class="absolute bottom-0 left-1/4 w-64 h-64 rounded-full blur-3xl opacity-20 pointer-events-none" style="background: color-mix(in srgb, var(--primary-color) 35%, #0f172a);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-12 md:pt-14 md:pb-16 lg:pt-16 lg:pb-20">
        @if($showBackLink)
            <nav class="mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-white/70 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Home
                </a>
            </nav>
        @endif

        <div class="max-w-3xl text-left">
            @if($overline !== '')
                <p class="text-sm font-semibold tracking-widest uppercase text-white/50 mb-3">{{ $overline }}</p>
            @else
                <div class="w-14 h-1.5 rounded-full mb-6 shadow-sm" style="background: linear-gradient(90deg, var(--primary-color), color-mix(in srgb, var(--primary-color) 35%, white));"></div>
            @endif
            <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-tight mb-4">{{ $title }}</h1>
            @if($sub !== '')
                <p class="text-lg md:text-xl text-slate-300 leading-relaxed">{{ $sub }}</p>
            @endif

            @if($showHeroCtas)
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    @if($minimalCtas)
                        <a href="{{ $settings->heroPrimaryUrl() }}" @if(str_starts_with($settings->heroPrimaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-semibold text-white shadow-lg transition-all hover:scale-[1.02]" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 78%, #1e1b4b) 100%); box-shadow: 0 4px 18px color-mix(in srgb, var(--primary-color) 35%, transparent);">
                            {{ $settings->heroPrimaryLabel() }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ $settings->heroSecondaryUrl() }}" @if(str_starts_with($settings->heroSecondaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="text-sm font-medium text-slate-300 hover:text-white underline underline-offset-4 decoration-white/40">
                            {{ $settings->heroSecondaryLabel() }} →
                        </a>
                    @else
                        <a href="{{ $settings->heroPrimaryUrl() }}" @if(str_starts_with($settings->heroPrimaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-white shadow-lg transition-all hover:scale-[1.02]" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 78%, #1e1b4b) 100%); box-shadow: 0 4px 18px color-mix(in srgb, var(--primary-color) 35%, transparent);">
                            {{ $settings->heroPrimaryLabel() }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ $settings->heroSecondaryUrl() }}" @if(str_starts_with($settings->heroSecondaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold bg-white/10 backdrop-blur-sm border border-white/25 text-white hover:bg-white/15 transition-all">
                            {{ $settings->heroSecondaryLabel() }}
                        </a>
                        @if($settings->heroTertiaryLabel() && $settings->heroTertiaryUrl())
                            <a href="{{ $settings->heroTertiaryUrl() }}" @if(str_starts_with($settings->heroTertiaryUrl(), 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold bg-white/10 backdrop-blur-sm border border-white/25 text-white hover:bg-white/15 transition-all">
                                {{ $settings->heroTertiaryLabel() }}
                            </a>
                        @endif
                        <a href="{{ route('public.packages.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-slate-200 border border-white/20 hover:bg-white/10 transition-all">
                            View packages
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Wave into page content (same curve as Test Packages) --}}
    <div class="relative w-full overflow-hidden leading-[0] {{ $waveFillClass }}">
        <svg class="relative block w-full h-[44px] sm:h-[56px] md:h-[72px]" viewBox="0 0 1440 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill="currentColor" d="M0,96L60,88C120,80,240,64,360,58.7C480,53,600,59,720,64C840,69,960,75,1080,69.3C1200,64,1320,48,1380,40L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"/>
        </svg>
    </div>
</section>
