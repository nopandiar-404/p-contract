

<?php $__env->startSection('title', 'Detail Jenis COA'); ?>
<?php $__env->startSection('subtitle', 'Informasi lengkap Jenis COA'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($coa->name); ?> <small class="text-muted">(<?php echo e($coa->code); ?>)</small></h5>
                    <p class="card-text"><?php echo e($coa->description ?? '-'); ?></p>

                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <a href="<?php echo e(route('coa.edit', $coa)); ?>" class="btn btn-outline-primary">Edit</a>
                        <a href="<?php echo e(route('coa.index')); ?>" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DASHBOARD\p-contract\resources\views\coa\show.blade.php ENDPATH**/ ?>