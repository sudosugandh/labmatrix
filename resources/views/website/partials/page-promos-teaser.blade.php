{{-- $settings, $promos (collection, may be empty) --}}
@if(isset($promos) && $promos->isNotEmpty() && ($settings->show_promos_section ?? true))
<section class="py-10 md:py-12 bg-white border-b border-slate-100" aria-label="Current offers">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="font-display text-xl md:text-2xl font-bold text-slate-900 tracking-tight">{{ $settings->promos_today_heading ?? 'Current offers' }}</h2>
                <p class="mt-1 text-sm text-slate-600 max-w-xl">{{ $settings->promos_today_subtitle ?? 'Limited-time packages — validity dates apply automatically.' }}</p>
            </div>
            <a href="{{ route('home') }}#offers" class="text-sm font-semibold shrink-0 hover:underline inline-flex items-center gap-1" style="color: var(--primary-color);">
                See all on homepage
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        <div class="flex gap-4 overflow-x-auto pb-2 snap-x snap-mandatory -mx-4 px-4 md:mx-0 md:px-0">
            @foreach($promos->take(4) as $promo)
                @php
                    $promoHref = $promo->link ? (str_starts_with($promo->link, 'http') ? $promo->link : url($promo->link)) : route('public.appointments.create');
                    $promoExternal = str_starts_with($promo->link ?? '', 'http');
                @endphp
                <article class="snap-start shrink-0 w-[min(100%,260px)] rounded-2xl border border-slate-200/90 bg-gradient-to-b from-slate-50/80 to-white p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                    <h3 class="font-display font-semibold text-slate-900 text-sm leading-snug mb-2 line-clamp-2">{{ $promo->title }}</h3>
                    @if($promo->short_text)
                        <p class="text-xs text-slate-600 line-clamp-2 flex-1 mb-3">{{ $promo->short_text }}</p>
                    @endif
                    @if($promo->valid_until)
                        <span class="inline-flex w-fit text-xs font-semibold px-2 py-0.5 rounded-md bg-amber-50 text-amber-900 border border-amber-100/80">Ends {{ $promo->valid_until->format('M j') }}</span>
                    @endif
                    <a href="{{ $promoHref }}" @if($promoExternal) target="_blank" rel="noopener" @endif class="mt-3 inline-flex items-center gap-1 text-xs font-semibold" style="color: var(--primary-color);">
                        Book now
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
