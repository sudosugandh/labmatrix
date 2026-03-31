<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Report</title>
    <style>
        @page {
            margin: 9mm 11mm;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
            line-height: 1.3;
        }
        
        /* Header - Dr Lal PathLabs style */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 0;
            padding-bottom: 6px;
        }
        
        .header-left {
            display: table-cell;
            width: 55%;
            vertical-align: top;
        }
        
        .header-right {
            display: table-cell;
            width: 45%;
            text-align: right;
            vertical-align: top;
            font-size: 7pt;
            color: #1e40af;
        }
        
        .header-divider {
            height: 3px;
            background: linear-gradient(90deg, #fcd34d 0%, #fbbf24 100%);
            margin: 4px 0 8px 0;
        }
        
        .lab-logo {
            max-height: 48px;
            max-width: 180px;
            margin-bottom: 4px;
        }
        
        .lab-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1e40af;
            font-style: italic;
            letter-spacing: 0.3px;
        }
        
        /* Patient info - 2 column grid */
        .patient-meta {
            display: table;
            width: 100%;
            margin-bottom: 6px;
            border: 1px solid #e5e7eb;
            padding: 5px 8px;
            background-color: #fafafa;
        }
        
        .patient-meta-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }
        
        .patient-meta-col:last-child {
            padding-right: 0;
            padding-left: 15px;
            border-left: 1px solid #e5e7eb;
        }
        
        .meta-row {
            margin-bottom: 4px;
            font-size: 8.5pt;
        }
        
        .meta-label {
            font-weight: bold;
            display: inline;
        }
        
        .meta-label::after {
            content: " ";
        }
        
        .meta-value {
            display: inline;
        }
        
        .nabl-placeholder {
            float: right;
            width: 36px;
            height: 36px;
            border: 1px solid #cbd5e1;
            border-radius: 50%;
            text-align: center;
            line-height: 34px;
            font-size: 6pt;
            color: #64748b;
        }
        
        /* Report title */
        .report-heading {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin: 5px 0 6px 0;
            text-transform: none;
        }
        
        /* Test results table - Dr Lal style */
        .test-group-header {
            background-color: #e5e7eb;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            margin-top: 6px;
        }
        
        .test-group-header:first-child {
            margin-top: 0;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        
        .results-table th {
            padding: 3px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 7.5pt;
            border-bottom: 1px solid #000;
            border-top: 1px solid #000;
        }
        
        .results-table td {
            padding: 3px 6px;
            font-size: 8pt;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .results-table th:nth-child(2),
        .results-table td:nth-child(2) {
            text-align: center;
        }
        
        .results-table th:nth-child(3),
        .results-table td:nth-child(3) {
            text-align: center;
        }
        
        .results-table th:nth-child(4),
        .results-table td:nth-child(4) {
            text-align: right;
        }
        
        .test-name-method {
            font-size: 7.5pt;
            color: #4b5563;
            font-weight: normal;
            margin-top: 1px;
        }
        
        .result-value {
            font-weight: bold;
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
        
        /* Notes section */
        .notes-section {
            margin: 6px 0;
        }
        
        .notes-title {
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .notes-list {
            font-size: 8pt;
            line-height: 1.5;
            padding-left: 16px;
        }
        
        /* Interpretation - monospace for tables */
        .interpretation-section {
            margin: 6px 0;
        }
        
        .interpretation-title {
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .interpretation-table {
            font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
            font-size: 7.5pt;
            white-space: pre;
            margin: 6px 0;
        }
        
        /* Doctor signatures - 2x4 grid style */
        .signatures-grid {
            display: table;
            width: 100%;
            margin-top: 8px;
            margin-bottom: 6px;
        }
        
        .signatures-row {
            display: table-row;
        }
        
        .signature-cell {
            display: table-cell;
            width: 25%;
            padding: 4px;
            text-align: center;
            vertical-align: top;
            border: 1px solid #e5e7eb;
        }
        
        .signature-img {
            max-height: 50px;
            max-width: 120px;
            margin-bottom: 4px;
        }
        
        .signature-reg {
            font-size: 6.5pt;
            color: #64748b;
            margin-bottom: 2px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 9pt;
        }
        
        .signature-qual {
            font-size: 7pt;
            color: #4b5563;
        }
        
        /* Footer */
        .footer-qr-barcode {
            text-align: center;
            margin: 6px 0 4px 0;
        }
        
        .footer-qr {
            display: inline-block;
            margin-bottom: 6px;
        }
        
        .page-number {
            text-align: right;
            font-size: 7.5pt;
            color: #64748b;
            margin-bottom: 4px;
        }
        
        .footer-contact-bar {
            background-color: #fcd34d;
            padding: 5px 10px;
            font-size: 7.5pt;
            font-weight: bold;
            text-align: center;
            margin-top: 4px;
        }
        
        .footer-contact-details {
            font-size: 7.5pt;
            margin-top: 4px;
            font-weight: normal;
        }
        
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            @if(isset($settings) && $settings->logo)
                <img src="{{ public_path('storage/' . $settings->logo) }}" alt="Logo" class="lab-logo">
            @endif
            <div class="lab-name">{{ $settings->brand_name ?? $lab->name ?? 'HealthMatrix' }}</div>
        </div>
        <div class="header-right">
            @if($settings && $settings->address)
                <div>{{ $settings->address }}</div>
            @endif
            @if($settings && $settings->website_url ?? false)
                <div>{{ $settings->website_url }}</div>
            @endif
        </div>
    </div>
    <div class="header-divider"></div>
    
    <!-- Patient & Lab Metadata (2 columns - Dr Lal style) -->
    <div class="patient-meta">
        <div class="patient-meta-col">
            <span class="nabl-placeholder">NABL</span>
            <div class="meta-row"><span class="meta-label">Name :</span><span class="meta-value">{{ $patient->name ?? 'N/A' }}</span></div>
            <div class="meta-row"><span class="meta-label">Lab No. :</span><span class="meta-value">{{ $report->report_number ?? strtoupper(substr($lab->name ?? 'HM', 0, 2)) . '-' . str_pad($report->id ?? $testOrder->id, 8, '0', STR_PAD_LEFT) }}</span></div>
            <div class="meta-row"><span class="meta-label">Ref By :</span><span class="meta-value">{{ $testOrder->doctor ? 'Dr. ' . $testOrder->doctor->name : 'SELF' }}</span></div>
            <div class="meta-row"><span class="meta-label">Collected :</span><span class="meta-value">{{ $testOrder->sample_collected_at ? $testOrder->sample_collected_at->format('d-M-Y H:i') : ($testOrder->created_at->format('d-M-Y H:i')) }}</span></div>
            <div class="meta-row"><span class="meta-label">A/c Status :</span><span class="meta-value">-</span></div>
            <div class="meta-row"><span class="meta-label">Collected at :</span><span class="meta-value">{{ $settings->address ?? $lab->address ?? '-' }}</span></div>
        </div>
        <div class="patient-meta-col">
            <div class="meta-row"><span class="meta-label">Age :</span><span class="meta-value">{{ $patient->age ? $patient->age . ' Years' : 'N/A' }}</span></div>
            <div class="meta-row"><span class="meta-label">Gender :</span><span class="meta-value">{{ ucfirst($patient->gender ?? 'N/A') }}</span></div>
            <div class="meta-row"><span class="meta-label">Reported :</span><span class="meta-value">{{ isset($report->released_at) && $report->released_at ? $report->released_at->format('d-M-Y H:i') : (isset($report->created_at) ? $report->created_at : now())->format('d-M-Y H:i') }}</span></div>
            <div class="meta-row"><span class="meta-label">Report Status :</span><span class="meta-value">{{ (isset($report->workflow_status) && $report->workflow_status === 'revised') ? 'Revised' : 'Final' }}</span></div>
            <div class="meta-row"><span class="meta-label">Processed at :</span><span class="meta-value">{{ $settings->address ?? $lab->address ?? '-' }}</span></div>
        </div>
    </div>
    
    <!-- Report Title -->
    <div class="report-heading">Test Report</div>
    
    <!-- Test Results -->
    @if($testResults && $testResults->count() > 0)
    <div class="test-group-header">{{ $testOrder->test->name ?? 'Test Results' }}</div>
    <table class="results-table">
        <thead>
            <tr>
                <th style="width: 40%;">Test Name</th>
                <th style="width: 18%;">Results</th>
                <th style="width: 12%;">Units</th>
                <th style="width: 30%;">Bio. Ref. Interval</th>
            </tr>
        </thead>
        <tbody>
            @foreach($testResults as $result)
            <tr>
                <td>
                    <strong>{{ $result->parameter_name }}</strong>
                    @if($result->method_name || ($result->testParameter && $result->testParameter->method_name))
                        <div class="test-name-method">({{ $result->method_name ?? $result->testParameter->method_name ?? 'Standard' }})</div>
                    @endif
                </td>
                <td>
                    <span class="result-value {{ $result->status === 'high' ? 'flag-high' : ($result->status === 'low' ? 'flag-low' : ($result->status === 'critical' ? 'flag-critical' : '')) }}">
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
    <div style="font-size: 7pt; color: #64748b; margin-top: 4px;">
        <strong>Legend:</strong> H = High, L = Low, C = Critical
    </div>
    @endif
    @else
    <div class="test-group-header">{{ $testOrder->test->name ?? 'Test Results' }}</div>
    <div style="padding: 20px; text-align: center; color: #64748b; font-size: 9pt;">
        Test results are being processed.
    </div>
    @endif
    
    <!-- Notes -->
    @if(isset($report->remarks) && $report->remarks)
    <div class="notes-section">
        <div class="notes-title">Note</div>
        <div class="notes-list">{{ $report->remarks }}</div>
    </div>
    @endif
    
    <!-- Interpretation -->
    @if(isset($report->interpretation) && $report->interpretation)
    <div class="interpretation-section">
        <div class="interpretation-title">Interpretation</div>
        <div style="font-size: 8.5pt; line-height: 1.5;">{{ $report->interpretation }}</div>
    </div>
    @endif
    
    <!-- Doctor Signatures -->
    <div class="signatures-grid">
        <div class="signatures-row">
            <div class="signature-cell">
                @if(isset($report->doctor_signature) && $report->doctor_signature)
                    <img src="{{ public_path('storage/' . $report->doctor_signature) }}" alt="Signature" class="signature-img">
                @endif
                @if(isset($report->doctor_registration) && $report->doctor_registration)
                <div class="signature-reg">{{ $report->doctor_registration }}</div>
                @endif
                <div class="signature-name">Dr. {{ $report->doctor_name ?? ($report->uploadedBy->name ?? 'Authorized Signatory') }}</div>
                <div class="signature-qual">{{ $report->doctor_qualification ?? 'MD, Pathology' }}</div>
                <div class="signature-qual">Consultant Pathologist</div>
                <div class="signature-qual">{{ $settings->brand_name ?? $lab->name }}</div>
            </div>
            <div class="signature-cell"></div>
            <div class="signature-cell"></div>
            <div class="signature-cell"></div>
        </div>
    </div>
    
    @if(isset($report->workflow_status) && $report->workflow_status === 'revised')
    <div style="font-size: 8pt; color: #64748b; margin-bottom: 10px;">This is a revised report & supersedes all the previously issued reports.</div>
    @endif
    
    <!-- Footer: QR Code -->
    <div class="footer-qr-barcode">
        @if(isset($qrCodeData) && $qrCodeData)
            <div class="footer-qr">
                <img src="{{ $qrCodeData }}" alt="QR" style="width: 70px; height: 70px; border: 1px solid #e5e7eb;">
            </div>
        @endif
    </div>
    
    <div class="page-number">Page 1 of 1</div>
    
    <!-- Yellow Contact Bar -->
    <div class="footer-contact-bar">
        If test results are alarming or unexpected, please contact Customer Care immediately.
        <div class="footer-contact-details">
            @if($settings && ($settings->phone || $settings->contact_phone))
                Tel: {{ $settings->phone ?? $settings->contact_phone }}
            @elseif($lab && $lab->phone)
                Tel: {{ $lab->phone }}
            @endif
            @if($settings && ($settings->email || $settings->contact_email))
                | E-mail: {{ $settings->email ?? $settings->contact_email }}
            @elseif($lab && $lab->email)
                | E-mail: {{ $lab->email }}
            @endif
        </div>
    </div>
</body>
</html>
