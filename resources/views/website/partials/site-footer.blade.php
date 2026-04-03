{{--
  Premium footer + pre-footer CTA. Expects $settings (WebsiteSetting), lab from currentLab().
--}}
@php
    $lab = currentLab();
    $footerMenu = \App\Models\Menu::where('lab_id', currentLabId())->where('name', 'Footer Menu')->first();
    $footerMenuItems = $footerMenu ? $footerMenu->items : collect();

    $defaultQuickLinks = collect([
        (object) ['title' => 'Home', 'url' => route('home')],
        (object) ['title' => 'About', 'url' => url('/about-us')],
        (object) ['title' => 'Contact', 'url' => url('/contact')],
        (object) ['title' => 'Packages', 'url' => route('public.packages.index')],
        (object) ['title' => 'Book Appointment', 'url' => route('public.appointments.create')],
    ]);
    $quickLinks = $footerMenuItems->isNotEmpty() ? $footerMenuItems : $defaultQuickLinks;

    $footerTelDisplay = trim((string) ($settings->contact_phone ?? '')) ?: trim((string) ($settings->phone ?? ''));
    $footerTelHref = $footerTelDisplay !== '' ? 'tel:'.preg_replace('/\s+/', '', $footerTelDisplay) : '';

    $footerWaPhone = trim((string) ($settings->contact_phone ?? '')) ?: trim((string) ($settings->phone ?? ''));
    $footerWaUrl = \App\Models\WebsiteSetting::whatsappUrlFromPhone($footerWaPhone) ?? '';
    if ($footerWaUrl === '') {
        $ft = trim((string) ($settings->hero_cta_tertiary_url ?? ''));
        if ($ft !== '' && (str_contains(strtolower($ft), 'wa.me') || str_contains(strtolower($ft), 'whatsapp'))) {
            $footerWaUrl = \App\Models\WebsiteSetting::resolvePublicUrl($ft);
        }
    }

    $footerHours = trim((string) ($settings->contact_working_hours ?? ''))
        ?: trim((string) ($settings->about_working_hours ?? ''))
        ?: \App\Models\WebsiteSetting::defaultContactWorkingHours();

    $footerTagline = trim((string) ($settings->footer_tagline ?? ''))
        ?: trim((string) ($settings->hero_subtitle ?? ''))
        ?: 'Accurate diagnostics, compassionate care, and reports you can trust.';

    $preHeading = trim((string) ($settings->pre_footer_heading ?? '')) ?: 'Ready to book your test?';
    $preSubtitle = trim((string) ($settings->pre_footer_subtitle ?? ''))
        ?: 'Book online in minutes or call us — we are here to help.';

    $showTrustRow = ($settings->footer_show_trust_badges ?? true)
        && (($settings->footer_trust_nabl ?? true) || ($settings->footer_trust_iso ?? true) || ($settings->footer_trust_patients ?? true));

    $hasSocial = ! empty($settings->facebook_url) || ! empty($settings->twitter_url) || ! empty($settings->instagram_url)
        || ! empty($settings->linkedin_url) || ! empty($settings->youtube_url);
@endphp

@if(($settings->pre_footer_cta_enabled ?? true) && (($settings->online_booking_enabled ?? true) || $footerTelHref !== ''))
    <section class="relative border-t border-slate-200/80 bg-gradient-to-b from-slate-50 via-white to-slate-50/90" aria-labelledby="pre-footer-cta-heading">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[color:var(--primary-color)] to-transparent opacity-80" aria-hidden="true"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16 text-center">
            <h2 id="pre-footer-cta-heading" class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 tracking-tight [text-wrap:balance]">
                {{ $preHeading }}
            </h2>
            <p class="mt-3 text-slate-600 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
                {{ $preSubtitle }}
            </p>
            <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 sm:gap-4">
                @if($settings->online_booking_enabled ?? true)
                    <a href="{{ route('public.appointments.create') }}"
                       class="inline-flex min-h-[52px] items-center justify-center gap-2 rounded-2xl px-8 py-3.5 text-base font-bold text-white shadow-lg transition-all duration-200 hover:shadow-xl hover:scale-[1.02] active:scale-[0.99]"
                       style="background: linear-gradient(135deg, var(--primary-color) 0%, color-mix(in srgb, var(--primary-color) 78%, #1e1b4b) 100%); box-shadow: 0 8px 28px color-mix(in srgb, var(--primary-color) 35%, transparent);">
                        Book Appointment
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                @endif
                @if($footerTelHref !== '')
                    <a href="{{ $footerTelHref }}"
                       class="inline-flex min-h-[52px] items-center justify-center gap-2 rounded-2xl px-8 py-3.5 text-base font-bold border-2 border-slate-900/90 text-slate-900 bg-white/80 backdrop-blur-sm transition-all duration-200 hover:bg-slate-900 hover:text-white hover:border-slate-900">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Call Now
                    </a>
                @endif
            </div>
        </div>
    </section>
@endif

<footer class="relative bg-slate-950 text-white pb-[env(safe-area-inset-bottom,0px)] overflow-hidden" aria-label="Site footer">
    <div class="h-1 w-full bg-gradient-to-r from-[color:var(--primary-color)] via-[color:var(--secondary-color)] to-[color:var(--primary-color)] opacity-95" aria-hidden="true"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 md:pt-16 pb-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10">
            {{-- Column 1: brand --}}
            <div class="lg:col-span-4 space-y-5">
                <a href="{{ route('home') }}" class="inline-flex items-center rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950 focus-visible:ring-[color:var(--primary-color)]">
                    @if($settings->logo)
                        <span class="inline-flex rounded-xl bg-white/10 p-3 ring-1 ring-white/10">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($settings->logo) }}" alt="{{ $lab?->name ?? 'Lab' }} — Home" class="h-10 md:h-11 w-auto max-w-[200px] object-contain" width="200" height="48" decoding="async">
                        </span>
                    @else
                        <span class="font-display text-xl md:text-2xl font-bold tracking-tight text-white">{{ $lab?->name ?? 'HealthMatrix' }}</span>
                    @endif
                </a>
                <p class="text-slate-400 text-sm md:text-[0.9375rem] leading-relaxed max-w-sm">
                    {{ $footerTagline }}
                </p>
                @if($settings->address)
                    <p class="text-slate-500 text-sm leading-relaxed max-w-sm border-l-2 border-slate-700 pl-4">
                        {{ $settings->address }}
                    </p>
                @endif
                @if($showTrustRow)
                    <div class="flex flex-wrap gap-2 pt-2">
                        @if($settings->footer_trust_nabl ?? true)
                            <span class="inline-flex items-center rounded-full border border-slate-600/80 bg-slate-900/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-slate-200">NABL Certified</span>
                        @endif
                        @if($settings->footer_trust_iso ?? true)
                            <span class="inline-flex items-center rounded-full border border-slate-600/80 bg-slate-900/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-slate-200">ISO Certified</span>
                        @endif
                        @if($settings->footer_trust_patients ?? true)
                            <span class="inline-flex items-center rounded-full border border-slate-600/80 bg-slate-900/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-slate-200">Trusted by patients</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Column 2: contact + WhatsApp --}}
            <div class="lg:col-span-4 space-y-6">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 mb-4">Contact</h3>
                    <ul class="space-y-3 text-sm md:text-[0.9375rem]">
                        @if($footerTelDisplay !== '')
                            <li>
                                <span class="text-slate-500 block text-xs font-medium uppercase tracking-wide mb-0.5">Phone</span>
                                <a href="{{ $footerTelHref }}" class="text-white font-semibold hover:text-[color:var(--primary-color)] transition-colors">{{ $footerTelDisplay }}</a>
                            </li>
                        @endif
                        @if($settings->email)
                            <li>
                                <span class="text-slate-500 block text-xs font-medium uppercase tracking-wide mb-0.5">Email</span>
                                <a href="mailto:{{ $settings->email }}" class="text-slate-300 hover:text-white transition-colors break-all">{{ $settings->email }}</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="rounded-2xl border border-emerald-500/35 bg-gradient-to-br from-emerald-950/50 to-slate-900/80 p-5 shadow-lg shadow-emerald-900/10">
                    <h3 class="text-sm font-bold text-emerald-100 flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Chat with us on WhatsApp
                    </h3>
                    <p class="text-xs text-emerald-200/80 mb-4 leading-relaxed">Quick questions, slot help, or report guidance — message us anytime.</p>
                    @if($footerWaUrl !== '')
                        <a href="{{ $footerWaUrl }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex w-full sm:w-auto min-h-[48px] items-center justify-center gap-2 rounded-xl bg-[#25D366] px-5 py-3 text-sm font-bold text-white shadow-md transition-all duration-200 hover:bg-[#20bd5a] hover:shadow-lg hover:scale-[1.02] active:scale-[0.99]">
                            Open WhatsApp
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    @else
                        <p class="text-xs text-slate-500">Add a WhatsApp number in Settings (contact phone) or hero tertiary link to enable this button.</p>
                    @endif
                </div>

                <div class="pt-2 border-t border-slate-800/80">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 mb-3">Working hours</h3>
                    <p class="text-slate-400 text-sm leading-relaxed whitespace-pre-line">{{ $footerHours }}</p>
                </div>
            </div>

            {{-- Column 3: quick links --}}
            <div class="lg:col-span-4">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 mb-4">Quick links</h3>
                <nav aria-label="Footer quick links">
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                        @foreach($quickLinks as $item)
                            <li>
                                <a href="{{ $item->url }}" class="group inline-flex min-h-[44px] items-center py-2 text-slate-300 hover:text-white transition-colors">
                                    <span class="border-b border-transparent group-hover:border-[color:var(--primary-color)] transition-colors pb-0.5">{{ $item->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
                @if($settings->footer_text)
                    <p class="mt-8 text-slate-500 text-sm leading-relaxed border-t border-slate-800/80 pt-6">{{ $settings->footer_text }}</p>
                @endif
                @if(! empty($settings->footer_content))
                    <div class="footer-cms mt-4 text-slate-400 prose prose-invert prose-sm max-w-none">
                        {!! $settings->footer_content !!}
                    </div>
                @endif
            </div>
        </div>

        @if($hasSocial)
            <div class="mt-14 pt-10 border-t border-slate-800/90">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <p class="text-slate-500 text-sm font-semibold tracking-wide">Follow us</p>
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        @if(! empty($settings->facebook_url))
                            <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                               class="group inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900/90 text-slate-400 border border-slate-700/80 transition-all duration-200 hover:text-white hover:border-[color:var(--primary-color)] hover:bg-slate-800 hover:scale-110 hover:shadow-lg hover:shadow-[color:var(--primary-color)]/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--primary-color)] focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950">
                                <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                        @if(! empty($settings->twitter_url))
                            <a href="{{ $settings->twitter_url }}" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)"
                               class="group inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900/90 text-slate-400 border border-slate-700/80 transition-all duration-200 hover:text-white hover:border-slate-300 hover:bg-slate-800 hover:scale-110 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950">
                                <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        @endif
                        @if(! empty($settings->instagram_url))
                            <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                               class="group inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900/90 text-slate-400 border border-slate-700/80 transition-all duration-200 hover:text-pink-400 hover:border-pink-500/50 hover:bg-slate-800 hover:scale-110 hover:shadow-lg hover:shadow-pink-500/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-pink-500 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950">
                                <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        @endif
                        @if(! empty($settings->linkedin_url))
                            <a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
                               class="group inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900/90 text-slate-400 border border-slate-700/80 transition-all duration-200 hover:text-sky-300 hover:border-sky-500/50 hover:bg-slate-800 hover:scale-110 hover:shadow-lg hover:shadow-sky-500/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950">
                                <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        @endif
                        @if(! empty($settings->youtube_url))
                            <a href="{{ $settings->youtube_url }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"
                               class="group inline-flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900/90 text-slate-400 border border-slate-700/80 transition-all duration-200 hover:text-red-400 hover:border-red-500/50 hover:bg-slate-800 hover:scale-110 hover:shadow-lg hover:shadow-red-500/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950">
                                <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-12 pt-8 border-t border-slate-800/80 text-center">
            <p class="text-slate-500 text-sm">
                &copy; {{ date('Y') }} {{ $lab?->name ?? 'HealthMatrix' }}. All rights reserved.
            </p>
        </div>
    </div>
</footer>
