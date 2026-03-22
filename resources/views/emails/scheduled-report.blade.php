<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3B82F6;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9fafb;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Scheduled Report: {{ $scheduledReport->name }}</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Your scheduled report "<strong>{{ $scheduledReport->name }}</strong>" has been generated and is attached to this email.</p>
            
            @if($scheduledReport->description)
                <p><strong>Description:</strong> {{ $scheduledReport->description }}</p>
            @endif
            
            <p><strong>Report Type:</strong> {{ ucfirst($template->report_type) }}</p>
            <p><strong>Frequency:</strong> {{ ucfirst($scheduledReport->frequency) }}</p>
            <p><strong>Format:</strong> {{ strtoupper($scheduledReport->format) }}</p>
            
            <p>Please find the report attached to this email.</p>
            
            <p>Best regards,<br>HealthMatrix System</p>
        </div>
        <div class="footer">
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
