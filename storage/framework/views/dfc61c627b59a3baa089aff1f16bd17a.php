<!-- resources/views/clients/edit.blade.php -->


<?php $__env->startSection('title', 'Edit Klien'); ?>
<?php $__env->startSection('subtitle', 'Edit data klien'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i> Edit Klien: <?php echo e($client->name); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('clients.update', $client)); ?>" method="POST" id="clientForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Informasi Perusahaan -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-building text-primary me-2"></i> Informasi Perusahaan
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-building me-1"></i> Nama Perusahaan <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="name" name="name" value="<?php echo e(old('name', $client->name)); ?>"
                                        placeholder="PT. Contoh Perusahaan" required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i> Email Perusahaan <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="email" name="email" value="<?php echo e(old('email', $client->email)); ?>"
                                        placeholder="company@example.com" required>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i> Telepon Perusahaan
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="phone" name="phone" value="<?php echo e(old('phone', $client->phone)); ?>"
                                        placeholder="021-12345678">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-tag me-1"></i> Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status"
                                        name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="active"
                                            <?php echo e(old('status', $client->status) == 'active' ? 'selected' : ''); ?>>Aktif
                                        </option>
                                        <option value="inactive"
                                            <?php echo e(old('status', $client->status) == 'inactive' ? 'selected' : ''); ?>>Non-Aktif
                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i> Alamat Perusahaan
                                </label>
                                <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address" name="address" rows="2"
                                    placeholder="Jl. Contoh No. 123, Jakarta..."><?php echo e(old('address', $client->address)); ?></textarea>
                                <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Person In Charge (PIC) -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user-tie text-success me-2"></i> Person In Charge (PIC)
                            </h6>

                            <div class="alert alert-info small mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi kontak utama untuk klien ini
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pic_name" class="form-label">
                                        <i class="fas fa-user me-1"></i> Nama PIC
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['pic_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="pic_name" name="pic_name" value="<?php echo e(old('pic_name', $client->pic_name)); ?>"
                                        placeholder="Nama lengkap">
                                    <?php $__errorArgs = ['pic_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="pic_phone" class="form-label">
                                        <i class="fas fa-mobile-alt me-1"></i> Telepon PIC
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['pic_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="pic_phone" name="pic_phone" value="<?php echo e(old('pic_phone', $client->pic_phone)); ?>"
                                        placeholder="0812-3456-7890">
                                    <?php $__errorArgs = ['pic_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pic_email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i> Email PIC
                                </label>
                                <input type="email" class="form-control <?php $__errorArgs = ['pic_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="pic_email" name="pic_email" value="<?php echo e(old('pic_email', $client->pic_email)); ?>"
                                    placeholder="pic@example.com">
                                <?php $__errorArgs = ['pic_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-sticky-note text-info me-2"></i> Catatan Tambahan
                            </h6>

                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-comment-dots me-1"></i> Catatan
                                </label>
                                <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="notes" name="notes" rows="3"
                                    placeholder="Catatan atau keterangan tambahan mengenai klien..."><?php echo e(old('notes', $client->notes)); ?></textarea>
                                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Meta Information -->
                        <div class="mb-4">
                            <div class="alert alert-light">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Dibuat: <?php echo e($client->created_at->format('d M Y H:i')); ?>

                                    &nbsp;|&nbsp;
                                    <i class="fas fa-sync me-1"></i>
                                    Diperbarui: <?php echo e($client->updated_at->format('d M Y H:i')); ?>

                                </small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <div>
                                <a href="<?php echo e(route('clients.show', $client)); ?>" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-eye me-2"></i> Lihat
                                </a>
                                <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                            </div>
                            <div>
                                <button type="button" class="btn btn-danger me-2" onclick="showDeleteModal()">
                                    <i class="fas fa-trash me-2"></i> Hapus
                                </button>
                                <button type="submit" class="btn btn-warning" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Update Klien
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus klien ini?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Perhatian:</strong>
                        <?php if($client->contracts->count() > 0): ?>
                            Klien ini memiliki <?php echo e($client->contracts->count()); ?> kontrak dan tidak dapat dihapus!
                        <?php else: ?>
                            Data yang dihapus tidak dapat dikembalikan!
                        <?php endif; ?>
                    </div>
                    <p><strong>Klien:</strong> <?php echo e($client->name); ?> (<?php echo e($client->email); ?>)</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <?php if($client->contracts->count() == 0): ?>
                        <button type="button" class="btn btn-danger" id="confirmDeleteClientBtn"
                            data-action="<?php echo e(route('clients.destroy', $client)); ?>" data-id="<?php echo e($client->id); ?>">
                            Hapus Klien
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger" disabled>
                            Tidak Dapat Dihapus (Memiliki Kontrak)
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        window.showDeleteModal = function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        };

        // Form submission handling
        document.getElementById('clientForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });

        // Handle JS-driven deletion
        document.getElementById('confirmDeleteClientBtn')?.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            if (!action) return;
            if (!confirm('Hapus klien ini? Tindakan ini tidak dapat dibatalkan.')) return;

            fetch(action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(resp => {
                    if (resp.ok) return resp.json().catch(() => ({
                        success: true
                    }));
                    throw new Error('Request failed');
                })
                .then(data => {
                    window.location.href = '<?php echo e(route('clients.index')); ?>';
                })
                .catch(err => {
                    console.error('Failed to delete client', err);
                    alert('Gagal menghapus klien. Cek log untuk detail.');
                });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DASHBOARD\p-contract\resources\views\clients\edit.blade.php ENDPATH**/ ?>