{{-- Security Tab: CMS-style UI --}}
<div class="space-y-8">
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="relative p-6 space-y-6">
            {{-- 2FA --}}
            <div class="flex items-center justify-between gap-4 p-5 rounded-xl bg-slate-50/80">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Two Factor Authentication</h3>
                        <p class="text-sm text-slate-500">Require 2FA for all user accounts</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="hidden" name="two_factor_enabled" value="0">
                    <input type="checkbox" name="two_factor_enabled" value="1" {{ old('two_factor_enabled', $settings->two_factor_enabled ?? false) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                </label>
            </div>

            {{-- Session Timeout --}}
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
                <div class="flex-1">
                    <label for="session_timeout" class="block text-sm font-medium text-slate-700 mb-2">Session Timeout (minutes)</label>
                    <input type="number" name="session_timeout" id="session_timeout" value="{{ old('session_timeout', $settings->session_timeout ?? 120) }}" min="5" max="1440" class="block w-full max-w-xs rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5" placeholder="120">
                    <p class="mt-1 text-xs text-slate-500">Session will expire after this many minutes of inactivity</p>
                </div>
            </div>
        </div>
    </div>

    {{-- IP Whitelist --}}
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">IP Whitelist</h3>
                        <p class="text-sm text-slate-500">Restrict access to specific IP addresses</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="hidden" name="ip_whitelist_enabled" value="0">
                    <input type="checkbox" name="ip_whitelist_enabled" value="1" {{ old('ip_whitelist_enabled', $settings->ip_whitelist_enabled ?? false) ? 'checked' : '' }} class="sr-only peer" onchange="toggleIpWhitelist(this)">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                </label>
            </div>
            <div id="ip-whitelist-input" class="{{ old('ip_whitelist_enabled', $settings->ip_whitelist_enabled ?? false) ? '' : 'hidden' }}">
                <label for="ip_whitelist" class="block text-sm font-medium text-slate-700 mb-2">IP Addresses (comma-separated)</label>
                <input type="text" name="ip_whitelist" id="ip_whitelist" value="{{ old('ip_whitelist', is_array($settings->ip_whitelist ?? []) ? implode(', ', $settings->ip_whitelist) : ($settings->ip_whitelist ?? '')) }}" placeholder="192.168.1.1, 10.0.0.1" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                <p class="mt-1 text-xs text-slate-500">Enter IP addresses separated by commas</p>
            </div>
        </div>
    </div>

    {{-- Password Policy --}}
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Password Policy</h3>
                        <p class="text-sm text-slate-500">Enforce password complexity requirements</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="hidden" name="password_policy_enabled" value="0">
                    <input type="checkbox" name="password_policy_enabled" value="1" {{ old('password_policy_enabled', $settings->password_policy_enabled ?? true) ? 'checked' : '' }} class="sr-only peer" onchange="togglePasswordPolicy(this)">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                </label>
            </div>
            <div id="password-policy-input" class="{{ old('password_policy_enabled', $settings->password_policy_enabled ?? true) ? '' : 'hidden' }}">
                <label for="password_min_length" class="block text-sm font-medium text-slate-700 mb-2">Minimum Password Length</label>
                <input type="number" name="password_min_length" id="password_min_length" value="{{ old('password_min_length', $settings->password_min_length ?? 8) }}" min="6" max="32" class="block w-full max-w-xs rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                <p class="mt-1 text-xs text-slate-500">Minimum number of characters required for passwords</p>
            </div>
        </div>
    </div>
</div>

<script>
function toggleIpWhitelist(checkbox) {
    var el = document.getElementById('ip-whitelist-input');
    el.classList.toggle('hidden', !checkbox.checked);
}

function togglePasswordPolicy(checkbox) {
    var el = document.getElementById('password-policy-input');
    el.classList.toggle('hidden', !checkbox.checked);
}
</script>
