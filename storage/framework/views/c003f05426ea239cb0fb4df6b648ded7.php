<!-- resources/views/settings/index.blade.php -->


<?php $__env->startSection('title', 'Pengaturan'); ?>
<?php $__env->startSection('subtitle', 'Kelola preferensi dan keamanan akun Anda'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        /* Settings Page Styles */
        .settings-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 20px;
        }

        .settings-sidebar {
            display: flex;
            flex-direction: column;
            gap: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .settings-menu-item {
            padding: 15px 20px;
            border-left: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #6c757d;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid #f0f0f0;
        }

        .settings-menu-item:last-child {
            border-bottom: none;
        }

        .settings-menu-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .settings-menu-item:hover {
            background: #f8f9fa;
            color: #495057;
        }

        .settings-menu-item.active {
            background: rgba(67, 97, 238, 0.1);
            color: #4361ee;
            border-left-color: #4361ee;
        }

        .settings-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .settings-section {
            display: none;
            padding: 30px;
        }

        .settings-section.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .settings-section h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .settings-section h3 i {
            font-size: 24px;
            color: #4361ee;
        }

        .settings-group {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #f0f0f0;
        }

        .settings-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .settings-group h4 {
            font-size: 14px;
            font-weight: 700;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #6c757d;
            font-size: 13px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-check input[type="checkbox"],
        .form-check input[type="radio"] {
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        .form-check label {
            cursor: pointer;
            margin: 0;
            flex: 1;
            font-weight: 500;
        }

        .form-check:hover {
            background: #e9ecef;
        }

        /* Alert styles */
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert i {
            font-size: 18px;
        }

        .alert-success {
            background: white;
            color: #0c8599;
            border-left: 3px solid #4cc9f0;
        }

        .alert-danger {
            background: white;
            color: #5a189a;
            border-left: 3px solid #7209b7;
        }

        .alert-warning {
            background: rgba(247, 37, 133, 0.1);
            color: #a61d4d;
            border-left: 3px solid #f72585;
        }

        /* Button styles */
        .btn-primary,
        .btn-secondary,
        .btn-danger {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            color: white;
        }

        .btn-secondary {
            background: #e9ecef;
            color: #495057;
        }

        .btn-secondary:hover {
            background: #dee2e6;
            color: #495057;
        }

        .btn-danger {
            background: rgba(231, 76, 60, 0.1);
            color: #c0392b;
        }

        .btn-danger:hover {
            background: rgba(231, 76, 60, 0.2);
            color: #c0392b;
        }

        .btn-group-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        /* Profile section */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
        }

        .profile-info h2 {
            margin: 0;
            font-size: 22px;
            color: var(--dark-color);
        }

        .profile-info p {
            margin: 5px 0 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* Danger zone */
        .danger-zone {
            background: rgba(231, 76, 60, 0.05);
            border: 1px solid rgba(231, 76, 60, 0.2);
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .danger-zone h4 {
            color: #c0392b;
            margin-bottom: 10px;
        }

        .danger-zone p {
            margin: 0 0 15px;
            color: #6c757d;
            font-size: 14px;
        }

        /* Responsive design */
        @media (max-width: 992px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }

            .settings-sidebar {
                display: flex;
                flex-direction: row;
                overflow-x: auto;
                position: relative;
                top: 0;
                gap: 0;
            }

            .settings-menu-item {
                border-left: none;
                border-bottom: 3px solid transparent;
                padding: 15px 20px;
                white-space: nowrap;
            }

            .settings-menu-item.active {
                border-left: none;
                border-bottom-color: #4361ee;
                background: transparent;
                color: #4361ee;
            }

            .settings-menu-item:hover {
                background: transparent;
                color: #4361ee;
            }
        }

        @media (max-width: 768px) {
            .settings-sidebar {
                flex-direction: column;
                gap: 0;
                overflow-x: visible;
                position: relative;
            }

            .settings-menu-item {
                border-left: 3px solid transparent;
                border-bottom: 1px solid #f0f0f0;
                white-space: normal;
            }

            .settings-menu-item.active {
                border-left-color: #4361ee;
                border-bottom: 1px solid #f0f0f0;
                background: rgba(67, 97, 238, 0.1);
            }

            .settings-section {
                padding: 20px;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .btn-group-actions {
                flex-direction: column;
            }

            .btn-group-actions .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .settings-section {
                padding: 15px;
            }

            .settings-group {
                margin-bottom: 20px;
                padding-bottom: 20px;
            }

            .profile-avatar {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .profile-info h2 {
                font-size: 18px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Notification preference styles */
        .notification-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 3px solid #4361ee;
        }

        .notification-card .info {
            flex: 1;
        }

        .notification-card .info h5 {
            margin: 0 0 5px;
            font-weight: 600;
            color: #495057;
        }

        .notification-card .info p {
            margin: 0;
            font-size: 13px;
            color: #6c757d;
        }

        .form-switch {
            padding-left: 0;
        }

        .form-switch input[type="checkbox"] {
            cursor: pointer;
        }
    </style>

    <div class="settings-container fade-in">
        <!-- Header dengan info -->
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div><?php echo e(session('success')); ?></div>
            </div>
        <?php endif; ?>

        <div class="settings-grid">
            <!-- Sidebar Menu -->
            <div class="settings-sidebar">
                <a href="#account" class="settings-menu-item active" data-section="account">
                    <i class="fas fa-user"></i>
                    <span>Akun</span>
                </a>
                <a href="#notifications" class="settings-menu-item" data-section="notifications">
                    <i class="fas fa-bell"></i>
                    <span>Notifikasi</span>
                </a>
                <a href="#preferences" class="settings-menu-item" data-section="preferences">
                    <i class="fas fa-sliders-h"></i>
                    <span>Preferensi</span>
                </a>
                <a href="#security" class="settings-menu-item" data-section="security">
                    <i class="fas fa-lock"></i>
                    <span>Keamanan</span>
                </a>
                                <a href="#gmail" class="settings-menu-item" data-section="gmail">
                    <i class="fab fa-google" style="color: #ea4335;"></i>
                    <span>Gmail API</span>
                </a>
                <a href="#about" class="settings-menu-item" data-section="about">
                    <i class="fas fa-info-circle"></i>
                    <span>Tentang</span>
                </a>
            </div>

            <!-- Content Area -->
            <div class="settings-content">
                <!-- Account Settings -->
                <div class="settings-section active" id="account-section">
                    <h3><i class="fas fa-user"></i> Pengaturan Akun</h3>

                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                        </div>
                        <div class="profile-info">
                            <h2><?php echo e(auth()->user()->name); ?></h2>
                            <p><?php echo e(auth()->user()->email); ?></p>
                            <p style="font-size: 12px; margin-top: 8px;">
                                Member sejak <?php echo e(auth()->user()->created_at->format('d M Y')); ?>

                            </p>
                        </div>
                    </div>

                    <div class="btn-group-actions" style="margin-bottom: 20px;">
                        <button type="button" class="btn btn-outline-primary" onclick="activateSection('security')">
                            <i class="fas fa-lock me-2"></i> Ubah Password
                        </button>
                    </div>

                    <!-- Edit Profile Form -->
                    <form action="<?php echo e(route('settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="settings-group">
                            <h4><i class="fas fa-edit"></i> Informasi Pribadi</h4>

                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo e(auth()->user()->name); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo e(auth()->user()->email); ?>" required>
                            </div>
                        </div>

                        <div class="btn-group-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                                <i class="fas fa-times me-2"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Notification Settings -->
                <div class="settings-section" id="notifications-section">
                    <h3><i class="fas fa-bell"></i> Pengaturan Notifikasi</h3>

                    <form action="<?php echo e(route('settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="settings-group">
                            <h4>Preferensi Notifikasi</h4>
                            <p style="color: #6c757d; margin-bottom: 20px;">
                                Pilih saluran notifikasi mana yang ingin Anda terima
                            </p>

                            <div class="notification-card">
                                <div class="info">
                                    <h5>Notifikasi Email</h5>
                                    <p>Terima pemberitahuan penting melalui email</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notifEmail"
                                        name="notifications_email"
                                        <?php echo e(session('notifications_email', true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="notifEmail"></label>
                                </div>
                            </div>

                            <div class="notification-card">
                                <div class="info">
                                    <h5>Notifikasi Dashboard</h5>
                                    <p>Tampilkan notifikasi di dashboard sistem</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notifDash"
                                        name="notifications_dashboard"
                                        <?php echo e(session('notifications_dashboard', true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="notifDash"></label>
                                </div>
                            </div>

                            <div class="notification-card">
                                <div class="info">
                                    <h5>Notifikasi WhatsApp</h5>
                                    <p>Terima reminder melalui WhatsApp Business API</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notifWhatsapp"
                                        name="notifications_whatsapp"
                                        <?php echo e(session('notifications_whatsapp', false) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="notifWhatsapp"></label>
                                </div>
                            </div>
                        </div>

                        <div class="settings-group">
                            <h4>Tipe Notifikasi</h4>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notifExpiring" checked>
                                <label class="form-check-label" for="notifExpiring">
                                    Kontrak yang akan berakhir
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notifGrace" checked>
                                <label class="form-check-label" for="notifGrace">
                                    Kontrak dalam masa tenggang
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notifExpired" checked>
                                <label class="form-check-label" for="notifExpired">
                                    Kontrak yang sudah berakhir
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notifNewContract" checked>
                                <label class="form-check-label" for="notifNewContract">
                                    Kontrak baru ditambahkan
                                </label>
                            </div>
                        </div>

                        <div class="btn-group-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Preferensi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- System Preferences -->
                <div class="settings-section" id="preferences-section">
                    <h3><i class="fas fa-sliders-h"></i> Preferensi Sistem</h3>

                    <form action="<?php echo e(route('settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="settings-group">
                            <h4>Bahasa dan Zona Waktu</h4>

                            <div class="form-group">
                                <label for="language">Bahasa</label>
                                <select class="form-select" id="language" name="language" required>
                                    <option value="id" <?php echo e(session('user_language', 'id') == 'id' ? 'selected' : ''); ?>>
                                        Bahasa Indonesia</option>
                                    <option value="en" <?php echo e(session('user_language', 'id') == 'en' ? 'selected' : ''); ?>>
                                        English</option>
                                </select>
                                <small>Pengaturan ini akan mempengaruhi tampilan interface</small>
                            </div>

                            <div class="form-group">
                                <label for="timezone">Zona Waktu</label>
                                <select class="form-select" id="timezone" name="timezone" required>
                                    <option value="Asia/Jakarta"
                                        <?php echo e(session('user_timezone', 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : ''); ?>>
                                        Asia/Jakarta (UTC+7)</option>
                                    <option value="Asia/Surabaya"
                                        <?php echo e(session('user_timezone', 'Asia/Jakarta') == 'Asia/Surabaya' ? 'selected' : ''); ?>>
                                        Asia/Surabaya (UTC+7)</option>
                                    <option value="Asia/Makassar"
                                        <?php echo e(session('user_timezone', 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : ''); ?>>
                                        Asia/Makassar (UTC+8)</option>
                                    <option value="Asia/Jayapura"
                                        <?php echo e(session('user_timezone', 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : ''); ?>>
                                        Asia/Jayapura (UTC+9)</option>
                                </select>
                                <small>Zona waktu digunakan untuk menampilkan tanggal dan waktu</small>
                            </div>
                        </div>

                        <div class="settings-group">
                            <h4>Tampilan</h4>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="themeDark" name="theme_dark"
                                    <?php echo e(session('theme_dark', false) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="themeDark">
                                    Gunakan tema gelap
                                </label>
                                <small style="display: block;">Aktifkan untuk mode gelap pada antarmuka</small>
                            </div>
                        </div>

                        <div class="btn-group-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Preferensi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Settings -->
                <div class="settings-section" id="security-section">
                    <h3><i class="fas fa-lock"></i> Keamanan</h3>

                    <div class="settings-group">
                        <h4>Ubah Password</h4>
                        <p style="color: #6c757d; margin-bottom: 20px;">
                            Ganti password Anda secara berkala untuk menjaga keamanan akun
                        </p>

                        <form action="<?php echo e(route('settings.changePassword')); ?>" method="POST" id="changePasswordForm">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label for="current_password">Password Saat Ini</label>
                                <div style="position: relative;">
                                    <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="current_password" name="current_password" required>
                                    <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('current_password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger" style="font-size: 13px; margin-top: 5px;">
                                        <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <div style="position: relative;">
                                    <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="password" name="password" placeholder="Minimal 8 karakter dengan kombinasi unik"
                                        required>
                                    <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger" style="font-size: 13px; margin-top: 10px;">
                                        <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <div style="position: relative;">
                                    <input type="password" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="password_confirmation" name="password_confirmation" required>
                                    <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('password_confirmation', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="confirmationMatch" style="margin-top: 5px; font-size: 13px; display: none;"></div>
                                <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger" style="font-size: 13px; margin-top: 5px;">
                                        <i class="fas fa-exclamation-circle"></i> <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="btn-group-actions">
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    <i class="fas fa-save me-2"></i> Ubah Password
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-redo me-2"></i> Bersihkan
                                </button>
                            </div>

                            <!-- Security Info -->
                            <div style="background: rgba(67, 97, 238, 0.1); border-left: 3px solid #4361ee; padding: 12px; border-radius: 6px; margin-top: 20px; font-size: 13px; color: #495057;">
                                <i class="fas fa-info-circle" style="color: #4361ee; margin-right: 8px;"></i>
                                <strong>Tips Keamanan:</strong> Gunakan password yang unik, jangan bagikan password dengan siapapun, dan hindari menggunakan informasi pribadi yang mudah ditebak.
                            </div>
                        </form>
                    </div>

                    <!-- Danger Zone -->
                    <div class="danger-zone">
                        <h4><i class="fas fa-exclamation-triangle me-2"></i>Zona Berbahaya</h4>
                        <p>Aksi berikut akan menghapus data permanen dan tidak dapat dibatalkan</p>
                        <button type="button" class="btn btn-danger" onclick="confirmDeleteAccount()">
                            <i class="fas fa-trash me-2"></i> Hapus Akun Selamanya
                        </button>
                    </div>
                </div>

                <!-- About -->
                <div class="settings-section" id="about-section">
                    <h3><i class="fas fa-info-circle"></i> Tentang Sistem</h3>

                    <div class="settings-group">
                        <h4>Informasi Sistem</h4>
                        <div class="form-check" style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                            <p style="margin: 0;">
                                <strong>Nama Aplikasi:</strong> Contract Monitoring System<br>
                                <strong>Versi:</strong> 1.0.0<br>
                                <strong>Environment:</strong> <?php echo e(app()->environment()); ?><br>
                                <strong>Last Updated:</strong> <?php echo e(now()->format('d M Y H:i')); ?><br>
                            </p>
                        </div>
                    </div>

                    <div class="settings-group">
                        <h4>Link Berguna</h4>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <a href="#" class="btn btn-secondary" style="text-align: left;">
                                <i class="fas fa-book me-2"></i> Dokumentasi
                            </a>
                            <a href="#" class="btn btn-secondary" style="text-align: left;">
                                <i class="fas fa-bug me-2"></i> Laporkan Bug
                            </a>
                            <a href="#" class="btn btn-secondary" style="text-align: left;">
                                <i class="fas fa-envelope me-2"></i> Hubungi Support
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Gmail API & Push Notification Settings -->
                <div class="settings-section" id="gmail-section">
                    <h3><i class="fab fa-google" style="color: #ea4335;"></i> Pengaturan Gmail API & Push Notifications</h3>

                    <!-- Status Overview Card -->
                    <div class="settings-group">
                        <h4>Status Integrasi</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded border <?php echo e(!empty($gmailCreds['refresh_token']) ? 'bg-success-subtle border-success' : 'bg-warning-subtle border-warning'); ?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="fw-bold">Status OAuth 2.0</span>
                                        <?php if(!empty($gmailCreds['refresh_token'])): ?>
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Terhubung</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Belum Terhubung</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted d-block mb-3">
                                        <?php if(!empty($gmailCreds['refresh_token'])): ?>
                                            Akun Gmail siap digunakan untuk mengirim reminder dan menerima balasan.
                                        <?php else: ?>
                                            Lengkapi Client ID & Secret lalu klik tombol "Hubungkan Akun Google".
                                        <?php endif; ?>
                                    </small>
                                    <?php if(empty($gmailCreds['refresh_token'])): ?>
                                        <a href="<?php echo e(route('gmail.oauth')); ?>" class="btn btn-sm btn-danger">
                                            <i class="fab fa-google me-1"></i> Hubungkan Akun Google
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('gmail.oauth')); ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-sync-alt me-1"></i> Otorisasi Ulang Google
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded border <?php echo e(!empty($gmailWatch['active']) ? 'bg-info-subtle border-info' : 'bg-light border-secondary-subtle'); ?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="fw-bold">Gmail Watch (Pub/Sub)</span>
                                        <?php if(!empty($gmailWatch['active'])): ?>
                                            <span class="badge bg-info text-dark"><i class="fas fa-broadcast-tower me-1"></i> Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="fas fa-pause-circle me-1"></i> Tidak Aktif</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted d-block mb-3">
                                        <?php if(!empty($gmailWatch['active'])): ?>
                                            Kedaluwarsa: <?php echo e($gmailWatch['expiration'] ? date('d M Y H:i', (int)($gmailWatch['expiration'] / 1000)) : '-'); ?>

                                        <?php else: ?>
                                            Memerlukan Google Cloud Pub/Sub Topic untuk menerima push notifikasi otomatis.
                                        <?php endif; ?>
                                    </small>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-primary" id="btnTriggerWatch">
                                            <i class="fas fa-play me-1"></i> Aktifkan Watch
                                        </button>
                                        <?php if(!empty($gmailWatch['active'])): ?>
                                            <button type="button" class="btn btn-outline-danger" id="btnStopWatch">
                                                <i class="fas fa-stop me-1"></i> Hentikan
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Webhook URL Card -->
                    <div class="settings-group">
                        <h4>URL Webhook Cloud Pub/Sub</h4>
                        <p class="text-muted small mb-2">Masukkan URL ini di Google Cloud Console pada push subscription Pub/Sub Anda:</p>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light font-monospace" id="gmailWebhookUrl" value="<?php echo e(url('/api/gmail/webhook')); ?>" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('gmailWebhookUrl').value); alert('URL Webhook berhasil disalin!');">
                                <i class="fas fa-copy me-1"></i> Salin URL
                            </button>
                        </div>
                    </div>

                    <!-- Form Konfigurasi -->
                    <div class="settings-group">
                        <h4>Konfigurasi Kredensial (.env)</h4>
                        <form action="<?php echo e(route('settings.gmail.update')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="form-group mb-3">
                                <label for="gmail_client_id">Google Client ID <span class="text-danger">*</span></label>
                                <input type="text" name="gmail_client_id" id="gmail_client_id" class="form-control font-monospace" value="<?php echo e(old('gmail_client_id', $gmailCreds['client_id'] ?? '')); ?>" placeholder="xxxxx-xxxxxxx.apps.googleusercontent.com">
                                <small class="text-muted">Didapatkan dari Google Cloud Console &gt; Credentials &gt; OAuth 2.0 Client ID (Web Application).</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="gmail_client_secret">Google Client Secret <span class="text-danger">*</span></label>
                                <input type="password" name="gmail_client_secret" id="gmail_client_secret" class="form-control font-monospace" value="<?php echo e(old('gmail_client_secret', $gmailCreds['client_secret'] ?? '')); ?>" placeholder="GOCSPX-xxxxxxxxxxxxxxxx">
                                <small class="text-muted">Rahasia klien Google OAuth.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="gmail_refresh_token">Refresh Token</label>
                                <div class="input-group">
                                    <input type="text" name="gmail_refresh_token" id="gmail_refresh_token" class="form-control font-monospace" value="<?php echo e(old('gmail_refresh_token', $gmailCreds['refresh_token'] ?? '')); ?>" placeholder="Otomatis terisi setelah login Google">
                                    <?php if(empty($gmailCreds['refresh_token'])): ?>
                                        <a href="<?php echo e(route('gmail.oauth')); ?>" class="btn btn-outline-danger">
                                            <i class="fab fa-google me-1"></i> Otorisasi Sekarang
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">Refresh token disimpan otomatis saat Anda mengklik tombol "Hubungkan Akun Google".</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="gmail_pubsub_topic">Cloud Pub/Sub Topic Name</label>
                                <input type="text" name="gmail_pubsub_topic" id="gmail_pubsub_topic" class="form-control font-monospace" value="<?php echo e(old('gmail_pubsub_topic', $gmailCreds['topic_name'] ?? 'projects/myproject/topics/mytopic')); ?>" placeholder="projects/YOUR_PROJECT_ID/topics/YOUR_TOPIC">
                                <small class="text-muted">Topik Google Cloud Pub/Sub untuk fitur push notifications / webhook.</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="gmail_from_email">Email Pengirim Default</label>
                                        <input type="email" name="gmail_from_email" id="gmail_from_email" class="form-control" value="<?php echo e(old('gmail_from_email', $gmailCreds['from_email'] ?? 'nopandi4r@gmail.com')); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="gmail_from_name">Nama Pengirim</label>
                                        <input type="text" name="gmail_from_name" id="gmail_from_name" class="form-control" value="<?php echo e(old('gmail_from_name', $gmailCreds['from_name'] ?? 'Pelindo E-Contract')); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Konfigurasi
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Petunjuk Pengaturan Google Cloud Console -->
                    <div class="settings-group">
                        <h4>Panduan Pengaturan Google Cloud Console</h4>
                        <ol class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
                            <li>Buka <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a> dan buat atau pilih project.</li>
                            <li>Aktifkan <strong>Gmail API</strong> dan <strong>Cloud Pub/Sub API</strong> di menu <em>APIs & Services &gt; Library</em>.</li>
                            <li>Buka menu <em>OAuth consent screen</em>, pilih User Type, dan tambahkan scope <code>https://www.googleapis.com/auth/gmail.send</code> serta <code>https://www.googleapis.com/auth/gmail.readonly</code>.</li>
                            <li>Buka menu <em>Credentials &gt; Create Credentials &gt; OAuth client ID</em>, pilih tipe <strong>Web application</strong>.</li>
                            <li>Di bagian <strong>Authorized redirect URIs</strong>, tambahkan: <code><?php echo e(route('gmail.callback')); ?></code></li>
                            <li>Salin <strong>Client ID</strong> dan <strong>Client Secret</strong> ke formulir di atas, lalu simpan.</li>
                            <li>Klik tombol <strong>Hubungkan Akun Google</strong> untuk menyelesaikan otorisasi OAuth.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Password strength and security styles */
        .btn-toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            font-size: 14px;
            padding: 5px;
            transition: all 0.3s ease;
        }

        .btn-toggle-password:hover {
            color: #4361ee;
        }

        /* Add padding to password fields with toggle button */
        #current_password,
        #password,
        #password_confirmation {
            padding-right: 38px;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .text-danger {
            color: #dc3545;
        }

        #strengthText {
            color: #6c757d;
        }

        #strengthText.weak {
            color: #dc3545;
        }

        #strengthText.fair {
            color: #f39c12;
        }

        #strengthText.good {
            color: #f39c12;
        }

        #strengthText.strong {
            color: #27ae60;
        }
    </style>

    <script>
        // Auto-activate section from URL hash (e.g. #gmail)
        const initialHash = window.location.hash.replace('#', '');
        if (initialHash) {
            activateSection(initialHash);
        }

        // Watch button handlers
        document.getElementById('btnTriggerWatch')?.addEventListener('click', async function () {
            if (!confirm('Aktifkan Gmail Watch ke Cloud Pub/Sub?')) return;
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengaktifkan...';
            try {
                const res = await fetch('<?php echo e(route('gmail.watch')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                if (data.success) {
                    alert('Gmail watch berhasil diaktifkan!\nExpiration: ' + (data.expiration_formatted || '-'));
                    window.location.reload();
                } else {
                    alert('Gagal: ' + (data.message || 'Terjadi kesalahan.'));
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-play me-1"></i> Aktifkan Watch';
                }
            } catch (e) {
                alert('Gagal menghubungi server.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-play me-1"></i> Aktifkan Watch';
            }
        });

        document.getElementById('btnStopWatch')?.addEventListener('click', async function () {
            if (!confirm('Hentikan Gmail Watch?')) return;
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menghentikan...';
            try {
                const res = await fetch('<?php echo e(route('gmail.stop')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                alert(data.message || 'Selesai');
                window.location.reload();
            } catch (e) {
                alert('Gagal menghubungi server.');
                btn.disabled = false;
            }
        });

        // Menu navigation
        document.querySelectorAll('.settings-menu-item').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();

                activateSection(this.getAttribute('data-section'));
            });
        });

        function activateSection(section) {
            // Remove active class from all items and sections
            document.querySelectorAll('.settings-menu-item').forEach(i => i.classList.remove('active'));
            document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));

            // Activate selected menu item and section
            const targetMenu = document.querySelector(`.settings-menu-item[data-section="${section}"]`);
            if (targetMenu) {
                targetMenu.classList.add('active');
            }
            const targetSection = document.getElementById(section + '-section');
            if (targetSection) {
                targetSection.classList.add('active');
                targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Toggle password visibility
        function togglePasswordVisibility(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.add('fa-eye');
                icon.classList.remove('fa-eye-slash');
            }
        }

        // Check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchIndicator = document.getElementById('confirmationMatch');

            if (confirmation.length > 0) {
                matchIndicator.style.display = 'block';
                if (password === confirmation) {
                    matchIndicator.innerHTML = '<i class="fas fa-check-circle" style="color: #27ae60; margin-right: 5px;"></i><span style="color: #27ae60;">Password cocok</span>';
                } else {
                    matchIndicator.innerHTML = '<i class="fas fa-times-circle" style="color: #dc3545; margin-right: 5px;"></i><span style="color: #dc3545;">Password tidak cocok</span>';
                }
            } else {
                matchIndicator.style.display = 'none';
            }
        }

        // Update submit button state
        function updateSubmitButton() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const currentPassword = document.getElementById('current_password').value;
            const submitBtn = document.getElementById('submitBtn');

            // Check all requirements
            const requirements = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[@$!%*?&]/.test(password),
            };

            const allRequirementsMet = Object.values(requirements).every(Boolean);
            const passwordMatch = password === confirmation && confirmation.length > 0;
            const currentPasswordProvided = currentPassword.length > 0;

            if (allRequirementsMet && passwordMatch && currentPasswordProvided) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        function resetPasswordFeedback() {
            const passwordInput = document.getElementById('password');
            const strengthIndicator = document.getElementById('strengthIndicator');
            const strengthText = document.getElementById('strengthText');
            const matchIndicator = document.getElementById('confirmationMatch');

            if (matchIndicator) {
                matchIndicator.style.display = 'none';
                matchIndicator.innerHTML = '';
            }

            updateSubmitButton();
        }

        // Add event listeners for password validation
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const currentInput = document.getElementById('current_password');

            if (passwordInput) {
                passwordInput.addEventListener('input', updateSubmitButton);
            }
            if (confirmInput) {
                confirmInput.addEventListener('input', checkPasswordMatch);
                confirmInput.addEventListener('input', updateSubmitButton);
            }
            if (currentInput) {
                currentInput.addEventListener('input', updateSubmitButton);
            }

            const form = document.getElementById('changePasswordForm');
            if (form) {
                form.addEventListener('reset', function () {
                    setTimeout(resetPasswordFeedback, 0);
                });
            }
        });

        // Confirm delete account
        function confirmDeleteAccount() {
            if (confirm(
                'Apakah Anda yakin ingin menghapus akun Anda?\n\nTindakan ini tidak dapat dibatalkan dan semua data akan dihapus secara permanen.'
            )) {
                if (confirm('Ketik "HAPUS" untuk mengkonfirmasi penghapusan akun.')) {
                    // Implement actual delete logic here
                    alert('Fitur penghapusan akun akan segera diimplementasikan');
                }
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DASHBOARD\p-contract\resources\views/settings/index.blade.php ENDPATH**/ ?>