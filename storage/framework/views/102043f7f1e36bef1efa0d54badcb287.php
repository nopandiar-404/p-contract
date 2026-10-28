<?php if($notification->contract): ?>
    <?php ($contract = $notification->contract); ?>
    <div class="mb-3">
        <h6 class="mb-1"><?php echo e($notification->message); ?></h6>
        <small class="text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></small>
    </div>

    <dl class="row mb-0">
        <dt class="col-sm-4">Nomor Kontrak</dt>
        <dd class="col-sm-8"><?php echo e($contract->contract_number ?? '-'); ?></dd>

        <dt class="col-sm-4">Nama Kontrak</dt>
        <dd class="col-sm-8"><?php echo e($contract->contract_name ?? '-'); ?></dd>

        <dt class="col-sm-4">Vendor</dt>
        <dd class="col-sm-8"><?php echo e($contract->client?->name ?? '-'); ?></dd>
    </dl>
<?php else: ?>
    <div class="alert alert-warning mb-0">
        Kontrak terkait sudah tidak tersedia. Notifikasi ini dapat dihapus dari daftar.
    </div>
<?php endif; ?>
<?php /**PATH D:\DASHBOARD\p-contract\resources\views\notifications\partials\detail-modal.blade.php ENDPATH**/ ?>