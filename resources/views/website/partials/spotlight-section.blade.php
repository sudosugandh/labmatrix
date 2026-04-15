{{-- Expects $settings; optional $compact (bool) for inner pages --}}
@php $compact = $compact ?? false; @endphp
@if(($settings->spotlight_enabled ?? false) && trim((string)($settings->spotlight_name ?? '')) !== '')
@if($compact)
<section class="py-12 md:py-16 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200/80 bg-white p-8 md:p-10 shadow-sm shadow-slate-200/40">
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                <div class="shrink-0">
                    @if($settings->spotlight_image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($settings->spotlight_image) }}" alt="{{ $settings->spotlight_name }}" class="w-36 h-36 md:w-44 md:h-44 rounded-2xl object-cover shadow-lg ring-4 ring-slate-50" loading="lazy">
                    @else
                        <div class="w-36 h-36 md:w-44 md:h-44 rounded-2xl flex items-center justify-center text-white text-3xl md:text-4xl font-display font-bold shadow-lg" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e1b4b) 100%);">
                            {{ strtoupper(substr($settings->spotlight_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="text-center md:text-left flex-1">
                    <p class="text-sm font-semibold uppercase tracking-wider mb-2" style="color: var(--primary-color);">Meet our team</p>
                    <h2 class="font-display text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">{{ $settings->spotlight_name }}</h2>
                    @if(!empty($settings->spotlight_credentials))
                        <p class="mt-2 text-base md:text-lg text-slate-600 font-medium">{{ $settings->spotlight_credentials }}</p>
                    @endif
                    @if(!empty($settings->spotlight_tagline))
                        <p class="mt-4 text-slate-600 leading-relaxed max-w-xl mx-auto md:mx-0">{{ $settings->spotlight_tagline }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@else
<section class="py-16 md:py-24 bg-gradient-to-b from-slate-50/70 to-white border-y border-slate-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200/80 bg-white/90 backdrop-blur-sm p-8 md:p-12 shadow-md shadow-slate-200/40 flex flex-col md:flex-row items-center gap-10 md:gap-14">
            <div class="shrink-0">
                @if($settings->spotlight_image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($settings->spotlight_image) }}" alt="{{ $settings->spotlight_name }}" class="w-40 h-40 md:w-48 md:h-48 rounded-3xl object-cover shadow-xl ring-4 ring-white" loading="lazy">
                @else
                    <div class="w-40 h-40 md:w-48 md:h-48 rounded-3xl flex items-center justify-center text-white text-4xl font-display font-bold shadow-xl" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e1b4b) 100%);">
                        {{ strtoupper(substr($settings->spotlight_name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="text-center md:text-left flex-1 min-w-0">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] mb-2" style="color: var(--primary-color);">Meet our team</p>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">{{ $settings->spotlight_name }}</h2>
                @if(!empty($settings->spotlight_credentials))
                    <p class="mt-2 text-lg text-slate-600 font-medium">{{ $settings->spotlight_credentials }}</p>
                @endif
                @if(!empty($settings->spotlight_tagline))
                    <p class="mt-4 text-slate-600 text-lg leading-relaxed max-w-xl mx-auto md:mx-0">{{ $settings->spotlight_tagline }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
@endif
