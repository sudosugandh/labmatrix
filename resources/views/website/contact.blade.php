@extends('website.layout')

@section('content')
    @php
        $cPhone = trim((string) ($settings->contact_phone ?? '')) ?: trim((string) ($settings->phone ?? ''));
        $cEmail = trim((string) ($settings->contact_email ?? '')) ?: trim((string) ($settings->email ?? ''));
        $cAddress = trim((string) ($settings->address ?? ''));
        $hoursBlock = trim((string) ($settings->contact_working_hours ?? ''))
            ?: trim((string) ($settings->about_working_hours ?? ''))
            ?: \App\Models\WebsiteSetting::defaultContactWorkingHours();
        $telHref = $cPhone !== '' ? 'tel:' . preg_replace('/\s+/', '', $cPhone) : '';
        $bookUrl = $settings->heroPrimaryUrl();
        $waUrl = \App\Models\WebsiteSetting::whatsappUrlFromPhone($cPhone) ?? '';
        if ($waUrl === '') {
            $tertiary = trim((string) ($settings->hero_cta_tertiary_url ?? ''));
            if ($tertiary !== '' && (str_contains(strtolower($tertiary), 'wa.me') || str_contains(strtolower($tertiary), 'whatsapp'))) {
                $waUrl = \App\Models\WebsiteSetting::resolvePublicUrl($tertiary);
            }
        }
        $mapSrc = $cAddress !== '' ? 'https://maps.google.com/maps?q=' . rawurlencode($cAddress) . '&z=15&output=embed' : '';
    @endphp

    @include('website.partials.inner-page-hero', [
        'settings' => $settings,
        'title' => 'Get in Touch with Us',
        'subtitle' => 'Book an appointment, access your reports, or ask a question — our team is here to help you quickly.',
        'showHeroCtas' => false,
        'overline' => 'Contact',
    ])

    @if(session('success'))
        <div class="bg-emerald-50 border-b border-emerald-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-emerald-900 text-sm font-medium text-center">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-b border-red-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-red-800 text-sm font-medium text-center">{{ session('error') }}</div>
        </div>
    @endif

    {{-- Section 2: Contact actions --}}
    <section class="relative -mt-px py-10 md:py-12 bg-white border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm font-semibold uppercase tracking-wider text-slate-500 mb-6">Quick actions</p>
            <div class="flex flex-col sm:flex-row flex-wrap items-stretch justify-center gap-5 sm:gap-5 md:gap-6">
                @if($telHref !== '')
                    <a href="{{ $telHref }}" class="flex-1 min-w-[200px] max-w-md mx-auto sm:mx-0 inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl text-base font-bold text-white shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 75%, #1e1b4b) 100%);">
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Call now
                    </a>
                @endif
                @if($waUrl !== '')
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="flex-1 min-w-[200px] max-w-md mx-auto sm:mx-0 inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl text-base font-bold bg-[#25D366] text-white shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all">
                        <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                @endif
                <a href="{{ $bookUrl }}" @if(str_starts_with($bookUrl, 'http')) target="_blank" rel="noopener noreferrer" @endif class="flex-1 min-w-[200px] max-w-md mx-auto sm:mx-0 inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl text-base font-bold border-2 border-slate-300 bg-white text-slate-800 shadow-md hover:border-slate-400 hover:bg-slate-50 transition-all">
                    <svg class="w-6 h-6 shrink-0" style="color: var(--primary-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Book appointment
                </a>
            </div>
            @if($telHref === '' && $waUrl === '')
                <p class="text-center text-sm text-slate-500 mt-4">Add a phone number under Settings → Branding (and optionally a WhatsApp link under Hero buttons) to enable call and chat.</p>
            @endif
        </div>
    </section>

    {{-- Section 3: Form --}}
    <section class="py-14 md:py-16 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 text-center mb-3">Send us a message</h2>
            <p class="text-center text-sm font-medium text-slate-700 mb-2 max-w-lg mx-auto">We usually respond within 1–2 hours</p>
            <p class="text-center text-slate-500 text-sm mb-8">Leave your details and we will get back to you as soon as we can.</p>
            <form method="post" action="{{ route('public.contact.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6 md:p-9 shadow-sm space-y-5">
                @csrf
                <div>
                    <label for="contact_name" class="block text-sm font-medium text-slate-700 mb-1.5">Name</label>
                    <input type="text" name="name" id="contact_name" value="{{ old('name') }}" required maxlength="255" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-3 text-slate-900 @error('name') border-red-400 @enderror" autocomplete="name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
                    <input type="tel" name="phone" id="contact_phone" value="{{ old('phone') }}" required maxlength="80" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-3 text-slate-900 @error('phone') border-red-400 @enderror" autocomplete="tel">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="contact_message" class="block text-sm font-medium text-slate-700 mb-1.5">Message</label>
                    <textarea name="message" id="contact_message" rows="5" required maxlength="5000" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-3 text-slate-900 @error('message') border-red-400 @enderror" placeholder="How can we help?">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-base font-bold text-white shadow-lg transition-all hover:opacity-95" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 78%, #1e1b4b) 100%);">
                    Submit message
                </button>
            </form>
        </div>
    </section>

    {{-- Section 4 & 5: Details + hours --}}
    <section class="py-14 md:py-16 bg-white border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 text-center mb-10">Contact details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($cPhone !== '')
                    <a href="{{ $telHref }}" class="flex flex-col p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 hover:shadow-md transition-all">
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Phone</span>
                        <span class="mt-2 text-xl font-semibold" style="color: var(--primary-color);">{{ $cPhone }}</span>
                        <span class="mt-2 text-sm text-slate-600">Tap to call</span>
                    </a>
                @endif
                @if($cEmail !== '')
                    <a href="mailto:{{ $cEmail }}" class="flex flex-col p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 hover:shadow-md transition-all break-all">
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Email</span>
                        <span class="mt-2 text-lg font-semibold text-slate-900">{{ $cEmail }}</span>
                    </a>
                @endif
                @if($cAddress !== '')
                    <div class="flex flex-col p-6 rounded-2xl border border-slate-200 bg-slate-50/50 md:col-span-2 lg:col-span-1">
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Address</span>
                        <p class="mt-2 text-slate-700 leading-relaxed flex-1">{{ $cAddress }}</p>
                    </div>
                @endif
            </div>

            @if($hoursBlock !== '')
                <div class="mt-10 p-6 md:p-8 rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white">
                    <h3 class="font-display text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" style="color: var(--primary-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Working hours
                    </h3>
                    <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $hoursBlock }}</div>
                </div>
            @endif
        </div>
    </section>

    {{-- Section 6: Map --}}
    @if($mapSrc !== '')
    <section class="py-10 md:py-14 bg-slate-100 border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-xl font-bold text-slate-900 text-center mb-6">Find us</h2>
            <div class="rounded-2xl overflow-hidden shadow-lg border border-slate-200 bg-slate-200 aspect-[16/9] min-h-[280px] max-h-[420px]">
                <iframe title="Lab location map" class="w-full h-full min-h-[280px]" src="{{ $mapSrc }}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    @endif

    {{-- Section 7: Bottom CTA --}}
    <section class="py-14 md:py-18 relative overflow-hidden" style="background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 88%, #0f172a) 0%, var(--primary-color) 40%, color-mix(in srgb, var(--primary-color) 72%, #1e1b4b) 100%);">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(circle_at_70%_30%,white,transparent_50%)] pointer-events-none"></div>
        <div class="relative max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-display text-2xl md:text-3xl font-bold text-white tracking-tight leading-snug">Skip the Queue — Book Your Test in Minutes</h2>
            <p class="mt-3 text-white/85 text-sm md:text-base max-w-xl mx-auto">Book online or call us — same-day slots where available.</p>
            <div class="mt-8 flex flex-col sm:flex-row flex-wrap items-center justify-center gap-5 sm:gap-6">
                <a href="{{ $bookUrl }}" @if(str_starts_with($bookUrl, 'http')) target="_blank" rel="noopener noreferrer" @endif class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl text-base font-bold bg-white shadow-xl hover:scale-[1.02] transition-transform w-full sm:w-auto" style="color: var(--primary-color);">
                    Book appointment
                </a>
                @if($telHref !== '')
                    <a href="{{ $telHref }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl text-base font-bold text-white border-2 border-white/50 hover:bg-white/10 transition-colors w-full sm:w-auto">
                        Call now
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection
