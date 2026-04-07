{{--
    Promo offer card (homepage strip/grid) — aligned with public package cards.
    Expects: $promo, $promoHref, $promoExternal, optional $articleClass (extra classes on <article>)
--}}
@php
    $pc = \App\Support\PromoCardPresenter::for($promo);
@endphp
<article class="group relative flex flex-col bg-white rounded-2xl border border-slate-200/90 shadow-md shadow-slate-200/50 overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/40 hover:-translate-y-0.5 ring-1 ring-slate-100/80 {{ $articleClass ?? '' }}">
    <div class="relative shrink-0 bg-gradient-to-r from-slate-100 via-sky-50/90 to-slate-100 border-b border-slate-200/60 px-4 pt-3 pb-3 sm:px-5">
        <div class="flex items-start justify-between gap-3 min-h-[2.25rem]">
            <div class="flex-1 min-w-0 flex items-start flex-wrap gap-2">
                @if($pc['discount_percent'])
                    <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-[11px] font-bold tracking-wide text-white shadow-sm" style="background-color: color-mix(in srgb, var(--primary-color) 88%, #0f172a);">
                        {{ $pc['discount_percent'] }}% off
                    </span>
                @elseif($pc['ending_soon'])
                    <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-[11px] font-bold tracking-wide bg-amber-100 text-amber-900 border border-amber-200/80">
                        Ending soon
                    </span>
                @endif
            </div>
            <div class="w-9 h-9 shrink-0 rounded-full bg-white border border-slate-200/90 shadow-sm flex items-center justify-center text-slate-400 group-hover:text-slate-600 transition-colors" title="Save for later" aria-hidden="true">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
            </div>
        </div>
    </div>

    <div class="relative z-10 flex flex-col flex-1 px-5 pt-4 pb-5 bg-white">
        <h3 class="font-display text-base sm:text-lg font-bold text-slate-900 leading-snug mb-2 break-words group-hover:text-slate-800 transition-colors">
            {{ $pc['title'] }}
        </h3>

        @if($pc['short_text'] !== '' && ! $pc['is_placeholder_description'])
            <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4 flex-1">{{ $pc['short_text'] }}</p>
        @elseif($pc['short_text'] !== '' && $pc['is_placeholder_description'])
            <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4 flex-1">Limited-time pricing on this panel — book before the offer ends.</p>
        @else
            <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4 flex-1">Book online in minutes. Validity dates apply automatically.</p>
        @endif

        <div class="mt-auto pt-4 border-t border-slate-100">
            <p class="text-[10px] font-bold uppercase tracking-[0.12em] mb-3" style="color: var(--primary-color);">
                Featured offer
            </p>
            @if($promo->valid_until)
                <div class="mb-4">
                    <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-900 border border-amber-100">Ends {{ $promo->valid_until->format('M j, Y') }}</span>
                </div>
            @elseif($promo->valid_from)
                <p class="text-xs text-slate-600 mb-4">Starts {{ $promo->valid_from->format('M j, Y') }}</p>
            @else
                <div class="mb-4" aria-hidden="true"></div>
            @endif
            <a href="{{ $promoHref }}" @if($promoExternal) target="_blank" rel="noopener" @endif class="inline-flex w-full items-center justify-center gap-2 rounded-xl py-3 px-4 text-sm font-semibold text-white transition-all duration-200 hover:brightness-110 active:brightness-95 shadow-md" style="background-color: var(--primary-color); box-shadow: 0 4px 14px color-mix(in srgb, var(--primary-color) 28%, transparent);">
                Book now
                <svg class="w-4 h-4 opacity-95 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</article>
