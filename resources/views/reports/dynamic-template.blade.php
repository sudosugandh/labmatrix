<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Test Report</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.32;
        }
        
        .header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 2px solid #333;
            padding-bottom: 6px;
        }
        
        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .header-right {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: top;
        }
        
        .lab-logo {
            max-width: 110px;
            max-height: 64px;
            margin-bottom: 3px;
        }
        
        .lab-name {
            font-size: 17pt;
            font-weight: bold;
            margin-bottom: 3px;
            letter-spacing: 0.3px;
            color: {{ $settings->primary_color ?? '#1e40af' }};
        }
        
        .lab-tagline {
            font-size: 9pt;
            color: #666;
            font-style: italic;
        }
        
        .lab-contact {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }
        
        .report-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            margin: 6px 0 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .qr-section {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            margin: 0 auto 5px;
        }
        
        .qr-text {
            font-size: 7pt;
            color: #666;
        }
        
        .patient-info {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border: 1px solid #ddd;
            padding: 6px 8px;
            background-color: #f9fafb;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            padding: 3px 5px;
            border-right: 1px solid #ddd;
        }
        
        .info-value {
            display: table-cell;
            padding: 3px 8px;
        }
        
        .section {
            margin-bottom: 10px;
            page-break-inside: auto;
        }
        
        .section-title {
            background-color: {{ $settings->primary_color ?? '#1e40af' }};
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 3px;
            border-radius: 3px;
        }
        
        .section-content {
            padding: 6px 4px;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        
        .results-table th {
            background-color: #e5e7eb;
            padding: 4px 6px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 8pt;
        }
        
        .results-table td {
            padding: 3px 6px;
            border: 1px solid #ddd;
            font-size: 8.5pt;
        }
        
        .results-table tbody tr {
            page-break-inside: avoid;
        }
        
        .results-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .abnormal-high {
            background-color: #fee2e2 !important;
            color: #991b1b;
            font-weight: bold;
        }
        
        .abnormal-low {
            background-color: #fef3c7 !important;
            color: #92400e;
            font-weight: bold;
        }
        
        .abnormal-critical {
            background-color: #fecaca !important;
            color: #7f1d1d;
            font-weight: bold;
        }
        
        .calculated-value {
            font-size: 10pt;
            font-weight: bold;
            color: {{ $settings->primary_color ?? '#1e40af' }};
            padding: 6px 8px;
            background-color: #f0f9ff;
            border-left: 4px solid {{ $settings->primary_color ?? '#1e40af' }};
            margin: 6px 0;
        }
        
        .interpretation-box {
            padding: 6px 8px;
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            margin: 8px 0;
            border-radius: 3px;
            font-size: 8.5pt;
        }
        
        .text-content {
            padding: 4px 2px;
            line-height: 1.45;
            font-size: 8.5pt;
        }
        
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
        }
        
        .custom-table th,
        .custom-table td {
            padding: 3px 6px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 8.5pt;
        }
        
        .custom-table th {
            background-color: #e5e7eb;
            font-weight: bold;
        }
        
        .notes-section {
            padding: 6px 8px;
            background-color: #f9fafb;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 8pt;
            color: #666;
        }
        
        .footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 2px solid #333;
            display: table;
            width: 100%;
            font-size: 8pt;
        }
        
        .footer-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }
        
        .doctor-signature {
            margin-top: 8px;
        }
        
        .doctor-name {
            font-weight: bold;
            margin-top: 5px;
        }
        
        .doctor-qualification {
            font-size: 9pt;
            color: #666;
        }
        
        .signature-image {
            max-width: 150px;
            max-height: 60px;
            margin-bottom: 5px;
        }
        
        .report-number {
            font-size: 9pt;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            @if($settings && $settings->logo)
                <img src="{{ Storage::url($settings->logo) }}" alt="Lab Logo" class="lab-logo">
            @endif
            <div class="lab-name">{{ $settings->lab_name ?? ($lab->name ?? 'Laboratory') }}</div>
            @if($settings && $settings->tagline)
                <div class="lab-tagline">{{ $settings->tagline }}</div>
            @endif
            @if($settings)
                <div class="lab-contact">
                    @if($settings->address) {{ $settings->address }}<br> @endif
                    @if($settings->phone) Phone: {{ $settings->phone }} @endif
                    @if($settings->email) | Email: {{ $settings->email }} @endif
                </div>
            @endif
        </div>
        <div class="header-right">
            <div class="qr-section">
                @if($qrCodeData)
                    <img src="{{ $qrCodeData }}" alt="QR Code" class="qr-code">
                @endif
                <div class="qr-text">Report #{{ $report->report_number ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Report Title -->
    <div class="report-title">{{ $testOrder->test->name ?? 'Test Report' }}</div>

    <!-- Patient Information -->
    <div class="patient-info">
        <div class="info-row">
            <div class="info-label">Patient Name:</div>
            <div class="info-value">{{ $patient->name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Age / Gender:</div>
            <div class="info-value">
                @if($patient->age) {{ $patient->age }} years @endif
                @if($patient->gender) / {{ ucfirst($patient->gender) }} @endif
            </div>
        </div>
        @if($patient->phone)
        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value">{{ $patient->phone }}</div>
        </div>
        @endif
        @if($testOrder->doctor_name)
        <div class="info-row">
            <div class="info-label">Referring Doctor:</div>
            <div class="info-value">{{ $testOrder->doctor_name }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Sample Date:</div>
            <div class="info-value">{{ $testOrder->sample_collected_at ? $testOrder->sample_collected_at->format('d M Y, h:i A') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Report Date:</div>
            <div class="info-value">{{ $report->created_at->format('d M Y, h:i A') }}</div>
        </div>
    </div>

    <!-- Dynamic Template Sections -->
    @if($template && $template->sections->count() > 0)
        @foreach($template->sections->where('is_visible', true) as $section)
            @php
                $sectionData = $templateService->renderSection($section, $testOrder, $testResults);
            @endphp
            
            <div class="section">
                @if($section->title)
                    <div class="section-title">{{ $section->title }}</div>
                @endif
                
                <div class="section-content">
                    @if($section->section_type === 'parameters')
                        <!-- Parameters Table -->
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Result</th>
                                    <th>Unit</th>
                                    <th>Reference Range</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testResults as $result)
                                    @php
                                        $statusClass = '';
                                        if($result->status === 'high') $statusClass = 'abnormal-high';
                                        elseif($result->status === 'low') $statusClass = 'abnormal-low';
                                        elseif($result->status === 'critical') $statusClass = 'abnormal-critical';
                                    @endphp
                                    <tr class="{{ $statusClass }}">
                                        <td>{{ $result->parameter_name ?? 'N/A' }}</td>
                                        <td>{{ $result->result_value ?? 'N/A' }}</td>
                                        <td>{{ $result->unit ?? 'N/A' }}</td>
                                        <td>{{ $result->formatted_reference_range ?? 'N/A' }}</td>
                                        <td>
                                            @if($result->status === 'normal')
                                                <span style="color: green;">✓ Normal</span>
                                            @elseif($result->status === 'high')
                                                <span style="color: red;">↑ High</span>
                                            @elseif($result->status === 'low')
                                                <span style="color: orange;">↓ Low</span>
                                            @elseif($result->status === 'critical')
                                                <span style="color: darkred;">⚠ Critical</span>
                                            @else
                                                {{ ucfirst($result->status ?? 'N/A') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    
                    @elseif($section->section_type === 'calculated')
                        <!-- Calculated Value -->
                        @if(isset($sectionData['calculated_value']))
                            <div class="calculated-value">
                                {{ $section->title ?? 'Calculated Value' }}: 
                                {{ number_format($sectionData['calculated_value'], 2) }}
                                @if(isset($sectionData['unit']))
                                    {{ $sectionData['unit'] }}
                                @endif
                            </div>
                            @if(isset($sectionData['formula']))
                                <div style="font-size: 8pt; color: #666; margin-top: 5px;">
                                    Formula: {{ $sectionData['formula'] }}
                                </div>
                            @endif
                        @endif
                    
                    @elseif($section->section_type === 'interpretation')
                        <!-- Interpretation -->
                        @if(isset($sectionData['interpretation']) && $sectionData['interpretation'])
                            <div class="interpretation-box">
                                <strong>Interpretation:</strong><br>
                                {{ $sectionData['interpretation'] }}
                            </div>
                        @endif
                    
                    @elseif($section->section_type === 'text')
                        <!-- Text Content -->
                        <div class="text-content">
                            {!! nl2br(e($sectionData['text_content'] ?? '')) !!}
                        </div>
                    
                    @elseif($section->section_type === 'table')
                        <!-- Custom Table -->
                        @if(isset($sectionData['table_data']) && is_array($sectionData['table_data']))
                            <table class="custom-table">
                                @if(isset($sectionData['table_data']['headers']))
                                    <thead>
                                        <tr>
                                            @foreach($sectionData['table_data']['headers'] as $header)
                                                <th>{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                @endif
                                @if(isset($sectionData['table_data']['rows']))
                                    <tbody>
                                        @foreach($sectionData['table_data']['rows'] as $row)
                                            <tr>
                                                @foreach($row as $cell)
                                                    <td>{{ $cell }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @endif
                            </table>
                        @endif
                    
                    @elseif($section->section_type === 'notes')
                        <!-- Notes -->
                        <div class="notes-section">
                            {!! nl2br(e($sectionData['notes'] ?? '')) !!}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <!-- Fallback: Default Parameters Table -->
        <div class="section">
            <div class="section-title">Test Results</div>
            <div class="section-content">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Result</th>
                            <th>Unit</th>
                            <th>Reference Range</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testResults as $result)
                            @php
                                $statusClass = '';
                                if($result->status === 'high') $statusClass = 'abnormal-high';
                                elseif($result->status === 'low') $statusClass = 'abnormal-low';
                                elseif($result->status === 'critical') $statusClass = 'abnormal-critical';
                            @endphp
                            <tr class="{{ $statusClass }}">
                                <td>{{ $result->parameter_name ?? 'N/A' }}</td>
                                <td>{{ $result->result_value ?? 'N/A' }}</td>
                                <td>{{ $result->unit ?? 'N/A' }}</td>
                                <td>{{ $result->formatted_reference_range ?? 'N/A' }}</td>
                                <td>
                                    @if($result->status === 'normal')
                                        <span style="color: green;">✓ Normal</span>
                                    @elseif($result->status === 'high')
                                        <span style="color: red;">↑ High</span>
                                    @elseif($result->status === 'low')
                                        <span style="color: orange;">↓ Low</span>
                                    @elseif($result->status === 'critical')
                                        <span style="color: darkred;">⚠ Critical</span>
                                    @else
                                        {{ ucfirst($result->status ?? 'N/A') }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Report Interpretation (if exists) -->
    @if($report->interpretation)
        <div class="interpretation-box">
            <strong>Overall Interpretation:</strong><br>
            {{ $report->interpretation }}
        </div>
    @endif

    <!-- Footer with Doctor Signature -->
    <div class="footer">
        <div class="footer-left">
            <div class="report-number">
                Report Number: {{ $report->report_number ?? 'N/A' }}<br>
                Generated on: {{ now()->format('d M Y, h:i A') }}
            </div>
        </div>
        <div class="footer-right">
            @if($report->doctor_name || $report->doctor_signature)
                <div class="doctor-signature">
                    @if($report->doctor_signature)
                        <img src="{{ Storage::url($report->doctor_signature) }}" alt="Doctor Signature" class="signature-image">
                    @endif
                    @if($report->doctor_name)
                        <div class="doctor-name">{{ $report->doctor_name }}</div>
                    @endif
                    @if($report->doctor_qualification)
                        <div class="doctor-qualification">{{ $report->doctor_qualification }}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>

