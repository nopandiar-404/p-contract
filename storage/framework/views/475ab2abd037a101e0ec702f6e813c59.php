<?php $__env->startSection('title', 'Profil Saya'); ?>
<?php $__env->startSection('subtitle', 'Kelola informasi akun dan keamanan Anda dengan tampilan modern yang responsif'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4">
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm overflow-hidden h-100">
                <div class="bg-primary bg-gradient p-4 text-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center"
                            style="width:72px; height:72px; font-size:28px;">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </div>
                        <div>
                            <h2 class="mb-1"><?php echo e($user->name); ?></h2>
                            <p class="mb-1"><?php echo e($user->email); ?></p>
                            <p class="mb-0 text-white-75 mb-2">
                                <?php echo e($user->position ?? 'Jabatan belum diisi'); ?>

                                <?php if($user->division): ?>
                                    | <?php echo e($user->division); ?>

                                <?php endif; ?>
                            </p>
                              
                            <span class="badge bg-white bg-opacity-25 text-white">Bergabung sejak <?php echo e($user->created_at->format('d M Y')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-4">
                            <div class="profile-stat-card p-3 text-center">
                                <small class="text-muted d-block mb-2">Kontrak</small>
                                <h3 class="mb-0"><?php echo e($totalContracts ?? 0); ?></h3>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="profile-stat-card p-3 text-center">
                                <small class="text-muted d-block mb-2">Aktif</small>
                                <h3 class="mb-0"><?php echo e($activeContracts ?? 0); ?></h3>
                                <small class="text-muted">Berjalan</small>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="profile-stat-card p-3 text-center">
                                <small class="text-muted d-block mb-2">Masa Tenggang</small>
                                <h3 class="mb-0"><?php echo e($gracePeriodContracts ?? 0); ?></h3>
                                <small class="text-muted">Perlu tindakan</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h5 class="mb-1">Ringkasan Akun</h5>
                                <p class="text-muted mb-0">Lihat performa singkat dan status akun Anda.</p>
                            </div>
                            <span class="badge bg-info bg-opacity-10 text-info">Stabil</span>
                        </div>

                        <div class="progress" style="height: 12px; border-radius: 8px;">
                            <div class="progress-bar bg-primary"
                                style="width: <?php echo e(($totalContracts > 0 ? round(($activeContracts / $totalContracts) * 100) : 0)); ?>%;"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 text-muted" style="font-size: 0.92rem;">
                            <span>Aktif saat ini</span>
                            <span><?php echo e($activeContracts ?? 0); ?> / <?php echo e($totalContracts ?? 0); ?></span>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm mt-4 security-card-responsive">
                <div class="card-header bg-light border-0 p-3 p-md-4">
                    <h4 class="mb-0"><i class="fas fa-shield-alt text-primary me-2"></i> Keamanan Akun</h4>
                </div>
                <div class="card-body p-3 p-md-4">
                    <p class="text-muted mb-4">Kelola keamanan akun dengan cepat dan aman. Ubah password kapan saja jika Anda merasa perlu.</p>
                    
                    <div class="security-items-container">
                        <div class="security-item p-3 p-md-4 mb-3 rounded-3 bg-light">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-md-items-center gap-2 gap-md-3">
                                <div class="flex-grow-1 min-width-0">
                                    <h6 class="mb-1 mb-md-2">
                                        <i class="fas fa-lock text-success me-2"></i> Password terlindungi
                                    </h6>
                                    <small class="text-muted d-block">Gunakan password dengan kombinasi kuat.</small>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success flex-shrink-0 mt-2 mt-md-0">Aman</span>
                            </div>
                        </div>
                        
                        <div class="security-item p-3 p-md-4 rounded-3 bg-light">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-md-items-center gap-2 gap-md-3">
                                <div class="flex-grow-1 min-width-0">
                                    <h6 class="mb-1 mb-md-2">
                                        <i class="fas fa-bell text-info me-2"></i> Notifikasi aktif
                                    </h6>
                                    <small class="text-muted d-block">Dapatkan update penting dari sistem.</small>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary flex-shrink-0 mt-2 mt-md-0">Tersedia</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid d-md-flex flex-md-wrap gap-2 mt-4">
                        <a href="<?php echo e(route('settings.index')); ?>#security-section" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-key me-2"></i> Ubah Password
                        </a>
                        <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-cog me-2"></i> Pengaturan Lengkap
                        </a>
                    </div>
                </div>
            </div>
                </div>
            </div>

            
        </div>

        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h3 class="mb-2"><i class="fas fa-user-circle text-primary me-2"></i> Profil Saya</h3>
                            <p class="text-muted mb-0">Perbarui informasi akun dan preferensi bahasa untuk pengalaman yang lebih personal.</p>
                        </div>
                        <div class="badge bg-info bg-opacity-10 text-info py-2 px-3 rounded-pill">
                            <?php echo e($user->role ?? 'Pengguna'); ?>

                        </div>
                    </div>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo e(old('name', $user->name)); ?>" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo e(old('email', $user->email)); ?>" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="position" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="position" name="position"
                                    value="<?php echo e(old('position', $user->position)); ?>" placeholder="Contoh: Manajer Kontrak">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="division" class="form-label">Divisi</label>
                                <input type="text" class="form-control" id="division" name="division"
                                    value="<?php echo e(old('division', $user->division)); ?>" placeholder="Contoh: Pengelolaan Kontrak">
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-12 col-md-6">
                                <label for="language" class="form-label">Bahasa</label>
                                <select class="form-select" id="language" name="language" required>
                                    <option value="id" <?php echo e(session('user_language', 'id') == 'id' ? 'selected' : ''); ?>>Bahasa Indonesia</option>
                                    <option value="en" <?php echo e(session('user_language', 'id') == 'en' ? 'selected' : ''); ?>>English</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="timezone" class="form-label">Zona Waktu</label>
                                <select class="form-select" id="timezone" name="timezone" required>
                                    <option value="Asia/Jakarta" <?php echo e(session('user_timezone', 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : ''); ?>>Asia/Jakarta (UTC+7)</option>
                                    <option value="Asia/Surabaya" <?php echo e(session('user_timezone', 'Asia/Jakarta') == 'Asia/Surabaya' ? 'selected' : ''); ?>>Asia/Surabaya (UTC+7)</option>
                                    <option value="Asia/Makassar" <?php echo e(session('user_timezone', 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : ''); ?>>Asia/Makassar (UTC+8)</option>
                                    <option value="Asia/Jayapura" <?php echo e(session('user_timezone', 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : ''); ?>>Asia/Jayapura (UTC+9)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" id="themeDark" name="theme_dark"
                                <?php echo e(session('theme_dark', false) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="themeDark">Gunakan tema gelap</label>
                        </div>

                        <input type="hidden" name="notifications_email" value="<?php echo e(session('notifications_email', true) ? 1 : 0); ?>">
                        <input type="hidden" name="notifications_dashboard" value="<?php echo e(session('notifications_dashboard', true) ? 1 : 0); ?>">
                        <input type="hidden" name="notifications_whatsapp" value="<?php echo e(session('notifications_whatsapp', false) ? 1 : 0); ?>">

                        <div class="d-flex flex-wrap gap-2 align-items-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                                <i class="fas fa-redo me-2"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ==========================================
           CSS Variables for Consistency
           ========================================== */
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --border-color: rgba(0, 0, 0, 0.08);
            --light-bg: #f8f9fa;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ==========================================
           Profile Statistics Cards
           ========================================== */
        .profile-stat-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 16px;
            min-height: 112px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .profile-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
            opacity: 0;
            transition: var(--transition);
        }

        .profile-stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 24px rgba(13, 110, 253, 0.15);
            border-color: var(--primary-color);
        }

        .profile-stat-card:hover::before {
            opacity: 1;
        }

        .profile-stat-card h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0;
            color: var(--primary-color);
            line-height: 1;
        }

        .profile-stat-card small {
            display: block;
            font-weight: 500;
            color: #6c757d;
        }

        /* ==========================================
           Security Card Container
           ========================================== */
        .security-card-responsive {
            border-radius: 18px !important;
            border: none !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06) !important;
            transition: var(--transition);
            overflow: hidden;
            position: relative;
        }

        .security-card-responsive::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color), var(--info-color));
            opacity: 0;
            transition: var(--transition);
        }

        .security-card-responsive:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1) !important;
        }

        .security-card-responsive:hover::before {
            opacity: 1;
        }

        /* ==========================================
           Security Card Header
           ========================================== */
        .security-card-responsive .card-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
            border-bottom: 2px solid var(--light-bg) !important;
            padding: 1.75rem 2rem !important;
            border-radius: 18px 18px 0 0 !important;
        }

        .security-card-responsive .card-header h4 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .security-card-responsive .card-header i {
            font-size: 1.4rem;
            animation: fadeInScale 0.5s ease-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ==========================================
           Security Card Body
           ========================================== */
        .security-card-responsive .card-body {
            padding: 2rem !important;
            background: #ffffff;
        }

        .security-card-responsive .card-body > p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        /* ==========================================
           Security Items Container & Items
           ========================================== */
        .security-items-container {
            margin: 1.5rem -2rem 2rem -2rem;
            padding: 0;
        }

        .security-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid var(--border-color);
            border-left: 4px solid var(--primary-color);
            border-radius: 14px;
            transition: var(--transition);
            padding: 1.5rem 1.75rem !important;
            margin: 0 2rem 1rem 2rem !important;
            position: relative;
            overflow: hidden;
        }

        .security-item::before {
            content: '';
            position: absolute;
            top: -100%;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.02) 0%, rgba(25, 135, 84, 0.02) 100%);
            transition: var(--transition);
            z-index: -1;
        }

        .security-item:hover {
            transform: translateX(6px);
            border-color: var(--primary-color);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.1);
            background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
        }

        .security-item:hover::before {
            top: 0;
        }

        /* Security Item Text Content */
        .security-item h6 {
            font-size: 1rem;
            font-weight: 700;
            color: #212529;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .security-item h6 i {
            font-size: 1.1rem;
            min-width: 20px;
            transition: var(--transition);
        }

        .security-item:hover h6 i {
            transform: scale(1.15);
        }

        .security-item small {
            font-size: 0.85rem;
            color: #6c757d;
            word-break: break-word;
            display: block;
            line-height: 1.5;
        }

        /* ==========================================
           Security Item Badge
           ========================================== */
        .security-item .badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.6rem 1rem !important;
            border-radius: 8px;
            white-space: nowrap;
            flex-shrink: 0;
            transition: var(--transition);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .security-item:hover .badge {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* ==========================================
           Button Group
           ========================================== */
        .security-card-responsive .d-grid {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            gap: 0.75rem;
        }

        .security-card-responsive .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            transition: var(--transition);
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
        }

        .security-card-responsive .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            background: transparent;
        }

        .security-card-responsive .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--primary-color), #0b5ed7);
            color: white;
            border-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .security-card-responsive .btn-outline-secondary {
            color: #6c757d;
            border: 2px solid #6c757d;
            background: transparent;
        }

        .security-card-responsive .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            border-color: #6c757d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .security-card-responsive .btn i {
            transition: var(--transition);
        }

        .security-card-responsive .btn:hover i {
            transform: translateX(2px);
        }

        /* ==========================================
           Utility Classes
           ========================================== */
        .min-width-0 {
            min-width: 0;
        }

        .flex-md-wrap {
            flex-wrap: wrap;
        }

        .flex-shrink-0 {
            flex-shrink: 0;
        }

        /* ==========================================
           Mobile Responsive (< 576px)
           ========================================== */
        @media (max-width: 575.98px) {
            .security-card-responsive .card-header {
                padding: 1.25rem 1.5rem !important;
            }

            .security-card-responsive .card-header h4 {
                font-size: 1.15rem;
            }

            .security-card-responsive .card-body {
                padding: 1.25rem !important;
            }

            .security-card-responsive .card-body > p {
                font-size: 0.9rem;
                margin-bottom: 1.25rem;
            }

            .security-items-container {
                margin: 1rem -1.25rem 1.5rem -1.25rem;
            }

            .security-item {
                padding: 1.25rem !important;
                margin: 0 1.25rem 0.75rem 1.25rem !important;
                border-left-width: 3px;
            }

            .security-item h6 {
                font-size: 0.95rem;
                margin-bottom: 0.4rem;
            }

            .security-item h6 i {
                font-size: 1rem;
            }

            .security-item small {
                font-size: 0.8rem;
            }

            .security-item .badge {
                font-size: 0.75rem;
                padding: 0.4rem 0.7rem !important;
                margin-top: 0.5rem;
            }

            .security-card-responsive .d-grid {
                margin-top: 1.5rem;
                padding-top: 1rem;
                gap: 0.6rem;
                display: grid;
                grid-template-columns: 1fr;
            }

            .security-card-responsive .btn {
                padding: 0.65rem 1rem;
                font-size: 0.9rem;
                width: 100%;
            }

            .security-item:hover {
                transform: translateX(4px);
            }
        }

        /* ==========================================
           Tablet Responsive (576px - 991px)
           ========================================== */
        @media (min-width: 576px) and (max-width: 991.98px) {
            .security-card-responsive .card-header {
                padding: 1.5rem 1.75rem !important;
            }

            .security-card-responsive .card-header h4 {
                font-size: 1.25rem;
            }

            .security-card-responsive .card-body {
                padding: 1.75rem !important;
            }

            .security-items-container {
                margin: 1.25rem -1.75rem 1.75rem -1.75rem;
            }

            .security-item {
                padding: 1.35rem 1.5rem !important;
                margin: 0 1.75rem 0.85rem 1.75rem !important;
            }

            .security-card-responsive .d-grid {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .security-card-responsive .btn {
                flex: 1 1 auto;
                min-width: 120px;
            }

            .security-item:hover {
                transform: translateX(5px);
            }
        }

        /* ==========================================
           Desktop Responsive (≥ 992px)
           ========================================== */
        @media (min-width: 992px) {
            .security-card-responsive .card-header {
                padding: 1.75rem 2rem !important;
            }

            .security-card-responsive .card-header h4 {
                font-size: 1.35rem;
            }

            .security-card-responsive .card-body {
                padding: 2rem !important;
            }

            .security-items-container {
                margin: 1.5rem -2rem 2rem -2rem;
            }

            .security-item {
                padding: 1.5rem 1.75rem !important;
                margin: 0 2rem 1rem 2rem !important;
            }

            .security-item:last-child {
                margin-bottom: 2rem !important;
            }

            .security-card-responsive .d-grid {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .security-card-responsive .btn {
                flex: 1 1 auto;
                min-width: 140px;
            }

            .security-item:hover {
                transform: translateX(6px);
            }

            .d-md-flex {
                display: flex;
            }

            .align-md-items-center {
                align-items: center;
            }
        }

        /* ==========================================
           Flexbox Utilities
           ========================================== */
        .d-flex {
            display: flex;
        }

        .flex-column {
            flex-direction: column;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }

        /* Smooth animations for all transitions */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views\profile\index.blade.php ENDPATH**/ ?>