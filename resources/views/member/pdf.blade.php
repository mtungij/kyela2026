<html>
<head>
    <meta charset="utf-8">
    <title>Members Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        /* Header / Title box */
        .report-header {
            border: 2px solid #1e88e5;
            padding: 15px;
            text-align: center;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .report-header img {
            height: 60px;
            margin-bottom: 8px;
        }

        .report-header h1 {
            font-size: 20px;
            margin: 4px 0;
            color: #1e88e5;
        }

        .report-header h2 {
            font-size: 15px;
            margin: 0;
            font-weight: normal;
            color: #333;
        }

        .meta {
            margin-top: 8px;
            font-size: 11px;
            color: #555;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f2f6fb;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="report-header">
        <!-- Logo (optional) -->
    <img src="{{ public_path('images/logo.jpeg') }}" alt="Logo">


        <h1>Kikundi Cha Kuwezeshana Kalumbulu</h1>
        <h2>WANAKIKUNDI WA {{ strtoupper($payType === 'mchango_mdogo' ? 'MCHANGO MDOGO' : ($payType === 'mchango_mkubwa' ? 'MCHANGO MKUBWA' : '')) }}</h2>
    </div>

    <!-- Meta info -->
    <div class="meta">
     
        @if(!empty($search))
            Search: "{{ $search }}" |
        @endif
        Tarehe: {{ now()->format('d-m-Y H:i') }}
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Jina</th>
                <th>Simu</th>
                <th>Kiasi</th>
                <th>Kiasi Alichochangia</th>
                <th>Kiasi Kilichobaki</th>
                <th>Alianza</th>
                <th>Mwisho</th>
                <th>Makazi</th>
                <th>Biashara</th>
            </tr>
        </thead>
      <tbody>
    @php $no = 1; @endphp

    @foreach($members as $m)
        @php
            $collection = $m->collections->first();
            $amountPaid = $collection ? $collection->amount_paid : 0;
            $balance = $collection ? $collection->balance : ($m->amount * ($m->number_type ?? 1));
        @endphp
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $m->name }}</td>
            <td>{{ $m->phone }}</td>
            
            <td>{{ number_format($m->amount, 0) }}</td>
            <td>{{ number_format($amountPaid, 0) }}</td>
            <td>{{ number_format($balance, 0) }}</td>
            <td>{{ optional($m->start_date) ? \Carbon\Carbon::parse($m->start_date)->format('d-m-Y') : '' }}</td>
            <td>{{ optional($m->end_date) ? \Carbon\Carbon::parse($m->end_date)->format('d-m-Y') : '' }}</td>
            <td>{{ $m->address }}</td>
            <td>{{ $m->business_address }}</td>
        </tr>
    @endforeach
</tbody>

    </table>

</body>
</html>
