<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Test Laboratory Report</title>
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
            line-height: 1.3;
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
        
        .lab-name {
            font-size: 17pt;
            font-weight: bold;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }
        
        .lab-tagline {
            font-size: 9pt;
            font-style: italic;
        }
        
        .report-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            margin: 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            padding: 6px 0;
        }
        
        .patient-info {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border: 1px solid #d1d9e6;
            padding: 8px 10px;
            background-color: #e8ecf1;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            padding: 5px;
            border-right: 1px solid #ddd;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 10px;
        }
        
        .test-section {
            margin-bottom: 12px;
            page-break-inside: auto;
        }
        
        .test-section-title {
            color: white;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 3px;
            border-radius: 3px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
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
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 8pt;
        }
        
        .results-table td {
            padding: 3px 6px;
            border: 1px solid #ddd;
            font-size: 8.5pt;
        }
        
        .results-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .flag-high {
            color: #dc2626;
            font-weight: bold;
        }
        
        .flag-low {
            color: #ea580c;
            font-weight: bold;
        }
        
        .flag-critical {
            color: #991b1b;
            font-weight: bold;
        }
        
        .interpretation-box {
            margin-top: 6px;
            padding: 6px 8px;
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            font-size: 8pt;
        }
        
        .signatures {
            display: table;
            width: 100%;
            margin-top: 12px;
            margin-bottom: 8px;
        }
        
        .signature-block {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 10px;
            vertical-align: top;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 28px;
            padding-top: 4px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 10pt;
        }
        
        .signature-title {
            font-size: 8pt;
            color: #666;
        }
        
        .footer {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 2px solid #333;
            font-size: 7.5pt;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            @if(isset($settings) && $settings->logo)
                <div style="margin-bottom: 8px;">
                    <img src="{{ public_path('storage/' . $settings->logo) }}" alt="Lab Logo" style="max-height: 60px; max-width: 200px;">
                </div>
            @endif
            <div class="lab-name" style="color: {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#6b21a8' }};">{{ isset($settings) && $settings->brand_name ? $settings->brand_name : ($lab->name ?? 'HealthMatrix Laboratory') }}</div>
            <div class="lab-tagline" style="color: {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#6b21a8' }};">{{ isset($settings) && $settings->description ? $settings->description : 'Pathology lab that cares' }}</div>
            @if(isset($settings) && $settings->address)
                <div style="font-size: 8pt; color: #666; margin-top: 3px;">{{ $settings->address }}</div>
            @endif
        </div>
        <div class="header-right">
            <div style="text-align: center;">
                @if(isset($qrCodeData) && $qrCodeData)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ $qrCodeData }}" alt="QR Code" style="width: 80px; height: 80px; border: 1px solid #ddd; padding: 3px; background: white;">
                    </div>
                    <div style="font-size: 7pt; color: #666; margin-bottom: 5px;">Scan to verify</div>
                @endif
                <div style="font-weight: bold; font-size: 11pt; margin-bottom: 3px;">{{ $report->report_number ?? 'N/A' }}</div>
                <div style="font-size: 8pt; color: #666;">Report Number</div>
            </div>
        </div>
    </div>
    
    <!-- Report Title -->
    <div class="report-title">Multi-Test Laboratory Report</div>
    
    <!-- Patient Information -->
    <div class="patient-info">
        <div class="info-row">
            <div class="info-label">Name:</div>
            <div class="info-value">{{ $patient->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Sex/Age:</div>
            <div class="info-value">{{ ucfirst($patient->gender ?? 'N/A') }} / {{ $patient->age ?? 'N/A' }} Y</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date of Birth:</div>
            <div class="info-value">{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d-M-Y') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value">{{ $patient->phone ?? 'N/A' }}</div>
        </div>
        @if(!empty(trim((string) ($patient->address ?? ''))))
        <div class="info-row">
            <div class="info-label">Address:</div>
            <div class="info-value" style="white-space: pre-line;">{{ trim($patient->address) }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Report Date:</div>
            <div class="info-value">{{ $report->created_at->format('d-M-Y H:i') }}</div>
        </div>
    </div>
    
    <!-- Test Results Sections -->
    @foreach($testOrders as $testOrder)
        @php
            $testResults = $testOrder->testResults;
            $template = $testTemplates[$testOrder->id] ?? null;
        @endphp
        
        <div class="test-section">
            <div class="test-section-title" style="background-color: {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#6b21a8' }};">{{ $testOrder->test->name }}</div>
            
            @if($template && $template->sections->count() > 0)
                <!-- Use Template Sections -->
                @foreach($template->sections->where('is_visible', true) as $section)
                    @php
                        $sectionData = $templateService->renderSection($section, $testOrder, $testResults);
                    @endphp
                    
                    @if($section->title)
                        <div style="font-weight: bold; font-size: 10pt; margin: 10px 0 5px 0; color: {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#1e40af' }};">{{ $section->title }}</div>
                    @endif
                    
                    @if($section->section_type === 'parameters')
                        <!-- Parameters Table -->
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Parameter</th>
                                    <th style="width: 15%;">Method</th>
                                    <th style="width: 15%;">Result</th>
                                    <th style="width: 10%;">Unit</th>
                                    <th style="width: 30%;">Biological Ref. Interval</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testResults as $result)
                                    @php
                                        $statusClass = '';
                                        if($result->status === 'high') $statusClass = 'flag-high';
                                        elseif($result->status === 'low') $statusClass = 'flag-low';
                                        elseif($result->status === 'critical') $statusClass = 'flag-critical';
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $result->parameter_name }}</strong></td>
                                        <td>{{ $result->method_name ?? ($result->testParameter->method_name ?? 'Standard') }}</td>
                                        <td>
                                            <span class="{{ $statusClass }}">
                                                {{ $result->result_value ?? 'N/A' }}
                                                @if($result->status_flag)
                                                    <sup>{{ $result->status_flag }}</sup>
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $result->unit ?? '-' }}</td>
                                        <td>{{ $result->reference_range ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    
                    @elseif($section->section_type === 'calculated')
                        <!-- Calculated Value -->
                        @if(isset($sectionData['calculated_value']))
                            <div style="font-size: 12pt; font-weight: bold; color: {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#1e40af' }}; padding: 10px; background-color: #f0f9ff; border-left: 4px solid {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#1e40af' }}; margin: 10px 0;">
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
                            <div style="padding: 12px; background-color: #fef3c7; border-left: 4px solid #f59e0b; margin: 15px 0; border-radius: 4px;">
                                <strong>Interpretation:</strong><br>
                                {{ $sectionData['interpretation'] }}
                            </div>
                        @endif
                    
                    @elseif($section->section_type === 'text')
                        <!-- Text Content -->
                        <div style="padding: 10px; line-height: 1.6;">
                            {!! nl2br(e($sectionData['text_content'] ?? '')) !!}
                        </div>
                    
                    @elseif($section->section_type === 'table')
                        <!-- Custom Table -->
                        @if(isset($sectionData['table_data']) && is_array($sectionData['table_data']))
                            <table class="results-table" style="margin: 10px 0;">
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
                        <div style="padding: 10px; background-color: #f9fafb; border: 1px solid #ddd; border-radius: 4px; font-size: 9pt; color: #666;">
                            {!! nl2br(e($sectionData['notes'] ?? '')) !!}
                        </div>
                    @endif
                @endforeach
            @elseif($testOrder->testResults && $testOrder->testResults->count() > 0)
                <!-- Fallback: Default Parameters Table if no template -->
                <table class="results-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Parameter</th>
                            <th style="width: 15%;">Method</th>
                            <th style="width: 15%;">Result</th>
                            <th style="width: 10%;">Unit</th>
                            <th style="width: 30%;">Biological Ref. Interval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testOrder->testResults as $result)
                            @php
                                $statusClass = '';
                                if($result->status === 'high') $statusClass = 'flag-high';
                                elseif($result->status === 'low') $statusClass = 'flag-low';
                                elseif($result->status === 'critical') $statusClass = 'flag-critical';
                            @endphp
                            <tr>
                                <td><strong>{{ $result->parameter_name }}</strong></td>
                                <td>{{ $result->method_name ?? ($result->testParameter->method_name ?? 'Standard') }}</td>
                                <td>
                                    <span class="{{ $statusClass }}">
                                        {{ $result->result_value ?? 'N/A' }}
                                        @if($result->status_flag)
                                            <sup>{{ $result->status_flag }}</sup>
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $result->unit ?? '-' }}</td>
                                <td>{{ $result->reference_range ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding: 20px; text-align: center; color: #666;">
                    <p>Test results are being processed.</p>
                </div>
            @endif
        </div>
    @endforeach
    
    <!-- Overall Interpretation -->
    @if(isset($report->interpretation) && $report->interpretation)
    <div class="interpretation-box">
        <strong>Overall Interpretation:</strong>
        <div style="margin-top: 5px;">{{ $report->interpretation }}</div>
    </div>
    @endif
    
    <!-- Signatures -->
    <div class="signatures">
        @if(isset($report->doctor_name) && $report->doctor_name)
        <div class="signature-block">
            <div class="signature-line">
                @if(isset($report->doctor_signature) && $report->doctor_signature)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ public_path('storage/' . $report->doctor_signature) }}" alt="Doctor Signature" style="max-height: 60px; max-width: 150px;">
                    </div>
                @endif
                <div class="signature-name">Dr. {{ $report->doctor_name }}</div>
                <div class="signature-title">{{ $report->doctor_qualification ?? 'M.D. Pathology' }}</div>
            </div>
        </div>
        @endif
        <div class="signature-block">
            <div class="signature-line">
                <div class="signature-name">Authorized Signatory</div>
                <div class="signature-title">MD Path</div>
                <div style="font-size: 7pt; color: #666; margin-top: 5px;">This is an Electronically Authenticated Report.</div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div><strong>{{ isset($settings) && $settings->brand_name ? $settings->brand_name : ($lab->name ?? 'HealthMatrix Laboratory') }}</strong></div>
        @if(isset($settings) && $settings->address)
        <div>{{ $settings->address }}</div>
        @elseif($lab && $lab->address)
        <div>{{ $lab->address }}</div>
        @endif
        @if(isset($settings) && $settings->phone)
        <div>Ph.: {{ $settings->phone }}</div>
        @elseif(isset($settings) && $settings->contact_phone)
        <div>Ph.: {{ $settings->contact_phone }}</div>
        @elseif($lab && $lab->phone)
        <div>Ph.: {{ $lab->phone }}</div>
        @endif
        @if(isset($settings) && $settings->email)
        <div>Email: {{ $settings->email }}</div>
        @elseif(isset($settings) && $settings->contact_email)
        <div>Email: {{ $settings->contact_email }}</div>
        @elseif($lab && $lab->email)
        <div>Email: {{ $lab->email }}</div>
        @endif
        @if(isset($settings) && $settings->footer_text)
        <div style="margin-top: 8px; font-style: italic;">{{ $settings->footer_text }}</div>
        @endif
    </div>
</body>
</html>
