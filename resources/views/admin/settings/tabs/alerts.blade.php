{{-- Alerts Tab: CMS-style UI --}}
<div class="space-y-8">
    <div class="rounded-xl border border-violet-200/80 bg-violet-50/50 p-5 flex items-start gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </span>
        <div class="text-sm text-slate-700">
            <p class="font-medium">Cron setup</p>
            <p class="mt-1">Automated reminders run via scheduled tasks. Make sure your cron job is configured: <code class="bg-white/80 px-2 py-0.5 rounded font-mono text-violet-700">php artisan schedule:run</code></p>
        </div>
    </div>

    {{-- Alert Channels --}}
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-slate-50 to-violet-50/30 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Alert Channels</h3>
                    <p class="text-sm text-slate-600">Choose how notifications are delivered</p>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-4">
            @foreach([
                ['key' => 'alerts_email_enabled', 'title' => 'Enable Email Alerts', 'desc' => 'Send notifications via email', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['key' => 'alerts_sms_enabled', 'title' => 'Enable SMS/WhatsApp Alerts', 'desc' => 'Send notifications via SMS/WhatsApp', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ] as $c)
                <div class="flex items-center justify-between p-5 rounded-xl bg-slate-50/80 hover:bg-violet-50/30 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-200/80 text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c['icon'] }}"></path></svg>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $c['title'] }}</p>
                            <p class="text-sm text-slate-500">{{ $c['desc'] }}</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="hidden" name="{{ $c['key'] }}" value="0">
                        <input type="checkbox" name="{{ $c['key'] }}" value="1" {{ old($c['key'], $settings->{$c['key']} ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Alert Types --}}
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Alert Types</h3>
                    <p class="text-sm text-slate-600">Choose which events trigger notifications</p>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-4">
            @foreach([
                ['key' => 'alert_test_results', 'title' => 'Test Results Ready', 'desc' => 'Notify patients when test reports are ready'],
                ['key' => 'alert_appointments', 'title' => 'Appointment Reminders', 'desc' => 'Send reminders before appointments'],
                ['key' => 'alert_payments', 'title' => 'Payment Reminders', 'desc' => 'Send reminders for pending payments'],
                ['key' => 'alert_inventory', 'title' => 'Inventory Alerts', 'desc' => 'Notify admins about low stock, expiring items, etc.'],
            ] as $a)
                <div class="flex items-center justify-between p-5 rounded-xl bg-slate-50/80 hover:bg-violet-50/30 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-slate-900">{{ $a['title'] }}</p>
                        <p class="text-sm text-slate-500">{{ $a['desc'] }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="hidden" name="{{ $a['key'] }}" value="0">
                        <input type="checkbox" name="{{ $a['key'] }}" value="1" {{ old($a['key'], $settings->{$a['key']} ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Reminder Settings --}}
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Reminder Settings</h3>
                    <p class="text-sm text-slate-600">Configure timing for automated reminders</p>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-6">
            <div class="p-5 rounded-xl bg-slate-50/80 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-900">Appointment Reminders</p>
                        <p class="text-sm text-slate-500">Enable automatic appointment reminders</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="hidden" name="appointment_reminder_enabled" value="0">
                        <input type="checkbox" name="appointment_reminder_enabled" value="1" {{ old('appointment_reminder_enabled', $settings->appointment_reminder_enabled ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                    </label>
                </div>
                <div>
                    <label for="appointment_reminder_hours" class="block text-sm font-medium text-slate-700 mb-2">Send reminder (hours before appointment)</label>
                    <input type="number" name="appointment_reminder_hours" id="appointment_reminder_hours" value="{{ old('appointment_reminder_hours', $settings->appointment_reminder_hours ?? 24) }}" min="1" max="168" class="block w-full max-w-xs rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                    <p class="mt-1 text-xs text-slate-500">Default: 24 hours before appointment</p>
                </div>
            </div>

            <div class="p-5 rounded-xl bg-slate-50/80 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-900">Payment Reminders</p>
                        <p class="text-sm text-slate-500">Enable automatic payment reminders</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="hidden" name="payment_reminder_enabled" value="0">
                        <input type="checkbox" name="payment_reminder_enabled" value="1" {{ old('payment_reminder_enabled', $settings->payment_reminder_enabled ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                    </label>
                </div>
                <div>
                    <label for="payment_reminder_days" class="block text-sm font-medium text-slate-700 mb-2">Send reminder (days before due date)</label>
                    <input type="number" name="payment_reminder_days" id="payment_reminder_days" value="{{ old('payment_reminder_days', $settings->payment_reminder_days ?? 7) }}" min="1" max="30" class="block w-full max-w-xs rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                    <p class="mt-1 text-xs text-slate-500">Default: 7 days before due date</p>
                </div>
            </div>
        </div>
    </div>
</div>
