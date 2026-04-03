@extends('website.layout')

@section('content')
@include('website.partials.doctor-portal-chrome', [
    'title' => 'Referrals',
    'subtitle' => 'Test orders attributed to your account (including bookings via your referral link).',
    'active' => 'referrals',
])

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="rounded-2xl border border-slate-200/90 bg-white shadow-sm ring-1 ring-slate-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Order</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Patient</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Test</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide" title="Order, visit, and report progress from the lab">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-semibold text-[color:var(--primary-color)]">#{{ $order->id }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-800">{{ $order->patient?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-700">{{ $order->test?->name ?? $order->package?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-700 max-w-md">{{ $order->doctorPortalStatusLabel() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-14 text-center text-slate-500 text-sm">No referred orders yet. Share your referral link from the overview.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
