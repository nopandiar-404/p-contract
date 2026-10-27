<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Contracts</title>
    <style>
<<<<<<< HEAD
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 0; }
        th, td { border: 1px solid #ddd; padding: 4px; }
=======
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 0; }
        th, td { border: 1px solid #ddd; padding: 6px; }
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8
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
<<<<<<< HEAD
                <th>Nomor Surat</th>
                <th>Nama Kontrak</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Berakhir</th>
                <th>Jenis Kontrak</th>
                <th>Klien</th>
                <th>Tahun I</th>
                <th>Tahun II</th>
                <th>Jumlah</th>
                <th>PPN (11%)</th>
                <th>Total</th>
                <th>PR</th>
                <th>PO</th>
                <th>TTB</th>
                <th>Berita Acara</th>
                <th>Keterangan</th>
=======
                <th>Nama Kontrak</th>
                <th>Jenis Kontrak</th>
                <th>Klien</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Berakhir</th>
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contracts as $c)
                <tr>
                    <td>{{ $c->contract_number }}</td>
<<<<<<< HEAD
                    <td>{{ $c->nomor_surat ?? '-' }}</td>
                    <td>{{ $c->contract_name }}</td>
                    <td>{{ optional($c->start_date)->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ optional($c->end_date)->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ config('contract_types.types.' . $c->contract_type . '.name', '-') }}</td>
                    <td>{{ optional($c->client)->name ?? '-' }}</td>
                    <td>{{ $c->tahun_1 !== null ? 'Rp ' . number_format($c->tahun_1, 0, ',', '.') : '-' }}</td>
                    <td>{{ $c->tahun_2 !== null ? 'Rp ' . number_format($c->tahun_2, 0, ',', '.') : '-' }}</td>
                    <td>{{ $c->jumlah !== null ? 'Rp ' . number_format($c->jumlah, 0, ',', '.') : '-' }}</td>
                    <td>{{ $c->ppn !== null ? 'Rp ' . number_format($c->ppn, 0, ',', '.') : '-' }}</td>
                    <td>{{ $c->total !== null ? 'Rp ' . number_format($c->total, 0, ',', '.') : '-' }}</td>
                    <td>{{ $c->pr ?? '-' }}</td>
                    <td>{{ $c->po ?? '-' }}</td>
                    <td>{{ $c->ttb ?? '-' }}</td>
                    <td>{{ $c->berita_acara ?? '-' }}</td>
                    <td>
                        @php
                            $termLabel = ['termin_1' => 'Termin I', 'termin_2' => 'Termin II', 'termin_3' => 'Termin III', 'termin_4' => 'Termin IV'][$c->contract_term] ?? $c->contract_term;
                        @endphp
                        {{ $termLabel ? $termLabel . ' - ' : '' }}{{ $c->payment_date ? $c->payment_date->format('d/m/Y') . ' - ' : '' }}{{ $c->payment_status ?? 'BELUM BAYAR' }}{{ $c->keterangan ? ' (' . $c->keterangan . ')' : '' }}
                    </td>
=======
                    <td>{{ $c->contract_name }}</td>
                    <td>{{ config('contract_types.types.' . $c->contract_type . '.name', '-') }}</td>
                    <td>{{ optional($c->client)->name ?? '-' }}</td>
                    <td>{{ optional($c->start_date)->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ optional($c->end_date)->format('Y-m-d') ?? '-' }}</td>
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8
                    <td>{{ ucfirst(str_replace('_', ' ', $c->status)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
