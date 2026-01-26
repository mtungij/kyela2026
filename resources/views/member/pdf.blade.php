<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Members PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; }
        table { width:100%; border-collapse: collapse; margin-bottom:10px; }
        th, td { border:1px solid #ccc; padding:6px; text-align:left; }
        th { background:#f0f0f0; }
        .right { text-align:right; }
    </style>
</head>
<body>
    <h2>Members List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Jina</th>
                <th>Simu</th>
                <th>Makazi</th>
                <th>Biashara</th>
                <th>Aina Ya Mchango</th>
                <th class="right">Kiasi</th>
                <th>Start</th>
                <th>End</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $i => $member)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->phone }}</td>
                    <td>{{ $member->address }}</td>
                    <td>{{ $member->business_address }}</td>
                    <td>{{ $member->pay_type }}</td>
                    <td class="right">{{ number_format($member->amount, 0) }}</td>
                    <td>{{ optional($member->start_date) ? \Carbon\Carbon::parse($member->start_date)->format('d-m-Y') : '' }}</td>
                    <td>{{ optional($member->end_date) ? \Carbon\Carbon::parse($member->end_date)->format('d-m-Y') : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>Generated: {{ now()->format('d-m-Y H:i') }}</div>
</body>
</html>
