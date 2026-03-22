<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pay Bill #{{ $bill->bill_number }} - {{ $bill->lab->name ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body { font-family: 'Figtree', sans-serif; background: #f3f4f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .card { max-width: 28rem; width: 100%; background: white; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.1); overflow: hidden; }
        .card-header { background: linear-gradient(to right, #4f46e5, #7c3aed); color: white; padding: 1.25rem 1.5rem; }
        .card-body { padding: 1.5rem; }
        .amount { font-size: 1.75rem; font-weight: 700; color: #4f46e5; }
        .text-muted { color: #6b7280; font-size: 0.875rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; width: 100%; padding: 0.75rem 1rem; font-weight: 600; border-radius: 0.5rem; border: none; cursor: pointer; font-size: 1rem; transition: all 0.2s; }
        .btn-primary { background: #4f46e5; color: white; }
        .btn-primary:hover { background: #4338ca; }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .alert { padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
        .alert-error { background: #fef2f2; color: #b91c1c; }
        .alert-info { background: #eff6ff; color: #1d4ed8; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1 class="text-lg font-bold">{{ $bill->lab->name ?? config('app.name') }}</h1>
            <p class="text-sm opacity-90 mt-0.5">Pay your bill online</p>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <p class="text-muted">Bill number</p>
            <p class="font-semibold text-gray-900">{{ $bill->bill_number }}</p>

            <p class="text-muted mt-4">Patient</p>
            <p class="font-semibold text-gray-900">{{ $bill->patient->name }}</p>

            <p class="text-muted mt-4">Amount due</p>
            <p class="amount">₹{{ number_format($bill->due_amount, 2) }}</p>

            @if(!$paymentsEnabled || !$gateway)
                <div class="alert alert-info mt-4">
                    Online payment is not configured for this lab. Please pay at the lab or contact them for other options.
                </div>
            @elseif($gateway === 'razorpay' && $razorpayKey)
                <button type="button" id="pay-razorpay" class="btn btn-primary mt-6">
                    Pay ₹{{ number_format($bill->due_amount, 2) }} with Razorpay
                </button>
                <form id="razorpay-verify-form" method="POST" action="{{ url('/pay-bill/verify-razorpay') }}" style="display: none;">
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
                        btn.textContent = 'Please wait...';
                        fetch('{{ url("/pay-bill/create-razorpay-order") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                            body: JSON.stringify({ token: '{{ $bill->payment_token }}' })
                        }).then(function(r) { return r.json(); }).then(function(data) {
                            if (data.error) { alert(data.error); btn.disabled = false; btn.textContent = 'Pay ₹{{ number_format($bill->due_amount, 2) }} with Razorpay'; return; }
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
                            btn.textContent = 'Pay ₹{{ number_format($bill->due_amount, 2) }} with Razorpay';
                        }).catch(function() { btn.disabled = false; btn.textContent = 'Pay ₹{{ number_format($bill->due_amount, 2) }} with Razorpay'; });
                    });
                </script>
            @elseif($gateway === 'stripe')
                <button type="button" id="pay-stripe" class="btn btn-primary mt-6">
                    Pay ₹{{ number_format($bill->due_amount, 2) }} with Stripe
                </button>
                <script>
                    document.getElementById('pay-stripe').addEventListener('click', function() {
                        var btn = this;
                        btn.disabled = true;
                        btn.textContent = 'Redirecting...';
                        fetch('{{ url("/pay-bill/create-stripe-session") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                            body: JSON.stringify({ token: '{{ $bill->payment_token }}' })
                        }).then(function(r) { return r.json(); }).then(function(data) {
                            if (data.url) window.location.href = data.url; else { alert(data.error || 'Something went wrong'); btn.disabled = false; btn.textContent = 'Pay ₹{{ number_format($bill->due_amount, 2) }} with Stripe'; }
                        }).catch(function() { btn.disabled = false; btn.textContent = 'Pay ₹{{ number_format($bill->due_amount, 2) }} with Stripe'; });
                    });
                </script>
            @else
                <div class="alert alert-info mt-4">No payment method available. Please contact the lab.</div>
            @endif
        </div>
    </div>
</body>
</html>
