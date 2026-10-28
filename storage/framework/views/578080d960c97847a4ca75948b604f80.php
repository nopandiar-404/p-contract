<!-- resources/views/clients/show.blade.php -->


<?php $__env->startSection('title', 'Detail Klien'); ?>
<?php $__env->startSection('subtitle', 'Informasi lengkap klien'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .client-detail-page {
            --client-navy: #102a43;
            --client-blue: #1d4e89;
            --client-pelindo: #0072bc;
            --client-muted: #627d98;
            --client-line: #e5edf5;
            padding: clamp(0.75rem, 2vw, 1.5rem);
            border: 1px solid #e4ebf3;
            border-radius: 12px;
            background: #f4f7fb;
            color: var(--client-navy);
        }

        .client-detail-page .client-summary {
            overflow: hidden;
            border: 1px solid var(--client-line) !important;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(16, 42, 67, 0.05) !important;
        }

        .client-detail-page .client-header {
            padding: 1rem 1.25rem;
            border: 0;
            background: var(--client-pelindo);
            color: #fff;
        }

        .client-detail-page .client-header h5 {
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .client-detail-page .client-summary > .card-body {
            padding: clamp(1rem, 2.5vw, 1.5rem);
        }

        .client-detail-page .badge-status {
            padding: 0.5rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
        }

        .client-detail-page .info-card {
            margin-bottom: 1.25rem;
            padding: 1rem 1.1rem;
            border: 1px solid var(--client-line);
            border-left: 3px solid var(--client-blue);
            border-radius: 8px;
            background: #fff;
        }

        .client-detail-page .info-card h6 {
            color: var(--client-navy);
            font-weight: 700;
        }

        .client-detail-page .info-card h6 i {
            color: var(--client-blue) !important;
        }

        .client-detail-page .info-card label,
        .client-detail-page .contract-item small,
        .client-detail-page .contract-item label {
            color: var(--client-muted) !important;
        }

        .client-detail-page .contract-item {
            margin-bottom: 1rem;
            padding: 1rem;
            border: 1px solid var(--client-line);
            border-left: 3px solid var(--client-blue);
            border-radius: 8px;
            background: #f8fafc;
        }

        .client-detail-page .contract-item.active {
            border-left-color: #198754;
        }

        .client-detail-page .contract-item.expired {
            border-left-color: #dc3545;
        }

        .client-detail-page .contract-item.terminated {
            border-left-color: #9aa9b8;
        }

        .client-detail-page .contract-item h6 {
            color: var(--client-navy);
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .client-detail-page .contract-item .border-top,
        .client-detail-page .border-top {
            border-color: var(--client-line) !important;
        }

        .client-detail-page .btn-warning {
            border-color: #ebb917;
            background: #ebb917;
            color: #fff;
        }

        .client-detail-page .btn-warning:hover {
            border-color: #d8a408;
            background: #d8a408;
            color: #fff;
        }

        .client-detail-page .btn-outline-primary {
            border-color: var(--client-blue);
            color: var(--client-blue);
        }

        .client-detail-page .btn-outline-primary:hover {
            border-color: var(--client-navy);
            background: var(--client-navy);
            color: #fff;
        }

        .client-detail-page a:not(.btn) {
            color: var(--client-blue);
        }

        @media (max-width: 576px) {
            .client-detail-page {
                padding: 0.65rem;
            }

            .client-detail-page .client-header > .d-flex {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 0.75rem;
            }

            .client-detail-page .client-header .badge-status {
                align-self: flex-start;
            }

            .client-detail-page .client-actions {
                flex-direction: column;
                align-items: stretch !important;
            }

            .client-detail-page .client-actions > div {
                display: grid;
                grid-template-columns: 1fr;
            }

            .client-detail-page .client-actions .btn {
                width: 100%;
                margin-right: 0 !important;
            }
        }
    </style>

    <div class="client-detail-page">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Card -->
            <div class="card client-summary border-0 shadow-sm mb-4">
                <div class="card-header client-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i> <?php echo e($client->name); ?>

                        </h5>
                        <span class="badge badge-status bg-<?php echo e($client->status == 'active' ? 'success' : 'danger'); ?>">
                            <?php echo e($client->status == 'active' ? 'Aktif' : 'Non-Aktif'); ?>

                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Informasi Umum Klien -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i> Informasi Umum
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0">
                                    <i class="fas fa-envelope me-2 text-info"></i>
                                    <a href="mailto:<?php echo e($client->email); ?>"><?php echo e($client->email); ?></a>
                                </p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Nomor Telepon</label>
                                <p class="mb-0">
                                    <i class="fas fa-phone me-2 text-info"></i>
                                    <?php echo e($client->phone ?? '-'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Alamat</label>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2 text-info"></i>
                                <?php echo e($client->address ?? '-'); ?>

                            </p>
                        </div>

                        <?php if($client->notes): ?>
                            <div class="mb-3">
                                <label class="text-muted small">Catatan</label>
                                <p class="mb-0">
                                    <i class="fas fa-sticky-note me-2 text-info"></i>
                                    <?php echo e($client->notes); ?>

                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Informasi PIC -->
                    <div class="info-card pic mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-user-tie text-success me-2"></i> Informasi Person In Charge (PIC)
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Nama PIC</label>
                                <p class="mb-0"><?php echo e($client->pic_name ?? '-'); ?></p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Email PIC</label>
                                <p class="mb-0">
                                    <?php if($client->pic_email): ?>
                                        <a href="mailto:<?php echo e($client->pic_email); ?>"><?php echo e($client->pic_email); ?></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="text-muted small">Nomor Telepon PIC</label>
                            <p class="mb-0"><?php echo e($client->pic_phone ?? '-'); ?></p>
                        </div>
                    </div>

                    <!-- Daftar Kontrak -->
                    <div class="info-card contracts">
                        <h6 class="mb-3">
                            <i class="fas fa-file-contract text-warning me-2"></i>
                            Kontrak Terkait <span
                                class="badge bg-warning text-dark"><?php echo e($client->contracts->count()); ?></span>
                        </h6>

                        <?php if($client->contracts->count() > 0): ?>
                            <?php $__currentLoopData = $client->contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="contract-item <?php echo e(strtolower($contract->status)); ?>">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fas fa-file me-2"></i> <?php echo e($contract->contract_name); ?>

                                            </h6>
                                            <small class="text-muted"><?php echo e($contract->contract_number); ?></small>
                                        </div>
                                        <span
                                            class="badge bg-<?php echo e($contract->status == 'active'
                                                ? 'success'
                                                : ($contract->status == 'expired'
                                                    ? 'danger'
                                                    : ($contract->status == 'terminated'
                                                        ? 'secondary'
                                                        : 'warning'))); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $contract->status))); ?>

                                        </span>
                                    </div>

                                    <?php if($contract->description): ?>
                                        <p class="text-muted small mb-2"><?php echo e($contract->description); ?></p>
                                    <?php endif; ?>

                                    <div class="row small mb-2">
                                        <div class="col-md-6">
                                            <label class="text-muted">Tanggal Mulai</label>
                                            <p class="mb-0">
                                                <i class="fas fa-calendar-check text-success me-1"></i>
                                                <?php echo e($contract->start_date->format('d M Y')); ?>

                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted">Tanggal Berakhir</label>
                                            <p class="mb-0">
                                                <i class="fas fa-calendar-times text-danger me-1"></i>
                                                <?php echo e($contract->end_date->format('d M Y')); ?>

                                            </p>
                                        </div>
                                    </div>

                                    <?php if($contract->grace_period_days): ?>
                                        <div class="alert alert-info py-2 px-3 small mb-2">
                                            <i class="fas fa-clock me-1"></i>
                                            <strong>Masa Tenggang:</strong>
                                            <?php echo e(number_format($contract->grace_period_days, 0)); ?> hari
                                        </div>
                                    <?php endif; ?>

                                    <?php if($contract->status == 'terminated' && $contract->termination_reason): ?>
                                        <div class="alert alert-warning py-2 px-3 small mb-0">
                                            <i class="fas fa-comment me-1"></i>
                                            <strong>Alasan Penghentian:</strong> <?php echo e($contract->termination_reason); ?>

                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-2 pt-2 border-top">
                                        <a href="<?php echo e(route('contracts.show', $contract)); ?>"
                                            class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-eye me-1"></i> Lihat Detail
                                        </a>
                                        <a href="<?php echo e(route('contracts.edit', $contract)); ?>"
                                            class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="alert alert-light mb-0">
                                <i class="fas fa-inbox text-muted me-2"></i>
                                <span class="text-muted">Tidak ada kontrak untuk klien ini</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Metadata -->
                    <div class="border-top pt-3 mt-4">
                        <div class="row small text-muted">
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-calendar me-1"></i> Dibuat: <?php echo e($client->created_at->format('d M Y H:i')); ?>

                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-sync me-1"></i> Diperbarui: <?php echo e($client->updated_at->format('d M Y H:i')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 justify-content-between mb-4 client-actions">
                <div>
                    <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
                <div>
                    <a href="<?php echo e(route('clients.edit', $client)); ?>" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i> Edit Klien
                    </a>
                    <button type="button" class="btn btn-danger" onclick="showDeleteModal()">
                        <i class="fas fa-trash me-2"></i> Hapus Klien
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus klien ini?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Perhatian:</strong>
                        <?php if($client->contracts->count() > 0): ?>
                            Klien ini memiliki <?php echo e($client->contracts->count()); ?> kontrak dan tidak dapat dihapus!
                        <?php else: ?>
                            Data yang dihapus tidak dapat dikembalikan!
                        <?php endif; ?>
                    </div>
                    <p><strong>Klien:</strong> <?php echo e($client->name); ?> (<?php echo e($client->email); ?>)</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <?php if($client->contracts->count() == 0): ?>
                        <button type="button" class="btn btn-danger" id="confirmDeleteClientBtn"
                            data-action="<?php echo e(route('clients.destroy', $client)); ?>"
                            data-index-url="<?php echo e(route('clients.index')); ?>" data-id="<?php echo e($client->id); ?>">
                            Hapus Klien
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger" disabled>
                            Tidak Dapat Dihapus (Memiliki Kontrak)
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        window.showDeleteModal = function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        };
    </script>
    <script>
        document.getElementById('confirmDeleteClientBtn')?.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const indexUrl = this.getAttribute('data-index-url');
            if (!action) return;
            if (!confirm('Hapus klien ini? Tindakan ini tidak dapat dibatalkan.')) return;

            fetch(action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(resp => {
                    if (resp.ok) return resp.json().catch(() => ({
                        success: true
                    }));
                    throw new Error('Request failed');
                })
                .then(data => {
                    window.location.href = indexUrl;
                })
                .catch(err => {
                    console.error('Failed to delete client', err);
                    alert('Gagal menghapus klien. Cek log untuk detail.');
                });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DASHBOARD\p-contract\resources\views\clients\show.blade.php ENDPATH**/ ?>