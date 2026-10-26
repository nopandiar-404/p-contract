<!-- resources/views/contracts/create.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Tambah Kontrak Baru')
@section('subtitle', 'Buat kontrak baru dengan klien')

@section('content')
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
                    <form action="{{ route('contracts.store') }}" method="POST" id="contractForm" enctype="multipart/form-data">
                        @csrf

                        <!-- Informasi Umum -->
                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border">
                            <h6 class="border-bottom pb-2 mb-2">
                                <i class="fas fa-info-circle text-primary me-2"></i> Informasi Umum Kontrak
                            </h6>
                            <p class="text-muted small mb-3">Lengkapi nama, nomor, deskripsi, dan jenis kontrak ini.</p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contract_number" class="form-label fw-semibold">
                                        <i class="fas fa-hashtag text-danger me-1"></i> Nomor Kontrak <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @error('contract_number') is-invalid @enderror"
                                            id="contract_number" name="contract_number" value="{{ old('contract_number') }}"
                                            placeholder="CTR-001-2024" required>
                                        <button type="button" class="btn btn-outline-secondary" id="generateContractNumberBtn">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    @error('contract_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format: CTR-XXX-YYYY</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="contract_name" class="form-label">
                                        <i class="fas fa-file-signature text-primary me-1"></i> Nama Kontrak <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('contract_name') is-invalid @enderror"
                                        id="contract_name" name="contract_name" value="{{ old('contract_name') }}"
                                        placeholder="Kontrak Kerja Sama" required>
                                    @error('contract_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left text-info me-1"></i> Deskripsi Kontrak
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="Deskripsi singkat tentang kontrak...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contract_term" class="form-label">Termin Kontrak</label>
                                <select class="form-select @error('contract_term') is-invalid @enderror" id="contract_term" name="contract_term">
                                    <option value="">Pilih Termin</option>
                                    <option value="termin_1" {{ old('contract_term') == 'termin_1' ? 'selected' : '' }}>Termin I</option>
                                    <option value="termin_2" {{ old('contract_term') == 'termin_2' ? 'selected' : '' }}>Termin II</option>
                                    <option value="termin_3" {{ old('contract_term') == 'termin_3' ? 'selected' : '' }}>Termin III</option>
                                    <option value="termin_4" {{ old('contract_term') == 'termin_4' ? 'selected' : '' }}>Termin IV</option>
                                </select>
                                @error('contract_term')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="contract_type" class="form-label">
                                    <i class="fas fa-layer-group me-1"></i> Jenis Kontrak
                                </label>
                                <select class="form-select @error('contract_type') is-invalid @enderror" id="contract_type"
                                    name="contract_type" required>
                                    <option value="">-- Pilih Jenis Kontrak --</option>
                                    @foreach ($contractTypes as $typeKey => $type)
                                        <option value="{{ $typeKey }}"
                                            {{ old('contract_type') === $typeKey ? 'selected' : '' }}>
                                            {{ $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('contract_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Pilihan: Kontrak Barang, Kontrak Jasa, atau License.
                                </small>
                            </div>

                            @php
                                $boqRows = old('boq_items', [['description' => '', 'unit' => '', 'quantity' => '', 'unit_price' => '']]);
                            @endphp
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
                                            @foreach ($boqRows as $index => $item)
                                                <tr class="boq-row">
                                                    <td><input class="form-control form-control-sm boq-field" data-field="description" name="boq_items[{{ $index }}][description]" value="{{ $item['description'] ?? '' }}" maxlength="255" aria-label="Uraian item BOQ"></td>
                                                    <td><input class="form-control form-control-sm boq-field" data-field="unit" name="boq_items[{{ $index }}][unit]" value="{{ $item['unit'] ?? '' }}" maxlength="30" aria-label="Satuan BOQ" placeholder="m2"></td>
                                                    <td><input class="form-control form-control-sm boq-field" data-field="quantity" name="boq_items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? '' }}" type="number" min="0.001" step="0.001" aria-label="Kuantitas BOQ"></td>
                                                    <td><input class="form-control form-control-sm boq-field" data-field="unit_price" name="boq_items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] ?? '' }}" type="number" min="0" step="0.01" aria-label="Harga satuan BOQ"></td>
                                                    <td class="text-end text-nowrap"><span class="boq-subtotal">Rp 0</span></td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-boq-row" aria-label="Hapus item BOQ"><i class="fas fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
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
                                    <select class="form-select @error('client_id') is-invalid @enderror" id="client_id"
                                        name="client_id" data-clients="{{ json_encode($clients->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}" required>
                                        <option value="">-- Pilih Klien --</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}"
                                                {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }} - {{ $client->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <a href="{{ route('clients.create') }}" class="text-decoration-none">
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
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date" value="{{ old('start_date') }}"
                                        min="{{ now()->subYear()->toDateString() }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">
                                        <i class="fas fa-stop-circle text-danger me-1"></i> Tanggal Berakhir <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="grace_period_days" class="form-label">
                                        <i class="fas fa-clock text-warning me-1"></i> Masa Tenggang (hari)
                                    </label>
                                    <input type="number"
                                        class="form-control @error('grace_period_days') is-invalid @enderror"
                                        id="grace_period_days" name="grace_period_days"
                                        value="{{ old('grace_period_days', 30) }}" min="1" max="90">
                                    @error('grace_period_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    <input type="file" class="form-control @error('pdf_files') is-invalid @enderror"
                                        id="pdf_files" name="pdf_files[]" accept=".pdf" multiple
                                        data-max-size="50000000">
                                    <button class="btn btn-outline-secondary" type="button" id="clearAllPdfBtn"
                                        style="display: none;">
                                        <i class="fas fa-times"></i> Hapus Semua
                                    </button>
                                </div>
                                @error('pdf_files')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
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
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                        Aktif</option>
                                    <option value="grace_period" {{ old('status') == 'grace_period' ? 'selected' : '' }}>
                                        Masa Tenggang</option>
                                    <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa
                                    </option>
                                    <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>
                                        Dihentikan</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- <div class="mb-3">
                                <label for="contract_term" class="form-label">Termin Kontrak</label>
                                <select class="form-select @error('contract_term') is-invalid @enderror" id="contract_term" name="contract_term">
                                    <option value="">Pilih termin (opsional)</option>
                                    <option value="termin_1" {{ old('contract_term') == 'termin_1' ? 'selected' : '' }}>Termin I</option>
                                    <option value="termin_2" {{ old('contract_term') == 'termin_2' ? 'selected' : '' }}>Termin II</option>
                                    <option value="termin_3" {{ old('contract_term') == 'termin_3' ? 'selected' : '' }}>Termin III</option>
                                    <option value="termin_4" {{ old('contract_term') == 'termin_4' ? 'selected' : '' }}>Termin IV</option>
                                </select>
                                @error('contract_term')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> -->

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="actual_end_date" class="form-label">
                                        <i class="fas fa-calendar-times text-danger me-1"></i> Tanggal Berakhir Aktual
                                    </label>
                                    <input type="date"
                                        class="form-control @error('actual_end_date') is-invalid @enderror"
                                        id="actual_end_date" name="actual_end_date"
                                        value="{{ old('actual_end_date') }}">
                                    @error('actual_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Jika berbeda dengan tanggal berakhir
                                        rencana</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="termination_reason" class="form-label">
                                        <i class="fas fa-comment-dots text-warning me-1"></i> Alasan Penghentian
                                    </label>
                                    <input type="text"
                                        class="form-control @error('termination_reason') is-invalid @enderror"
                                        id="termination_reason" name="termination_reason"
                                        value="{{ old('termination_reason') }}"
                                        placeholder="Alasan penghentian kontrak...">
                                    @error('termination_reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 border-top pt-4">
                            <div>
                                <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
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

@section('scripts')
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
@endsection
@endsection
