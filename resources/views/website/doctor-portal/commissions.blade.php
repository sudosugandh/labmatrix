@extends('website.layout')

@section('content')
@include('website.partials.doctor-portal-chrome', [
    'title' => 'Commissions',
    'subtitle' => 'Commission lines generated from bills for your referred orders.',
    'active' => 'commissions',
])

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <form method="GET" action="{{ route('doctor.portal.commissions') }}" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label for="status" class="block text-xs font-semibold text-slate-600 mb-1.5">Filter by status</label>
            <select name="status" id="status" onchange="this.form.submit()" class="rounded-xl border-slate-200 text-sm py-2.5 px-3 shadow-sm focus:border-[color:var(--primary-color)] focus:ring-[color:var(--primary-color)]">
                <option value="">All</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>
    </form>

    <div class="rounded-2xl border border-slate-200/90 bg-white shadow-sm ring-1 ring-slate-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Date</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Order / test</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Order amt</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Commission</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($commissions as $c)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 text-sm text-slate-700 whitespace-nowrap">{{ $c->commission_date?->format('M j, Y') ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm">
                                <span class="font-semibold text-slate-900">#{{ $c->test_order_id }}</span>
                                @if($c->testOrder?->test)
                                    <span class="text-slate-500"> · {{ $c->testOrder->test->name }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-sm text-right tabular-nums text-slate-700">₹{{ number_format($c->order_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-sm text-right font-semibold tabular-nums text-slate-900">₹{{ number_format($c->commission_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-sm">
                                @if($c->status === 'paid')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-600/15">Paid</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-900 ring-1 ring-inset ring-amber-600/15">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-14 text-center text-slate-500 text-sm">No commission records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($commissions->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $commissions->links() }}</div>
        @endif
    </div>
</div>
@endsection
