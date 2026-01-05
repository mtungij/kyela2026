<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ripoti Ya Malipo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0891b2;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #0891b2;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            background: #f0f9ff;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #0891b2;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }
        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #0891b2;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background: #0891b2;
            color: white;
        }
        th {
            padding: 10px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid #0891b2;
        }
        td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        tbody tr:hover {
            background: #f0f9ff;
        }
        .text-right {
            text-align: right;
        }
        .amount {
            font-weight: bold;
            color: #059669;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .date-range {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ripoti Ya Malipo</h1>
        <p>Ambao Wamelipa</p>
    </div>

    <div class="date-range">
        Tarehe: {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}
    </div>

    @if($payments->count() > 0)
        <div class="summary">
            <div class="summary-item">
                <div class="label">Jumla ya Malipo</div>
                <div class="value">{{ $summary['total_payments'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Jumla ya Kiasi</div>
                <div class="value">{{ number_format($summary['total_amount'], 0) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Jumla ya Wanachama</div>
                <div class="value">{{ $summary['total_members'] }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nambari</th>
                    <th>Jina la Mwanachama</th>
                    <th>Simu</th>
                    <th>Tarehe ya Malipo</th>
                    <th class="text-right">Kiasi</th>
                    <th>Kumbuka</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payment->member->name }}</td>
                        <td>{{ $payment->member->phone }}</td>
                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                        <td class="text-right amount">{{ number_format($payment->amount, 0) }}</td>
                        <td>{{ $payment->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            Hakuna malipo kwenye kipindi hiki
        </div>
    @endif

    <div class="footer">
        <p>Ripoti hii ilizalishwa kwa sehemu: {{ now()->format('d/m/Y H:i') }}</p>
        <p>KWS - Sistemu ya Usimamizi wa Michango</p>
    </div>
</body>
</html>
