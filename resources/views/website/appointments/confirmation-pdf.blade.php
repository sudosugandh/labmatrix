<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Appointment confirmation') }} — {{ $appointment->id }}</title>
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
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.45;
            margin: 0;
            padding: 28px 32px 40px;
        }
        .brand-bar {
            height: 4px;
            background: {{ $primary }};
            margin: -28px -32px 20px -32px;
        }
        .header { text-align: center; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 2px solid {{ $primary }}; }
        .header h1 {
            margin: 0 0 8px 0;
            font-size: 20px;
            font-weight: bold;
            color: {{ $primary }};
            letter-spacing: -0.02em;
        }
        .header .meta { color: #64748b; font-size: 10px; margin: 2px 0; }
        h2 {
            margin: 18px 0 10px 0;
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }
        table.rows { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        table.rows td { padding: 5px 0; vertical-align: top; }
        table.rows td.k { width: 32%; color: #64748b; font-weight: bold; font-size: 10px; }
        table.rows td.v { color: #0f172a; }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
            background: {{ $primary }};
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            margin-top: 10px;
            background: #f8fafc;
        }
        .tests-list { margin: 6px 0 0 16px; padding: 0; }
        .tests-list li { margin-bottom: 4px; color: #334155; }
        .footer {
            margin-top: 28px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #64748b;
            text-align: center;
        }
        .footer strong { color: #475569; }
    </style>
</head>
<body>
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
            <span style="color: {{ $primary }};"> — {{ __('INR') }} {{ number_format((float) $appointment->package->final_price, 2) }}</span>
            <p style="margin: 8px 0 0 0; font-size: 10px; color: #475569;">
                {{ __('This package includes') }} {{ $appointment->package->tests->count() }} {{ Str::plural('test', $appointment->package->tests->count()) }}.
                {{ __('Reports will be available when the lab releases them.') }}
            </p>
        </div>
    @elseif($appointment->tests && $appointment->tests->count() > 0)
        <h2>{{ __('Tests booked') }}</h2>
        <div class="card">
            <ul class="tests-list">
                @foreach($appointment->tests as $t)
                    <li>{{ $t->name }} — {{ __('INR') }} {{ number_format((float) $t->price, 2) }}</li>
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
        <p style="font-size: 10px; color: #64748b; margin-top: 12px;">{{ __('A prescription or referral file was uploaded with this booking.') }}</p>
    @endif

    @if($appointment->notes)
        <h2>{{ __('Notes') }}</h2>
        <p style="margin: 0; font-size: 10px; color: #334155;">{{ $appointment->notes }}</p>
    @endif

    <div class="footer">
        <p style="margin: 0 0 8px 0;"><strong>{{ __('Important') }}:</strong> {{ __('Please arrive 10 minutes before your scheduled time.') }}</p>
        <p style="margin: 0 0 8px 0;">{{ __('To cancel or reschedule, contact the lab at least 24 hours in advance.') }}</p>
        <p style="margin: 0;">{{ __('Generated on') }} {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
