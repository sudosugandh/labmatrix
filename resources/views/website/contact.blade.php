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

    {{-- Form + contact details side by side --}}
    <section class="relative py-14 md:py-20 bg-gradient-to-b from-slate-50 via-white to-slate-50 border-b border-slate-100 overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,color-mix(in_srgb,var(--primary-color)_12%,transparent),transparent)]" aria-hidden="true"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center lg:text-left mb-10 lg:mb-12">
                <p class="text-sm font-semibold uppercase tracking-wider text-slate-500 mb-2">Message us</p>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">We’re here to help</h2>
                <p class="mt-3 text-slate-600 text-base max-w-2xl mx-auto lg:mx-0">Write to us or use the details beside — we usually respond within <span class="font-semibold text-slate-800">1–2 hours</span>.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-10 lg:gap-12 xl:gap-16 items-start">
                {{-- Left: form --}}
                <div>
                    <div class="rounded-3xl bg-white p-6 sm:p-8 lg:p-10 shadow-[0_2px_8px_-2px_rgba(15,23,42,0.08),0_24px_48px_-12px_rgba(15,23,42,0.12)] border border-slate-100 ring-1 ring-slate-900/[0.04]">
                        <div class="flex items-start gap-4 mb-8">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-white shadow-md" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 72%, #1e1b4b) 100%);">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-display text-xl font-bold text-slate-900">Send us a message</h3>
                                <p class="mt-1 text-sm text-slate-500 leading-relaxed">Leave your details and we’ll get back to you as soon as we can.</p>
                            </div>
                        </div>
                        <form method="post" action="{{ route('public.contact.store') }}" class="space-y-5">
                            @csrf
                            <div>
                                <label for="contact_name" class="block text-sm font-semibold text-slate-800 mb-2">Name</label>
                                <input type="text" name="name" id="contact_name" value="{{ old('name') }}" required maxlength="255" class="w-full rounded-xl border-slate-200 bg-slate-50/50 shadow-sm focus:bg-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/15 px-4 py-3.5 text-slate-900 transition-colors @error('name') border-red-400 ring-red-200 @enderror" autocomplete="name" placeholder="Your full name">
                                @error('name')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-sm font-semibold text-slate-800 mb-2">Phone</label>
                                <input type="tel" name="phone" id="contact_phone" value="{{ old('phone') }}" required maxlength="80" class="w-full rounded-xl border-slate-200 bg-slate-50/50 shadow-sm focus:bg-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/15 px-4 py-3.5 text-slate-900 transition-colors @error('phone') border-red-400 ring-red-200 @enderror" autocomplete="tel" placeholder="Mobile number">
                                @error('phone')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="contact_message" class="block text-sm font-semibold text-slate-800 mb-2">Message</label>
                                <textarea name="message" id="contact_message" rows="5" required maxlength="5000" class="w-full rounded-xl border-slate-200 bg-slate-50/50 shadow-sm focus:bg-white focus:border-violet-500 focus:ring-2 focus:ring-violet-500/15 px-4 py-3.5 text-slate-900 transition-colors resize-y min-h-[140px] @error('message') border-red-400 ring-red-200 @enderror" placeholder="How can we help?">{{ old('message') }}</textarea>
                                @error('message')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl text-base font-bold text-white shadow-lg shadow-slate-900/10 transition-all hover:shadow-xl hover:brightness-[1.03] active:scale-[0.99]" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 76%, #1e1b4b) 100%);">
                                <span>Submit message</span>
                                <svg class="w-5 h-5 shrink-0 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right: contact details + hours --}}
                <div class="lg:sticky lg:top-28 space-y-6">
                    <div class="rounded-3xl overflow-hidden shadow-[0_2px_8px_-2px_rgba(15,23,42,0.08),0_20px_40px_-12px_rgba(15,23,42,0.14)] border border-slate-200/80 ring-1 ring-slate-900/[0.03] bg-white">
                        <div class="relative overflow-hidden px-6 py-5 sm:px-8 sm:py-6 text-white">
                            <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 55%, #0f172a) 100%);" aria-hidden="true"></div>
                            <div class="absolute inset-0 z-[1] opacity-25 bg-[radial-gradient(circle_at_100%_0%,white,transparent_55%)] pointer-events-none" aria-hidden="true"></div>
                            <div class="relative z-10">
                                <h3 class="font-display text-xl font-bold">Contact details</h3>
                                <p class="mt-1 text-sm text-white/85">Reach us directly anytime</p>
                            </div>
                        </div>
                        <div class="p-6 sm:p-8 space-y-1 bg-gradient-to-b from-white to-slate-50/80">
                            @if($cPhone !== '')
                                <a href="{{ $telHref }}" class="group flex gap-4 p-4 rounded-2xl hover:bg-white hover:shadow-md border border-transparent hover:border-slate-100 transition-all">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:scale-105 transition-transform" style="color: var(--primary-color); background: color-mix(in srgb, var(--primary-color) 12%, white);">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Phone</span>
                                        <span class="mt-1 block text-lg font-bold text-slate-900 group-hover:underline decoration-2 underline-offset-2" style="text-decoration-color: color-mix(in srgb, var(--primary-color) 50%, transparent);">{{ $cPhone }}</span>
                                        <span class="mt-0.5 text-sm text-slate-500">Tap to call</span>
                                    </span>
                                </a>
                            @endif
                            @if($cEmail !== '')
                                <a href="mailto:{{ $cEmail }}" class="group flex gap-4 p-4 rounded-2xl hover:bg-white hover:shadow-md border border-transparent hover:border-slate-100 transition-all">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl group-hover:scale-105 transition-transform" style="color: var(--primary-color); background: color-mix(in srgb, var(--primary-color) 12%, white);">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Email</span>
                                        <span class="mt-1 block text-base sm:text-lg font-semibold text-slate-900 break-all group-hover:underline">{{ $cEmail }}</span>
                                    </span>
                                </a>
                            @endif
                            @if($cAddress !== '')
                                <div class="flex gap-4 p-4 rounded-2xl bg-slate-50/80 border border-slate-100">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white text-slate-600 shadow-sm border border-slate-100" style="color: var(--primary-color);">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Address</span>
                                        <p class="mt-1.5 text-slate-700 leading-relaxed">{{ $cAddress }}</p>
                                    </span>
                                </div>
                            @endif
                            @if($cPhone === '' && $cEmail === '' && $cAddress === '')
                                <p class="text-sm text-slate-500 text-center py-6 px-2">Add phone, email, or address in <strong class="text-slate-700">Settings → Contact</strong> to show them here.</p>
                            @endif
                        </div>
                    </div>

                    @if($hoursBlock !== '')
                        <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-md shadow-slate-900/[0.04] ring-1 ring-slate-900/[0.03]">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-white shadow-sm" style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 70%, #312e81) 100%);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                                <div>
                                    <h3 class="font-display text-lg font-bold text-slate-900">Working hours</h3>
                                    <p class="text-xs text-slate-500 font-medium">When we’re available</p>
                                </div>
                            </div>
                            <div class="pl-0 sm:pl-14 text-slate-700 text-sm leading-relaxed whitespace-pre-line rounded-2xl bg-slate-50/90 border border-slate-100/80 px-4 py-4 sm:px-5 sm:py-4">{{ $hoursBlock }}</div>
                        </div>
                    @endif
                </div>
            </div>
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
