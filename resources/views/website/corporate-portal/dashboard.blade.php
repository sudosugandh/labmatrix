@extends('website.layout')

@section('content')
@include('website.partials.corporate-portal-chrome', [
    'title' => $account->name,
    'subtitle' => 'Health camps, participant lists, and report downloads for your organization.',
    'active' => 'dashboard',
])

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="rounded-2xl border border-slate-200/90 bg-white shadow-sm ring-1 ring-slate-900/5 overflow-hidden">
        <div class="px-5 sm:px-6 py-4 border-b border-slate-200/90 bg-gradient-to-r from-slate-50/90 to-indigo-50/40">
            <h2 class="text-lg font-semibold text-slate-900">Health camps</h2>
            <p class="text-sm text-slate-600 mt-0.5">Open a camp to view participants and export CSV.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Camp</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Date</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Orders</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Reports</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($camps as $camp)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-semibold text-slate-900">{{ $camp->name }}</td>
                            <td class="px-4 py-3.5 text-sm text-slate-600">{{ $camp->camp_date?->format('M j, Y') ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-sm capitalize text-slate-700">{{ $camp->status }}</td>
                            <td class="px-4 py-3.5 text-sm text-right tabular-nums text-slate-800">{{ $camp->test_orders_count }}</td>
                            <td class="px-4 py-3.5 text-sm text-right tabular-nums text-slate-800">{{ $camp->reports_ready_count ?? 0 }}</td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('corporate.portal.camp', $camp) }}" class="text-sm font-semibold text-[color:var(--primary-color)] hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-14 text-center text-slate-500 text-sm">No camps assigned yet. Your lab will create camps linked to this account.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($camps->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $camps->links() }}</div>
        @endif
    </div>
</div>
@endsection
