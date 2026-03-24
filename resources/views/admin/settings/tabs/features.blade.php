{{-- Features Tab: CMS-style UI --}}
<div class="space-y-8">
    <div class="rounded-xl border border-violet-200/80 bg-violet-50/50 p-5 flex items-start gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </span>
        <p class="text-sm text-slate-700">Enable or disable features for your lab management system. Changes take effect immediately.</p>
    </div>

    <div class="space-y-4">
        @foreach([
            ['key' => 'online_booking_enabled', 'title' => 'Online Booking', 'desc' => 'Allow patients to book appointments online', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'default' => true],
            ['key' => 'online_reports_enabled', 'title' => 'Online Reports', 'desc' => 'Allow patients to view and download reports online', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'default' => true],
            ['key' => 'notifications_enabled', 'title' => 'Notifications', 'desc' => 'Enable system notifications for appointments, reports, etc.', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'default' => true],
            ['key' => 'analytics_enabled', 'title' => 'Analytics', 'desc' => 'Enable analytics and reporting features', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'default' => true],
            ['key' => 'payments_enabled', 'title' => 'Online payments', 'desc' => 'Enable patient-facing online bill payment. Configure gateways under Settings → Payments.', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'default' => false],
            ['key' => 'booking_require_payment', 'title' => 'Pay when booking', 'desc' => 'After booking, redirect to pay online before confirmation. Uses Razorpay or Stripe only (not PayPal). Under Payments, set gateway + API Key + API Secret.', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'default' => false],
        ] as $f)
            <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
                <div class="relative p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"></path></svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">{{ $f['title'] }}</h3>
                            <p class="text-sm text-slate-500">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="hidden" name="{{ $f['key'] }}" value="0">
                        <input type="checkbox" name="{{ $f['key'] }}" value="1" {{ old($f['key'], $settings->{$f['key']} ?? ($f['default'] ?? true)) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>
