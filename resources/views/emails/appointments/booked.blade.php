@component('mail::message')
# Appointment Confirmed

Hello {{ $appointment->patient->name }},

Your appointment has been successfully booked!

**Appointment Details:**
- Date: {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
- Time: {{ $appointment->appointment_time }}
- Status: {{ ucfirst($appointment->status) }}

@if($appointment->package)
- Package: {{ $appointment->package->name }}
@endif

@if($appointment->doctor)
- Doctor: {{ $appointment->doctor->name }}
@endif

@component('mail::button', ['url' => route('public.appointments.show', $appointment)])
View Appointment Details
@endcomponent

Please arrive 10 minutes before your scheduled time.

If you need to reschedule or cancel, please visit the appointment page above.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
