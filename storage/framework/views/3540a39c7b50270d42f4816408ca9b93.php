

<?php $__env->startSection('title', 'Jenis Kontrak'); ?>
<?php $__env->startSection('subtitle', 'Kategori kontrak yang tersedia'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"><i class="fas fa-layer-group text-primary me-2"></i>Jenis Kontrak</h5>
                <small class="text-muted">Jenis yang dapat dipilih pada setiap kontrak.</small>
            </div>
            <span class="badge bg-primary"><?php echo e(count($contractTypes)); ?> jenis</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px">No.</th>
                        <th>Jenis Kontrak</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $contractTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><span class="badge bg-primary-subtle text-primary border"><?php echo e($type['name']); ?></span></td>
                            <td><?php echo e($type['description']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DASHBOARD\p-contract\resources\views/contract-types/index.blade.php ENDPATH**/ ?>