@component('mail::message')
# Payment Reminder

Hello {{ $bill->patient->name }},

This is a friendly reminder that you have a pending payment.

**Bill Details:**
- Bill Number: #{{ $bill->bill_number }}
- Total Amount: ₹{{ number_format($bill->total_amount, 2) }}
- Due Date: {{ $bill->due_date ? \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') : 'N/A' }}
- Status: {{ ucfirst($bill->payment_status) }}

@if($bill->due_date && \Carbon\Carbon::parse($bill->due_date)->isPast())
**⚠️ This payment is overdue. Please make payment at your earliest convenience.**
@endif

Please visit our lab or contact us to make the payment.

If you have already made the payment, please ignore this reminder.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
