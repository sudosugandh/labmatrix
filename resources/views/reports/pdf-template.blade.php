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
            color: #666;
            font-style: italic;
        }
        
        .report-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            margin: 8px 0;
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
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            color: #666;
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
            padding: 5px;
            border-right: 1px solid #ddd;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 10px;
        }
        
        .sample-info {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f0f9ff;
        }
        
        .test-results {
            margin-bottom: 8px;
        }
        
        .test-section-title {
            background-color: #1e40af;
            color: white;
            padding: 5px 10px;
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
        
        .signatures {
            display: table;
            width: 100%;
            margin-top: 12px;
            margin-bottom: 8px;
        }
        
        .signature-block {
            display: table-cell;
            width: 33.33%;
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
        
        .footer-info {
            margin-bottom: 3px;
        }
        
        .page-number {
            text-align: center;
            font-size: 8pt;
            color: #666;
            margin-top: 10px;
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
            <div class="lab-name" style="color: {{ $settings->primary_color ?? '#1e40af' }};">{{ $settings->brand_name ?? $lab->name ?? 'HealthMatrix Laboratory' }}</div>
            <div class="lab-tagline">{{ $settings->description ?? 'Pathology lab that cares' }}</div>
            @if($settings && $settings->address)
                <div style="font-size: 8pt; color: #666; margin-top: 3px;">{{ $settings->address }}</div>
            @endif
        </div>
        <div class="header-right">
            <div class="qr-section">
                @if(isset($qrCodeData) && $qrCodeData)
                    <div class="qr-code" style="display: flex; align-items: center; justify-content: center; padding: 5px;">
                        <img src="{{ $qrCodeData }}" alt="QR Code" style="width: 80px; height: 80px; border: 1px solid #ddd; padding: 3px; background: white;">
                    </div>
                @else
                    <div class="qr-code">
                        <div style="font-size: 6pt; text-align: center;">
                            QR<br>CODE<br>{{ $report->id ?? 'N/A' }}
                        </div>
                    </div>
                @endif
                <div class="qr-text">Scan QR code to verify report</div>
                <div class="qr-text" style="font-weight: bold; margin-top: 3px; font-size: 9pt;">{{ $report->report_number ?? strtoupper(substr($lab->name ?? 'HM', 0, 2)) . '-' . str_pad($report->id ?? '0000', 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
    </div>
    
    <!-- Report Title -->
    <div class="report-title">Laboratory Test Report</div>
    
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
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $patient->email ?? 'N/A' }}</div>
        </div>
        @if(!empty(trim((string) ($patient->address ?? ''))))
        <div class="info-row">
            <div class="info-label">Address:</div>
            <div class="info-value" style="white-space: pre-line;">{{ trim($patient->address) }}</div>
        </div>
        @endif
        @if($testOrder->doctor)
        <div class="info-row">
            <div class="info-label">Ref. By:</div>
            <div class="info-value">Dr. {{ $testOrder->doctor->name }}</div>
        </div>
        @endif
    </div>
    
    <!-- Sample Information -->
    <div class="sample-info">
        <div class="info-row">
            <div class="info-label">Lab ID:</div>
            <div class="info-value">{{ strtoupper(substr($lab->name ?? 'HM', 0, 2)) }}-{{ str_pad($testOrder->id, 8, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Registration on:</div>
            <div class="info-value">{{ $testOrder->created_at->format('d-M-Y H:i') }}</div>
        </div>
        @if($testOrder->sample_collected_at)
        <div class="info-row">
            <div class="info-label">Collected on:</div>
            <div class="info-value">{{ $testOrder->sample_collected_at->format('d-M-Y H:i') }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Test Name:</div>
            <div class="info-value">{{ $testOrder->test->name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Sample Type:</div>
            <div class="info-value">Blood / Serum / Other</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">{{ ucfirst($report->status ?? 'Final') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Approved on:</div>
            <div class="info-value">{{ $report->created_at->format('d-M-Y H:i') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Printed On:</div>
            <div class="info-value">{{ now()->format('d-M-Y H:i') }}</div>
        </div>
    </div>
    
    <!-- Test Results -->
    @if($testResults && $testResults->count() > 0)
    <div class="test-results">
        <div class="test-section-title" style="background-color: {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#1e40af' }};">{{ $testOrder->test->name ?? 'Test Results' }}</div>
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
                <tr>
                    <td><strong>{{ $result->parameter_name }}</strong></td>
                    <td>{{ $result->method_name ?? ($result->testParameter->method_name ?? 'Standard') }}</td>
                    <td>
                        <span class="{{ $result->status === 'high' ? 'flag-high' : ($result->status === 'low' ? 'flag-low' : ($result->status === 'critical' ? 'flag-critical' : '')) }}">
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
        
        @if($testResults->where('status', '!=', 'normal')->count() > 0)
        <div style="margin-top: 10px; font-size: 8pt; color: #666;">
            <strong>Legend:</strong> H = High, L = Low, C = Critical
        </div>
        @endif
    </div>
    @else
    <div class="test-results">
        <div class="test-section-title" style="background-color: {{ isset($settings) && $settings->primary_color ? $settings->primary_color : '#1e40af' }};">{{ $testOrder->test->name ?? 'Test Results' }}</div>
        <div style="padding: 20px; text-align: center; color: #666;">
            <p>Test results are being processed. Please check back later.</p>
        </div>
    </div>
    @endif
    
    <!-- Interpretation -->
    @if(isset($report->interpretation) && $report->interpretation)
    <div style="margin-top: 15px; padding: 10px; background-color: #dbeafe; border-left: 4px solid #3b82f6;">
        <strong>Interpretation:</strong>
        <div style="margin-top: 5px;">{{ $report->interpretation }}</div>
    </div>
    @endif
    
    <!-- Remarks -->
    @if(isset($report->remarks) && $report->remarks)
    <div style="margin-top: 15px; padding: 10px; background-color: #fef3c7; border-left: 4px solid #f59e0b;">
        <strong>Remarks:</strong> {{ $report->remarks }}
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
        @else
        <div class="signature-block">
            <div class="signature-line">
                <div class="signature-name">Dr. {{ $report->uploadedBy->name ?? 'Authorized Signatory' }}</div>
                <div class="signature-title">M.D. Pathology</div>
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
        <div class="signature-block">
            <div class="signature-line">
                <div class="signature-name">Report Status</div>
                <div class="signature-title" style="text-transform: uppercase; color: {{ isset($report->workflow_status) && $report->workflow_status === 'released' ? '#059669' : '#f59e0b' }};">
                    {{ isset($report->workflow_status) ? ucfirst($report->workflow_status) : 'Draft' }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div class="footer-info"><strong>{{ $settings->brand_name ?? $lab->name ?? 'HealthMatrix Laboratory' }}</strong></div>
        @if($settings && $settings->address)
        <div class="footer-info">{{ $settings->address }}</div>
        @elseif($lab && $lab->address)
        <div class="footer-info">{{ $lab->address }}</div>
        @endif
        @if($settings && $settings->phone)
        <div class="footer-info">Ph.: {{ $settings->phone }}</div>
        @elseif($settings && $settings->contact_phone)
        <div class="footer-info">Ph.: {{ $settings->contact_phone }}</div>
        @elseif($lab && $lab->phone)
        <div class="footer-info">Ph.: {{ $lab->phone }}</div>
        @endif
        @if($settings && $settings->email)
        <div class="footer-info">Email: {{ $settings->email }}</div>
        @elseif($settings && $settings->contact_email)
        <div class="footer-info">Email: {{ $settings->contact_email }}</div>
        @elseif($lab && $lab->email)
        <div class="footer-info">Email: {{ $lab->email }}</div>
        @endif
        @if($settings && $settings->footer_text)
        <div class="footer-info" style="margin-top: 8px; font-style: italic;">{{ $settings->footer_text }}</div>
        @endif
    </div>
    
    <!-- Page Number -->
    <div class="page-number">Page 1 of 1</div>
</body>
</html>
