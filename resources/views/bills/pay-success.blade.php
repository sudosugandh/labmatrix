<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment successful — {{ $bill->lab->name ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|dm-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $brandSettings = \App\Models\WebsiteSetting::where('lab_id', $bill->lab_id)->first();
        $primary = $brandSettings?->primary_color ?? '#5b5bd6';
        $brandName = $brandSettings?->brand_name ?? $bill->lab->name ?? config('app.name');
        $bill->loadMissing(['patient', 'appointment', 'testOrders']);
        $paidDisplay = (float) ($bill->paid_amount > 0 ? $bill->paid_amount : $bill->total);
    @endphp
    <style>
        :root {
            --ok-primary: {{ $primary }};
            --ok-primary-dark: color-mix(in srgb, {{ $primary }} 72%, #1e1b4b);
            --ok-surface: rgba(255, 255, 255, 0.94);
            --ok-border: rgba(15, 23, 42, 0.08);
            --ok-success: #059669;
            --ok-success-soft: color-mix(in srgb, #10b981 18%, white);
        }
        .ok-font-display { font-family: 'Fraunces', Georgia, serif; }
        .ok-font-body { font-family: 'DM Sans', system-ui, sans-serif; }
        .ok-bg-mesh {
            background-color: #0f172a;
            background-image:
                radial-gradient(ellipse 120% 80% at 50% -20%, rgba(16, 185, 129, 0.22), transparent 55%),
                radial-gradient(ellipse 80% 50% at 100% 40%, rgba(99, 102, 241, 0.12), transparent 45%),
                radial-gradient(ellipse 60% 45% at 0% 100%, rgba(59, 130, 246, 0.12), transparent 50%);
        }
        .ok-card {
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.07),
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 100px -30px color-mix(in srgb, var(--ok-primary) 28%, transparent);
        }
        .ok-btn {
            background: linear-gradient(135deg, var(--ok-primary) 0%, var(--ok-primary-dark) 100%);
            box-shadow: 0 4px 16px color-mix(in srgb, var(--ok-primary) 40%, transparent);
        }
        .ok-btn:hover {
            filter: brightness(1.06);
            box-shadow: 0 6px 22px color-mix(in srgb, var(--ok-primary) 48%, transparent);
        }
        @keyframes ok-pop {
            0% { transform: scale(0.92); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .ok-hero-icon { animation: ok-pop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    </style>
</head>
<body class="ok-font-body min-h-screen ok-bg-mesh text-slate-800 antialiased">
    <div class="relative min-h-screen flex flex-col items-center justify-center px-4 py-10 sm:py-16">
        <div class="absolute inset-0 opacity-[0.035] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h100v100H0z\' fill=\'none\'/%3E%3Cpath d=\'M0 50h100M50 0v100\' stroke=\'%23fff\' stroke-width=\'0.5\'/%3E%3C/svg%3E'); background-size: 24px 24px;"></div>

        <div class="relative z-10 w-full max-w-md">
            <p class="text-center text-xs font-medium uppercase tracking-[0.2em] text-emerald-400/90 mb-6">Payment received</p>

            <div class="ok-card rounded-3xl overflow-hidden border border-white/15 bg-[var(--ok-surface)] backdrop-blur-xl">
                {{-- Success header --}}
                <div class="px-6 sm:px-8 pt-10 pb-8 text-center border-b border-[var(--ok-border)]" style="background: linear-gradient(180deg, var(--ok-success-soft) 0%, rgba(255,255,255,0.65) 100%);">
                    <div class="ok-hero-icon mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-white shadow-lg shadow-emerald-900/10 ring-4 ring-emerald-400/25">
                        <svg class="h-10 w-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <h1 class="ok-font-display text-2xl sm:text-3xl font-semibold text-slate-900 tracking-tight">You&apos;re all set</h1>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed max-w-sm mx-auto">Thank you — your payment was processed securely. A receipt summary is below.</p>
                </div>

                {{-- Brand strip --}}
                <div class="px-6 sm:px-8 py-4 flex items-center justify-center gap-3 border-b border-[var(--ok-border)] bg-white/50">
                    @if($brandSettings && $brandSettings->logo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($brandSettings->logo) }}" alt="" class="h-9 w-auto max-w-[140px] object-contain">
                    @else
                        <span class="ok-font-display font-semibold text-slate-800">{{ $brandName }}</span>
                    @endif
                </div>

                {{-- Receipt --}}
                <div class="px-6 sm:px-8 py-7 space-y-5">
                    <div class="rounded-2xl border border-slate-200/90 bg-slate-50/90 px-4 py-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Bill number</span>
                            <span class="font-semibold text-slate-900 tabular-nums">{{ $bill->bill_number }}</span>
                        </div>
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Patient</span>
                            <span class="font-medium text-slate-900 text-right">{{ $bill->patient?->name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between gap-4 pt-2 border-t border-slate-200/80">
                            <span class="text-slate-500">Amount paid</span>
                            <span class="ok-font-display text-lg font-bold tabular-nums" style="color: var(--ok-success);">₹{{ number_format($paidDisplay, 2) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-2">
                        @if($bill->status === 'paid')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-800">Fully paid</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900">Partial — due ₹{{ number_format($bill->fresh()->due_amount, 2) }}</span>
                        @endif
                    </div>

                    @if($bill->appointment_id && $bill->appointment)
                        <a href="{{ route('public.appointments.confirmation', $bill->appointment) }}" class="ok-btn ok-font-body flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3.5 text-center text-sm font-semibold text-white transition-all">
                            View appointment confirmation
                            <svg class="w-4 h-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                        <p class="text-center text-xs text-slate-500">You can print or save your booking details from the next page.</p>
                    @else
                        <p class="text-center text-sm text-slate-600">Keep this bill number for your records.</p>
                    @endif
                </div>
            </div>

            <p class="mt-8 text-center text-xs text-slate-500/90">Questions? Contact <span class="text-slate-300">{{ $brandName }}</span> directly.</p>
        </div>
    </div>
</body>
</html>
