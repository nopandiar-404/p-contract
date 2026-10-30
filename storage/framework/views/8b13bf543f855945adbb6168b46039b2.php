<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo e($contract->contract_number); ?></title>
    <style>
        @page { margin: 12mm 10mm; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; color: #17324d; font-size: 9.5px; line-height: 1.35; }
        h1 { margin: 0 0 6px; color: #ffffff; font-size: 20px; }
        h2 { margin: 0; padding: 7px 9px; color: #17324d; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        .header-logo-bar { margin-bottom: 12px; padding: 2px 14px 4px; text-align: right; background: #ffffff; }
        .header-logo { display: inline-block; width: 128px; max-width: 32%; height: auto; vertical-align: middle; }
        .header { margin-bottom: 14px; padding: 13px 16px 12px; border-left: 5px solid #f4b942; background: #1769aa; color: #ffffff; }
        .header strong { color: #ffffff; }
        .header-meta { margin-top: 3px; color: #e9f4ff; line-height: 1; }
        .status { display: inline-block; margin-left: 3px; padding: 1px 5px; border-radius: 3px; background: #ffffff; color: #1769aa; font-size: 8px; font-weight: bold; line-height: 1.15; vertical-align: middle; }
        .section { margin-bottom: 13px; border: 1px solid #d6e4ef; background: #ffffff; }
        .section-title { border-bottom: 1px solid #d6e4ef; background: #edf4fa; }
        .section-content { padding: 7px; }
        .section-content > table { margin-top: 0; }
        th, td { border: 1px solid #dbe4ee; padding: 5px 6px; vertical-align: top; }
        th { background: #edf4fa; color: #17324d; font-weight: bold; }
        tbody tr:nth-child(even) td { background: #f8fbfd; }
        tfoot th { background: #dceafa; color: #0e5f99; }
        .detail-table td:first-child { width: 31%; background: #edf4fa; color: #375a78; font-weight: bold; }
        .detail-table td:last-child { width: 69%; }
        .grid { table-layout: fixed; }
        .grid td { width: 50%; border: 0; padding: 0; vertical-align: top; }
        .grid td:first-child { padding-right: 6px; }
        .grid td:last-child { padding-left: 6px; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .nowrap { white-space: nowrap; }
        .boq th:nth-child(1) { width: 28px; }
        .boq th:nth-child(3) { width: 52px; }
        .boq th:nth-child(4), .boq th:nth-child(5), .boq th:nth-child(6) { width: 82px; }
        .avoid-break { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="header-logo-bar">
        <img class="header-logo" src="data:image/png;base64,<?php echo e(base64_encode(file_get_contents(public_path('images/logo-pelindo.png')))); ?>" alt="Pelindo">
    </div>
    <div class="header">
        <h1><?php echo e($contract->contract_name); ?></h1>
        <?php if($contract->nomor_surat): ?>
            <div><strong>Nomor Surat:</strong> <?php echo e($contract->nomor_surat); ?></div>
        <?php endif; ?>
        <div><strong>Nomor Kontrak:</strong> <?php echo e($contract->contract_number); ?></div>
        <div class="header-meta">
            Status: <span class="status"><?php echo e(ucfirst(str_replace('_', ' ', $contract->status))); ?></span>
            &nbsp;&nbsp;|
            <?php if($contract->status === 'active'): ?>
                Sisa waktu: <strong><?php echo e(number_format($contract->days_remaining, 0)); ?> hari</strong>
            <?php elseif($contract->status === 'grace_period'): ?>
                Sisa masa tenggang: <strong><?php echo e(number_format($contract->remaining_grace_period_days, 0)); ?> hari</strong>
            <?php else: ?>
                Sisa waktu: <strong>-</strong>
            <?php endif; ?>
        </div>
    </div>

    <div class="section avoid-break">
        <div class="section-title"><h2>BOQ Kontrak Pekerjaan</h2></div>
        <?php if($contract->boqItems->isNotEmpty()): ?>
            <div class="section-content">
            <table class="boq">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Uraian Pekerjaan/Barang</th>
                        <th>Satuan</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-center">Harga Satuan</th>
                        <th class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $contract->boqItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($item->description); ?></td>
                            <td><?php echo e($item->unit ?: '-'); ?></td>
                            <td class="text-center nowrap"><?php echo e($item->formatted_quantity); ?></td>
                            <td class="text-left nowrap">Rp <?php echo e(number_format((float) $item->unit_price, 0, ',', '.')); ?></td>
                            <td class="text-left nowrap">Rp <?php echo e(number_format((float) $item->subtotal, 0, ',', '.')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total BOQ</th>
                        <th class="text-left nowrap">Rp <?php echo e(number_format((float) $contract->boqItems->sum('subtotal'), 0, ',', '.')); ?></th>
                    </tr>
                </tfoot>
            </table>
            </div>
        <?php else: ?>
            <div class="section-content"><p>Belum ada rincian BOQ untuk kontrak ini.</p></div>
        <?php endif; ?>
    </div>

    <table class="grid">
        <tr>
            <td>
                <div class="section">
                    <div class="section-title"><h2>Informasi Kontrak</h2></div>
                    <div class="section-content"><table class="detail-table">
                        <tr><td>Nomor Kontrak</td><td><?php echo e($contract->contract_number); ?></td></tr>
                        <tr><td>Nama Kontrak</td><td><?php echo e($contract->contract_name); ?></td></tr>
                        <tr><td>Jenis Kontrak</td><td><?php echo e($contract->contract_type ? config('contract_types.types.' . $contract->contract_type . '.name', '-') : '-'); ?></td></tr>
                        <tr><td>Deskripsi</td><td><?php echo e($contract->description ?: '-'); ?></td></tr>
                        <tr><td>Status</td><td><?php echo e(ucfirst(str_replace('_', ' ', $contract->status))); ?></td></tr>
                        <?php if($contract->berita_acara): ?>
                            <tr><td>Berita Acara</td><td><?php echo e($contract->berita_acara); ?></td></tr>
                        <?php endif; ?>
                    </table></div>
                </div>

                <div class="section avoid-break">
                    <div class="section-title"><h2>Informasi Vendor</h2></div>
                    <div class="section-content">
                    <?php if($contract->client): ?>
                        <table class="detail-table">
                            <tr><td>Nama</td><td><?php echo e($contract->client->name); ?></td></tr>
                            <tr><td>Email</td><td><?php echo e($contract->client->email ?: '-'); ?></td></tr>
                            <tr><td>Telepon</td><td><?php echo e($contract->client->phone ?: '-'); ?></td></tr>
                            <tr><td>Alamat</td><td><?php echo e($contract->client->address ?: '-'); ?></td></tr>
                            <tr><td>PIC</td><td><?php echo e($contract->client->pic_name ?: '-'); ?></td></tr>
                            <tr><td>Telp PIC</td><td><?php echo e($contract->client->pic_phone ?: '-'); ?></td></tr>
                        </table>
                    <?php else: ?>
                        <p>Klien tidak ditemukan.</p>
                    <?php endif; ?>
                    </div>
                </div>
            </td>
            <td>
                <div class="section">
                    <div class="section-title"><h2>Periode Kontrak</h2></div>
                    <div class="section-content"><table class="detail-table">
                        <tr><td>Tanggal Mulai</td><td><?php echo e(optional($contract->start_date)->format('d/m/Y') ?: '-'); ?></td></tr>
                        <tr><td>Tanggal Berakhir</td><td><?php echo e(optional($contract->end_date)->format('d/m/Y') ?: '-'); ?></td></tr>
                        <tr><td>Masa Tenggang</td><td><?php echo e(number_format($contract->grace_period_days ?? 0, 0)); ?> hari</td></tr>
                        <tr><td>Masa Tenggang Hingga</td><td><?php echo e(optional($contract->grace_period_end_date)->format('d/m/Y') ?: '-'); ?></td></tr>
                    </table></div>
                </div>

                <div class="section avoid-break">
                    <div class="section-title"><h2>Informasi Detail Kontrak</h2></div>
                    <div class="section-content"><table class="detail-table">
                        <?php if($contract->contract_term): ?>
                            <tr><td>Termin Kontrak</td><td><?php echo e(['termin_1' => 'Termin I', 'termin_2' => 'Termin II', 'termin_3' => 'Termin III', 'termin_4' => 'Termin IV'][$contract->contract_term] ?? '-'); ?></td></tr>
                        <?php endif; ?>
                        <tr><td>Tahun I</td><td><?php echo e($contract->tahun_1 !== null ? 'Rp ' . number_format($contract->tahun_1, 0, ',', '.') : '-'); ?></td></tr>
                        <tr><td>Tahun II</td><td><?php echo e($contract->tahun_2 !== null ? 'Rp ' . number_format($contract->tahun_2, 0, ',', '.') : '-'); ?></td></tr>
                        <tr><td>Jumlah</td><td><?php echo e($contract->jumlah !== null ? 'Rp ' . number_format($contract->jumlah, 0, ',', '.') : '-'); ?></td></tr>
                        <tr><td>PPN (11%)</td><td><?php echo e($contract->ppn !== null ? 'Rp ' . number_format($contract->ppn, 0, ',', '.') : '-'); ?></td></tr>
                        <tr><td>Total</td><td><?php echo e($contract->total !== null ? 'Rp ' . number_format($contract->total, 0, ',', '.') : '-'); ?></td></tr>
                        <tr><td>No. PR</td><td><?php echo e($contract->pr ?: '-'); ?></td></tr>
                        <tr><td>No. PO</td><td><?php echo e($contract->po ?: '-'); ?></td></tr>
                        <tr><td>No. TTB</td><td><?php echo e($contract->ttb ?: '-'); ?></td></tr>
                        <tr><td>Status Pembayaran</td><td><?php echo e($contract->payment_status ?: 'BELUM BAYAR'); ?></td></tr>
                    </table></div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views/contracts/print_pdf.blade.php ENDPATH**/ ?>