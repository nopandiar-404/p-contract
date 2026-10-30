<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Pelindo E-Contract')); ?></title>

    <!-- Favicon Pelindo -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/icon-pelindo.png')); ?>">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body data-success-message="<?php echo e(session('success')); ?>">
    <div id="app">
        <?php if (! empty(trim($__env->yieldContent('hideNavbar')))): ?>
            
        <?php else: ?>
            
            
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="#navbarSupportedContent" aria-expanded="false"
                aria-label="<?php echo e(__('Toggle navigation')); ?>">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                
            </div>
    </div>
    
    <?php endif; ?>

    <main class="py-4">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    </div>
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.body.dataset.successMessage;
            if (!successMessage) return;

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: successMessage,
                timer: 2500,
                showConfirmButton: false
            });
        });
    </script>
</body>

</html>
<?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views\layouts\app.blade.php ENDPATH**/ ?>