<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pay Bill #{{ $bill->bill_number }} — {{ $bill->lab->name ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @php
        $primary = $brandSettings?->primary_color ?? '#5b5bd6';
        $brandName = $brandSettings?->brand_name ?? $bill->lab->name ?? config('app.name');
        $lineItems = $bill->testOrders;
    @endphp
    <style>
        :root {
            --pay-primary: {{ $primary }};
            --pay-primary-dark: color-mix(in srgb, {{ $primary }} 75%, #1e1b4b);
            --pay-surface: rgba(255, 255, 255, 0.92);
            --pay-border: rgba(15, 23, 42, 0.08);
        }
        .pay-font-display { font-family: 'Fraunces', Georgia, serif; }
        .pay-font-body { font-family: 'DM Sans', system-ui, sans-serif; }
        .pay-bg-mesh {
            background-color: #0f172a;
            background-image:
                radial-gradient(ellipse 120% 80% at 50% -20%, rgba(99, 102, 241, 0.35), transparent 55%),
                radial-gradient(ellipse 80% 50% at 100% 50%, rgba(139, 92, 246, 0.12), transparent 45%),
                radial-gradient(ellipse 60% 40% at 0% 100%, rgba(59, 130, 246, 0.15), transparent 50%);
        }
        .pay-card {
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.06),
                0 25px 50px -12px rgba(0, 0, 0, 0.45),
                0 0 80px -20px color-mix(in srgb, var(--pay-primary) 35%, transparent);
        }
        .pay-btn-primary {
            background: linear-gradient(135deg, var(--pay-primary) 0%, var(--pay-primary-dark) 100%);
            box-shadow: 0 4px 14px color-mix(in srgb, var(--pay-primary) 45%, transparent);
        }
        .pay-btn-primary:hover:not(:disabled) {
            filter: brightness(1.05);
            box-shadow: 0 6px 20px color-mix(in srgb, var(--pay-primary) 50%, transparent);
        }
        .pay-line-items { max-height: 14rem; overflow-y: auto; }
    </style>
</head>
<body class="pay-font-body min-h-screen pay-bg-mesh text-slate-800 antialiased">
    <div class="relative min-h-screen flex flex-col items-center justify-center px-4 py-10 sm:py-14">
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h100v100H0z\' fill=\'none\'/%3E%3Cpath d=\'M0 50h100M50 0v100\' stroke=\'%23fff\' stroke-width=\'0.5\'/%3E%3C/svg%3E'); background-size: 24px 24px;"></div>

        <div class="relative z-10 w-full max-w-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-sm font-medium text-slate-300/90 hover:text-white transition-colors order-2 sm:order-1">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Back
                </a>
                <div class="flex items-center gap-2 text-slate-400/90 text-xs order-1 sm:order-2 sm:ml-auto">
                    <svg class="w-4 h-4 text-emerald-400/90" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                    <span>Secure checkout</span>
                </div>
            </div>

            <div class="pay-card rounded-3xl overflow-hidden backdrop-blur-xl bg-[var(--pay-surface)] border border-white/20">
                <div class="relative px-6 sm:px-8 pt-8 pb-6 border-b border-[var(--pay-border)]" style="background: linear-gradient(165deg, color-mix(in srgb, var(--pay-primary) 12%, white) 0%, rgba(255,255,255,0.5) 100%);">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                        <div class="flex items-center gap-4 min-w-0">
                            @if($brandSettings && $brandSettings->logo)
                                <div class="shrink-0 h-14 w-14 sm:h-16 sm:w-16 rounded-2xl bg-white/90 border border-slate-200/80 shadow-sm flex items-center justify-center p-2">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($brandSettings->logo) }}" alt="" class="max-h-full max-w-full object-contain">
                                </div>
                            @else
                                <div class="shrink-0 h-14 w-14 sm:h-16 sm:w-16 rounded-2xl flex items-center justify-center text-white text-xl font-bold pay-font-display shadow-lg" style="background: linear-gradient(145deg, var(--pay-primary), var(--pay-primary-dark));">
                                    {{ strtoupper(mb_substr($brandName, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="pay-font-display text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight leading-tight">{{ $brandName }}</p>
                                <p class="text-sm text-slate-500 mt-0.5">Pay your bill online</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 sm:px-8 py-6 space-y-6">
                    @if(session('error'))
                        <div class="rounded-2xl bg-red-50 border border-red-100 text-red-800 text-sm px-4 py-3">{{ session('error') }}</div>
                    @endif
                    @if(session('info'))
                        <div class="rounded-2xl bg-indigo-50/90 border border-indigo-100/80 text-indigo-900 text-sm px-4 py-3 leading-relaxed">{{ session('info') }}</div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-slate-500 font-medium">Bill number</p>
                            <p class="font-semibold text-slate-900 mt-0.5 tabular-nums">{{ $bill->bill_number }}</p>
                        </div>
                        <div class="text-right sm:text-left sm:col-start-2">
                            <p class="text-slate-500 font-medium">Date</p>
                            <p class="font-semibold text-slate-900 mt-0.5">{{ $bill->bill_date?->format('M j, Y') ?? '—' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1.5">Patient</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $bill->patient->name }}</p>
                    </div>

                    @if($lineItems->isNotEmpty())
                        <div>
                            <p class="text-slate-500 text-sm font-medium mb-3">Items</p>
                            <div class="pay-line-items rounded-2xl border border-slate-200/80 bg-slate-50/80 divide-y divide-slate-200/60">
                                @foreach($lineItems as $order)
                                    @php
                                        $label = $order->test?->name ?? 'Test';
                                        if ($order->package_id && $order->package) {
                                            $label = $order->test?->name ? $order->test->name.' ('.$order->package->name.')' : $order->package->name;
                                        }
                                        $lineAmt = (float) ($order->final_price ?? $order->price ?? 0);
                                    @endphp
                                    <div class="flex justify-between gap-4 px-4 py-3 text-sm">
                                        <span class="text-slate-700 leading-snug">{{ $label }}</span>
                                        <span class="font-semibold text-slate-900 tabular-nums shrink-0">₹{{ number_format($lineAmt, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="rounded-2xl border border-slate-200/80 bg-white/60 px-4 py-4 space-y-2 text-sm">
                        @if((float) $bill->subtotal > 0)
                            <div class="flex justify-between text-slate-600">
                                <span>Subtotal</span>
                                <span class="tabular-nums">₹{{ number_format($bill->subtotal, 2) }}</span>
                            </div>
                        @endif
                        @if((float) $bill->discount > 0)
                            <div class="flex justify-between text-emerald-700">
                                <span>Discount</span>
                                <span class="tabular-nums">−₹{{ number_format($bill->discount, 2) }}</span>
                            </div>
                        @endif
                        @if((float) $bill->tax > 0)
                            <div class="flex justify-between text-slate-600">
                                <span>Tax</span>
                                <span class="tabular-nums">₹{{ number_format($bill->tax, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-end pt-2 border-t border-slate-200/80">
                            <span class="pay-font-display text-lg font-semibold text-slate-900">Amount due</span>
                            <span class="pay-font-display text-2xl sm:text-3xl font-bold tabular-nums" style="color: var(--pay-primary);">₹{{ number_format($bill->due_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs text-slate-500">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Encrypted connection
                        </span>
                        <span class="hidden sm:inline text-slate-300">·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            PCI-compliant gateways
                        </span>
                    </div>

                    @if(!$paymentsEnabled || !$gateway)
                        <div class="rounded-2xl bg-amber-50 border border-amber-100 text-amber-950 text-sm px-4 py-3 text-center leading-relaxed">
                            Online payment is not configured for this lab. Please pay at the lab or contact them for other options.
                        </div>
                    @elseif($gateway === 'razorpay' && $razorpayKey)
                        <button type="button" id="pay-razorpay" class="pay-btn-primary w-full inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-4 text-white font-semibold text-base transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                            <span>Pay ₹{{ number_format($bill->due_amount, 2) }}</span>
                            <span class="opacity-90 font-normal">with Razorpay</span>
                        </button>
                        <form id="razorpay-verify-form" method="POST" action="{{ url('/pay-bill/verify-razorpay') }}" class="hidden">
                            @csrf
                            <input type="hidden" name="token" value="{{ $bill->payment_token }}">
                            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                        </form>
                        <script>
                            document.getElementById('pay-razorpay').addEventListener('click', function() {
                                var btn = this;
                                btn.disabled = true;
                                var label = btn.innerHTML;
                                btn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Please wait…</span>';
                                fetch('{{ url("/pay-bill/create-razorpay-order") }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                                    body: JSON.stringify({ token: '{{ $bill->payment_token }}' })
                                }).then(function(r) { return r.json(); }).then(function(data) {
                                    if (data.error) { alert(data.error); btn.disabled = false; btn.innerHTML = label; return; }
                                    var options = {
                                        key: data.key,
                                        amount: data.amount,
                                        currency: data.currency,
                                        order_id: data.order_id,
                                        name: '{{ addslashes($bill->lab->name ?? config("app.name")) }}',
                                        description: 'Bill #{{ $bill->bill_number }}',
                                        handler: function(res) {
                                            document.getElementById('razorpay_order_id').value = res.razorpay_order_id;
                                            document.getElementById('razorpay_payment_id').value = res.razorpay_payment_id;
                                            document.getElementById('razorpay_signature').value = res.razorpay_signature;
                                            document.getElementById('razorpay-verify-form').submit();
                                        }
                                    };
                                    var rzp = new Razorpay(options);
                                    rzp.open();
                                    btn.disabled = false;
                                    btn.innerHTML = label;
                                }).catch(function() { btn.disabled = false; btn.innerHTML = label; });
                            });
                        </script>
                    @elseif($gateway === 'stripe')
                        <button type="button" id="pay-stripe" class="pay-btn-primary w-full inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-4 text-white font-semibold text-base transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                            <span>Pay ₹{{ number_format($bill->due_amount, 2) }}</span>
                            <span class="opacity-90 font-normal">with Stripe</span>
                        </button>
                        <script>
                            document.getElementById('pay-stripe').addEventListener('click', function() {
                                var btn = this;
                                btn.disabled = true;
                                var label = btn.innerHTML;
                                btn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Redirecting…</span>';
                                fetch('{{ url("/pay-bill/create-stripe-session") }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                                    body: JSON.stringify({ token: '{{ $bill->payment_token }}' })
                                }).then(function(r) { return r.json(); }).then(function(data) {
                                    if (data.url) window.location.href = data.url; else { alert(data.error || 'Something went wrong'); btn.disabled = false; btn.innerHTML = label; }
                                }).catch(function() { btn.disabled = false; btn.innerHTML = label; });
                            });
                        </script>
                    @else
                        <div class="rounded-2xl bg-slate-100 border border-slate-200 text-slate-700 text-sm px-4 py-3 text-center">No payment method available. Please contact the lab.</div>
                    @endif
                </div>
            </div>

            <p class="mt-8 text-center text-xs text-slate-500/80">Questions? Contact {{ $bill->lab->name ?? $brandName }} directly.</p>
        </div>
    </div>
</body>
</html>
