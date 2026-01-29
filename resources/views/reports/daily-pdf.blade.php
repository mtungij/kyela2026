<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ripoti ya Siku - {{ $date->format('d/m/Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #0891b2 0%, #2563eb 100%);
            color: white;
            border-radius: 5px;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            margin: 3px 0;
        }
        .filter-info {
            background: #f3f4f6;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 4px solid #0891b2;
        }
        .filter-info strong {
            color: #0891b2;
        }
        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-row {
            display: table-row;
        }
        .summary-card {
            display: table-cell;
            width: 25%;
            padding: 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
            vertical-align: top;
        }
        .summary-card h3 {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .summary-card .value {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .summary-card .subtext {
            font-size: 9px;
            color: #9ca3af;
        }
        .summary-card.indigo { border-left: 4px solid #6366f1; }
        .summary-card.indigo .value { color: #6366f1; }
        .summary-card.teal { border-left: 4px solid #14b8a6; }
        .summary-card.teal .value { color: #14b8a6; }
        .summary-card.blue { border-left: 4px solid #3b82f6; }
        .summary-card.blue .value { color: #3b82f6; }
        .summary-card.green { border-left: 4px solid #10b981; }
        .summary-card.green .value { color: #10b981; }
        .summary-card.orange { border-left: 4px solid #f97316; }
        .summary-card.orange .value { color: #f97316; }
        .summary-card.red { border-left: 4px solid #ef4444; }
        .summary-card.red .value { color: #ef4444; }
        .summary-card.cyan { 
            background: linear-gradient(135deg, #0891b2 0%, #2563eb 100%);
            color: white;
            border: none;
        }
        .summary-card.cyan h3,
        .summary-card.cyan .value,
        .summary-card.cyan .subtext {
            color: white;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 8px;
            background: #f9fafb;
            border-left: 4px solid #0891b2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table thead {
            background: #0891b2;
            color: white;
        }
        table th {
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        table tbody tr:hover {
            background: #f3f4f6;
        }
        table tfoot {
            background: #f0fdf4;
            font-weight: bold;
        }
        table.expenses tfoot {
            background: #fef2f2;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-green {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-orange {
            background: #fed7aa;
            color: #92400e;
        }
        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .formula-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .formula-box h3 {
            font-size: 11px;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .formula-box p {
            font-size: 10px;
            color: #1e3a8a;
            font-family: monospace;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
            font-style: italic;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>📊 RIPOTI YA SIKU - FUNGA HESABU</h1>
        <p>Tarehe: {{ $date->format('d/m/Y (l)') }}</p>
        @if($payType)
        <p>Aina ya Mchango: {{ $payTypeLabel }}</p>
        @endif
        <p style="font-size: 9px; margin-top: 5px;">Imetayarishwa: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <!-- Filter Info -->
    @if($payType)
    <div class="filter-info">
        <strong>🔍 Kichujio:</strong> Ripoti hii inaonyesha tu taarifa za wanachama wa <strong>{{ $payTypeLabel }}</strong>
    </div>
    @endif

    <!-- Summary Cards Section -->
    <div class="section">
        <div class="section-title">📊 Hesabu ya Jumla</div>
        <div class="summary-cards">
            <div class="summary-row">
                <div class="summary-card indigo">
                    <h3>Jumla ya Wanachama</h3>
                    <div class="value">{{ number_format($totalMembers) }}</div>
                </div>
                <div class="summary-card teal">
                    <h3>Waliomaliza Kulipa</h3>
                    <div class="value">{{ number_format($completedMembers) }}</div>
                </div>
                <div class="summary-card blue">
                    <h3>Kiasi Hitajika Leo</h3>
                    <div class="value">{{ number_format($expectedToday, 2) }}</div>
                    <div class="subtext">Waliokuwa walipaswa kulipa</div>
                </div>
                <div class="summary-card green">
                    <h3>Jumla Waliolipa Leo</h3>
                    <div class="value">{{ number_format($totalCollectionPayments, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary Section -->
    <div class="section">
        <div class="section-title">💰 Muhtasari wa Fedha</div>
        <div class="summary-cards">
            <div class="summary-row">
                <div class="summary-card green">
                    <h3>Malipo ya Michango</h3>
                    <div class="value">TZS {{ number_format($totalCollectionPayments, 2) }}</div>
                </div>
                <div class="summary-card orange">
                    <h3>Faini Iliyokusanywa</h3>
                    <div class="value">+ TZS {{ number_format($totalPenaltyPayments, 2) }}</div>
                </div>
                <div class="summary-card red">
                    <h3>Matumizi</h3>
                    <div class="value">- TZS {{ number_format($totalExpenses, 2) }}</div>
                </div>
                <div class="summary-card cyan">
                    <h3>Kiasi Kilichobaki</h3>
                    <div class="subtext">(Baada ya Matumizi)</div>
                    <div class="value" style="font-size: 18px;">TZS {{ number_format($netAmount, 2) }}</div>
                    <div class="subtext">({{ $netAmount >= 0 ? 'Faida' : 'Hasara' }})</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calculation Formula -->
    <div class="formula-box">
        <h3>📐 Hesabu ya Kiasi Kilichobaki:</h3>
        <p>
            Malipo ya Michango ({{ number_format($totalCollectionPayments, 2) }}) 
            + Faini Iliyokusanywa ({{ number_format($totalPenaltyPayments, 2) }}) 
            - Matumizi ({{ number_format($totalExpenses, 2) }}) 
            = <strong style="font-size: 12px;">{{ number_format($netAmount, 2) }}</strong>
        </p>
    </div>

    <!-- Payments Table -->
    <div class="section">
        <div class="section-title">💵 Malipo ({{ $payments->count() }})</div>
        @if($payments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 40%;">Jina la Mwanachama</th>
                    <th style="width: 25%;">Aina ya Malipo</th>
                    <th style="width: 30%; text-align: right;">Kiasi (TZS)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $index => $payment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payment->member->name ?? 'N/A' }}</td>
                    <td>
                        @if($payment->payment_type === 'penalty')
                            <span class="badge badge-orange">Faini</span>
                        @else
                            <span class="badge badge-green">Mchango</span>
                        @endif
                    </td>
                    <td class="text-right font-bold" style="color: #10b981;">
                        {{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="font-bold" style="color: #065f46;">JUMLA YA MALIPO</td>
                    <td class="text-right font-bold" style="color: #10b981;">
                        {{ number_format($totalIncome, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="no-data">Hakuna malipo yaliyorekodiwa kwa siku hii</div>
        @endif
    </div>

    <!-- Page Break Before Expenses -->
    @if($payments->count() > 10 && $expenses->count() > 0)
    <div class="page-break"></div>
    @endif

    <!-- Expenses Table -->
    <div class="section">
        <div class="section-title">💸 Matumizi ({{ $expenses->count() }})</div>
        @if($expenses->count() > 0)
        <table class="expenses">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 25%;">Aina</th>
                    <th style="width: 40%;">Maelezo</th>
                    <th style="width: 30%; text-align: right;">Kiasi (TZS)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $index => $expense)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span class="badge badge-red">{{ $expense->category }}</span>
                    </td>
                    <td>{{ $expense->description }}</td>
                    <td class="text-right font-bold" style="color: #ef4444;">
                        {{ number_format($expense->amount, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="font-bold" style="color: #991b1b;">JUMLA YA MATUMIZI</td>
                    <td class="text-right font-bold" style="color: #ef4444;">
                        {{ number_format($totalExpenses, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="no-data">Hakuna matumizi yaliyorekodiwa kwa siku hii</div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Ripoti hii ilizalishwa kiotomatiki kutoka kwa mfumo wa usimamizi wa michango</p>
        <p>© {{ now()->year }} - Mfumo wa Michango | Tarehe ya Kuchapishwa: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
