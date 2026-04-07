<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $labTitle = trim((string) ($settings->brand_name ?? '')) ?: (currentLab()?->name ?? 'Laboratory');
        $primary = $settings->primary_color ?: '#4f46e5';
        $phoneLine = trim((string) ($settings->contact_phone ?? '')) ?: trim((string) ($settings->phone ?? ''));
        $emailLine = trim((string) ($settings->contact_email ?? '')) ?: trim((string) ($settings->email ?? ''));
        $addr = trim((string) ($settings->address ?? ''));
        $timeStr = strlen((string) $appointment->appointment_time) === 5
            ? $appointment->appointment_time . ':00'
            : $appointment->appointment_time;
        $timeFormatted = \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
        $ref = str_pad((string) $appointment->id, 6, '0', STR_PAD_LEFT);
    @endphp
    <title>{{ __('Appointment confirmation') }} — {{ $labTitle }}</title>
    <style>
        @media print {
            body { margin: 0; padding: 16px; }
            .no-print { display: none !important; }
        }
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif;
            font-size: 15px;
            color: #0f172a;
            line-height: 1.5;
            max-width: 640px;
            margin: 0 auto;
            padding: 24px 20px 48px;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 28px;
        }
        .actions a, .actions button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
        .btn-download {
            background: {{ $primary }};
            color: #fff;
        }
        .btn-download:hover { filter: brightness(1.05); }
        .btn-print {
            background: #fff;
            color: #334155;
            border: 2px solid #e2e8f0;
        }
        .btn-print:hover { background: #f8fafc; }
        .brand-bar { height: 4px; background: {{ $primary }}; margin: -24px -20px 20px -20px; border-radius: 0; }
        .header { text-align: center; padding-bottom: 20px; margin-bottom: 24px; border-bottom: 2px solid {{ $primary }}; }
        .header h1 { margin: 0 0 10px; font-size: 1.75rem; font-weight: 800; color: {{ $primary }}; letter-spacing: -0.02em; }
        .header .meta { margin: 4px 0; color: #64748b; font-size: 0.875rem; }
        h2 {
            margin: 28px 0 12px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }
        table.rows { width: 100%; border-collapse: collapse; }
        table.rows td { padding: 8px 0; vertical-align: top; }
        table.rows td.k { width: 38%; color: #64748b; font-weight: 600; font-size: 0.8125rem; }
        table.rows td.v { color: #0f172a; }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            background: {{ $primary }};
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            background: #f8fafc;
            margin-top: 8px;
        }
        .tests-list { margin: 8px 0 0 20px; padding: 0; color: #334155; }
        .tests-list li { margin-bottom: 6px; }
        .footer {
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.8125rem;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="no-print actions">
        <a href="{{ route('public.appointments.confirmation.download', $appointment) }}" class="btn-download">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            {{ __('Download confirmation (PDF)') }}
        </a>
        <button type="button" class="btn-print" onclick="window.print()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            {{ __('Print this page') }}
        </button>
    </div>

    <div class="brand-bar"></div>
    <div class="header">
        <h1>{{ $labTitle }}</h1>
        @if($addr !== '')
            <p class="meta">{{ $addr }}</p>
        @endif
        @if($phoneLine !== '')
            <p class="meta">{{ __('Phone') }}: {{ $phoneLine }}</p>
        @endif
        @if($emailLine !== '')
            <p class="meta">{{ __('Email') }}: {{ $emailLine }}</p>
        @endif
    </div>

    <h2>{{ __('Appointment confirmation') }}</h2>
    <table class="rows">
        <tr>
            <td class="k">{{ __('Reference') }}</td>
            <td class="v"><strong>#{{ $ref }}</strong></td>
        </tr>
        <tr>
            <td class="k">{{ __('Date') }}</td>
            <td class="v">{{ $appointment->appointment_date->format('l, F d, Y') }}</td>
        </tr>
        <tr>
            <td class="k">{{ __('Time') }}</td>
            <td class="v">{{ $timeFormatted }}</td>
        </tr>
        <tr>
            <td class="k">{{ __('Status') }}</td>
            <td class="v"><span class="badge">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span></td>
        </tr>
    </table>

    <h2>{{ __('Patient information') }}</h2>
    <table class="rows">
        <tr>
            <td class="k">{{ __('Name') }}</td>
            <td class="v">{{ $appointment->patient->name }}</td>
        </tr>
        <tr>
            <td class="k">{{ __('Phone') }}</td>
            <td class="v">{{ $appointment->patient->phone }}</td>
        </tr>
        @if($appointment->patient->email)
            <tr>
                <td class="k">{{ __('Email') }}</td>
                <td class="v">{{ $appointment->patient->email }}</td>
            </tr>
        @endif
        @if(!empty(trim((string) ($appointment->patient->address ?? ''))))
            <tr>
                <td class="k">{{ __('Address') }}</td>
                <td class="v" style="white-space: pre-line;">{{ trim($appointment->patient->address) }}</td>
            </tr>
        @endif
        @if($appointment->patient->age)
            <tr>
                <td class="k">{{ __('Age') }}</td>
                <td class="v">{{ $appointment->patient->age }} {{ __('years') }}</td>
            </tr>
        @endif
        @if($appointment->patient->gender)
            <tr>
                <td class="k">{{ __('Gender') }}</td>
                <td class="v">{{ ucfirst($appointment->patient->gender) }}</td>
            </tr>
        @endif
    </table>

    @if($appointment->package)
        <h2>{{ __('Package') }}</h2>
        <div class="card">
            <strong>{{ $appointment->package->name }}</strong>
            <span style="color: {{ $primary }};"> — ₹{{ number_format((float) $appointment->package->final_price, 2) }}</span>
            <p style="margin: 12px 0 0; font-size: 0.875rem; color: #475569;">
                {{ __('This package includes') }} {{ $appointment->package->tests->count() }} {{ Str::plural('test', $appointment->package->tests->count()) }}.
                {{ __('Reports will be available when the lab releases them.') }}
            </p>
        </div>
    @elseif($appointment->tests && $appointment->tests->count() > 0)
        <h2>{{ __('Tests booked') }}</h2>
        <div class="card">
            <ul class="tests-list">
                @foreach($appointment->tests as $t)
                    <li>{{ $t->name }} — ₹{{ number_format((float) $t->price, 2) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($appointment->doctor)
        <h2>{{ __('Referred by') }}</h2>
        <table class="rows">
            <tr>
                <td class="k">{{ __('Doctor') }}</td>
                <td class="v">Dr. {{ $appointment->doctor->name }}</td>
            </tr>
            @if($appointment->doctor->specialization)
                <tr>
                    <td class="k">{{ __('Specialization') }}</td>
                    <td class="v">{{ $appointment->doctor->specialization }}</td>
                </tr>
            @endif
            @if($appointment->doctor->clinic_name)
                <tr>
                    <td class="k">{{ __('Clinic') }}</td>
                    <td class="v">{{ $appointment->doctor->clinic_name }}</td>
                </tr>
            @endif
        </table>
    @endif

    @if($appointment->referring_doctor_name || $appointment->referring_doctor_phone)
        <h2>{{ __('Referring doctor (patient provided)') }}</h2>
        <table class="rows">
            @if($appointment->referring_doctor_name)
                <tr>
                    <td class="k">{{ __('Name') }}</td>
                    <td class="v">{{ $appointment->referring_doctor_name }}</td>
                </tr>
            @endif
            @if($appointment->referring_doctor_phone)
                <tr>
                    <td class="k">{{ __('Phone') }}</td>
                    <td class="v">{{ $appointment->referring_doctor_phone }}</td>
                </tr>
            @endif
        </table>
    @endif

    @if($appointment->prescription_file)
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 16px;">{{ __('A prescription or referral file was uploaded with this booking.') }}</p>
    @endif

    @if($appointment->notes)
        <h2>{{ __('Notes') }}</h2>
        <p style="margin: 0; color: #334155;">{{ $appointment->notes }}</p>
    @endif

    <div class="footer">
        <p style="margin: 0 0 10px;"><strong>{{ __('Important') }}:</strong> {{ __('Please arrive 10 minutes before your scheduled time.') }}</p>
        <p style="margin: 0 0 10px;">{{ __('To cancel or reschedule, contact the lab at least 24 hours in advance.') }}</p>
        <p style="margin: 0;">{{ __('Generated on') }} {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
