{{-- Payments Tab: CMS-style UI --}}
<div class="space-y-8">
    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 overflow-hidden">
        <div class="relative p-6">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Enable Payments</h3>
                        <p class="text-sm text-slate-500">Allow online payment processing</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="hidden" name="payments_enabled" value="0">
                    <input type="checkbox" name="payments_enabled" value="1" {{ old('payments_enabled', $settings->payments_enabled ?? false) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                </label>
            </div>
        </div>
    </div>

    <div class="group relative bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-slate-50 to-violet-50/30 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-800 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </span>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Payment Gateway</h3>
                    <p class="text-sm text-slate-600">Configure your payment provider and API credentials</p>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label for="payment_gateway" class="block text-sm font-medium text-slate-700 mb-2">Gateway</label>
                <select name="payment_gateway" id="payment_gateway" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                    <option value="">Select Gateway</option>
                    <option value="stripe" {{ old('payment_gateway', $settings->payment_gateway) === 'stripe' ? 'selected' : '' }}>Stripe</option>
                    <option value="razorpay" {{ old('payment_gateway', $settings->payment_gateway) === 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                    <option value="paypal" {{ old('payment_gateway', $settings->payment_gateway) === 'paypal' ? 'selected' : '' }}>PayPal</option>
                </select>
                <p class="mt-2 text-xs text-slate-500 leading-relaxed"><strong class="font-medium text-slate-600">Stripe (INR)</strong>: This app requests <strong class="font-medium text-slate-600">card + UPI</strong> on Checkout. In <a href="https://dashboard.stripe.com/settings/payment_methods" target="_blank" rel="noopener" class="text-violet-600 hover:underline">Stripe Dashboard → Settings → Payment methods</a>, enable <strong class="font-medium text-slate-600">UPI</strong> for India—then patients see UPI next to card with no code change. <span class="text-slate-400">Razorpay includes UPI by default for INR.</span></p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="payment_api_key" class="block text-sm font-medium text-slate-700 mb-2">API Key</label>
                    <input type="text" name="payment_api_key" id="payment_api_key" value="{{ old('payment_api_key', $settings->payment_api_key ?? '') }}" placeholder="Enter your payment gateway API key" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                </div>
                <div>
                    <label for="payment_api_secret" class="block text-sm font-medium text-slate-700 mb-2">API Secret</label>
                    <input type="password" name="payment_api_secret" id="payment_api_secret" value="{{ old('payment_api_secret', $settings->payment_api_secret ?? '') }}" placeholder="Enter your payment gateway API secret" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-violet-500 focus:ring-violet-500/20 px-4 py-2.5">
                </div>
            </div>
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50/80">
                <div>
                    <p class="text-sm font-medium text-slate-900">Test Mode</p>
                    <p class="text-sm text-slate-500">Enable test mode for payment processing</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="hidden" name="payment_test_mode" value="0">
                    <input type="checkbox" name="payment_test_mode" value="1" {{ old('payment_test_mode', $settings->payment_test_mode ?? true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                </label>
            </div>
        </div>
    </div>
</div>
