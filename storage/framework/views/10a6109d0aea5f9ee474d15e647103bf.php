

<?php $__env->startSection('hideNavbar'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title', 'Daftar - Contract Monitoring'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $manifestPath = public_path('build/manifest.json');
        $useVite = false;
        if (file_exists($manifestPath)) {
            $content = @file_get_contents($manifestPath);
            $manifest = @json_decode($content, true) ?: [];
            if (is_array($manifest)) {
                if (
                    array_key_exists('resources/css/login.css', $manifest) ||
                    array_key_exists('resources/js/login.js', $manifest)
                ) {
                    $useVite = true;
                }
            }
        }
    ?>

    <div class="login-page register-page">
        <main class="login-container register-container" aria-labelledby="registerHeading">
            <section class="login-card auth-card" role="region" aria-label="Register form">
                <header class="brand center">
                    <img src="<?php echo e(asset('images/logo-pelindo-white.png')); ?>" alt="Pelindo Logo" width="120" height="auto"
                        class="brand-logo" />
                    <h1 id="registerHeading">Buat Akun Baru</h1>
                    <p class="muted">Isi data Anda untuk membuat akun</p>
                </header>

                <form method="POST" action="<?php echo e(route('register')); ?>" class="login-form" novalidate>
                    <?php echo csrf_field(); ?>

                    <div class="field">
                        <label for="name">Nama Lengkap</label>
                        <div class="control input-group">
                            <span class="input-icon"><i class="bi bi-person"></i></span>
                            <input id="name" name="name" type="text" required value="<?php echo e(old('name')); ?>"
                                placeholder="Masukkan nama lengkap Anda"
                                class="input with-icon <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        </div>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <div class="control input-group">
                            <span class="input-icon"><i class="bi bi-envelope"></i></span>
                            <input id="email" name="email" type="email" inputmode="email" required
                                value="<?php echo e(old('email')); ?>" placeholder="Masukkan email Anda"
                                class="input with-icon <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" aria-describedby="emailHelp">
                        </div>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="control input-group password-control">
                            <span class="input-icon"><i class="bi bi-lock"></i></span>
                            <input id="password" name="password" type="password" required
                                placeholder="Buat password minimal 8 karakter"
                                class="input with-icon <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" aria-describedby="pwdHelp">

                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="muted">Password harus mengandung huruf besar, kecil, angka, dan simbol</small>
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="control input-group">
                            <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                placeholder="Ulangi password Anda" class="input with-icon">
                        </div>
                    </div>

                    <div class="form-actions">
                        <label class="remember">
                            <input type="checkbox" name="terms" required>
                            <span>Saya menyetujui Syarat & Ketentuan</span>
                        </label>
                    </div>

                    <button type="submit" class="btn primary">Daftar Sekarang</button>

                    <div class="divider"><span>atau</span></div>

                    <div class="login-footer muted">Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Masuk</a>
                    </div>
                </form>
            </section>

            <?php echo $__env->make('auth.partials.login-promo', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </main>
    </div>

    <?php $__env->startPush('styles'); ?>
        <?php if($useVite): ?>
            <?php echo app('Illuminate\Foundation\Vite')(['resources/css/login.css']); ?>
        <?php else: ?>
            <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
        <?php endif; ?>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <?php if($useVite): ?>
            <?php echo app('Illuminate\Foundation\Vite')(['resources/js/login.js']); ?>
        <?php else: ?>
            <script>
                // Simple password toggle + small client-side checks for register
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.password-toggle').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            const input = this.parentElement.querySelector('input');
                            if (!input) return;
                            const icon = this.querySelector('i');
                            if (input.type === 'password') {
                                input.type = 'text';
                                if (icon) {
                                    icon.classList.remove('bi-eye-slash');
                                    icon.classList.add('bi-eye');
                                }
                            } else {
                                input.type = 'password';
                                if (icon) {
                                    icon.classList.remove('bi-eye');
                                    icon.classList.add('bi-eye-slash');
                                }
                            }
                        });
                    });

                    // simple strength preview for password (non-blocking)
                    const pwd = document.getElementById('password');
                    if (pwd) {
                        pwd.addEventListener('input', function() {
                            // reuse logic from login page script if available
                            const evt = new Event('input', {
                                bubbles: true
                            });
                            pwd.dispatchEvent(evt);
                        });
                    }
                });
            </script>
        <?php endif; ?>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DASHBOARD\p-contract\resources\views\auth\register.blade.php ENDPATH**/ ?>