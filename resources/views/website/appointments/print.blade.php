<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation - {{ $settings->lab_name ?? 'HealthMatrix' }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section h2 {
            color: #374151;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #6B7280;
        }
        .info-value {
            flex: 1;
            color: #111827;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            color: #6B7280;
            font-size: 12px;
        }
        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-button button {
            background: #4F46E5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .print-button button:hover {
            background: #4338CA;
        }
    </style>
</head>
<body>
    <div class="no-print print-button">
        <button onclick="window.print()">Print / Save as PDF</button>
    </div>

    <div class="header">
        <h1>{{ $settings->lab_name ?? 'HealthMatrix Test Lab' }}</h1>
        <p>{{ $settings->address ?? '' }}</p>
        @if($settings->phone)
            <p>Phone: {{ $settings->phone }}</p>
        @endif
        @if($settings->email)
            <p>Email: {{ $settings->email }}</p>
        @endif
    </div>

    <div class="info-section">
        <h2>Appointment Confirmation</h2>
        <div class="info-row">
            <div class="info-label">Appointment ID:</div>
            <div class="info-value">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date:</div>
            <div class="info-value">{{ $appointment->appointment_date->format('l, F d, Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Time:</div>
            <div class="info-value">
                @php
                    $timeStr = strlen($appointment->appointment_time) == 5 ? $appointment->appointment_time . ':00' : $appointment->appointment_time;
                    echo \Carbon\Carbon::createFromFormat('H:i:s', $timeStr)->format('h:i A');
                @endphp
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge" style="background: #DBEAFE; color: #1E40AF;">
                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h2>Patient Information</h2>
        <div class="info-row">
            <div class="info-label">Name:</div>
            <div class="info-value">{{ $appointment->patient->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value">{{ $appointment->patient->phone }}</div>
        </div>
        @if($appointment->patient->email)
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $appointment->patient->email }}</div>
        </div>
        @endif
        @if($appointment->patient->age)
        <div class="info-row">
            <div class="info-label">Age:</div>
            <div class="info-value">{{ $appointment->patient->age }} years</div>
        </div>
        @endif
        @if($appointment->patient->gender)
        <div class="info-row">
            <div class="info-label">Gender:</div>
            <div class="info-value">{{ ucfirst($appointment->patient->gender) }}</div>
        </div>
        @endif
    </div>

    @if($appointment->notes)
    <div class="info-section">
        <h2>Notes</h2>
        <p>{{ $appointment->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p><strong>Important:</strong> Please arrive 10 minutes before your scheduled appointment time.</p>
        <p>If you need to cancel or reschedule, please contact us at least 24 hours in advance.</p>
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>
</body>
</html>
