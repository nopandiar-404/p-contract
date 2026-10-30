<?php $__env->startSection('hideNavbar'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title', 'Reset Password - Contract Monitoring'); ?>

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
        <main class="register-container" aria-labelledby="resetHeading">
            <section class="login-card auth-card" role="region" aria-label="Reset password form">
                <header class="brand center">
                    <img src="<?php echo e(asset('images/pelindo-contract.svg')); ?>" alt="Pelindo Logo" width="120"
                        height="auto"
                        class="brand-logo" />
                    <h1 id="resetHeading">Reset Password</h1>
                    <p class="muted">Masukkan email serta password baru untuk memvalidasi akun Anda.</p>
                </header>

                <form method="POST" action="<?php echo e(route('password.direct-update')); ?>" class="login-form" novalidate>
                    <?php echo csrf_field(); ?>

                    <div class="field">
                        <label for="email">Email</label>
                        <div class="control input-group">
                            <span class="input-icon"><i class="bi bi-envelope"></i></span>
                            <input id="email" type="email" name="email" required
                                value="<?php echo e(old('email')); ?>" placeholder="Masukkan email Anda"
                                class="input with-icon <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" autocomplete="email"
                                autofocus>
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
                        <label for="password">Password Baru</label>
                        <div class="control input-group password-control">
                            <span class="input-icon"><i class="bi bi-lock"></i></span>
                            <input id="password" type="password" name="password" required
                                placeholder="Masukkan password baru minimal 8 karakter"
                                class="input with-icon <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                autocomplete="new-password">
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="bi bi-eye-slash"></i>
                            </button>
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
                        <label for="password-confirm">Konfirmasi Password Baru</label>
                        <div class="control input-group password-control">
                            <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                            <input id="password-confirm" type="password" name="password_confirmation" required
                                placeholder="Ulangi password baru Anda"
                                class="input with-icon <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                autocomplete="new-password">
                            <button type="button" class="password-toggle" aria-label="Toggle confirmation password visibility">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['password_confirmation'];
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

                    <div class="form-actions">
                        <label class="remember">
                            <input type="checkbox" id="showPasswordCheckbox">
                            <span>Tampilkan password</span>
                        </label>
                    </div>

                    <button type="submit" class="btn primary">Reset Password</button>

                    <div class="divider"><span>atau</span></div>

                    <div class="login-footer muted">Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Masuk</a>
                    </div>
                </form>
            </section>
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
                document.addEventListener('DOMContentLoaded', function() {
                    const password = document.getElementById('password');
                    const passwordConfirm = document.getElementById('password-confirm');
                    const showCheckbox = document.getElementById('showPasswordCheckbox');
                    const toggleButtons = document.querySelectorAll('.password-toggle');

                    function toggleVisibility(input) {
                        if (!input) return;
                        input.type = input.type === 'password' ? 'text' : 'password';
                    }

                    toggleButtons.forEach(function(button) {
                        button.addEventListener('click', function() {
                            const input = this.parentElement.querySelector('input');
                            toggleVisibility(input);
                            const icon = this.querySelector('i');
                            if (icon) {
                                icon.classList.toggle('bi-eye');
                                icon.classList.toggle('bi-eye-slash');
                            }
                        });
                    });

                    if (showCheckbox) {
                        showCheckbox.addEventListener('change', function() {
                            const type = this.checked ? 'text' : 'password';
                            if (password) password.type = type;
                            if (passwordConfirm) passwordConfirm.type = type;
                            toggleButtons.forEach(function(button) {
                                const icon = button.querySelector('i');
                                if (icon) {
                                    icon.classList.toggle('bi-eye', this.checked);
                                    icon.classList.toggle('bi-eye-slash', !this.checked);
                                }
                            }, this);
                        });
                    }

                    let mismatchEl = document.getElementById('passwordMismatch');
                    if (!mismatchEl && passwordConfirm) {
                        mismatchEl = document.createElement('div');
                        mismatchEl.id = 'passwordMismatch';
                        mismatchEl.className = 'error';
                        mismatchEl.style.display = 'none';
                        mismatchEl.textContent = 'Password tidak cocok.';
                        passwordConfirm.parentNode.appendChild(mismatchEl);
                    }

                    function validateMatch() {
                        if (!password || !passwordConfirm) return true;
                        if (password.value !== passwordConfirm.value) {
                            mismatchEl.style.display = 'block';
                            return false;
                        }
                        mismatchEl.style.display = 'none';
                        return true;
                    }

                    [password, passwordConfirm].forEach(function(el) {
                        if (!el) return;
                        el.addEventListener('input', validateMatch);
                    });

                    const form = document.querySelector('form');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            if (!validateMatch()) {
                                e.preventDefault();
                                passwordConfirm.focus();
                            }
                        });
                    }
                });
            </script>
        <?php endif; ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views\auth\passwords\email.blade.php ENDPATH**/ ?>