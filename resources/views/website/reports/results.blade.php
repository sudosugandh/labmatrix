@extends('website.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-100">
        <div class="flex items-center justify-center gap-2 mb-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold text-white" style="background-color: var(--primary-color);">2</span>
            <span class="text-sm font-medium text-gray-500">of 2</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Your Reports</h1>

        @if(isset($patient))
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                <p class="text-sm text-gray-600">Showing reports for <span class="font-semibold text-gray-900">{{ $patient->name }}</span> ({{ $patient->phone }})</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 p-5" role="alert">
                <p class="font-medium text-amber-900">No account found for this number</p>
                <p class="mt-1 text-sm text-amber-800">{{ session('error') }}</p>
                <p class="mt-3 text-sm text-amber-800">Please check the number and try again, or contact us if you need help.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('public.reports.search') }}" class="inline-flex items-center px-4 py-2 rounded-lg font-medium text-white" style="background-color: var(--primary-color);">Try again</a>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50">Contact us</a>
                </div>
            </div>
        @endif

        @if(isset($reports) && $reports->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $report->testOrder->test->name ?? 'Report' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $report->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $report->status === 'ready' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($report->status === 'ready')
                                        <a href="{{ route('public.reports.download', $report->id) }}" class="font-semibold mr-4" style="color: var(--primary-color);">Download</a>
                                        <button type="button" onclick="openEmailModal({{ $report->id }}, '{{ addslashes($report->testOrder->test->name ?? 'Report') }}')" class="text-gray-600 hover:text-gray-900 font-medium">Email link</button>
                                    @else
                                        <span class="text-gray-400">Not ready</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(session('email_sent'))
                <div class="mt-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('email_sent') }}</div>
            @endif
        @elseif(!session('error'))
            <div class="text-center py-12 rounded-lg bg-gray-50 border border-gray-100">
                <p class="text-gray-700 font-medium">No reports found</p>
                <p class="mt-1 text-sm text-gray-500">Reports will appear here once they are ready. If you expected a report, please contact us.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('public.reports.search') }}" class="inline-flex items-center px-4 py-2 rounded-lg font-medium text-white" style="background-color: var(--primary-color);">Search again</a>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50">Contact us</a>
                </div>
            </div>
        @endif

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <a href="{{ route('public.reports.search') }}" class="text-sm font-medium" style="color: var(--primary-color);">← Back to search</a>
        </div>
    </div>
</div>

{{-- Email report link modal --}}
@if(isset($reports) && $reports->count() > 0)
<div id="emailReportModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" aria-hidden="true">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Email report link</h3>
        <p class="text-sm text-gray-500 mb-4">We’ll send a secure download link to your email.</p>
        <form id="emailReportForm" method="POST" action="">
            @csrf
            <input type="hidden" name="report_id" id="email_report_id" value="">
            <div class="mb-4">
                <label for="email_report_email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input type="email" name="email" id="email_report_email" required placeholder="you@example.com"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-offset-0 px-3 py-2">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeEmailModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 rounded-lg text-white font-medium" style="background-color: var(--primary-color);">Send link</button>
            </div>
        </form>
    </div>
</div>
<script>
function openEmailModal(reportId, reportName) {
    document.getElementById('email_report_id').value = reportId;
    document.getElementById('emailReportForm').action = '{{ url("/patient-reports/email-link") }}';
    document.getElementById('email_report_email').value = '';
    document.getElementById('emailReportModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEmailModal() {
    document.getElementById('emailReportModal').classList.add('hidden');
    document.body.style.overflow = '';
}
document.getElementById('emailReportModal').addEventListener('click', function(e) { if (e.target === this) closeEmailModal(); });
</script>
@endif
@endsection
