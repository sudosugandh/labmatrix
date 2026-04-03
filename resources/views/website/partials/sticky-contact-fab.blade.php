{{-- WhatsApp (first) + Call: icon-only circles; mobile = centered row; md+ = bottom-right stack --}}
@php
    $fabPhone = trim((string) ($settings->contact_phone ?? '')) ?: trim((string) ($settings->phone ?? ''));
    $fabTel = $fabPhone !== '' ? 'tel:' . preg_replace('/\s+/', '', $fabPhone) : '';
    $fabWa = \App\Models\WebsiteSetting::whatsappUrlFromPhone($fabPhone) ?? '';
    if ($fabWa === '') {
        $t = trim((string) ($settings->hero_cta_tertiary_url ?? ''));
        if ($t !== '' && (str_contains(strtolower($t), 'wa.me') || str_contains(strtolower($t), 'whatsapp'))) {
            $fabWa = \App\Models\WebsiteSetting::resolvePublicUrl($t);
        }
    }
@endphp
@if($fabTel !== '' || $fabWa !== '')
<nav class="fixed z-40 flex pointer-events-none gap-3 [&>a]:pointer-events-auto
    max-md:left-1/2 max-md:-translate-x-1/2 max-md:right-auto max-md:flex-row max-md:justify-center
    max-md:bottom-[max(0.65rem,env(safe-area-inset-bottom,0px))]
    md:flex-col md:items-end md:justify-start md:w-auto md:left-auto md:translate-x-0
    md:right-[max(1.25rem,env(safe-area-inset-right,0px))]
    md:bottom-[max(1.25rem,env(safe-area-inset-bottom,0px))]"
     aria-label="Quick contact">
    @if($fabWa !== '')
        <a href="{{ $fabWa }}" target="_blank" rel="noopener noreferrer"
           aria-label="Chat on WhatsApp"
           class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-full text-white shadow-lg hover:shadow-xl active:scale-[0.96] transition-all bg-[#25D366] ring-2 ring-white/40 hover:bg-[#20bd5a] touch-manipulation">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
    @endif
    @if($fabTel !== '')
        <a href="{{ $fabTel }}"
           aria-label="Call now"
           class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-full text-white shadow-lg hover:shadow-xl active:scale-[0.96] transition-all ring-2 ring-white/40 touch-manipulation"
           style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e1b4b) 100%);">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
        </a>
    @endif
</nav>
@endif
