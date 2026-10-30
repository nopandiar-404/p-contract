<?php $__env->startSection('title', 'Jenis COA'); ?>
<?php $__env->startSection('subtitle', 'Daftar Jenis Chart of Accounts'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="data-table mb-4">
                <div class="table-header d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i> Data Jenis COA</h5>
                        <small class="text-muted">Daftar Jenis Chart of Accounts</small>
                    </div>
                    <div class="d-flex gap-2">
                        <form class="d-flex" method="GET" action="<?php echo e(route('coa.index')); ?>">
                            <input name="q" value="<?php echo e(old('q', $q ?? '')); ?>" class="form-control form-control-sm"
                                type="search" placeholder="Cari kode atau nama..." aria-label="Search">
                            <button class="btn btn-sm btn-outline-secondary ms-2" type="submit">Search</button>
                            <?php if(!empty($q)): ?>
                                <a href="<?php echo e(route('coa.index')); ?>" class="btn btn-sm btn-outline-light ms-2">Bersihkan</a>
                            <?php endif; ?>
                        </form>
                        <a href="<?php echo e(route('coa.create')); ?>" class="btn btn-primary btn-sm">Tambah Jenis COA</a>
                    </div>
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <div class="table-responsive desktop-only p-3">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th style="width:220px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($coa->code); ?></td>
                                    <td><?php echo e($coa->name); ?></td>
                                    <td><?php echo e(Str::limit($coa->description, 60)); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('coa.show', $coa)); ?>"
                                            class="btn btn-sm btn-outline-secondary me-1">Lihat</a>
                                        <a href="<?php echo e(route('coa.edit', $coa)); ?>"
                                            class="btn btn-sm btn-outline-warning me-1">Edit</a>
                                        <form action="<?php echo e(route('coa.destroy', $coa)); ?>" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus Jenis COA?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">Belum ada Jenis COA.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile list -->
                <div class="mobile-only p-3">
                    <?php $__empty_1 = true; $__currentLoopData = $coas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="mobile-contract-card mb-2">
                            <div class="meta">
                                <div class="title fw-bold"><?php echo e($coa->code); ?> — <?php echo e($coa->name); ?></div>
                                <div class="subtitle text-muted"><?php echo e(Str::limit($coa->description, 80)); ?></div>
                            </div>
                            <div class="actions mt-2">
                                <a href="<?php echo e(route('coa.edit', $coa)); ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-4">Belum ada Jenis COA.</div>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3">
                    <div class="text-muted small">
                        <?php if($coas->total() > 0): ?>
                            Menampilkan <?php echo e($coas->firstItem()); ?>—<?php echo e($coas->lastItem()); ?> dari <?php echo e($coas->total()); ?> hasil
                        <?php else: ?>
                            0 hasil
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php echo e($coas->appends(request()->except('page'))->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views\coa\index.blade.php ENDPATH**/ ?>