<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Contracts</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 0; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f4f4f4; text-align: left; }
        h3 { margin-bottom: 8px; }
    </style>
</head>
<body>
    <h3>Daftar Kontrak</h3>
    <small>Di-export: {{ now()->format('d M Y H:i') }}</small>
    <table>
        <thead>
            <tr>
                <th>No. Kontrak</th>
                <th>Nama Kontrak</th>
                <th>Jenis Kontrak</th>
                <th>Klien</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Berakhir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $c)
                <tr>
                    <td>{{ $c->contract_number }}</td>
                    <td>{{ $c->contract_name }}</td>
                    <td>{{ config('contract_types.types.' . $c->contract_type . '.name', '-') }}</td>
                    <td>{{ optional($c->client)->name ?? '-' }}</td>
                    <td>{{ optional($c->start_date)->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ optional($c->end_date)->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $c->status)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
