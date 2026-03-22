<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bill already paid - {{ $bill->lab->name ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; background: #f3f4f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .card { max-width: 28rem; width: 100%; background: white; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.1); overflow: hidden; text-align: center; }
        .card-body { padding: 2rem; }
        .text-muted { color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <h2 class="text-xl font-bold text-gray-900">This bill is already paid</h2>
            <p class="text-muted mt-2">Bill #{{ $bill->bill_number }} has no remaining amount due.</p>
        </div>
    </div>
</body>
</html>
