<!-- resources/views/contracts/create.blade.php -->


<?php $__env->startSection('title', 'Tambah Kontrak Baru'); ?>
<?php $__env->startSection('subtitle', 'Buat kontrak baru dengan klien'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-gradient-info text-white border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);" >
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                        <div>
                            <h5 class="mb-1"><i class="fas fa-plus-circle me-2"></i> Tambah Kontrak Baru</h5>
                            <p class="text-white-75 mb-0">Isi semua detail kontrak, pilih klien dan unggah dokumen PDF dalam satu halaman.</p>
                        </div>
                        
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('contracts.store')); ?>" method="POST" id="contractForm" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <!-- Informasi Umum -->
                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border">
                            <h6 class="border-bottom pb-2 mb-2">
                                <i class="fas fa-info-circle text-primary me-2"></i> Informasi Umum Kontrak
                            </h6>
                            <p class="text-muted small mb-3">Lengkapi nama, nomor, deskripsi, dan jenis kontrak ini.</p>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="contract_number" class="form-label fw-semibold">
                                        <i class="fas fa-hashtag text-danger me-1"></i> Nomor Kontrak <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control <?php $__errorArgs = ['contract_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="contract_number" name="contract_number" value="<?php echo e(old('contract_number')); ?>"
                                            placeholder="CTR-001-2024" required>
                                        <button type="button" class="btn btn-outline-secondary" id="generateContractNumberBtn">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    <?php $__errorArgs = ['contract_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Format: CTR-XXX-YYYY</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="nomor_surat" class="form-label fw-semibold">
                                        <i class="fas fa-envelope-open-text text-secondary me-1"></i> Nomor Surat
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['nomor_surat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="nomor_surat" name="nomor_surat" value="<?php echo e(old('nomor_surat')); ?>"
                                        placeholder="Contoh: 001/DIR/X/2026" maxlength="10000">
                                    <?php $__errorArgs = ['nomor_surat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Format huruf / teks bebas (maks. 10.000 karakter)</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="contract_name" class="form-label">
                                        <i class="fas fa-file-signature text-primary me-1"></i> Nama Kontrak <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contract_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="contract_name" name="contract_name" value="<?php echo e(old('contract_name')); ?>"
                                        placeholder="Kontrak Kerja Sama" required>
                                    <?php $__errorArgs = ['contract_name'];
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
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left text-info me-1"></i> Deskripsi Kontrak
                                </label>
                                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description"
                                    rows="3" placeholder="Deskripsi singkat tentang kontrak..."><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
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

                            <div class="mb-3">
                                <label for="contract_term" class="form-label">Termin Kontrak</label>
                                <select class="form-select <?php $__errorArgs = ['contract_term'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contract_term" name="contract_term">
                                    <option value="">Pilih Termin</option>
                                    <option value="termin_1" <?php echo e(old('contract_term') == 'termin_1' ? 'selected' : ''); ?>>Termin I</option>
                                    <option value="termin_2" <?php echo e(old('contract_term') == 'termin_2' ? 'selected' : ''); ?>>Termin II</option>
                                    <option value="termin_3" <?php echo e(old('contract_term') == 'termin_3' ? 'selected' : ''); ?>>Termin III</option>
                                    <option value="termin_4" <?php echo e(old('contract_term') == 'termin_4' ? 'selected' : ''); ?>>Termin IV</option>
                                </select>
                                <?php $__errorArgs = ['contract_term'];
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


                            <div class="mb-3">
                                <label for="contract_type" class="form-label">
                                    <i class="fas fa-layer-group me-1"></i> Jenis Kontrak
                                </label>
                                <select class="form-select <?php $__errorArgs = ['contract_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contract_type"
                                    name="contract_type" required>
                                    <option value="">-- Pilih Jenis Kontrak --</option>
                                    <?php $__currentLoopData = $contractTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeKey => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($typeKey); ?>"
                                            <?php echo e(old('contract_type') === $typeKey ? 'selected' : ''); ?>>
                                            <?php echo e($type['name']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['contract_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="form-text text-muted">
                                    Pilihan: Kontrak Barang, Kontrak Jasa, atau License.
                                </small>
                            </div>

                            <!-- Nilai Kontrak Per Tahun -->
                            <div class="mb-4 p-3 bg-light rounded-3 border">
                                <h6 class="fw-semibold mb-2 text-primary">
                                    <i class="fas fa-coins me-1"></i> Nilai Kontrak Per Tahun
                                </h6>
                                <p class="text-muted small mb-3">Masukkan nilai kontrak untuk Tahun I dan Tahun II. Jumlah, PPN (11%), dan Total (Jumlah + PPN) akan dihitung otomatis.</p>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="tahun_1" class="form-label fw-semibold">
                                            <i class="fas fa-calendar-alt text-secondary me-1"></i> Tahun I (Rp)
                                        </label>
                                        <input type="number" step="0.01" min="0" 
                                            class="form-control <?php $__errorArgs = ['tahun_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="tahun_1" name="tahun_1" 
                                            value="<?php echo e(old('tahun_1')); ?>" 
                                            placeholder="Contoh: 50000000">
                                        <?php $__errorArgs = ['tahun_1'];
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
                                        <label for="tahun_2" class="form-label fw-semibold">
                                            <i class="fas fa-calendar-alt text-secondary me-1"></i> Tahun II (Rp)
                                        </label>
                                        <input type="number" step="0.01" min="0" 
                                            class="form-control <?php $__errorArgs = ['tahun_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="tahun_2" name="tahun_2" 
                                            value="<?php echo e(old('tahun_2')); ?>" 
                                            placeholder="Contoh: 50000000">
                                        <?php $__errorArgs = ['tahun_2'];
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
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="jumlah_display" class="form-label fw-semibold">
                                            <i class="fas fa-calculator text-primary me-1"></i> Jumlah (Tahun I + II)
                                        </label>
                                        <div class="form-control bg-white fw-bold text-primary d-flex align-items-center" id="jumlah_display" style="border-style: dashed; min-height: 38px;">
                                            Rp 0
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ppn_display" class="form-label fw-semibold">
                                            <i class="fas fa-percent text-warning me-1"></i> PPN (11% x Jumlah)
                                        </label>
                                        <div class="form-control bg-white fw-bold text-dark d-flex align-items-center" id="ppn_display" style="border-style: dashed; min-height: 38px;">
                                            Rp 0
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="total_display" class="form-label fw-semibold">
                                            <i class="fas fa-money-bill-wave text-primary me-1"></i> Total (Jumlah + PPN)
                                        </label>
                                        <div class="form-control bg-white fw-bold text-primary d-flex align-items-center" id="total_display" style="border-style: dashed; min-height: 38px;">
                                            Rp 0
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Pengadaan & Pembayaran -->
                            <div class="mb-4 p-3 bg-light rounded-3 border">
                                <h6 class="fw-semibold mb-2 text-primary">
                                    <i class="fas fa-file-invoice-dollar me-1"></i> Informasi Pengadaan & Pembayaran
                                </h6>
                                <p class="text-muted small mb-3">Masukkan nomor PR, PO, TTB (angka), Berita Acara (teks), serta status pembayaran dan keterangan termin.</p>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="pr" class="form-label fw-semibold">
                                            <i class="fas fa-hashtag text-secondary me-1"></i> No. PR
                                        </label>
                                        <input type="number" 
                                            class="form-control <?php $__errorArgs = ['pr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="pr" name="pr" 
                                            value="<?php echo e(old('pr')); ?>" 
                                            placeholder="Contoh: 10023456">
                                        <?php $__errorArgs = ['pr'];
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
                                    <div class="col-md-4">
                                        <label for="po" class="form-label fw-semibold">
                                            <i class="fas fa-hashtag text-secondary me-1"></i> No. PO
                                        </label>
                                        <input type="number" 
                                            class="form-control <?php $__errorArgs = ['po'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="po" name="po" 
                                            value="<?php echo e(old('po')); ?>" 
                                            placeholder="Contoh: 20034567">
                                        <?php $__errorArgs = ['po'];
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
                                    <div class="col-md-4">
                                        <label for="ttb" class="form-label fw-semibold">
                                            <i class="fas fa-hashtag text-secondary me-1"></i> No. TTB
                                        </label>
                                        <input type="number" 
                                            class="form-control <?php $__errorArgs = ['ttb'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="ttb" name="ttb" 
                                            value="<?php echo e(old('ttb')); ?>" 
                                            placeholder="Contoh: 30045678">
                                        <?php $__errorArgs = ['ttb'];
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
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="berita_acara" class="form-label fw-semibold">
                                            <i class="fas fa-file-alt text-secondary me-1"></i> Berita Acara
                                        </label>
                                        <input type="text" 
                                            class="form-control <?php $__errorArgs = ['berita_acara'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="berita_acara" name="berita_acara" 
                                            value="<?php echo e(old('berita_acara')); ?>" 
                                            placeholder="Nomor atau teks Berita Acara...">
                                        <?php $__errorArgs = ['berita_acara'];
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
                                    <div class="col-md-4">
                                        <label for="payment_status" class="form-label fw-semibold">
                                            <i class="fas fa-money-check-alt text-secondary me-1"></i> Status Pembayaran
                                        </label>
                                        <select class="form-select <?php $__errorArgs = ['payment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="payment_status" name="payment_status">
                                            <option value="BELUM BAYAR" <?php echo e(old('payment_status', 'BELUM BAYAR') == 'BELUM BAYAR' ? 'selected' : ''); ?>>BELUM BAYAR</option>
                                            <option value="SUDAH BAYAR" <?php echo e(old('payment_status') == 'SUDAH BAYAR' ? 'selected' : ''); ?>>SUDAH BAYAR</option>
                                        </select>
                                        <?php $__errorArgs = ['payment_status'];
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
                                    <div class="col-md-4">
                                        <label for="payment_date" class="form-label fw-semibold">
                                            <i class="fas fa-calendar-check text-secondary me-1"></i> Tanggal Pembayaran
                                        </label>
                                        <input type="date" 
                                            class="form-control <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="payment_date" name="payment_date" 
                                            value="<?php echo e(old('payment_date')); ?>">
                                        <?php $__errorArgs = ['payment_date'];
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
                                <div class="mb-0">
                                    <label for="keterangan" class="form-label fw-semibold">
                                        <i class="fas fa-sticky-note text-secondary me-1"></i> Keterangan Tambahan
                                    </label>
                                    <textarea class="form-control <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="keterangan" name="keterangan" rows="2" 
                                        placeholder="Keterangan tambahan terkait termin, pembayaran, dsb..."><?php echo e(old('keterangan')); ?></textarea>
                                    <?php $__errorArgs = ['keterangan'];
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

                            <?php
                                $boqRows = old('boq_items', [['description' => '', 'unit' => '', 'quantity' => '', 'unit_price' => '']]);
                            ?>
                            <div class="mt-3">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
                                    <div>
                                        <h6 class="mb-1"><i class="fas fa-list-ol text-primary me-2"></i>BOQ Kontrak Pelindo</h6>
                                        <small class="text-muted">Jumlah setiap item dihitung dari kuantitas x harga satuan.</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="addBoqRow"><i class="fas fa-plus me-1"></i>Tambah item</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-2">
                                        <thead class="table-light">
                                            <tr><th>Uraian pekerjaan/barang</th><th style="width: 100px">Satuan</th><th style="width: 130px">Kuantitas</th><th style="width: 170px">Harga satuan (Rp)</th><th style="width: 160px">Jumlah (Rp)</th><th style="width: 48px"></th></tr>
                                        </thead>
                                        <tbody id="boqRows">
                                            <?php $__currentLoopData = $boqRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="boq-row">
                                                    <td><input class="form-control form-control-sm boq-field" data-field="description" name="boq_items[<?php echo e($index); ?>][description]" value="<?php echo e($item['description'] ?? ''); ?>" maxlength="255" aria-label="Uraian item BOQ"></td>
                                                    <td><input class="form-control form-control-sm boq-field" data-field="unit" name="boq_items[<?php echo e($index); ?>][unit]" value="<?php echo e($item['unit'] ?? ''); ?>" maxlength="30" aria-label="Satuan BOQ" placeholder="m2"></td>
                                                    <td><input class="form-control form-control-sm boq-field" data-field="quantity" name="boq_items[<?php echo e($index); ?>][quantity]" value="<?php echo e($item['quantity'] ?? ''); ?>" type="number" min="0.001" step="0.001" aria-label="Kuantitas BOQ"></td>
                                                    <td><input class="form-control form-control-sm boq-field" data-field="unit_price" name="boq_items[<?php echo e($index); ?>][unit_price]" value="<?php echo e($item['unit_price'] ?? ''); ?>" type="number" min="0" step="0.01" aria-label="Harga satuan BOQ"></td>
                                                    <td class="text-end text-nowrap"><span class="boq-subtotal">Rp 0</span></td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-boq-row" aria-label="Hapus item BOQ"><i class="fas fa-trash"></i></button></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot><tr><th colspan="4" class="text-end">Total BOQ</th><th class="text-end text-nowrap" id="boqTotal">Rp 0</th><th></th></tr></tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <!-- Informasi Klien -->
                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border">
                            <h6 class="border-bottom pb-2 mb-2">
                                <i class="fas fa-users text-success me-2"></i> Informasi Klien
                            </h6>
                            <p class="text-muted small mb-3">Pilih klien dan cek detail kontak secara otomatis di samping.</p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="client_id" class="form-label">
                                        <i class="fas fa-user-tie text-success me-1"></i> Pilih Klien <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="client_id"
                                        name="client_id" data-clients="<?php echo e(json_encode($clients->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP)); ?>" required>
                                        <option value="">-- Pilih Klien --</option>
                                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($client->id); ?>"
                                                <?php echo e(old('client_id') == $client->id ? 'selected' : ''); ?>>
                                                <?php echo e($client->name); ?> - <?php echo e($client->email); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">
                                        <a href="<?php echo e(route('clients.create')); ?>" class="text-decoration-none">
                                            <i class="fas fa-plus-circle me-1"></i> Tambah klien baru
                                        </a>
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card bg-info bg-opacity-10 border-info">
                                        <div class="card-body p-3">
                                            <small class="text-muted d-block">Info Klien Terpilih:</small>
                                            <div id="clientInfo" class="text-muted">Pilih klien untuk melihat detail</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Periode Kontrak -->
                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border">
                            <h6 class="border-bottom pb-2 mb-2">
                                <i class="fas fa-calendar-alt text-warning me-2"></i> Periode Kontrak
                            </h6>
                            <p class="text-muted small mb-3">Atur tanggal mulai, berakhir, durasi, dan masa tenggang secara jelas.</p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">
                                        <i class="fas fa-play-circle text-success me-1"></i> Tanggal Mulai <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="start_date" name="start_date" value="<?php echo e(old('start_date')); ?>"
                                        min="<?php echo e(now()->subYear()->toDateString()); ?>" required>
                                    <?php $__errorArgs = ['start_date'];
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
                                    <label for="end_date" class="form-label">
                                        <i class="fas fa-stop-circle text-danger me-1"></i> Tanggal Berakhir <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="end_date" name="end_date" value="<?php echo e(old('end_date')); ?>" required>
                                    <?php $__errorArgs = ['end_date'];
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
                                    <label for="grace_period_days" class="form-label">
                                        <i class="fas fa-clock text-warning me-1"></i> Masa Tenggang (hari)
                                    </label>
                                    <input type="number"
                                        class="form-control <?php $__errorArgs = ['grace_period_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="grace_period_days" name="grace_period_days"
                                        value="<?php echo e(old('grace_period_days', 30)); ?>" min="1" max="90">
                                    <?php $__errorArgs = ['grace_period_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Default: 30 hari</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-calculator text-info me-1"></i> Durasi Kontrak
                                    </label>
                                    <div class="form-control bg-info bg-opacity-10 border-0" id="durationDisplay">
                                        <span class="text-muted">Pilih tanggal untuk menghitung durasi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload File PDF -->
                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border">
                            <h6 class="border-bottom pb-2 mb-2">
                                <i class="fas fa-file-upload text-info me-2"></i> Upload File Kontrak (PDF)
                            </h6>
                            <p class="text-muted small mb-3">Unggah satu atau beberapa file PDF agar kontrak tersimpan lengkap.</p>

                            <div class="mb-3">
                                <label for="pdf_files" class="form-label">
                                    <i class="fas fa-file-pdf text-danger me-1"></i>Document File PDF</label>
                                <div class="input-group">
                                    <input type="file" class="form-control <?php $__errorArgs = ['pdf_files'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="pdf_files" name="pdf_files[]" accept=".pdf" multiple
                                        data-max-size="50000000">
                                    <button class="btn btn-outline-secondary" type="button" id="clearAllPdfBtn"
                                        style="display: none;">
                                        <i class="fas fa-times"></i> Hapus Semua
                                    </button>
                                </div>
                                <?php $__errorArgs = ['pdf_files'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback" style="display: block;"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="form-text text-muted">Format: PDF • Ukuran max per file: 50 MB</small>
                            </div>

                            <div id="pdfFilesPreview" class="mt-3"></div>

                            <div class="alert alert-info alert-sm mt-3" role="alert" id="pdfInfoAlert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Catatan:</strong>
                                <ul class="mb-0 mt-2" style="font-size: 0.9rem;">
                                    <li>Upload file PDF untuk dokumen kontrak utama</li>
                                    <li>Anda bisa upload beberapa file PDF sekaligus</li>
                                    <li>File akan tersimpan aman di server</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border">
                            <h6 class="border-bottom pb-2 mb-2">
                                <i class="fas fa-cogs text-secondary me-2"></i> Informasi Tambahan
                            </h6>
                            <p class="text-muted small mb-3">Atur status, tanggal akhir aktual, dan alasan penghentian jika perlu.</p>

                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    <i class="fas fa-tag me-1"></i> Status Kontrak
                                </label>
                                <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status"
                                    name="status">
                                    <option value="active" <?php echo e(old('status', 'active') == 'active' ? 'selected' : ''); ?>>
                                        Aktif</option>
                                    <option value="grace_period" <?php echo e(old('status') == 'grace_period' ? 'selected' : ''); ?>>
                                        Masa Tenggang</option>
                                    <option value="expired" <?php echo e(old('status') == 'expired' ? 'selected' : ''); ?>>Kadaluarsa
                                    </option>
                                    <option value="terminated" <?php echo e(old('status') == 'terminated' ? 'selected' : ''); ?>>
                                        Dihentikan</option>
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

                            <!-- <div class="mb-3">
                                <label for="contract_term" class="form-label">Termin Kontrak</label>
                                <select class="form-select <?php $__errorArgs = ['contract_term'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contract_term" name="contract_term">
                                    <option value="">Pilih termin (opsional)</option>
                                    <option value="termin_1" <?php echo e(old('contract_term') == 'termin_1' ? 'selected' : ''); ?>>Termin I</option>
                                    <option value="termin_2" <?php echo e(old('contract_term') == 'termin_2' ? 'selected' : ''); ?>>Termin II</option>
                                    <option value="termin_3" <?php echo e(old('contract_term') == 'termin_3' ? 'selected' : ''); ?>>Termin III</option>
                                    <option value="termin_4" <?php echo e(old('contract_term') == 'termin_4' ? 'selected' : ''); ?>>Termin IV</option>
                                </select>
                                <?php $__errorArgs = ['contract_term'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div> -->

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="actual_end_date" class="form-label">
                                        <i class="fas fa-calendar-times text-danger me-1"></i> Tanggal Berakhir Aktual
                                    </label>
                                    <input type="date"
                                        class="form-control <?php $__errorArgs = ['actual_end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="actual_end_date" name="actual_end_date"
                                        value="<?php echo e(old('actual_end_date')); ?>">
                                    <?php $__errorArgs = ['actual_end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">Jika berbeda dengan tanggal berakhir
                                        rencana</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="termination_reason" class="form-label">
                                        <i class="fas fa-comment-dots text-warning me-1"></i> Alasan Penghentian
                                    </label>
                                    <input type="text"
                                        class="form-control <?php $__errorArgs = ['termination_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="termination_reason" name="termination_reason"
                                        value="<?php echo e(old('termination_reason')); ?>"
                                        placeholder="Alasan penghentian kontrak...">
                                    <?php $__errorArgs = ['termination_reason'];
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
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 border-top pt-4">
                            <div>
                                <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-warning" onclick="resetForm()">
                                    <i class="fas fa-redo me-2"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Kontrak
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-info bg-opacity-10 border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <h6 class="mb-3"><i class="fas fa-lightbulb text-warning me-2"></i> Tips</h6>
                                <ul class="mb-0 text-muted" style="font-size: 0.95rem;">
                                    <li>Pastikan nomor kontrak unik</li>
                                    <li>Periksa tanggal mulai dan berakhir dengan teliti</li>
                                    <li>Set masa tenggang sesuai kebijakan perusahaan</li>
                                    <li>Simpan draft jika belum lengkap</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <h6 class="mb-3"><i class="fas fa-exclamation-circle text-danger me-2"></i> Validasi</h6>
                                <ul class="mb-0 text-muted" style="font-size: 0.95rem;">
                                    <li><span id="contractNumberValid" class="text-success">✓</span> Nomor kontrak</li>
                                    <li><span id="clientValid" class="text-muted">○</span> Klien terpilih</li>
                                    <li><span id="datesValid" class="text-muted">○</span> Periode kontrak</li>
                                    <li><span id="durationValid" class="text-muted">○</span> Durasi valid</li>
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
        (function() {
            const rows = document.getElementById('boqRows');
            const currency = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });

            function refreshBoq() {
                let total = 0;
                rows.querySelectorAll('.boq-row').forEach(row => {
                    const quantity = row.querySelector('[data-field="quantity"]').value;
                    const price = row.querySelector('[data-field="unit_price"]').value;
                    const subtotal = Number(quantity || 0) * Number(price || 0);
                    row.querySelector('.boq-subtotal').textContent = `Rp ${currency.format(subtotal)}`;
                    if (quantity && price && row.querySelector('[data-field="description"]').value.trim() && row.querySelector('[data-field="unit"]').value.trim()) total += subtotal;
                });
                document.getElementById('boqTotal').textContent = `Rp ${currency.format(total)}`;
            }

            function reindexRows() {
                rows.querySelectorAll('.boq-row').forEach((row, index) => {
                    row.querySelectorAll('.boq-field').forEach(input => {
                        input.name = `boq_items[${index}][${input.dataset.field}]`;
                    });
                });
                refreshBoq();
            }

            rows.addEventListener('input', refreshBoq);
            rows.addEventListener('click', event => {
                const removeButton = event.target.closest('.remove-boq-row');
                if (!removeButton) return;
                const row = removeButton.closest('.boq-row');
                if (rows.querySelectorAll('.boq-row').length === 1) row.querySelectorAll('.boq-field').forEach(input => input.value = '');
                else row.remove();
                reindexRows();
            });
            document.getElementById('addBoqRow').addEventListener('click', () => {
                const index = rows.querySelectorAll('.boq-row').length;
                const row = document.createElement('tr');
                row.className = 'boq-row';
                row.innerHTML = `<td><input class="form-control form-control-sm boq-field" data-field="description" name="boq_items[${index}][description]" maxlength="255" aria-label="Uraian item BOQ"></td><td><input class="form-control form-control-sm boq-field" data-field="unit" name="boq_items[${index}][unit]" maxlength="30" aria-label="Satuan BOQ" placeholder="m2"></td><td><input class="form-control form-control-sm boq-field" data-field="quantity" name="boq_items[${index}][quantity]" type="number" min="0.001" step="0.001" aria-label="Kuantitas BOQ"></td><td><input class="form-control form-control-sm boq-field" data-field="unit_price" name="boq_items[${index}][unit_price]" type="number" min="0" step="0.01" aria-label="Harga satuan BOQ"></td><td class="text-end text-nowrap"><span class="boq-subtotal">Rp 0</span></td><td><button type="button" class="btn btn-sm btn-outline-danger remove-boq-row" aria-label="Hapus item BOQ"><i class="fas fa-trash"></i></button></td>`;
                rows.appendChild(row);
                reindexRows();
                row.querySelector('[data-field="description"]').focus();
            });
            document.getElementById('contractForm').addEventListener('reset', () => setTimeout(refreshBoq, 0));
            refreshBoq();
        })();

        // Auto-calculate Jumlah (Tahun I + Tahun II), PPN (11%), & Total (Jumlah + PPN)
        (function() {
            function updateJumlahDisplay() {
                const t1 = parseFloat(document.getElementById('tahun_1')?.value) || 0;
                const t2 = parseFloat(document.getElementById('tahun_2')?.value) || 0;
                const total = t1 + t2;
                const ppn = total * 0.11;
                const grandTotal = total + ppn;
                const display = document.getElementById('jumlah_display');
                if (display) {
                    display.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                }
                const displayPpn = document.getElementById('ppn_display');
                if (displayPpn) {
                    displayPpn.textContent = 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(ppn);
                }
                const displayTotal = document.getElementById('total_display');
                if (displayTotal) {
                    displayTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(grandTotal);
                }
            }
            const inputT1 = document.getElementById('tahun_1');
            const inputT2 = document.getElementById('tahun_2');
            if (inputT1) inputT1.addEventListener('input', updateJumlahDisplay);
            if (inputT2) inputT2.addEventListener('input', updateJumlahDisplay);
            const form = document.getElementById('contractForm');
            if (form) form.addEventListener('reset', () => setTimeout(updateJumlahDisplay, 0));
            updateJumlahDisplay();
        })();
    </script>
    <script>
        // Client Info Display
        const clientSelect = document.getElementById('client_id');
        const clientInfo = document.getElementById('clientInfo');

        // Data passed from controller - pastikan nama variable konsisten
        const clientsData = JSON.parse(clientSelect.dataset.clients || '[]');

        // Event listener untuk menampilkan info klien
        clientSelect.addEventListener('change', function() {
            const clientId = parseInt(this.value);
            const client = clientsData.find(c => c.id == clientId);

            if (client) {
                if (clientInfo) {
                    clientInfo.innerHTML = `
                        <div class="mb-1"><strong>${client.name}</strong></div>
                        <div class="mb-1"><i class="fas fa-envelope fa-sm me-1"></i> ${client.email}</div>
                        ${client.phone ? `<div class="mb-1"><i class="fas fa-phone fa-sm me-1"></i> ${client.phone}</div>` : ''}
                        ${client.pic_name ? `<div class="mb-0"><i class="fas fa-user fa-sm me-1"></i> PIC: ${client.pic_name}</div>` : ''}
                        ${client.address ? `<div class="mb-0"><i class="fas fa-map-marker-alt fa-sm me-1"></i> ${client.address.substring(0, 50)}${client.address.length > 50 ? '...' : ''}</div>` : ''}
                    `;
                }
                document.getElementById('clientValid').className = 'text-success';
                document.getElementById('clientValid').textContent = '✓';
            } else {
                if (clientInfo) {
                    clientInfo.innerHTML = '<span class="text-muted">Pilih klien untuk melihat detail</span>';
                }
                document.getElementById('clientValid').className = 'text-muted';
                document.getElementById('clientValid').textContent = '○';
            }
        });

        // Calculate Duration
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const durationDisplay = document.getElementById('durationDisplay');

        function calculateDuration() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDateInput.value && endDateInput.value && startDate < endDate) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                const years = Math.floor(diffDays / 365);
                const months = Math.floor((diffDays % 365) / 30);
                const days = diffDays % 30;

                let durationText = `${diffDays} hari`;
                if (years > 0) durationText = `${years} tahun, ${months} bulan, ${days} hari`;
                else if (months > 0) durationText = `${months} bulan, ${days} hari`;

                durationDisplay.innerHTML = `<strong>${durationText}</strong>`;
                document.getElementById('durationValid').className = 'text-success';
                document.getElementById('durationValid').textContent = '✓';
                document.getElementById('datesValid').className = 'text-success';
                document.getElementById('datesValid').textContent = '✓';
            } else if (startDateInput.value && endDateInput.value) {
                durationDisplay.innerHTML = '<span class="text-danger">Tanggal berakhir harus setelah tanggal mulai</span>';
                document.getElementById('durationValid').className = 'text-danger';
                document.getElementById('durationValid').textContent = '✗';
                document.getElementById('datesValid').className = 'text-danger';
                document.getElementById('datesValid').textContent = '✗';
            } else {
                durationDisplay.innerHTML = '<span class="text-muted">Pilih tanggal untuk menghitung durasi</span>';
                document.getElementById('durationValid').className = 'text-muted';
                document.getElementById('durationValid').textContent = '○';
                document.getElementById('datesValid').className = 'text-muted';
                document.getElementById('datesValid').textContent = '○';
            }
        }

        startDateInput.addEventListener('change', calculateDuration);
        endDateInput.addEventListener('change', calculateDuration);

        // Auto-generate contract number
        const contractNumberInput = document.getElementById('contract_number');
        contractNumberInput.addEventListener('blur', function() {
            if (this.value) {
                document.getElementById('contractNumberValid').className = 'text-success';
                document.getElementById('contractNumberValid').textContent = '✓';
            } else {
                document.getElementById('contractNumberValid').className = 'text-danger';
                document.getElementById('contractNumberValid').textContent = '✗';
            }
        });

        // Generate contract number button
        const generateButton = document.getElementById('generateContractNumberBtn');
        generateButton.addEventListener('click', function() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            contractNumberInput.value = `CTR-${year}${month}-${random}`;

            document.getElementById('contractNumberValid').className = 'text-success';
            document.getElementById('contractNumberValid').textContent = '✓';
        });

        // Set minimum start date to one year ago and allow prior-year start dates
        const today = new Date().toISOString().split('T')[0];
        const yearAgo = new Date();
        yearAgo.setFullYear(yearAgo.getFullYear() - 1);
        const minStartDate = yearAgo.toISOString().split('T')[0];
        startDateInput.min = minStartDate;

        // Set minimum date for end date based on selected start date
        startDateInput.addEventListener('change', function() {
            if (this.value) {
                endDateInput.min = this.value;
            }
        });

        // Reset Form
        function resetForm() {
            if (confirm('Reset semua data form?')) {
                document.getElementById('contractForm').reset();
                if (clientInfo) {
                    clientInfo.innerHTML = '<span class="text-muted">Pilih klien untuk melihat detail</span>';
                }
                durationDisplay.innerHTML = '<span class="text-muted">Pilih tanggal untuk menghitung durasi</span>';

                // Reset PDF files
                uploadedFiles = [];
                displayPdfFilesPreview();

                // Reset validation indicators
                document.getElementById('contractNumberValid').className = 'text-success';
                document.getElementById('contractNumberValid').textContent = '✓';
                document.getElementById('clientValid').className = 'text-muted';
                document.getElementById('clientValid').textContent = '○';
                document.getElementById('datesValid').className = 'text-muted';
                document.getElementById('datesValid').textContent = '○';
                document.getElementById('durationValid').className = 'text-muted';
                document.getElementById('durationValid').textContent = '○';
            }
        }

        // Form Submission
        const form = document.getElementById('contractForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            // Basic validation
            if (!contractNumberInput.value) {
                e.preventDefault();
                showToast('Nomor kontrak harus diisi', 'error');
                contractNumberInput.focus();
                return;
            }

            if (!clientSelect.value) {
                e.preventDefault();
                showToast('Pilih klien terlebih dahulu', 'error');
                clientSelect.focus();
                return;
            }

            if (!startDateInput.value || !endDateInput.value) {
                e.preventDefault();
                showToast('Tanggal mulai dan berakhir harus diisi', 'error');
                return;
            }

            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            if (startDate >= endDate) {
                e.preventDefault();
                showToast('Tanggal berakhir harus setelah tanggal mulai', 'error');
                endDateInput.focus();
                return;
            }

            // Validasi PDF files
            if (uploadedFiles.length === 0) {
                e.preventDefault();
                showToast('Minimal upload 1 file PDF kontrak', 'error');
                pdfFilesInput.focus();
                return;
            }

            // Validasi ukuran total file
            let totalSize = 0;
            uploadedFiles.forEach(file => {
                totalSize += file.size;
            });
            
            if (totalSize > 50000000) { // 50MB total limit
                e.preventDefault();
                showToast('Total ukuran file melebihi 50MB', 'error');
                return;
            }

            // Show loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
            submitBtn.disabled = true;
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

        // Multiple PDF Files Upload Handler
        const pdfFilesInput = document.getElementById('pdf_files');
        const pdfFilesPreview = document.getElementById('pdfFilesPreview');
        const clearAllPdfBtn = document.getElementById('clearAllPdfBtn');
        const pdfInfoAlert = document.getElementById('pdfInfoAlert');
        let uploadedFiles = [];

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        function validateFile(file, maxSize) {
            const fileSize = file.size;
            const fileName = file.name;

            // Cek ukuran file
            if (fileSize > maxSize) {
                showToast(`Ukuran file ${fileName} terlalu besar. Maksimal: ${formatFileSize(maxSize)}`, 'error');
                return false;
            }

            // Cek tipe file
            if (!fileName.toLowerCase().endsWith('.pdf')) {
                showToast(`File ${fileName} bukan PDF. Hanya file PDF yang diizinkan`, 'error');
                return false;
            }

            return true;
        }

        function displayPdfFilesPreview() {
            pdfFilesPreview.innerHTML = '';

            if (uploadedFiles.length === 0) {
                clearAllPdfBtn.style.display = 'none';
                return;
            }

            clearAllPdfBtn.style.display = 'block';

            const container = document.createElement('div');
            container.className = 'card border-info';
            container.innerHTML = '<div class="card-header bg-info text-white"><strong>File yang Diupload (' + uploadedFiles.length + ')</strong></div>';

            const listContainer = document.createElement('div');
            listContainer.className = 'list-group list-group-flush';

            uploadedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                fileItem.innerHTML = `
                    <div class="d-flex align-items-center" style="flex: 1;">
                        <i class="fas fa-file-pdf text-danger me-2 fa-lg"></i>
                        <div>
                            <div class="mb-0"><strong>${file.name}</strong></div>
                            <small class="text-muted">${formatFileSize(file.size)}</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-index="${index}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `;

                // Delete button event
                fileItem.querySelector('button').addEventListener('click', function() {
                    uploadedFiles.splice(index, 1);
                    
                    // Update file input
                    const dataTransfer = new DataTransfer();
                    uploadedFiles.forEach(f => dataTransfer.items.add(f));
                    pdfFilesInput.files = dataTransfer.files;

                    displayPdfFilesPreview();
                    showToast('File dihapus', 'warning');
                });

                listContainer.appendChild(fileItem);
            });

            container.appendChild(listContainer);
            pdfFilesPreview.appendChild(container);
        }

        pdfFilesInput.addEventListener('change', function() {
            uploadedFiles = [];
            const maxSize = parseInt(this.dataset.maxSize);
            const newFiles = Array.from(this.files);

            let validFiles = 0;
            newFiles.forEach(file => {
                if (validateFile(file, maxSize)) {
                    uploadedFiles.push(file);
                    validFiles++;
                }
            });

            if (validFiles > 0) {
                showToast(`${validFiles} file berhasil ditambahkan`, 'success');
                displayPdfFilesPreview();

                // Update file input dengan hanya file yang valid
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(f => dataTransfer.items.add(f));
                this.files = dataTransfer.files;
            } else {
                pdfFilesInput.value = '';
                uploadedFiles = [];
                displayPdfFilesPreview();
            }
        });

        clearAllPdfBtn.addEventListener('click', function() {
            pdfFilesInput.value = '';
            uploadedFiles = [];
            displayPdfFilesPreview();
            showToast('Semua file telah dihapus', 'warning');
        });

        // Initialize with today's date
        window.addEventListener('DOMContentLoaded', function() {
            if (!startDateInput.value) {
                startDateInput.value = today;
            }

            if (!endDateInput.value) {
                const nextYear = new Date();
                nextYear.setFullYear(nextYear.getFullYear() + 1);
                endDateInput.value = nextYear.toISOString().split('T')[0];
            }

            calculateDuration();

            // Auto-generate contract number on page load
            if (!contractNumberInput.value) {
                generateButton.click();
            }

            // Trigger client info display if client is already selected
            if (clientSelect.value) {
                clientSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DASHBOARD\p-contract\resources\views\contracts\create.blade.php ENDPATH**/ ?>