<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $bill->bill_number }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4f46e5;
            margin: 0;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            flex: 1;
        }
        .info-box h3 {
            margin-top: 0;
            color: #374151;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .total-label {
            width: 150px;
            text-align: right;
            padding-right: 20px;
        }
        .total-value {
            width: 150px;
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #4f46e5; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
            Print Invoice
        </button>
        <button onclick="window.close()" style="background: #6b7280; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>

    <div class="header">
        <h1>{{ $lab->name ?? 'HealthMatrix Diagnostic Center' }}</h1>
        <p style="margin: 5px 0; color: #6b7280;">{{ $lab->address ?? '' }}</p>
        <p style="margin: 5px 0; color: #6b7280;">Phone: {{ $lab->phone ?? '' }} | Email: {{ $lab->email ?? '' }}</p>
    </div>

    <div class="invoice-info">
        <div class="info-box">
            <h3>Bill To:</h3>
            <p><strong>{{ $bill->patient->name }}</strong></p>
            <p>{{ $bill->patient->address }}</p>
            <p>Phone: {{ $bill->patient->phone }}</p>
            @if($bill->patient->email)
            <p>Email: {{ $bill->patient->email }}</p>
            @endif
        </div>
        <div class="info-box" style="text-align: right;">
            <h3>Invoice Details:</h3>
            <p><strong>Bill Number:</strong> {{ $bill->bill_number }}</p>
            <p><strong>Date:</strong> {{ $bill->bill_date->format('M d, Y') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($bill->status) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Test Name</th>
                <th style="text-align: right;">Price (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bill->testOrders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->test->name }}</td>
                <td style="text-align: right;">{{ number_format($order->final_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <div class="total-label">Subtotal:</div>
            <div class="total-value">₹{{ number_format($bill->subtotal, 2) }}</div>
        </div>
        @if($bill->discount > 0)
        <div class="total-row">
            <div class="total-label">Discount:</div>
            <div class="total-value" style="color: #dc2626;">-₹{{ number_format($bill->discount, 2) }}</div>
        </div>
        @endif
        @if($bill->tax > 0)
        <div class="total-row">
            <div class="total-label">Tax:</div>
            <div class="total-value">₹{{ number_format($bill->tax, 2) }}</div>
        </div>
        @endif
        <div class="total-row" style="border-top: 2px solid #4f46e5; padding-top: 10px; margin-top: 10px;">
            <div class="total-label" style="font-size: 18px;">Total:</div>
            <div class="total-value" style="font-size: 18px; color: #4f46e5;">₹{{ number_format($bill->total, 2) }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Paid:</div>
            <div class="total-value" style="color: #059669;">₹{{ number_format($bill->paid_amount, 2) }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Due:</div>
            <div class="total-value" style="color: #dc2626;">₹{{ number_format($bill->due_amount, 2) }}</div>
        </div>
    </div>

    @if($bill->notes)
    <div style="margin-top: 30px; padding: 15px; background-color: #f9fafb; border-radius: 5px;">
        <strong>Notes:</strong>
        <p style="margin: 5px 0 0 0;">{{ $bill->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated invoice.</p>
    </div>
</body>
</html>
