<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment successful - {{ $bill->lab->name ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; background: #f3f4f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .card { max-width: 28rem; width: 100%; background: white; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.1); overflow: hidden; text-align: center; }
        .card-body { padding: 2rem; }
        .icon { width: 4rem; height: 4rem; margin: 0 auto 1rem; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .amount { font-size: 1.5rem; font-weight: 700; color: #16a34a; margin-top: 0.5rem; }
        .text-muted { color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Payment successful</h2>
            <p class="text-muted">Thank you for your payment.</p>
            <p class="text-gray-700 mt-2">Bill #{{ $bill->bill_number }}</p>
            <p class="text-muted">{{ $bill->patient->name }}</p>
            @if($bill->status === 'paid')
                <p class="amount mt-4">Fully paid</p>
            @else
                <p class="text-gray-600 mt-4">Paid amount has been recorded. Remaining due: ₹{{ number_format($bill->fresh()->due_amount, 2) }}</p>
            @endif
        </div>
    </div>
</body>
</html>
