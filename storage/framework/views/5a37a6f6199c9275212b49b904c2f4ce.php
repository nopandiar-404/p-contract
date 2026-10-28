<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Contracts</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 0; }
        th, td { border: 1px solid #ddd; padding: 4px; }
        th { background: #f4f4f4; text-align: left; }
        h3 { margin-bottom: 8px; }
    </style>
</head>
<body>
    <h3>Daftar Kontrak</h3>
    <small>Di-export: <?php echo e(now()->format('d M Y H:i')); ?></small>
    <table>
        <thead>
            <tr>
                <th>No. Kontrak</th>
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
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($c->contract_number); ?></td>
                    <td><?php echo e($c->nomor_surat ?? '-'); ?></td>
                    <td><?php echo e($c->contract_name); ?></td>
                    <td><?php echo e(optional($c->start_date)->format('Y-m-d') ?? '-'); ?></td>
                    <td><?php echo e(optional($c->end_date)->format('Y-m-d') ?? '-'); ?></td>
                    <td><?php echo e(config('contract_types.types.' . $c->contract_type . '.name', '-')); ?></td>
                    <td><?php echo e(optional($c->client)->name ?? '-'); ?></td>
                    <td><?php echo e($c->tahun_1 !== null ? 'Rp ' . number_format($c->tahun_1, 0, ',', '.') : '-'); ?></td>
                    <td><?php echo e($c->tahun_2 !== null ? 'Rp ' . number_format($c->tahun_2, 0, ',', '.') : '-'); ?></td>
                    <td><?php echo e($c->jumlah !== null ? 'Rp ' . number_format($c->jumlah, 0, ',', '.') : '-'); ?></td>
                    <td><?php echo e($c->ppn !== null ? 'Rp ' . number_format($c->ppn, 0, ',', '.') : '-'); ?></td>
                    <td><?php echo e($c->total !== null ? 'Rp ' . number_format($c->total, 0, ',', '.') : '-'); ?></td>
                    <td><?php echo e($c->pr ?? '-'); ?></td>
                    <td><?php echo e($c->po ?? '-'); ?></td>
                    <td><?php echo e($c->ttb ?? '-'); ?></td>
                    <td><?php echo e($c->berita_acara ?? '-'); ?></td>
                    <td>
                        <?php
                            $termLabel = ['termin_1' => 'Termin I', 'termin_2' => 'Termin II', 'termin_3' => 'Termin III', 'termin_4' => 'Termin IV'][$c->contract_term] ?? $c->contract_term;
                        ?>
                        <?php echo e($termLabel ? $termLabel . ' - ' : ''); ?><?php echo e($c->payment_date ? $c->payment_date->format('d/m/Y') . ' - ' : ''); ?><?php echo e($c->payment_status ?? 'BELUM BAYAR'); ?><?php echo e($c->keterangan ? ' (' . $c->keterangan . ')' : ''); ?>

                    </td>
                    <td><?php echo e(ucfirst(str_replace('_', ' ', $c->status))); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH D:\DASHBOARD\p-contract\resources\views\contracts\export_pdf.blade.php ENDPATH**/ ?>