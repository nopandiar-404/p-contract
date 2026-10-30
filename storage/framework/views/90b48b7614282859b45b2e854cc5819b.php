<!-- resources/views/notifications/index.blade.php -->


<?php $__env->startSection('title', 'Notifikasi'); ?>
<?php $__env->startSection('subtitle', 'Pengingat dan Notifikasi Kontrak'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <!-- Header dengan Statistik -->
                <div
                    class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-bell text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Notifikasi Kontrak</h5>
                            <small class="opacity-75">Pengingat, peringatan, dan pembaruan kontrak</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <!-- Filter Status -->
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center"
                                type="button" id="statusFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-2"></i>
                                <span class="d-none d-md-inline">
                                    <?php if(request('status') === 'unread'): ?>
                                        Belum Dibaca
                                    <?php elseif(request('status') === 'read'): ?>
                                        Sudah Dibaca
                                    <?php else: ?>
                                        Semua Status
                                    <?php endif; ?>
                                </span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="statusFilter">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between <?php echo e(!request('status') ? 'active' : ''); ?>"
                                        href="<?php echo e(request()->fullUrlWithQuery(['status' => ''])); ?>">
                                        <div>
                                            <i class="fas fa-layer-group text-muted me-2"></i>
                                            Semua Notifikasi
                                        </div>
                                        <?php if(!request('status')): ?>
                                            <i class="fas fa-check text-primary"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-2">
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between <?php echo e(request('status') === 'unread' ? 'active' : ''); ?>"
                                        href="<?php echo e(request()->fullUrlWithQuery(['status' => 'unread'])); ?>">
                                        <div>
                                            <span
                                                class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 me-2">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <span>Belum Dibaca</span>
                                            <small class="text-muted d-block ms-4">Butuh perhatian Anda</small>
                                        </div>
                                        <?php if(request('status') === 'unread'): ?>
                                            <i class="fas fa-check text-warning"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between <?php echo e(request('status') === 'read' ? 'active' : ''); ?>"
                                        href="<?php echo e(request()->fullUrlWithQuery(['status' => 'read'])); ?>">
                                        <div>
                                            <span
                                                class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 me-2">
                                                <i class="fas fa-envelope-open"></i>
                                            </span>
                                            <span>Sudah Dibaca</span>
                                            <small class="text-muted d-block ms-4">Telah ditinjau</small>
                                        </div>
                                        <?php if(request('status') === 'read'): ?>
                                            <i class="fas fa-check text-success"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Filter Tipe -->
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center"
                                type="button" id="typeFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-tag me-2"></i>
                                <span class="d-none d-md-inline">
                                    <?php if(request('type')): ?>
                                        <?php echo e(ucfirst(str_replace('_', ' ', request('type')))); ?>

                                    <?php else: ?>
                                        Semua Tipe
                                    <?php endif; ?>
                                </span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="typeFilter">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between <?php echo e(!request('type') ? 'active' : ''); ?>"
                                        href="<?php echo e(request()->fullUrlWithQuery(['type' => ''])); ?>">
                                        <div>
                                            <i class="fas fa-tags text-muted me-2"></i>
                                            Semua Tipe
                                        </div>
                                        <?php if(!request('type')): ?>
                                            <i class="fas fa-check text-primary"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-2">
                                </li>
                                <?php $__currentLoopData = ['reminder' => 'Pengingat', 'grace_period_start' => 'Masa Tenggang', 'contract_expired' => 'Kadaluarsa', 'contract_terminated' => 'Dihentikan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeValue => $typeLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center justify-content-between <?php echo e(request('type') === $typeValue ? 'active' : ''); ?>"
                                            href="<?php echo e(request()->fullUrlWithQuery(['type' => $typeValue])); ?>">
                                            <div>
                                                <span
                                                    class="badge <?php echo e($typeValue === 'reminder' ? 'bg-warning' : ($typeValue === 'grace_period_start' ? 'bg-danger' : ($typeValue === 'contract_expired' ? 'bg-secondary' : 'bg-info'))); ?> bg-opacity-25 text-<?php echo e($typeValue === 'reminder' ? 'warning' : ($typeValue === 'grace_period_start' ? 'danger' : ($typeValue === 'contract_expired' ? 'secondary' : 'info'))); ?> border border-<?php echo e($typeValue === 'reminder' ? 'warning' : ($typeValue === 'grace_period_start' ? 'danger' : ($typeValue === 'contract_expired' ? 'secondary' : 'info'))); ?> border-opacity-25 me-2">
                                                    <i
                                                        class="fas <?php echo e($typeValue === 'reminder' ? 'fa-clock' : ($typeValue === 'grace_period_start' ? 'fa-hourglass-half' : ($typeValue === 'contract_expired' ? 'fa-times-circle' : 'fa-ban'))); ?>"></i>
                                                </span>
                                                <span><?php echo e($typeLabel); ?></span>
                                            </div>
                                            <?php if(request('type') === $typeValue): ?>
                                                <i
                                                    class="fas fa-check text-<?php echo e($typeValue === 'reminder' ? 'warning' : ($typeValue === 'grace_period_start' ? 'danger' : ($typeValue === 'contract_expired' ? 'secondary' : 'info'))); ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>

                        <!-- Tandai Semua sebagai Dibaca -->
                        <?php if($unreadCount > 0): ?>
                            <form action="<?php echo e(route('notifications.mark-all-read')); ?>" method="POST" class="me-2">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success btn-sm d-flex align-items-center">
                                    <i class="fas fa-check-double me-2"></i>
                                    <span class="d-none d-md-inline">Tandai Semua Dibaca</span>
                                </button>
                            </form>
                        <?php endif; ?>

                        <!-- Hapus Notifikasi -->
                        <form action="<?php echo e(route('notifications.clear-read')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline-light btn-sm d-flex align-items-center">
                                <i class="fas fa-trash-alt me-2"></i>
                                <span class="d-none d-md-inline">Bersihkan</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Filter Chips -->
                <div class="card-body py-2 border-bottom bg-light">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <small class="text-muted me-2">
                            <i class="fas fa-sliders-h me-1"></i> Filter:
                        </small>

                        <?php if(request()->has('status')): ?>
                            <div
                                class="filter-chip <?php echo e(request('status') === 'unread' ? 'bg-warning-subtle border-warning' : 'bg-success-subtle border-success'); ?> border rounded-pill px-3 py-1 d-flex align-items-center">
                                <span class="me-2">
                                    <i
                                        class="fas <?php echo e(request('status') === 'unread' ? 'fa-envelope text-warning' : 'fa-envelope-open text-success'); ?> me-1"></i>
                                    Status: <?php echo e(request('status') === 'unread' ? 'Belum Dibaca' : 'Sudah Dibaca'); ?>

                                </span>
                                <a href="<?php echo e(request()->fullUrlWithQuery(['status' => ''])); ?>" class="text-decoration-none">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if(request()->has('type')): ?>
                            <?php
                                $typeColors = [
                                    'reminder' => 'warning',
                                    'grace_period_start' => 'danger',
                                    'contract_expired' => 'secondary',
                                    'contract_terminated' => 'info',
                                ];
                                $typeLabels = [
                                    'reminder' => 'Pengingat',
                                    'grace_period_start' => 'Masa Tenggang',
                                    'contract_expired' => 'Kadaluarsa',
                                    'contract_terminated' => 'Dihentikan',
                                ];
                            ?>
                            <div
                                class="filter-chip bg-<?php echo e($typeColors[request('type')] ?? 'primary'); ?>-subtle border border-<?php echo e($typeColors[request('type')] ?? 'primary'); ?> rounded-pill px-3 py-1 d-flex align-items-center">
                                <span class="me-2">
                                    <i class="fas fa-tag text-<?php echo e($typeColors[request('type')] ?? 'primary'); ?> me-1"></i>
                                    Tipe: <?php echo e($typeLabels[request('type')] ?? ucfirst(request('type'))); ?>

                                </span>
                                <a href="<?php echo e(request()->fullUrlWithQuery(['type' => ''])); ?>" class="text-decoration-none">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if(request()->has('status') || request()->has('type')): ?>
                            <a href="<?php echo e(route('notifications.index')); ?>"
                                class="btn btn-sm btn-outline-danger rounded-pill d-flex align-items-center">
                                <i class="fas fa-times me-1"></i> Hapus Semua Filter
                            </a>
                        <?php endif; ?>

                        <!-- Statistik Cepat -->
                        <div class="ms-auto d-flex gap-3">
                            <div class="text-center">
                                <small class="text-muted d-block">Total</small>
                                <span id="notification-total-count" class="badge bg-primary rounded-pill"><?php echo e($notifications->total()); ?></span>
                            </div>
                            <div class="text-center">
                                <small class="text-muted d-block">Belum Dibaca</small>
                                <span id="notification-unread-count" class="badge bg-warning rounded-pill"><?php echo e($unreadCount); ?></span>
                            </div>
                            <div class="text-center">
                                <small class="text-muted d-block">Pengingat</small>
                                <span id="notification-reminder-count" class="badge bg-info rounded-pill"><?php echo e($reminderCount); ?></span>
                            </div>
                            <div class="text-center">
                                <small class="text-muted d-block">Kritis</small>
                                <span id="notification-critical-count" class="badge bg-danger rounded-pill"><?php echo e($criticalCount); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Container untuk dynamic toast notifications -->
                    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if($notifications->isEmpty()): ?>
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                <h4 class="h5">Tidak ada notifikasi</h4>
                                <p class="mb-0">Semua notifikasi sudah ditangani</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Daftar Notifikasi -->
                        <div class="notification-list">
                            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $notifData = $notification->data ?? [];
                                    $contract = $notification->contract;
                                    $statusText = 'Menunggu';
                                    $statusClass = 'text-muted';
                                    if (!empty($notification->sent_date) || !empty($notifData['sent'])) {
                                        $statusText = 'Terkirim';
                                        $statusClass = 'text-success';
                                    } elseif (!empty($notifData['error'])) {
                                        $statusText = 'Gagal: ' . $notifData['error'];
                                        $statusClass = 'text-danger';
                                    } elseif (!empty($notifData['queued'])) {
                                        $statusText = 'Antrian';
                                        $statusClass = 'text-info';
                                    }
                                ?>

                                <div id="notification-<?php echo e($notification->id); ?>"
                                    data-notification-id="<?php echo e($notification->id); ?>"
                                    data-notification-type="<?php echo e($notification->type); ?>"
                                    data-notification-read="<?php echo e($notification->is_read ? 1 : 0); ?>"
                                    class="notification-item <?php echo e(!$notification->is_read ? 'unread' : ''); ?> border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="notification-icon me-3">
                                                <?php if($notification->type === 'reminder'): ?>
                                                    <div class="bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center rounded-circle"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                <?php elseif($notification->type === 'grace_period_start'): ?>
                                                    <div class="bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center rounded-circle"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-hourglass-half"></i>
                                                    </div>
                                                <?php elseif($notification->type === 'contract_expired'): ?>
                                                    <div class="bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center rounded-circle"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-times-circle"></i>
                                                    </div>
                                                <?php elseif($notification->type === 'contract_terminated'): ?>
                                                    <div class="bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center rounded-circle"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-ban"></i>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-circle"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-bell"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 d-flex align-items-center">
                                                    <?php echo e($notification->message); ?>

                                                    <?php if(!$notification->is_read): ?>
                                                        <span class="badge bg-warning ms-2">Baru</span>
                                                    <?php endif; ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?php echo e($notification->created_at->diffForHumans()); ?>

                                                    •
                                                    <i class="fas fa-file-contract me-1"></i>
                                                    <?php echo e($contract?->contract_number ?? 'Kontrak tidak tersedia'); ?>

                                                </small>
                                                <div class="mt-1">
                                                    <small id="notification-status-<?php echo e($notification->id); ?>"
                                                        class="<?php echo e($statusClass); ?>">
                                                        <?php echo e($statusText); ?>

                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary border-0" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <?php if($contract): ?>
                                                        <a class="dropdown-item"
                                                            href="<?php echo e(route('contracts.show', $contract)); ?>">
                                                            <i class="fas fa-external-link-alt me-2"></i> Lihat Kontrak
                                                        </a>
                                                    <?php endif; ?>
                                                </li>
                                                <?php if(!$notification->is_read): ?>
                                                    <li>
                                                        <form
                                                            action="<?php echo e(route('notifications.mark-read', $notification)); ?>"
                                                            method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-check me-2"></i> Tandai Dibaca
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>
                                                <li>
                                                    <button type="button"
                                                        class="dropdown-item text-danger delete-notification-btn"
                                                        data-action="<?php echo e(route('notifications.destroy', $notification)); ?>"
                                                        data-id="<?php echo e($notification->id); ?>">
                                                        <i class="fas fa-trash me-2"></i> Hapus
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <small class="me-3">
                                                <i class="fas fa-user-circle me-1"></i>
                                                <?php echo e($contract?->contract_name ?? 'Kontrak tidak tersedia'); ?>

                                            </small>
                                            <small class="me-3">
                                                <i class="fas fa-building me-1"></i>
                                                <?php echo e($contract?->client?->name ?? '-'); ?>

                                            </small>
                                            <?php if($contract?->status === 'grace_period'): ?>
                                                <small>
                                                    <i class="fas fa-hourglass-end me-1 text-danger"></i>
                                                    <?php
                                                        $graceEnd = \Carbon\Carbon::parse(
                                                            $contract->end_date,
                                                        )->addDays($contract->grace_period_days);
                                                        $remainingDays = \Carbon\Carbon::now()->diffInDays(
                                                            $graceEnd,
                                                            false,
                                                        );
                                                    ?>
                                                    Sisa <?php echo e(number_format(max(0, $remainingDays), 0)); ?> hari
                                                </small>
                                            <?php endif; ?>
                                        </div>

                                        <div>
                                            <?php if(!$notification->is_read): ?>
                                                <form action="<?php echo e(route('notifications.mark-read', $notification)); ?>"
                                                    method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-check me-1"></i> Tandai Dibaca
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if($contract): ?>
                                                <a href="<?php echo e(route('contracts.show', $contract)); ?>"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Pagination -->
                        <?php if($notifications->hasPages()): ?>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    <small class="text-muted">
                                        Menampilkan
                                        <?php echo e($notifications->firstItem() ?? 0); ?>-<?php echo e($notifications->lastItem() ?? 0); ?> dari
                                        <?php echo e($notifications->total()); ?> notifikasi
                                    </small>
                                </div>
                                <nav aria-label="Page navigation">
                                    <?php echo e($notifications->links()); ?>

                                </nav>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Card Footer -->
                <?php if(!$notifications->isEmpty()): ?>
                    <div class="card-footer bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Notifikasi terakhir diperbarui: <?php echo e(now()->format('d M Y H:i')); ?>

                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted me-3">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    Auto-refresh: 5 menit
                                </small>
                                <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                                    <i class="fas fa-redo me-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal for Notification Details -->
    <div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-labelledby="notificationDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="notificationDetailModalLabel">
                        <i class="fas fa-bell me-2"></i> Detail Notifikasi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="notificationDetailContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="goToContractBtn">
                        <i class="fas fa-external-link-alt me-1"></i> Ke Kontrak
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal for notification details
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationDetailModal'));
            const goToContractBtn = document.getElementById('goToContractBtn');
            let currentContractUrl = '';

            // Load notification details
            document.querySelectorAll('.notification-item').forEach(item => {
                const notificationId = item.dataset.notificationId;
                if (notificationId) {
                    item.addEventListener('click', function(e) {
                        if (!e.target.closest('.dropdown') && !e.target.closest('button') && !e
                            .target.closest('a')) {
                            loadNotificationDetails(notificationId);
                        }
                    });
                }
            });

            function loadNotificationDetails(id) {
                fetch(`/notifications/${id}/details`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('notificationDetailContent').innerHTML = data.html;
                            currentContractUrl = data.contract_url;
                            notificationModal.show();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading notification details:', error);
                    });
            }

            goToContractBtn.addEventListener('click', function() {
                if (currentContractUrl) {
                    window.location.href = currentContractUrl;
                }
            });

            // Auto-mark as read when viewing
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const notificationId = entry.target.dataset.notificationId;
                        if (notificationId && !entry.target.classList.contains('read')) {
                            // Optionally mark as read after viewing for 3 seconds
                            setTimeout(() => {
                                markAsRead(notificationId, entry.target);
                            }, 3000);
                        }
                    }
                });
            }, {
                threshold: 0.5
            });

            document.querySelectorAll('.notification-item.unread').forEach(item => {
                observer.observe(item);
            });

            function markAsRead(id, element) {
                fetch(`/notifications/${id}/mark-read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            element.classList.remove('unread');
                            element.classList.add('read');
                            element.dataset.notificationRead = '1';
                            decrementBadge('notification-unread-count');
                        }
                    });
            }

            function decrementBadge(id) {
                const badge = document.getElementById(id);
                if (!badge) return;
                const currentCount = parseInt(badge.textContent) || 0;
                badge.textContent = Math.max(0, currentCount - 1);
            }

            function updateCountsAfterDelete(item) {
                if (!item) return;
                decrementBadge('notification-total-count');

                const type = item.dataset.notificationType;
                const isRead = item.dataset.notificationRead === '1';

                if (!isRead) {
                    decrementBadge('notification-unread-count');
                }

                if (type === 'reminder') {
                    decrementBadge('notification-reminder-count');
                }

                if (['grace_period_start', 'contract_expired', 'contract_terminated'].includes(type)) {
                    decrementBadge('notification-critical-count');
                }

                showEmptyStateIfNeeded();
            }

            function showEmptyStateIfNeeded() {
                const list = document.querySelector('.notification-list');
                if (!list) return;
                if (list.querySelectorAll('.notification-item').length === 0) {
                    list.innerHTML = `
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                <h4 class="h5">Tidak ada notifikasi</h4>
                                <p class="mb-0">Semua notifikasi sudah ditangani</p>
                            </div>
                        </div>
                    `;
                }
            }

            function showSuccessToast(message = 'Notifikasi berhasil dihapus') {
                const container = document.getElementById('toast-container');
                if (!container) return;

                const toastId = 'toast-' + Date.now();
                const toastHtml = `
                    <div id="${toastId}" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-check-circle me-2"></i>${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', toastHtml);

                const toastEl = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastEl);
                toast.show();

                // Remove toast element after it's hidden
                toastEl.addEventListener('hidden.bs.toast', () => {
                    toastEl.remove();
                });
            }

            // Auto-refresh every 5 minutes
            setInterval(() => {
                if (document.visibilityState === 'visible') {
                    location.reload();
                }
            }, 5 * 60 * 1000);

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + R to mark all as read
                if ((e.ctrlKey || e.metaKey) && e.key === 'r' && !e.shiftKey) {
                    e.preventDefault();
                    document.querySelector('form[action*="mark-all-read"]')?.submit();
                }

                // Ctrl/Cmd + F to focus filter
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    document.getElementById('statusFilter')?.focus();
                }
            });

            // Handle per-item delete buttons (replaces inline forms)
            document.querySelectorAll('.delete-notification-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.getAttribute('data-action');
                    const id = this.getAttribute('data-id');
                    if (!action) return;

                    if (!confirm('Hapus notifikasi ini? Tindakan ini tidak dapat dibatalkan.')) {
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    fetch(action, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: new URLSearchParams({ _method: 'DELETE' })
                        })
                        .then(resp => {
                            if (resp.ok) return resp.json().catch(() => ({
                                success: true
                            }));
                            return resp.text().then(text => {
                                throw new Error(text || 'Request failed');
                            });
                        })
                        .then(data => {
                            const li = btn.closest('.notification-item');
                            if (li) {
                                updateCountsAfterDelete(li);
                                // Smooth fade out before removal
                                li.style.transition = 'all 0.3s ease';
                                li.style.opacity = '0';
                                li.style.transform = 'translateX(-100%)';
                                setTimeout(() => {
                                    li.remove();
                                    showSuccessToast('Notifikasi berhasil dihapus');
                                }, 300);
                            }
                        })
                        .catch(err => {
                            console.error('Failed to delete notification', err);
                            alert('Gagal menghapus notifikasi. Cek log untuk detail.');
                        });
                });
            });
        });
    </script>

    <style>
        /* Notification styles */
        .notification-item {
            transition: all 0.3s ease;
            padding: 1rem;
            border-radius: 8px;
        }

        .notification-item:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transform: translateX(5px);
        }

        .notification-item.unread {
            background-color: rgba(255, 193, 7, 0.1);
            border-left: 3px solid #ffc107;
        }

        .notification-item.read {
            opacity: 0.8;
        }

        .notification-icon {
            transition: all 0.3s ease;
        }

        .notification-item:hover .notification-icon {
            transform: scale(1.1);
        }

        /* Filter chip */
        .filter-chip {
            transition: all 0.2s ease;
            cursor: default;
        }

        .filter-chip:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .filter-chip a:hover {
            opacity: 0.8;
        }

        /* Card header gradient */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .notification-item {
                padding: 0.75rem;
            }

            .notification-item .d-flex {
                flex-direction: column;
            }

            .notification-item .d-flex>div:last-child {
                margin-top: 0.5rem;
                width: 100%;
            }

            .card-header .d-flex {
                flex-direction: column;
                align-items: stretch !important;
            }

            .card-header .d-flex>div:last-child {
                margin-top: 1rem;
                width: 100%;
            }

            .card-header .d-flex>div:last-child>* {
                margin-bottom: 0.5rem;
                width: 100%;
                justify-content: center;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views\notifications\index.blade.php ENDPATH**/ ?>