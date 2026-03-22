@component('mail::message')
# Your Test Report is Ready

Hello {{ $testOrder->patient->name }},

Your test report for **{{ $testOrder->test->name }}** is now ready for download.

**Test Details:**
- Test Name: {{ $testOrder->test->name }}
- Order Date: {{ $testOrder->created_at->format('M d, Y') }}
- Report Status: Ready

@component('mail::button', ['url' => route('public.reports.search')])
View Your Reports
@endcomponent

You can view and download your report by visiting our website and entering your phone number: **{{ $testOrder->patient->phone }}**

If you have any questions, please contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
