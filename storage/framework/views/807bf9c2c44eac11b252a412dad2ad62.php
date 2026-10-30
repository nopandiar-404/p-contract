<?php $__env->startSection('hideNavbar'); ?>
<?php $__env->stopSection(); ?>
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

    <div class="login-page">
        <main class="login-container" aria-labelledby="loginHeading">

            <section class="login-card auth-card" role="region" aria-label="Login form">
                <header class="brand center">
                    <img src="<?php echo e(asset('images/logo-pelindo-white.png')); ?>" alt="Pelindo Logo" width="120" height="auto"
                        class="brand-logo" />
                    <h1 id="loginHeading">Selamat Datang</h1>
                    <p class="muted">Masuk untuk mengelola kontrak dan memantau status masa tenggang.</p>
                </header>

                <form method="POST" action="<?php echo e(route('login')); ?>" class="login-form" novalidate>
                    <?php echo csrf_field(); ?>

                    <div class="field">
                        <label for="email">Email</label>
                        <div class="control">
                            <input id="email" name="email" type="email" inputmode="email" autocomplete="email" autofocus required
                                value="<?php echo e(old('email')); ?>" placeholder="Masukkan email Anda"
                                class="input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> invalid <?php unset($message);
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
                        <div class="control password-control">
                            <input id="password" name="password" type="password" required autocomplete="current-password"
                                placeholder="Masukkan password Anda" class="input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                aria-describedby="pwdHelp">
                            <button type="button" class="password-toggle" aria-label="Tampilkan/Sembunyikan Password" title="Tampilkan atau sembunyikan password" aria-pressed="false">
                                <span class="icon-eye"></span>
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
                    </div>

                    <div class="form-actions">
                        <label class="remember">
                            <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                            <span>Ingat Saya</span>
                        </label>

                        <?php if(Route::has('password.request')): ?>
                            <a class="link-muted" href="<?php echo e(route('password.request')); ?>">Lupa Password?</a>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn primary">Masuk</button>

                    <div class="divider"><span>atau</span></div>

                    <div class="login-footer muted">Belum punya akun? <a href="<?php echo e(route('register')); ?>">Daftar Sekarang</a>
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
            <script src="<?php echo e(asset('js/login.js')); ?>" defer></script>
        <?php endif; ?>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views\auth\login.blade.php ENDPATH**/ ?>