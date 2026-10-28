<!-- resources/views/dashboard/analytics.blade.php -->


<?php $__env->startSection('title', 'Analisis Detail'); ?>
<?php $__env->startSection('subtitle', 'Laporan komprehensif dan analisis mendalam kontrak'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $totalContracts = max((int) ($stats['total'] ?? 0), 1);
        $activePct = round((($stats['active'] ?? 0) / $totalContracts) * 100, 1);
        $gracePct = round((($stats['grace_period'] ?? 0) / $totalContracts) * 100, 1);
        $expiredPct = round((($stats['expired'] ?? 0) / $totalContracts) * 100, 1);
    ?>
    <div class="container-fluid">
        <!-- Header dengan Quick Stats -->
        <div class="row mb-4">
            <div class="col-12">
                <!-- <div class="d-flex justify-content-between align-items-center mb-3" style="background-color: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <div>
                        <h4 class="mb-1"><i class="fas fa-chart-bar me-2"></i> Analisis Komprehensif</h4>
                        <p class="text-muted mb-0">Data real-time dan insights mendalam tentang portfolio kontrak Anda</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary" onclick="downloadAnalyticsReport()">
                            <i class="fas fa-download me-1"></i> Download
                        </button>
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- KPI Cards Section -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 kpi-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2 small"><i class="fas fa-file-contract me-1"></i> Total Kontrak</p>
                                <h3 class="mb-0 fw-bold"><?php echo e($stats['total'] ?? 0); ?></h3>
                            </div>
                            <div class="text-primary opacity-25">
                                <i class="fas fa-file-contract fa-3x"></i>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i> Semua status
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 kpi-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2 small"><i class="fas fa-check-circle me-1"></i> Aktif</p>
                                <h3 class="mb-0 fw-bold text-success"><?php echo e($stats['active'] ?? 0); ?></h3>
                            </div>
                            <div class="text-success opacity-25">
                                <i class="fas fa-check-circle fa-3x"></i>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <?php echo e(round(($stats['active'] ?? 0) / ($stats['total'] ?? 1) * 100, 1)); ?>% dari total
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 kpi-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2 small"><i class="fas fa-clock me-1"></i> Masa Tenggang</p>
                                <h3 class="mb-0 fw-bold text-warning"><?php echo e($stats['grace_period'] ?? 0); ?></h3>
                            </div>
                            <div class="text-warning opacity-25">
                                <i class="fas fa-clock fa-3x"></i>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <?php echo e(round(($stats['grace_period'] ?? 0) / ($stats['total'] ?? 1) * 100, 1)); ?>% dari total
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 kpi-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2 small"><i class="fas fa-times-circle me-1"></i> Kadaluarsa</p>
                                <h3 class="mb-0 fw-bold text-danger"><?php echo e($stats['expired'] ?? 0); ?></h3>
                            </div>
                            <div class="text-danger opacity-25">
                                <i class="fas fa-times-circle fa-3x"></i>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <?php echo e(round(($stats['expired'] ?? 0) / ($stats['total'] ?? 1) * 100, 1)); ?>% dari total
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4 mb-4">
            <!-- Status Distribution Pie Chart -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-pie-chart me-2"></i> Distribusi Status</h5>
                        <small class="text-muted"><?php echo e($stats['total'] ?? 0); ?> kontrak</small>
                    </div>
                    <div class="card-body">
                        <div style="min-height: 300px; position: relative;">
                            <canvas id="statusDistributionChart"
                                data-total="<?php echo e((int) ($stats['total'] ?? 0)); ?>"
                                data-values='<?php echo e(json_encode([(int) ($stats['active'] ?? 0), (int) ($stats['grace_period'] ?? 0), (int) ($stats['expired'] ?? 0)])); ?>'>
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Progress -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-timeline me-2"></i> Status Kontrak</h5>
                        <small class="text-muted">Ringkasan</small>
                    </div>
                    <div class="card-body">
                        <div class="timeline-progress">
                            <div class="timeline-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-success me-2"><?php echo e($stats['active'] ?? 0); ?></span>
                                    <span class="fw-semibold">Aktif</span>
                                    <small class="text-muted ms-auto"><?php echo e(round(($stats['active'] ?? 0) / ($stats['total'] ?? 1) * 100, 1)); ?>%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" data-width="<?php echo e($activePct); ?>"></div>
                                </div>
                            </div>

                            <div class="timeline-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-warning me-2"><?php echo e($stats['grace_period'] ?? 0); ?></span>
                                    <span class="fw-semibold">Masa Tenggang</span>
                                    <small class="text-muted ms-auto"><?php echo e(round(($stats['grace_period'] ?? 0) / ($stats['total'] ?? 1) * 100, 1)); ?>%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" data-width="<?php echo e($gracePct); ?>"></div>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-danger me-2"><?php echo e($stats['expired'] ?? 0); ?></span>
                                    <span class="fw-semibold">Kadaluarsa</span>
                                    <small class="text-muted ms-auto"><?php echo e(round(($stats['expired'] ?? 0) / ($stats['total'] ?? 1) * 100, 1)); ?>%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" data-width="<?php echo e($expiredPct); ?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Metrics Section -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm" style="padding-bottom: 100px;">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-coins me-2"></i> Analisis Finansial</h5>
                        <small class="text-muted">Total nilai DPP</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <p class="text-muted small mb-2"><i class="fas fa-layer-group me-1"></i> Total DPP</p>
                                    <h4 class="mb-0 fw-bold">Rp <?php echo e(number_format($stats['total_dpp'] ?? 0, 0, ',', '.')); ?></h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <p class="text-muted small mb-2"><i class="fas fa-calculator me-1"></i> Rata-rata DPP</p>
                                    <h4 class="mb-0 fw-bold">Rp <?php echo e(number_format(($stats['total_dpp'] ?? 0) / ($stats['total'] ?? 1), 0, ',', '.')); ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Berdasarkan <?php echo e($stats['total'] ?? 0); ?> kontrak dengan nilai DPP</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Insight Cepat</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-arrow-trend-up text-success mt-1"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Tingkat Kesehatan</p>
                                        <small class="text-muted"><?php echo e(round(($stats['active'] ?? 0) / ($stats['total'] ?? 1) * 100, 1)); ?>% kontrak aktif</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-hourglass-end text-warning mt-1"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Perhatian Diperlukan</p>
                                        <small class="text-muted"><?php echo e($stats['grace_period'] ?? 0); ?> kontrak masa tenggang</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-calendar-check text-info mt-1"></i>
                                    <div>
                                        <p class="fw-semibold mb-1">Pembaruan Terakhir</p>
                                        <small class="text-muted"><?php echo e(now()->format('d M Y H:i')); ?></small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Tables Section -->
        <div class="row g-4">
            <!-- Top Vendors -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0"><i class="fas fa-building me-2"></i> Vendor Terpenting</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Vendor</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-end">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $topVendors ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($vendor->name ?? 'N/A'); ?></td>
                                            <td class="text-end">
                                                <span class="badge bg-primary"><?php echo e($vendor->contracts_count ?? 0); ?></span>
                                            </td>
                                            <td class="text-end">
                                                <small class="text-muted">Rp <?php echo e(number_format($vendor->total_value ?? 0, 0, ',', '.')); ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">Tidak ada data vendor</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Expirations -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0"><i class="fas fa-calendar-times me-2"></i> Kontrak Akan Berakhir</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kontrak</th>
                                        <th class="text-end">Sisa Hari</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $upcomingExpirations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <small><?php echo e($contract->contract_name ?? 'N/A'); ?></small>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-<?php echo e($contract->days_remaining < 7 ? 'danger' : ($contract->days_remaining < 14 ? 'warning' : 'info')); ?>">
                                                    <?php echo e($contract->days_remaining ?? 0); ?> hari
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?php echo e(route('contracts.show', $contract)); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">Tidak ada kontrak akan berakhir</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* KPI Card Styles */
        .kpi-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1) !important;
        }

        /* Timeline Progress */
        .timeline-progress .timeline-item {
            transition: all 0.2s ease;
        }

        .timeline-progress .timeline-item:hover {
            transform: translateX(4px);
        }

        /* Chart Container */
        #statusDistributionChart {
            max-height: 300px;
        }

        /* Responsive Tables */
        @media (max-width: 768px) {
            .kpi-card h3 {
                font-size: 1.75rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }

            .btn-group-sm .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Print Styles */
        @media print {
            .btn, .dropdown, .d-flex > .btn {
                display: none !important;
            }

            .card {
                page-break-inside: avoid;
                border: 1px solid #dee2e6 !important;
            }
        }

        /* Fade-in Animation */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        function renderFallbackDoughnutChart(canvas, values, colors) {
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;

            const total = values.reduce((sum, value) => sum + Number(value || 0), 0) || 1;
            const size = Math.min(canvas.clientWidth || 260, 260);
            const centerX = size / 2;
            const centerY = size / 2;
            const radius = size * 0.34;
            const startAngle = -Math.PI / 2;
            let currentAngle = startAngle;

            canvas.width = size;
            canvas.height = size;
            ctx.clearRect(0, 0, size, size);

            values.forEach((value, index) => {
                const slice = Number(value || 0) / total;
                const endAngle = currentAngle + slice * Math.PI * 2;
                ctx.beginPath();
                ctx.moveTo(centerX, centerY);
                ctx.arc(centerX, centerY, radius, currentAngle, endAngle);
                ctx.closePath();
                ctx.fillStyle = colors[index % colors.length];
                ctx.fill();
                currentAngle = endAngle;
            });

            ctx.beginPath();
            ctx.arc(centerX, centerY, radius * 0.58, 0, Math.PI * 2);
            ctx.fillStyle = '#ffffff';
            ctx.fill();

            ctx.fillStyle = '#1f2937';
            ctx.font = 'bold 16px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(String(total), centerX, centerY + 6);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-width]').forEach((bar) => {
                const value = Number(bar.dataset.width || 0);
                bar.style.width = Math.min(Math.max(value, 0), 100) + '%';
            });

            const statusCtx = document.getElementById('statusDistributionChart');
            if (!statusCtx) return;

            const statusValues = JSON.parse(statusCtx.dataset.values || '[0,0,0]');
            const totalCount = Number(statusCtx.dataset.total || 1);

            if (typeof window.Chart !== 'undefined') {
                new window.Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Aktif', 'Masa Tenggang', 'Kadaluarsa'],
                        datasets: [{
                            data: statusValues,
                            backgroundColor: [
                                'rgba(46, 204, 113, 0.8)',
                                'rgba(243, 156, 18, 0.8)',
                                'rgba(231, 76, 60, 0.8)'
                            ],
                            borderColor: [
                                '#2ecc71',
                                '#f39c12',
                                '#e74c3c'
                            ],
                            borderWidth: 2,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: { size: 13 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = Number(context.raw || 0);
                                        const percentage = totalCount > 0 ? Math.round((value / totalCount) * 100) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                renderFallbackDoughnutChart(statusCtx, statusValues, [
                    '#2ecc71',
                    '#f39c12',
                    '#e74c3c'
                ]);
            }

            document.querySelectorAll('.kpi-card').forEach((card, index) => {
                card.style.animation = 'fadeIn 0.5s ease-in ' + (index * 0.1) + 's both';
            });
        });

        function downloadAnalyticsReport() {
            const date = new Date().toISOString().split('T')[0];
            window.location.href = `/analytics/export?type=pdf&date=${date}`;
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
                        <strong class="me-auto">${type.toUpperCase()}</strong>
                    </div>
                    <div class="toast-body">${message}</div>
                </div>
            `;

            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DASHBOARD\p-contract\resources\views\dashboard\analytics.blade.php ENDPATH**/ ?>