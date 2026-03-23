<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Statement</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; margin: 0; padding: 20px; }
        .header { border-bottom: 3px solid #0891b2; padding-bottom: 12px; margin-bottom: 16px; }
        .title { margin: 0; color: #0891b2; font-size: 22px; }
        .muted { margin: 4px 0 0; color: #6b7280; font-size: 12px; }
        .member-box { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 12px; margin-bottom: 14px; }
        .member-box p { margin: 4px 0; font-size: 12px; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .summary td { border: 1px solid #e5e7eb; padding: 8px; font-size: 12px; }
        .summary .label { background: #f0f9ff; font-weight: 700; width: 30%; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0891b2; color: #fff; font-size: 11px; text-align: left; border: 1px solid #0891b2; padding: 8px; }
        td { border: 1px solid #e5e7eb; font-size: 11px; padding: 7px 8px; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        .center { text-align: center; }
        .right { text-align: right; }
        .footer { margin-top: 16px; border-top: 1px solid #e5e7eb; padding-top: 8px; font-size: 10px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Ripoti ya Malipo ya Mwanachama</h1>
        <p class="muted">Imetolewa: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="member-box">
        <p><strong>Jina:</strong> {{ $member->name }}</p>
        <p><strong>Simu:</strong> {{ $member->phone ?? '-' }}</p>
        <p><strong>Aina ya Malipo:</strong> {{ $member->type }}</p>
        <p><strong>Kiasi kwa Kipindi:</strong> {{ number_format((float) $member->amount, 0) }} TSh</p>
    </div>

    <table class="summary">
        <tr>
            <td class="label">Jumla ya Kipindi</td>
            <td>{{ $summary['expected_periods'] }}</td>
            <td class="label">Zilizolipwa</td>
            <td>{{ $summary['paid_periods'] }}</td>
        </tr>
        <tr>
            <td class="label">Jumla Kiasi</td>
            <td>{{ number_format((float) $summary['total_amount'], 0) }} TSh</td>
            <td class="label">Baki</td>
            <td>{{ number_format((float) $summary['balance'], 0) }} TSh</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 28px;">#</th>
                <th>Tarehe</th>
                <th class="right">Kiasi</th>
                <th class="center">Hali</th>
                <th class="center">Faini</th>
                <th class="center">Hesabu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedule as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['date'] }}</td>
                    <td class="right">{{ number_format((float) ($row['amount'] ?? $member->amount), 0) }}</td>
                    <td class="center">{{ $row['is_paid'] ? 'Imelipwa' : 'Haijalipwa' }}</td>
                    <td class="center">
                        @if($row['is_closed'])
                            {{ $row['penalty_charged'] ? 'Imepigwa Faini' : 'Hakuna Faini' }}
                        @else
                            Haikufungwa
                        @endif
                    </td>
                    <td class="center">{{ $row['is_closed'] ? 'Ilifungwa' : 'Haikufungwa' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Hakuna taarifa ya statement kwa mwanachama huyu.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        KWS - Mfumo wa Usimamizi wa Michango
    </div>
</body>
</html>
