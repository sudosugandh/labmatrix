{{-- Expects $settings (WebsiteSetting) --}}
@if(($settings->trust_strip_enabled ?? false))
    @php $trustItems = $settings->trustStripItemsForDisplay(); @endphp
    @if(count($trustItems) > 0)
    <section class="border-b border-slate-200/80 bg-white py-6 md:py-8" aria-label="Accreditations and trust badges">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Mobile: 2-column grid (full width, balanced); md+: centered flex row --}}
            <ul class="grid gap-3 sm:gap-4 {{ count($trustItems) === 1 ? 'grid-cols-1 max-w-xs mx-auto' : 'grid-cols-2' }} md:flex md:flex-wrap md:justify-center md:max-w-none md:mx-0 md:gap-6 lg:gap-8 list-none p-0 m-0">
                @foreach($trustItems as $badge)
                    @php
                        $wrap = !empty($badge['url']);
                        $href = $wrap ? \App\Models\WebsiteSetting::resolvePublicUrl($badge['url']) : '#';
                    @endphp
                    <li @class([
                        'min-w-0 md:flex-1 md:min-w-[140px] md:max-w-[220px]',
                        'max-md:col-span-2 max-md:justify-self-center max-md:max-w-[min(100%,280px)]' => $loop->last && count($trustItems) % 2 === 1,
                    ])>
                        @if($wrap)
                            <a href="{{ $href }}" @if(str_starts_with($href, 'http')) target="_blank" rel="noopener noreferrer" @endif class="flex flex-col items-center text-center gap-2 p-4 rounded-2xl border border-slate-100 bg-slate-50/80 hover:border-violet-200 hover:bg-violet-50/40 transition-all h-full">
                        @else
                            <div class="flex flex-col items-center text-center gap-2 p-4 rounded-2xl border border-slate-100 bg-slate-50/80 h-full">
                        @endif
                            @if(!empty($badge['image']))
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($badge['image']) }}" alt="{{ $badge['label'] }}" class="h-12 w-auto max-w-[120px] object-contain" loading="lazy" decoding="async">
                            @else
                                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-sm border border-slate-100" style="color: var(--primary-color);" aria-hidden="true">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </span>
                            @endif
                            <span class="text-sm font-semibold text-slate-800 leading-snug">{{ $badge['label'] }}</span>
                        @if($wrap)
                            </a>
                        @else
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    @endif
@endif
