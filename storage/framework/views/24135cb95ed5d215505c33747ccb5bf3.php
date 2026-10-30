<!-- resources/views/contracts/show.blade.php -->


<?php $__env->startSection('title', 'Detail Kontrak: ' . $contract->contract_number); ?>
<?php $__env->startSection('subtitle', 'Detail lengkap kontrak'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .contract-detail-page {
            --contract-navy: #102a43;
            --contract-blue: #1d4e89;
            --contract-muted: #627d98;
            --contract-line: #e5edf5;
            padding: clamp(0.75rem, 2vw, 1.5rem);
            border: 1px solid #e4ebf3;
            border-radius: 12px;
            background: #f4f7fb;
            color: var(--contract-navy);
        }

        .contract-detail-page .card {
            overflow: hidden;
            border: 1px solid var(--contract-line) !important;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(16, 42, 67, 0.05) !important;
        }

        .contract-detail-page .card-header {
            padding: 0.9rem 1.1rem;
            border-bottom: 1px solid var(--contract-line);
            background: #fff !important;
            color: var(--contract-navy) !important;
        }

        .contract-detail-page .card-header h6 {
            font-weight: 700;
        }

        .contract-detail-page .card-header i {
            color: var(--contract-blue);
        }

        .contract-detail-page .card-body {
            padding: 1.1rem;
        }

        .contract-detail-page .contract-hero .card-body {
            padding: clamp(1.1rem, 3vw, 1.75rem);
        }

        .contract-detail-page .contract-hero {
            border-color: #0072bc !important;
            background: #0072bc;
        }

        .contract-detail-page .contract-hero h4 {
            color: #fff;
            font-size: clamp(1.25rem, 2vw, 1.65rem);
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .contract-detail-page .contract-hero h5 {
            color: #e7f4ff !important;
            font-size: 0.95rem;
        }

        .contract-detail-page .contract-status-actions {
            row-gap: 0.45rem !important;
        }

        .contract-detail-page .contract-status-actions .badge {
            margin-bottom: 0;
        }

        .contract-detail-page .contract-list-button {
            min-height: 28px;
            padding: 0.3rem 0.65rem;
            line-height: 1;
        }

        .contract-detail-page .contract-identity-row {
            display: grid;
            grid-template-columns: max-content minmax(0, 1fr);
            align-items: baseline;
            gap: 0.5rem 0.75rem;
            min-width: 0;
        }

        .contract-detail-page .contract-identity-label {
            color: #d8edff;
            font-size: 0.78rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .contract-detail-page .contract-identity-value {
            min-width: 0;
            color: #fff;
            overflow-wrap: anywhere;
        }

        .contract-detail-page .contract-letter-number {
            width: 100%;
            display: block;
            padding: 0.15rem 0 0.35rem 0;
            margin-bottom: 0.25rem;
            border-radius: 5px;
            background: #fff;
            color: var(--contract-navy);
            box-sizing: border-box;
        }

        .contract-detail-page .contract-letter-number .contract-identity-label {
            flex: 0 0 auto;
            margin-left: 0;
            color: var(--contract-navy);
            font-size: 1.08rem;
            font-weight: 700;
            margin-top: -2px;
        }

        .contract-detail-page .contract-letter-number .contract-identity-value {
            display: block;
            width: 100%;
            min-width: 0;
            color: var(--contract-navy);
            font-size: 1.13rem;
            font-weight: 700;
            line-height: 1.35;
            margin-top: -2px;
            text-align: left;
        }

        .contract-detail-page .quick-actions .btn-print-pdf {
            border-color: #0d6efd;
            background: #0d6efd;
            color: #fff;
        }

        .contract-detail-page .quick-actions .btn-print-pdf:hover {
            border-color: #0b5ed7;
            background: #0b5ed7;
            color: #fff;
        }

        .contract-detail-page #sendReminderBtn {
            border-color: #8ecae6;
            background: #8ecae6;
            color: var(--contract-navy);
        }

        .contract-detail-page #sendReminderBtn:hover {
            border-color: #72b6d9;
            background: #72b6d9;
            color: var(--contract-navy);
        }

        .contract-detail-page .contract-hero .btn-warning,
        .contract-detail-page .quick-actions .btn-warning {
            border-color: #ebb917;
            background: #ebb917;
            color: #fff;
        }

        .contract-detail-page .contract-hero .btn-warning:hover,
        .contract-detail-page .quick-actions .btn-warning:hover {
            border-color: #d8a408;
            background: #d8a408;
            color: #fff;
        }

        .contract-detail-page .contract-hero .btn-secondary {
            border-color: #d5e0eb;
            background: #fff;
            color: var(--contract-navy);
        }

        .contract-detail-page .contract-details-table td {
            vertical-align: top;
            border-top: 0;
            border-bottom: 1px solid #edf2f7;
            padding: 0.8rem 0.2rem;
            overflow-wrap: anywhere;
            word-break: normal;
        }

        .contract-detail-page .contract-details-table td .badge {
            max-width: 100%;
            white-space: normal;
            overflow-wrap: anywhere;
            line-height: 1.35;
        }

        .contract-detail-page .contract-details-table tr:last-child td {
            border-bottom: 0;
        }

        .contract-detail-page .contract-details-table td:first-child {
            width: 44%;
            color: var(--contract-muted);
            font-weight: 600;
        }

        .contract-detail-page .contract-details-table td:last-child {
            width: 56%;
            color: var(--contract-navy);
            text-align: right;
        }

        .contract-detail-page .contract-boq-table {
            min-width: 760px;
        }

        .contract-detail-page .contract-boq-table th:nth-child(2),
        .contract-detail-page .contract-boq-table td:nth-child(2) {
            min-width: 240px;
        }

        .contract-detail-page .contract-boq-table th:nth-child(n + 4),
        .contract-detail-page .contract-boq-table td:nth-child(n + 4) {
            min-width: 145px;
        }

        .contract-detail-page .contract-boq-mobile-item {
            padding: 1rem;
            border-bottom: 1px solid var(--contract-line);
        }

        .contract-detail-page .contract-boq-mobile-item:last-of-type {
            border-bottom: 0;
        }

        .contract-detail-page .contract-boq-mobile-description {
            overflow-wrap: anywhere;
            font-weight: 600;
        }

        .contract-detail-page .contract-boq-mobile-label {
            display: block;
            margin-bottom: 0.2rem;
            color: var(--contract-muted);
            font-size: 0.75rem;
        }

        .contract-detail-page .contract-boq-mobile-facts {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem 1rem;
            margin-top: 0.9rem;
        }

        .contract-detail-page .contract-boq-mobile-fact {
            min-width: 0;
        }

        .contract-detail-page .contract-boq-mobile-value {
            overflow-wrap: anywhere;
            font-weight: 600;
        }

        .contract-detail-page .contract-boq-mobile-total {
            padding: 1rem;
            border-top: 1px solid var(--contract-line);
            background: #f8f9fa;
        }

        .contract-detail-page .client-avatar {
            flex: 0 0 52px;
            width: 52px !important;
            height: 52px !important;
            background: var(--contract-navy) !important;
            font-size: 1.2rem !important;
        }

        .contract-detail-page .quick-actions .btn {
            min-height: 42px;
            font-weight: 600;
        }

        .contract-detail-page .timeline {
            position: relative;
            padding-left: 1.6rem;
        }

        .contract-detail-page .timeline::before {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0.35rem;
            width: 2px;
            background: var(--contract-line);
            content: '';
        }

        .contract-detail-page .timeline-item {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .contract-detail-page .timeline-item:last-child {
            margin-bottom: 0;
        }

        .contract-detail-page .timeline-marker {
            position: absolute;
            top: 0.1rem;
            left: -1.65rem;
            width: 14px;
            height: 14px;
            border: 3px solid #fff;
            border-radius: 50%;
            background: #9fb3c8 !important;
            box-shadow: 0 0 0 1px #9fb3c8;
        }

        .contract-detail-page .timeline-item.active .timeline-marker {
            background: var(--contract-blue) !important;
            box-shadow: 0 0 0 3px rgba(29, 78, 137, 0.16);
        }

        .contract-detail-page .timeline-content {
            padding-left: 0.25rem;
        }

        .contract-detail-page .timeline-content h6 {
            margin-bottom: 0.25rem;
            color: var(--contract-navy);
            font-size: 0.95rem;
        }

        .contract-detail-page .term-timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        .contract-detail-page .term-timeline::before {
            position: absolute;
            top: 0.45rem;
            bottom: 0.45rem;
            left: 0.35rem;
            width: 2px;
            background: var(--contract-line);
            content: '';
        }

        .contract-detail-page .term-timeline-item {
            position: relative;
            padding-bottom: 1.1rem;
        }

        .contract-detail-page .term-timeline-item:last-child {
            padding-bottom: 0;
        }

        .contract-detail-page .term-timeline-marker {
            position: absolute;
            top: 0.2rem;
            left: -1.45rem;
            width: 0.85rem;
            height: 0.85rem;
            border: 2px solid #fff;
            border-radius: 50%;
            background: #9fb3c8;
            box-shadow: 0 0 0 1px #9fb3c8;
        }

        .contract-detail-page .term-timeline-item.is-selected .term-timeline-marker {
            background: var(--contract-blue);
            box-shadow: 0 0 0 3px rgba(29, 78, 137, 0.16);
        }

        .contract-detail-page .term-timeline-item h6 {
            margin-bottom: 0;
            color: var(--contract-navy);
            font-size: 0.95rem;
        }

        .contract-detail-page .download-summary img {
            display: block;
            width: 100%;
            max-width: 160px;
            height: auto;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .contract-detail-page > .row > .col-lg-8,
            .contract-detail-page > .row > .col-lg-4 {
                min-width: 0;
            }

            .contract-detail-page .contract-hero .card-body > .d-flex {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 1rem;
            }

            .contract-detail-page .contract-hero .text-end {
                width: 100%;
                text-align: left !important;
            }

            .contract-detail-page .contract-hero .btn-group {
                display: flex;
                width: 100%;
            }

            .contract-detail-page .contract-hero .btn-group .btn {
                flex: 1;
            }

            .contract-detail-page .contract-identity-row {
                width: 100%;
                grid-template-columns: minmax(96px, max-content) minmax(0, 1fr);
            }

            .contract-detail-page .contract-letter-number {
                display: flex;
            }

            .contract-detail-page .contract-details-table,
            .contract-detail-page .contract-details-table tbody,
            .contract-detail-page .contract-details-table tr {
                display: block;
                width: 100%;
            }

            .contract-detail-page .contract-details-table tr {
                display: flex;
                justify-content: space-between;
                gap: 0.75rem;
            }

            .contract-detail-page .contract-details-table td {
                display: block;
                padding: 0.7rem 0.15rem;
            }

            .contract-detail-page .contract-details-table td:first-child {
                flex: 0 1 45%;
                width: auto;
                white-space: normal;
            }

            .contract-detail-page .contract-details-table td:last-child {
                flex: 1 1 55%;
                width: auto;
                min-width: 0;
            }

            .contract-detail-page .contract-details-table td:last-child > * {
                max-width: 100%;
            }

            .contract-detail-page .contract-details-table .badge {
                font-size: 0.78rem;
            }

            .contract-detail-page .card-header h6 {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 420px) {
            .contract-detail-page {
                padding: 0.65rem;
            }

            .contract-detail-page .card-body {
                padding: 0.9rem;
            }

            .contract-detail-page .contract-identity-row {
                grid-template-columns: minmax(0, 1fr);
                gap: 0.15rem;
            }

            .contract-detail-page .contract-letter-number {
                flex-wrap: wrap;
                gap: 0.15rem 0.45rem;
                padding: 0.5rem 0;
            }

            .contract-detail-page .contract-letter-number .contract-identity-value {
                flex-basis: 100%;
                font-size: 0.88rem;
            }

            .contract-detail-page .contract-details-table tr {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 0.15rem;
                padding: 0.55rem 0;
            }

            .contract-detail-page .contract-details-table td {
                width: 100%;
                padding: 0.1rem 0;
            }

            .contract-detail-page .contract-details-table td:first-child {
                flex-basis: auto;
                font-size: 0.78rem;
            }

            .contract-detail-page .contract-details-table td:last-child {
                text-align: left;
                font-size: 0.9rem;
            }
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            body {
                background: #fff !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .dashboard-sidebar,
            .dashboard-navbar,
            .breadcrumb,
            .main-footer,
            .contract-detail-page .col-lg-4,
            .contract-detail-page .modal,
            .contract-detail-page button,
            .contract-detail-page a.btn,
            .contract-detail-page .contract-boq-card .card-header a {
                display: none !important;
            }

            .contract-detail-page {
                padding: 0 !important;
                border: 0 !important;
                border-radius: 0 !important;
                background: #fff !important;
            }

            .contract-detail-page > .row {
                margin: 0 !important;
            }

            .contract-detail-page > .row > .col-lg-8 {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
            }

            .contract-detail-page .contract-boq-desktop {
                display: block !important;
            }

            .contract-detail-page .contract-boq-mobile {
                display: none !important;
            }

            .contract-detail-page .contract-boq-table {
                min-width: 0 !important;
                width: 100% !important;
                font-size: 9pt;
            }

            .contract-detail-page .row > .col-md-6 {
                width: 50% !important;
                max-width: 50% !important;
                flex: 0 0 50% !important;
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .contract-detail-page .card {
                break-inside: avoid;
                page-break-inside: avoid;
                box-shadow: none !important;
            }

            .contract-detail-page .contract-hero {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .contract-detail-page .contract-details-table tr {
                display: table-row !important;
            }

            .contract-detail-page .contract-details-table td {
                display: table-cell !important;
                padding: 0.45rem 0.2rem !important;
            }

            .contract-detail-page .contract-details-table td:first-child {
                width: 44% !important;
            }

            .contract-detail-page .contract-details-table td:last-child {
                width: 56% !important;
                text-align: right !important;
            }
        }
    </style>

    <div class="contract-detail-page">
    <div class="row g-3">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Header Card -->
            <div class="card contract-hero border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1"><?php echo e($contract->contract_name); ?></h4>
                            <?php if($contract->nomor_surat): ?>
                                <div class="contract-letter-number mb-1">
                                    <span class="contract-identity-value"><?php echo e($contract->nomor_surat); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="contract-identity-row mb-2">
                                <span class="contract-identity-label">Nomor Kontrak</span>
                                <h5 class="contract-identity-value text-primary mb-0">#<?php echo e($contract->contract_number); ?></h5>
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-2 contract-status-actions">
                                <?php echo $contract->getStatusBadgeAttribute(); ?>

                                <?php if($contract->status === 'grace_period'): ?>
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo e(number_format($contract->remaining_grace_period_days, 0)); ?> hari tersisa
                                    </span>
                                <?php elseif($contract->status === 'active'): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo e(number_format($contract->days_remaining, 0)); ?> hari tersisa
                                    </span>
                                <?php endif; ?>
                                <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-secondary btn-sm contract-list-button">
                                    <i class="fas fa-list me-2"></i> Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4 contract-boq-card">
                <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h6 class="mb-0"><i class="fas fa-list-ol me-2"></i>BOQ Kontrak Pekerjaan</h6>
                        <small class="text-muted">Rincian volume dan harga untuk kontrak ini.</small>
                    </div>
                    <?php if(!in_array($contract->status, ['expired', 'terminated'], true)): ?>
                        <a href="<?php echo e(route('contracts.edit', $contract)); ?>#boqRows" class="btn btn-sm btn-outline-primary flex-shrink-0">
                            <i class="fas fa-edit me-1"></i>Edit BOQ
                        </a>
                    <?php endif; ?>
                </div>
                <?php if($contract->boqItems->isNotEmpty()): ?>
                    <div class="contract-boq-desktop d-none d-lg-block table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0 contract-boq-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Uraian pekerjaan/barang</th>
                                    <th>Satuan</th>
                                    <th class="text-center">Kuantitas</th>
                                    <th class="text-center">Harga satuan</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $contract->boqItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($item->description); ?></td>
                                        <td><?php echo e($item->unit); ?></td>
                                        <td class="text-center text-nowrap"><?php echo e($item->formatted_quantity); ?></td>
                                        <td class="text-start text-nowrap">Rp <?php echo e(number_format((float) $item->unit_price, 2, ',', '.')); ?></td>
                                        <td class="text-center text-nowrap">Rp <?php echo e(number_format((float) $item->subtotal, 2, ',', '.')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total BOQ</th>
                                    <th class="text-center text-nowrap">Rp <?php echo e(number_format((float) $contract->boqItems->sum('subtotal'), 2, ',', '.')); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="contract-boq-mobile d-lg-none">
                        <?php $__currentLoopData = $contract->boqItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="contract-boq-mobile-item">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="contract-boq-mobile-label flex-shrink-0"><?php echo e($loop->iteration); ?>.</span>
                                    <div class="contract-boq-mobile-description"><?php echo e($item->description); ?></div>
                                </div>
                                <div class="contract-boq-mobile-facts">
                                    <div class="contract-boq-mobile-fact">
                                        <span class="contract-boq-mobile-label">Satuan</span>
                                        <div class="contract-boq-mobile-value"><?php echo e($item->unit ?: '-'); ?></div>
                                    </div>
                                    <div class="contract-boq-mobile-fact">
                                        <span class="contract-boq-mobile-label">Kuantitas</span>
                                        <div class="contract-boq-mobile-value"><?php echo e($item->formatted_quantity); ?></div>
                                    </div>
                                    <div class="contract-boq-mobile-fact">
                                        <span class="contract-boq-mobile-label">Harga satuan</span>
                                        <div class="contract-boq-mobile-value">Rp <?php echo e(number_format((float) $item->unit_price, 2, ',', '.')); ?></div>
                                    </div>
                                    <div class="contract-boq-mobile-fact">
                                        <span class="contract-boq-mobile-label">Jumlah</span>
                                        <div class="contract-boq-mobile-value">Rp <?php echo e(number_format((float) $item->subtotal, 2, ',', '.')); ?></div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="contract-boq-mobile-total d-flex justify-content-between align-items-start gap-3">
                            <strong>Total BOQ</strong>
                            <strong class="text-end">Rp <?php echo e(number_format((float) $contract->boqItems->sum('subtotal'), 2, ',', '.')); ?></strong>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card-body d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                        <span class="text-muted">Belum ada rincian BOQ untuk kontrak ini.</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Detail Cards -->
            <div class="row">
                <!-- Informasi Kontrak -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-file-contract me-2"></i> Informasi Kontrak</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0 contract-details-table">
                                <tr>
                                    <td width="40%"><strong>Nomor Kontrak</strong></td>
                                    <td>#<?php echo e($contract->contract_number); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Kontrak</strong></td>
                                    <td><?php echo e($contract->contract_name); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kontrak</strong></td>
                                    <td><?php echo e($contract->contract_type ? config('contract_types.types.' . $contract->contract_type . '.name', 'Tidak diketahui') : 'Belum ditentukan'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Deskripsi</strong></td>
                                    <td><?php echo e($contract->description ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td><?php echo $contract->getStatusBadgeAttribute(); ?></td>
                                </tr>
                                <?php if($contract->berita_acara): ?>
                                    <tr>
                                        <td><strong>Berita Acara</strong></td>
                                        <td><?php echo e($contract->berita_acara); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><strong>Dibuat Oleh</strong></td>
                                    <td><?php echo e($contract->creator->name ?? 'System'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Dibuat</strong></td>
                                    <td><?php echo e($contract->created_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Periode Kontrak -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Periode Kontrak</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0 contract-details-table">
                                <tr>
                                    <td width="40%"><strong>Tanggal Mulai</strong></td>
                                    <td><?php echo e($contract->start_date->format('d/m/Y')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Berakhir</strong></td>
                                    <td><span id="contractEndDate"><?php echo e($contract->end_date->format('d/m/Y')); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Durasi</strong></td>
                                    <td>
                                        <?php
                                            $diffDays = $contract->start_date->diffInDays($contract->end_date);
                                            $years = floor($diffDays / 365);
                                            $months = floor(($diffDays % 365) / 30);
                                            $days = $diffDays % 30;
                                        ?>
                                        <?php echo e(number_format($diffDays, 0)); ?> hari
                                        <?php if($years > 0): ?>
                                            (<?php echo e($years); ?> tahun <?php echo e($months); ?> bulan <?php echo e($days); ?>

                                            hari)
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Sisa Waktu</strong></td>
                                    <td>
                                        <?php if($contract->status === 'active'): ?>
                                            <span id="contractDaysRemainingBadge"
                                                class="badge bg-<?php echo e($contract->days_remaining < 7 ? 'danger' : 'info'); ?>">
                                                <?php echo e(number_format($contract->days_remaining, 0)); ?> hari
                                            </span>
                                        <?php elseif($contract->status === 'grace_period'): ?>
                                            <span id="contractGraceBadge"
                                                class="badge bg-<?php echo e($contract->remaining_grace_period_days < 7 ? 'danger' : 'warning'); ?>">
                                                <?php echo e(number_format($contract->remaining_grace_period_days, 0)); ?> hari masa
                                                tenggang
                                            </span>
                                        <?php else: ?>
                                            <span id="contractDaysRemainingBadge" class="badge bg-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Masa Tenggang</strong></td>
                                    <td><?php echo e(number_format($contract->grace_period_days, 0)); ?> hari</td>
                                </tr>
                                <?php if($contract->grace_period_end_date): ?>
                                    <tr>
                                        <td><strong>Masa Tenggang Hingga</strong></td>
                                        <td><?php echo e($contract->grace_period_end_date->format('d/m/Y')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informasi Vendor -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-users me-2"></i> Informasi Vendor</h6>
                        </div>
                        <div class="card-body">
                            <?php if($contract->client): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="client-avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 60px; height: 60px; font-size: 24px;">
                                        <?php echo e(substr($contract->client->name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <h5 class="mb-1"><?php echo e($contract->client->name); ?></h5>
                                        <p class="text-muted mb-0"><?php echo e($contract->client->email); ?></p>
                                    </div>
                                </div>

                                <table class="table table-borderless mb-0 contract-details-table">
                                    <tr>
                                        <td width="40%"><strong>Email</strong></td>
                                        <td><?php echo e($contract->client->email); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Telepon</strong></td>
                                        <td><?php echo e($contract->client->phone ?? '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat</strong></td>
                                        <td><?php echo e($contract->client->address ?? '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>PIC</strong></td>
                                        <td><?php echo e($contract->client->pic_name ?? '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Telp PIC</strong></td>
                                        <td><?php echo e($contract->client->pic_phone ?? '-'); ?></td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Klien tidak ditemukan</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Informasi Detail Kontrak -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Detail Kontrak</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0 contract-details-table">
                                <?php if($contract->contract_term): ?>
                                    <tr>
                                        <td width="40%"><strong>Termin Kontrak</strong></td>
                                        <td><?php echo e(['termin_1' => 'Termin I', 'termin_2' => 'Termin II', 'termin_3' => 'Termin III', 'termin_4' => 'Termin IV'][$contract->contract_term] ?? '-'); ?></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if($contract->tahun_1 !== null): ?>
                                    <tr>
                                        <td width="40%"><strong>Tahun I</strong></td>
                                        <td><span class="badge bg-light text-dark border">Rp <?php echo e(number_format($contract->tahun_1, 0, ',', '.')); ?></span></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if($contract->tahun_2 !== null): ?>
                                    <tr>
                                        <td width="40%"><strong>Tahun II</strong></td>
                                        <td><span class="badge bg-light text-dark border">Rp <?php echo e(number_format($contract->tahun_2, 0, ',', '.')); ?></span></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if($contract->jumlah !== null || ($contract->tahun_1 !== null || $contract->tahun_2 !== null)): ?>
                                    <tr>
                                        <td width="40%"><strong>Jumlah</strong></td>
                                        <td><span class="badge bg-primary bg-opacity-10 text-primary border fw-bold">Rp <?php echo e(number_format($contract->jumlah, 0, ',', '.')); ?></span></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><strong>PPN (11%)</strong></td>
                                        <td><span class="badge bg-warning bg-opacity-10 text-dark border">Rp <?php echo e(number_format($contract->ppn ?? ($contract->jumlah ? $contract->jumlah * 0.11 : 0), 0, ',', '.')); ?></span></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><strong>Total</strong></td>
                                        <td><span class="badge bg-white text-primary border border-primary fw-bold">Rp <?php echo e(number_format($contract->total ?? (($contract->jumlah ?? 0) + ($contract->ppn ?? ($contract->jumlah ? $contract->jumlah * 0.11 : 0))), 0, ',', '.')); ?></span></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if($contract->pr): ?>
                                    <tr>
                                        <td width="40%"><strong>No. PR</strong></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo e($contract->pr); ?></span></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if($contract->po): ?>
                                    <tr>
                                        <td width="40%"><strong>No. PO</strong></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo e($contract->po); ?></span></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if($contract->ttb): ?>
                                    <tr>
                                        <td width="40%"><strong>No. TTB</strong></td>
                                        <td><span class="badge bg-light text-dark border"><?php echo e($contract->ttb); ?></span></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td width="40%"><strong>Status Pembayaran</strong></td>
                                    <td>
                                        <?php if($contract->payment_status === 'SUDAH BAYAR'): ?>
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>SUDAH BAYAR</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>BELUM BAYAR</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if($contract->payment_date): ?>
                                    <tr>
                                        <td width="40%"><strong>Tanggal Pembayaran</strong></td>
                                        <td><?php echo e($contract->payment_date->format('d/m/Y')); ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if($contract->keterangan): ?>
                                    <tr>
                                        <td width="40%"><strong>Keterangan</strong></td>
                                        <td><?php echo e($contract->keterangan); ?></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if($contract->actual_end_date): ?>
                                    <tr>
                                        <td width="40%"><strong>Tanggal Berakhir Aktual</strong></td>
                                        <td><?php echo e($contract->actual_end_date->format('d/m/Y')); ?></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if($contract->termination_reason): ?>
                                    <tr>
                                        <td><strong>Alasan Penghentian</strong></td>
                                        <td><?php echo e($contract->termination_reason); ?></td>
                                    </tr>
                                <?php endif; ?>

                                <tr>
                                    <td><strong>Prioritas</strong></td>
                                    <td>
                                        <?php
                                            $priority = $contract->priority;
                                            $priorityClass =
                                                $priority === 'high'
                                                    ? 'danger'
                                                    : ($priority === 'medium'
                                                        ? 'warning'
                                                        : 'info');
                                            $priorityText =
                                                $priority === 'high'
                                                    ? 'Tinggi'
                                                    : ($priority === 'medium'
                                                        ? 'Sedang'
                                                        : 'Rendah');
                                        ?>
                                        <span class="badge bg-<?php echo e($priorityClass); ?>"><?php echo e($priorityText); ?></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Diperbarui Terakhir</strong></td>
                                    <td><?php echo e($contract->updated_at->format('d/m/Y H:i')); ?></td>
                                </tr>

                                <?php if($contract->deleted_at): ?>
                                    <tr class="table-danger">
                                        <td><strong>Dihapus Pada</strong></td>
                                        <td><?php echo e($contract->deleted_at->format('d/m/Y H:i')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>

                            <?php if($contract->status === 'grace_period'): ?>
                                <div class="mt-3">
                                    <div class="progress" style="height: 10px;">
                                        <?php
                                            $percentage =
                                                (($contract->grace_period_days -
                                                    $contract->remaining_grace_period_days) /
                                                    $contract->grace_period_days) *
                                                100;
                                        ?>
                                        <div class="progress-bar bg-warning" role="progressbar" data-progress="<?php echo e($percentage); ?>" aria-valuenow="<?php echo e($percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">Progress masa tenggang: <?php echo e(round($percentage)); ?>%</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card quick-actions border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bolt me-2"></i> Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('contracts.print-pdf', $contract)); ?>" class="btn btn-print-pdf">
                            <i class="fas fa-print me-2"></i> Cetak PDF
                        </a>

                        <a href="<?php echo e(route('contracts.edit', $contract)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Edit Kontrak
                        </a>

                        <?php if($contract->status === 'active' || $contract->status === 'grace_period'): ?>
                            <button id="sendReminderBtn" class="btn btn-primary" onclick="sendReminder()">
                                <i class="fas fa-bell me-2"></i> Kirim Reminder
                            </button>
                        <?php endif; ?>

                        <?php if($contract->status === 'active'): ?>
                            <button class="btn btn-success" onclick="extendContract()">
                                <i class="fas fa-calendar-plus me-2"></i> Perpanjang Kontrak
                            </button>
                        <?php endif; ?>

                        <?php if($contract->status !== 'terminated'): ?>
                            <button class="btn btn-danger" onclick="terminateContract()">
                                <i class="fas fa-ban me-2"></i> Hentikan Kontrak
                            </button>
                        <?php endif; ?>

                        <a href="<?php echo e(route('contracts.create')); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i> Kontrak Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i> Timeline Status</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item <?php echo e($contract->status === 'active' ? 'active' : ''); ?>">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Aktif</h6>
                                <small class="text-muted"><?php echo e($contract->start_date->format('d M Y')); ?></small>
                            </div>
                        </div>

                        <?php if($contract->status === 'grace_period' || $contract->status === 'expired'): ?>
                            <div class="timeline-item <?php echo e($contract->status === 'grace_period' ? 'active' : ''); ?>">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6>Masa Tenggang</h6>
                                    <small class="text-muted"><?php echo e($contract->end_date->format('d M Y')); ?></small>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($contract->status === 'expired' || $contract->status === 'terminated'): ?>
                            <div class="timeline-item <?php echo e($contract->status === 'expired' ? 'active' : ''); ?>">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6><?php echo e($contract->status === 'expired' ? 'Kadaluarsa' : 'Dihentikan'); ?></h6>
                                    <small class="text-muted">
                                        <?php if($contract->actual_end_date): ?>
                                            <?php echo e($contract->actual_end_date->format('d M Y')); ?>

                                        <?php elseif($contract->status === 'expired'): ?>
                                            <?php echo e($contract->grace_period_end_date->format('d M Y')); ?>

                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <style>
                        .timeline {
                            position: relative;
                            padding-left: 20px;
                        }

                        .timeline::before {
                            content: '';
                            position: absolute;
                            left: 9px;
                            top: 0;
                            bottom: 0;
                            width: 2px;
                            background: #e9ecef;
                        }

                        .timeline-item {
                            position: relative;
                            margin-bottom: 20px;
                        }

                        .timeline-item:last-child {
                            margin-bottom: 0;
                        }

                        .timeline-marker {
                            position: absolute;
                            left: -23px;
                            top: 0;
                            width: 16px;
                            height: 16px;
                            border-radius: 50%;
                            border: 3px solid #fff;
                        }

                        .timeline-item.active .timeline-marker {
                            box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.2);
                        }

                        .timeline-content {
                            padding-left: 10px;
                        }
                    </style>
                </div>
            </div>

            <!-- Contract Term Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-layer-group me-2"></i> Timeline Termin</h6>
                </div>
                <div class="card-body">
                    <?php
                        $contractTerms = [
                            'termin_1' => 'Termin I',
                            'termin_2' => 'Termin II',
                            'termin_3' => 'Termin III',
                            'termin_4' => 'Termin IV',
                        ];
                        $selectedContractTerm = $contractTerms[$contract->contract_term] ?? null;
                    ?>

                    <?php if(!$selectedContractTerm): ?>
                        <div class="alert alert-light border d-flex justify-content-between align-items-center gap-3 mb-3">
                            <span class="text-muted">Termin kontrak belum dipilih.</span>
                            <a href="<?php echo e(route('contracts.edit', $contract)); ?>" class="btn btn-sm btn-outline-primary flex-shrink-0">
                                <i class="fas fa-edit me-1"></i> Pilih Termin
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="term-timeline" aria-label="Pilihan termin kontrak">
                        <?php $__currentLoopData = $contractTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termValue => $termLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $isSelectedTerm = $contract->contract_term === $termValue;
                            ?>
                            <div class="term-timeline-item <?php echo e($isSelectedTerm ? 'is-selected' : ''); ?>">
                                <span class="term-timeline-marker" aria-hidden="true"></span>
                                <div class="d-flex align-items-center gap-2">
                                    <h6><?php echo e($termLabel); ?></h6>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bell me-2"></i> Notifikasi Terbaru</h6>
                </div>
                <div class="card-body">
                    <?php if($contract->notifications->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $contract->notifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex w-100 justify-content-between">
                                        <small><?php echo e($notification->message); ?></small>
                                        <small class="text-muted"><?php echo e($notification->created_at->format('d/m')); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($contract->notifications->count() > 5): ?>
                            <a href="<?php echo e(route('notifications.index')); ?>?contract_id=<?php echo e($contract->id); ?>"
                                class="btn btn-sm btn-outline-primary w-100 mt-2">
                                Lihat Semua Notifikasi
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">Belum ada notifikasi</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Download Dokumen PDF -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-file-pdf me-2"></i> Download Dokumen</h6>
                </div>
                <div class="card-body">
                    <?php
                        // Get pdf_files as array (auto-cast from model)
                        $pdfFiles = is_array($contract->pdf_files) ? $contract->pdf_files : [];
                        // Filter out empty/null values
                        $pdfFiles = collect($pdfFiles)->filter(function($item) {
                            return is_string($item) && !empty($item);
                        })->values();
                    ?>

                    <?php if($pdfFiles->isNotEmpty()): ?>
                        <?php
                            $downloadQrUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                                'contracts.download-pdf.scan',
                                now()->addHours(6),
                                ['contract' => $contract->id]
                            );
                        ?>

                        <div class="d-grid gap-2 mb-4">
                            <a href="<?php echo e(route('contracts.download-pdf', $contract)); ?>"
                                class="btn btn-success">
                                <i class="fas fa-download me-2"></i> Download
                                <?php if($pdfFiles->count() > 1): ?>
                                    <span class="badge bg-light text-dark"><?php echo e($pdfFiles->count()); ?> file</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark">1 file</span>
                                <?php endif; ?>
                            </a>
                        </div>

                        

                        <div>
                            <h6 class="mb-3">
                                <i class="fas fa-list me-2"></i> Dokumen yang tersedia
                                <span class="badge bg-secondary"><?php echo e($pdfFiles->count()); ?></span>
                            </h6>
                            <div class="list-group list-group-flush">
                                <?php $__currentLoopData = $pdfFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $filePath): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Validasi $filePath adalah string
                                        if (!is_string($filePath)) {
                                            continue;
                                        }
                                        
                                        // Clean file path
                                        $filePath = trim($filePath);
                                        if (empty($filePath)) {
                                            continue;
                                        }
                                        
                                        $fileUrl = Storage::disk('public')->url($filePath);
                                        $fileName = basename($filePath);
                                        $fileSize = 'Ukuran tidak tersedia';
                                        
                                        // Try to get file size safely with multiple fallbacks
                                        try {
                                            if (Storage::disk('public')->exists($filePath)) {
                                                try {
                                                    $fileSizeInBytes = Storage::disk('public')->size($filePath);
                                                } catch (\Exception $e) {
                                                    // Fallback: try to use direct file path
                                                    $fullPath = storage_path('app/public/' . $filePath);
                                                    if (file_exists($fullPath)) {
                                                        $fileSizeInBytes = filesize($fullPath);
                                                    }
                                                }
                                                
                                                if (isset($fileSizeInBytes) && $fileSizeInBytes) {
                                                    if ($fileSizeInBytes >= 1024 * 1024) {
                                                        $fileSize = number_format($fileSizeInBytes / 1024 / 1024, 2) . ' MB';
                                                    } else {
                                                        $fileSize = number_format($fileSizeInBytes / 1024, 2) . ' KB';
                                                    }
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            $fileSize = 'Ukuran tidak tersedia';
                                        }
                                    ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <i class="fas fa-file-pdf text-danger me-2 fa-lg"></i>
                                            <div class="flex-grow-1">
                                                <div class="fw-500"><?php echo e($fileName); ?></div>
                                                <small class="text-muted"><?php echo e($fileSize); ?></small>
                                            </div>
                                        </div>
                                        
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="mb-4">
                            <div class="card border-0 shadow-sm rounded-3 download-summary">
                                <div class="card-body p-3 d-flex align-items-center justify-content-between flex-column flex-sm-row gap-3">
                                    <div class="text-center text-sm-start">
                                        <p class="mb-1 fw-semibold">Scan QR code untuk mengunduh dokumen.</p>
                                        <p class="text-muted mb-0">Buka tautan unduhan PDF kontrak dengan kamera ponsel atau aplikasi QR.</p>
                                    </div>
                                    <div class="d-inline-block bg-white border rounded p-2 shadow-sm">
                                        <img id="downloadQrCodeImage"
                                            src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?php echo e(urlencode(route('contracts.download-pdf', $contract))); ?>"
                                            alt="QR Code Download Dokumen Kontrak"
                                            class="img-fluid" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-pdf fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                            <p class="text-muted mb-0">Belum ada dokumen PDF yang tersedia untuk diunduh</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Rename PDF Modal -->
    <div class="modal fade" id="renamePdfModal" tabindex="-1" aria-labelledby="renamePdfLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="renamePdfForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="renamePdfLabel">Ganti Nama File PDF</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama File Baru</label>
                            <input type="text" class="form-control" id="newPdfName" placeholder="Masukkan nama file baru">
                            <small class="text-muted">File akan disimpan dengan ekstensi .pdf secara otomatis</small>
                        </div>
                        <input type="hidden" id="oldPdfPath">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitRenamePdf">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete PDF Modal -->
    <div class="modal fade" id="deletePdfModal" tabindex="-1" aria-labelledby="deletePdfLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger" id="deletePdfLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Hapus File PDF
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Apakah Anda yakin ingin menghapus file: <br>
                        <strong id="deletePdfFileName" class="text-danger"></strong>
                    </p>
                    <p class="text-muted small mt-2">Tindakan ini tidak dapat dibatalkan.</p>
                    <input type="hidden" id="deletePdfPath">
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeletePdf" onclick="deletePdfFile()">
                        <i class="fas fa-trash me-2"></i> Hapus File
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Extend Contract Modal -->
    <div class="modal fade" id="extendContractModal" tabindex="-1" aria-labelledby="extendContractLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="extendContractForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="extendContractLabel">Perpanjang Kontrak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Berakhir Baru</label>
                            <input type="date" class="form-control" id="new_end_date" name="new_end_date">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Atau Tambah Hari (opsional)</label>
                            <input type="number" class="form-control" id="extend_days" name="extend_days"
                                min="1" placeholder="Jumlah hari untuk ditambahkan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea class="form-control" id="extend_note" name="note" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perpanjangan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.querySelectorAll('.progress-bar[data-progress]').forEach((progressBar) => {
            const percentage = Number(progressBar.dataset.progress);
            if (Number.isFinite(percentage)) {
                progressBar.style.width = `${Math.min(100, Math.max(0, percentage))}%`;
            }
        });

        function sendReminder() {
            if (!confirm('Kirim reminder untuk kontrak ini?')) return;

            const btn = document.getElementById('sendReminderBtn');
            if (btn) btn.disabled = true;

            showLoading('Mengirim reminder...');

            fetch("<?php echo e(route('contracts.send-reminder', $contract)); ?>", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(async (res) => {
                    hideLoading();
                    if (!res.ok) {
                        const err = await res.json().catch(() => ({}));
                        showToast(err.message || 'Gagal mengirim reminder', 'error');
                        if (btn) btn.disabled = false;
                        return;
                    }
                    const data = await res.json();
                    if (data.success) {
                        showToast(data.message || 'Reminder berhasil dikirim', 'success');

                        if (data.notification && data.notification.id) {
                            appendNotificationToList(data.notification);
                            pollNotificationStatus(data.notification.id);
                        }
                    } else {
                        showToast(data.message || 'Gagal mengirim reminder', 'error');
                    }
                    if (btn) btn.disabled = false;
                })
                .catch((err) => {
                    hideLoading();
                    if (btn) btn.disabled = false;
                    showToast('Gagal mengirim reminder (network error)', 'error');
                    console.error(err);
                });
        }

        function appendNotificationToList(notification) {
            const containerSelector = '.card .card-body .list-group.list-group-flush';
            let list = document.querySelector(containerSelector);

            // If list doesn't exist yet, create it inside the notifications card body
            if (!list) {
                const cardBody = document.querySelector('.card .card-body');
                if (!cardBody) return;
                list = document.createElement('div');
                list.className = 'list-group list-group-flush';
                cardBody.insertBefore(list, cardBody.firstChild);
            }

            const item = document.createElement('div');
            item.className = 'list-group-item px-0';
            item.id = 'notification-' + notification.id;

            const date = new Date(notification.created_at || Date.now());
            const dateStr = date.toLocaleDateString('en-GB').replace(/\//g, '/');

            const status = (notification.data && notification.data.sent) ? 'Terkirim' : 'Queued';

            item.innerHTML = `
                <div class="d-flex w-100 justify-content-between">
                    <small>${escapeHtml(notification.message)}</small>
                    <small class="text-muted">${dateStr}</small>
                </div>
                <div class="mt-1"><small id="notification-status-${notification.id}" class="text-muted">${status}</small></div>
            `;

            // Prepend
            if (list.firstChild) list.insertBefore(item, list.firstChild);
            else list.appendChild(item);
        }

        function escapeHtml(unsafe) {
            return String(unsafe)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // Poll notification status endpoint until sent_date appears or max attempts reached
        function pollNotificationStatus(notificationId, attempts = 0) {
            const maxAttempts = 20;
            const intervalMs = 3000;

            if (attempts >= maxAttempts) {
                const statusEl = document.getElementById('notification-status-' + notificationId);
                if (statusEl) statusEl.textContent = 'Gagal (timeout)';
                return;
            }

            // Build status URL template
            const urlTemplate = "<?php echo e(route('notifications.status', 'NOTIF_ID')); ?>";
            const url = urlTemplate.replace('NOTIF_ID', notificationId);

            fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(json => {
                    if (!json.success) throw new Error('No data');

                    const statusEl = document.getElementById('notification-status-' + notificationId);
                    if (json.sent_date || (json.data && json.data.sent)) {
                        if (statusEl) statusEl.textContent = 'Terkirim';
                        // Optionally update timestamp shown
                        const item = document.getElementById('notification-' + notificationId);
                        if (item) {
                            const smalls = item.querySelectorAll('.text-muted');
                            if (smalls && smalls[0]) smalls[0].textContent = (new Date(json.sent_date || json
                                .created_at)).toLocaleDateString('en-GB').replace(/\//g, '/');
                        }
                    } else if (json.data && json.data.error) {
                        if (statusEl) statusEl.textContent = 'Gagal: ' + json.data.error;
                    } else {
                        // Not yet sent, retry
                        setTimeout(() => pollNotificationStatus(notificationId, attempts + 1), intervalMs);
                    }
                })
                .catch(err => {
                    // network or server error -> retry a few times
                    setTimeout(() => pollNotificationStatus(notificationId, attempts + 1), intervalMs);
                });
        }

        function extendContract() {
            const modalEl = document.getElementById('extendContractModal');
            const modal = new bootstrap.Modal(modalEl);
            // prefill date from displayed end date if available (dd/mm/YYYY)
            const current = document.getElementById('contractEndDate')?.textContent?.trim();
            if (current) {
                const parts = current.split('/');
                if (parts.length === 3) {
                    const iso = `${parts[2]}-${parts[1].padStart(2,'0')}-${parts[0].padStart(2,'0')}`;
                    document.getElementById('new_end_date').value = iso;
                }
            }
            modal.show();
        }

        function terminateContract() {
            if (confirm('Hentikan kontrak ini?')) {
                showLoading('Menghentikan kontrak...');

                // Simulate API call
                setTimeout(() => {
                    hideLoading();
                    showToast('Kontrak berhasil dihentikan', 'success');
                    window.location.reload();
                }, 1000);
            }
        }

        function showLoading(message) {
            let loading = document.getElementById('loadingOverlay');
            if (!loading) {
                loading = document.createElement('div');
                loading.id = 'loadingOverlay';
                loading.className =
                    'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
                loading.style.backgroundColor = 'rgba(0,0,0,0.7)';
                loading.style.zIndex = '9999';
                loading.innerHTML = `
                <div class="text-center text-white">
                    <div class="spinner-border mb-3" role="status"></div>
                    <p class="mb-0">${message}</p>
                </div>
            `;
                document.body.appendChild(loading);
            }
        }

        function hideLoading() {
            const loading = document.getElementById('loadingOverlay');
            if (loading) {
                loading.remove();
            }
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 p-3';
            toast.style.zIndex = '9999';

            const bgColor = type === 'success' ? 'bg-success' :
                type === 'error' ? 'bg-danger' :
                type === 'warning' ? 'bg-warning' : 'bg-info';

            toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header ${bgColor} text-white">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 
                                    type === 'error' ? 'exclamation-circle' : 
                                    type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    <strong class="me-auto">${type === 'success' ? 'Sukses' : 
                                           type === 'error' ? 'Error' : 
                                           type === 'warning' ? 'Peringatan' : 'Info'}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Handle extend form submit
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('extendContractForm');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const newEnd = document.getElementById('new_end_date').value;
                const extendDays = document.getElementById('extend_days').value;
                const note = document.getElementById('extend_note').value;

                if (!newEnd && !extendDays) {
                    showToast('Masukkan tanggal baru atau jumlah hari untuk memperpanjang', 'warning');
                    return;
                }

                showLoading('Menyimpan perpanjangan...');

                fetch("<?php echo e(route('contracts.renew', $contract)); ?>", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            new_end_date: newEnd || null,
                            extend_days: extendDays || null,
                            note: note || null
                        })
                    })
                    .then(async res => {
                        hideLoading();
                        if (!res.ok) {
                            const err = await res.json().catch(() => ({}));
                            showToast(err.message || 'Gagal memperpanjang kontrak', 'error');
                            return;
                        }
                        const data = await res.json();
                        if (data.success) {
                            if (data.contract && data.contract.end_date) {
                                const d = new Date(data.contract.end_date);
                                const formatted = d.getDate().toString().padStart(2, '0') + '/' + (d
                                        .getMonth() + 1).toString().padStart(2, '0') + '/' + d
                                    .getFullYear();
                                const endEl = document.getElementById('contractEndDate');
                                if (endEl) endEl.textContent = formatted;
                            }
                            if (data.contract && typeof data.contract.days_remaining !==
                                'undefined') {
                                const badge = document.getElementById('contractDaysRemainingBadge');
                                if (badge) badge.textContent = data.contract.days_remaining +
                                    ' hari';
                            }
                            showToast(data.message || 'Kontrak berhasil diperpanjang', 'success');
                            const modalEl = document.getElementById('extendContractModal');
                            bootstrap.Modal.getInstance(modalEl).hide();
                        } else {
                            showToast(data.message || 'Gagal memperpanjang kontrak', 'error');
                        }
                    })
                    .catch(err => {
                        hideLoading();
                        showToast('Gagal memperpanjang kontrak (network error)', 'error');
                        console.error(err);
                    });
            });
        });

        // Copy URL to clipboard
        function copyToClipboard(url) {
            navigator.clipboard.writeText(url).then(() => {
                showToast('URL berhasil disalin ke clipboard', 'success');
            }).catch(() => {
                // Fallback untuk browser lama
                const textarea = document.createElement('textarea');
                textarea.value = url;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                showToast('URL berhasil disalin ke clipboard', 'success');
            });
        }

        // Open Rename PDF Modal
        function openRenamePdfModal(filePath, fileName) {
            const modal = new bootstrap.Modal(document.getElementById('renamePdfModal'));
            document.getElementById('oldPdfPath').value = filePath;
            
            // Remove file extension for display
            const nameWithoutExt = fileName.replace(/\.pdf$/i, '');
            document.getElementById('newPdfName').value = nameWithoutExt;
            document.getElementById('newPdfName').select();
            
            modal.show();
        }

        // Handle Rename PDF Form Submission
        document.getElementById('renamePdfForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const oldPath = document.getElementById('oldPdfPath').value;
            const newName = document.getElementById('newPdfName').value.trim();
            
            if (!newName) {
                showToast('Nama file tidak boleh kosong', 'error');
                return;
            }
            
            const btn = document.getElementById('submitRenamePdf');
            btn.disabled = true;
            showLoading('Memperbarui nama file...');
            
            try {
                const response = await fetch('<?php echo e(route("contracts.rename-pdf", $contract)); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        old_path: oldPath,
                        new_name: newName
                    })
                });
                
                const data = await response.json();
                hideLoading();
                
                if (data.success) {
                    showToast(data.message || 'File berhasil diganti nama', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('renamePdfModal')).hide();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast(data.message || 'Gagal mengganti nama file', 'error');
                }
            } catch (error) {
                hideLoading();
                showToast('Gagal mengganti nama file (network error)', 'error');
                console.error(error);
            }
            
            btn.disabled = false;
        });

        // Open Delete PDF Modal
        function openDeletePdfModal(filePath, fileName) {
            const modal = new bootstrap.Modal(document.getElementById('deletePdfModal'));
            document.getElementById('deletePdfPath').value = filePath;
            document.getElementById('deletePdfFileName').textContent = fileName;
            modal.show();
        }

        // Delete PDF File
        async function deletePdfFile() {
            const filePath = document.getElementById('deletePdfPath').value;
            const fileName = document.getElementById('deletePdfFileName').textContent;
            
            if (!filePath) {
                showToast('Path file tidak valid', 'error');
                return;
            }
            
            const btn = document.getElementById('confirmDeletePdf');
            btn.disabled = true;
            showLoading('Menghapus file...');
            
            try {
                const response = await fetch('<?php echo e(route("contracts.delete-pdf", $contract)); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        file_path: filePath
                    })
                });
                
                const data = await response.json();
                hideLoading();
                
                if (data.success) {
                    showToast(data.message || 'File berhasil dihapus', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('deletePdfModal')).hide();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast(data.message || 'Gagal menghapus file', 'error');
                    btn.disabled = false;
                }
            } catch (error) {
                hideLoading();
                showToast('Gagal menghapus file (network error)', 'error');
                console.error(error);
                btn.disabled = false;
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views\contracts\show.blade.php ENDPATH**/ ?>