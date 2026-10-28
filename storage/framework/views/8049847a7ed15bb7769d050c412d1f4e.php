<!-- resources/views/clients/create.blade.php -->


<?php $__env->startSection('title', 'Tambah Vendor Baru'); ?>
<?php $__env->startSection('subtitle', 'Tambahkan data vendor baru'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-gradient-primary text-white border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);" >
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                        <div>
                            <h5 class="mb-1"><i class="fas fa-user-plus me-2"></i> Tambah Vendor Baru</h5>
                            <p class="text-white-75 mb-0">Form responsive untuk menambahkan vendor dengan cepat dan mudah.</p>
                        </div>
                        
                    </div>
                </div>
                <div class="card-body p-4 bg-white">
                    <form action="<?php echo e(route('clients.store')); ?>" method="POST" id="clientForm">
                        <?php echo csrf_field(); ?>

                        <div class="mb-4 p-3 rounded-3 bg-light border">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <h6 class="mb-1 text-primary"><i class="fas fa-building me-2"></i> Informasi Perusahaan</h6>
                                    <p class="text-muted small mb-0">Lengkapi informasi perusahaan agar data vendor siap digunakan.</p>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary align-self-center">Wajib diisi</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="fas fa-building me-1"></i> Nama Perusahaan <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="name" name="name" value="<?php echo e(old('name')); ?>"
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

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-1"></i> Email Perusahaan <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="email" name="email" value="<?php echo e(old('email')); ?>"
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

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold">
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
                                        id="phone" name="phone" value="<?php echo e(old('phone')); ?>"
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
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold">
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
                                        <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>Aktif</option>
                                        <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Non-Aktif</option>
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

                            <div class="mt-3">
                                <label for="address" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-1"></i> Alamat Perusahaan
                                </label>
                                <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address" name="address" rows="3"
                                    placeholder="Jl. Contoh No. 123, Jakarta..."><?php echo e(old('address')); ?></textarea>
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

                        <div class="mb-4 p-3 rounded-3 bg-light border">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <h6 class="mb-1 text-success"><i class="fas fa-user-tie me-2"></i> Person In Charge (PIC)</h6>
                                    <p class="text-muted small mb-0">Data PIC membantu koordinasi langsung dengan vendor.</p>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success align-self-center">Opsional</span>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="pic_name" class="form-label fw-semibold">
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
                                        id="pic_name" name="pic_name" value="<?php echo e(old('pic_name')); ?>"
                                        placeholder="John Doe">
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
                                <div class="col-md-6">
                                    <label for="pic_phone" class="form-label fw-semibold">
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
                                        id="pic_phone" name="pic_phone" value="<?php echo e(old('pic_phone')); ?>"
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

                            <div class="mt-3">
                                <label for="pic_email" class="form-label fw-semibold">
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
                                    id="pic_email" name="pic_email" value="<?php echo e(old('pic_email')); ?>"
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

                        <div class="mb-4 p-3 rounded-3 bg-light border">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <h6 class="mb-1 text-warning"><i class="fas fa-sticky-note me-2"></i> Catatan Tambahan</h6>
                                    <p class="text-muted small mb-0">Tambahkan catatan penting untuk internal atau tim vendor.</p>
                                </div>
                            </div>

                            <div>
                                <label for="notes" class="form-label fw-semibold">
                                    <i class="fas fa-edit me-1"></i> Catatan
                                </label>
                                <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="notes" name="notes" rows="3"
                                    placeholder="Catatan penting tentang vendor..."><?php echo e(old('notes')); ?></textarea>
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
                                <small class="form-text text-muted">Opsional: informasi tambahan tentang vendor.</small>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 border-top pt-4">
                            <div>
                                <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-warning" onclick="resetForm()">
                                    <i class="fas fa-redo me-2"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Vendor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-info bg-opacity-10 border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-white h-100 shadow-sm">
                                <h6 class="mb-3"><i class="fas fa-lightbulb text-warning me-2"></i> Tips</h6>
                                <ul class="mb-0 text-muted" style="font-size: 0.95rem;">
                                    <li>Isi data dengan lengkap untuk memudahkan komunikasi</li>
                                    <li>Pastikan email valid dan aktif</li>
                                    <li>Isi data PIC jika ada kontak person khusus</li>
                                    <li>Gunakan notifikasi internal untuk catatan vendor</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-white h-100 shadow-sm">
                                <h6 class="mb-3"><i class="fas fa-exclamation-circle text-danger me-2"></i> Validasi</h6>
                                <ul class="mb-0 text-muted" style="font-size: 0.95rem;">
                                    <li><span id="nameValid" class="text-muted">○</span> Nama perusahaan</li>
                                    <li><span id="emailValid" class="text-muted">○</span> Email valid</li>
                                    <li><span id="statusValid" class="text-muted">○</span> Status</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('clientForm');
            const submitBtn = document.getElementById('submitBtn');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const statusSelect = document.getElementById('status');

            // Real-time validation
            nameInput.addEventListener('blur', function() {
                if (this.value.trim()) {
                    document.getElementById('nameValid').className = 'text-success';
                    document.getElementById('nameValid').textContent = '✓';
                } else {
                    document.getElementById('nameValid').className = 'text-danger';
                    document.getElementById('nameValid').textContent = '✗';
                }
            });

            emailInput.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailRegex.test(this.value)) {
                    document.getElementById('emailValid').className = 'text-success';
                    document.getElementById('emailValid').textContent = '✓';
                } else {
                    document.getElementById('emailValid').className = 'text-danger';
                    document.getElementById('emailValid').textContent = '✗';
                }
            });

            statusSelect.addEventListener('change', function() {
                if (this.value) {
                    document.getElementById('statusValid').className = 'text-success';
                    document.getElementById('statusValid').textContent = '✓';
                } else {
                    document.getElementById('statusValid').className = 'text-danger';
                    document.getElementById('statusValid').textContent = '✗';
                }
            });

            // Reset Form
            window.resetForm = function() {
                if (confirm('Reset semua data form?')) {
                    form.reset();
                    document.getElementById('nameValid').className = 'text-muted';
                    document.getElementById('nameValid').textContent = '○';
                    document.getElementById('emailValid').className = 'text-muted';
                    document.getElementById('emailValid').textContent = '○';
                    document.getElementById('statusValid').className = 'text-muted';
                    document.getElementById('statusValid').textContent = '○';
                }
            };

            // Form submission
            form.addEventListener('submit', function(e) {
                // Client-side validation
                if (!nameInput.value.trim()) {
                    e.preventDefault();
                    showToast('Nama perusahaan harus diisi', 'error');
                    nameInput.focus();
                    return;
                }

                if (!emailInput.value.trim()) {
                    e.preventDefault();
                    showToast('Email perusahaan harus diisi', 'error');
                    emailInput.focus();
                    return;
                }

                if (!statusSelect.value) {
                    e.preventDefault();
                    showToast('Status harus dipilih', 'error');
                    statusSelect.focus();
                    return;
                }

                // Show loading
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                submitBtn.disabled = true;
            });
        });

        // Toast Notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 p-3';
            toast.style.zIndex = '9999';

            const bgColor = type === 'success' ? 'bg-success' :
                type === 'error' ? 'bg-danger' :
                type === 'warning' ? 'bg-warning' : 'bg-info';

            toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header ${bgColor} text-white">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 
                                type === 'error' ? 'exclamation-circle' : 
                                type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                <strong class="me-auto">${type === 'success' ? 'Sukses' : 
                                       type === 'error' ? 'Error' : 
                                       type === 'warning' ? 'Peringatan' : 'Info'}</strong>
                <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.parentElement.parentElement.remove()"></button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;

            document.body.appendChild(toast);

            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 3000);
        }
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DASHBOARD\p-contract\resources\views\clients\create.blade.php ENDPATH**/ ?>