<!-- resources/views/clients/edit.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Edit Klien')
@section('subtitle', 'Edit data klien')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i> Edit Klien: {{ $client->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('clients.update', $client) }}" method="POST" id="clientForm">
                        @csrf
                        @method('PUT')

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
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $client->name) }}"
                                        placeholder="PT. Contoh Perusahaan" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i> Email Perusahaan <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $client->email) }}"
                                        placeholder="company@example.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i> Telepon Perusahaan
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $client->phone) }}"
                                        placeholder="021-12345678">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-tag me-1"></i> Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="active"
                                            {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Non-Aktif
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i> Alamat Perusahaan
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                    placeholder="Jl. Contoh No. 123, Jakarta...">{{ old('address', $client->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    <input type="text" class="form-control @error('pic_name') is-invalid @enderror"
                                        id="pic_name" name="pic_name" value="{{ old('pic_name', $client->pic_name) }}"
                                        placeholder="Nama lengkap">
                                    @error('pic_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="pic_phone" class="form-label">
                                        <i class="fas fa-mobile-alt me-1"></i> Telepon PIC
                                    </label>
                                    <input type="text" class="form-control @error('pic_phone') is-invalid @enderror"
                                        id="pic_phone" name="pic_phone" value="{{ old('pic_phone', $client->pic_phone) }}"
                                        placeholder="0812-3456-7890">
                                    @error('pic_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="pic_email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i> Email PIC
                                </label>
                                <input type="email" class="form-control @error('pic_email') is-invalid @enderror"
                                    id="pic_email" name="pic_email" value="{{ old('pic_email', $client->pic_email) }}"
                                    placeholder="pic@example.com">
                                @error('pic_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                    placeholder="Catatan atau keterangan tambahan mengenai klien...">{{ old('notes', $client->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Meta Information -->
                        <div class="mb-4">
                            <div class="alert alert-light">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Dibuat: {{ $client->created_at->format('d M Y H:i') }}
                                    &nbsp;|&nbsp;
                                    <i class="fas fa-sync me-1"></i>
                                    Diperbarui: {{ $client->updated_at->format('d M Y H:i') }}
                                </small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <div>
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-eye me-2"></i> Lihat
                                </a>
                                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
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
                        @if ($client->contracts->count() > 0)
                            Klien ini memiliki {{ $client->contracts->count() }} kontrak dan tidak dapat dihapus!
                        @else
                            Data yang dihapus tidak dapat dikembalikan!
                        @endif
                    </div>
                    <p><strong>Klien:</strong> {{ $client->name }} ({{ $client->email }})</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    @if ($client->contracts->count() == 0)
                        <button type="button" class="btn btn-danger" id="confirmDeleteClientBtn"
                            data-action="{{ route('clients.destroy', $client) }}" data-id="{{ $client->id }}">
                            Hapus Klien
                        </button>
                    @else
                        <button type="button" class="btn btn-danger" disabled>
                            Tidak Dapat Dihapus (Memiliki Kontrak)
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
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
                    window.location.href = '{{ route('clients.index') }}';
                })
                .catch(err => {
                    console.error('Failed to delete client', err);
                    alert('Gagal menghapus klien. Cek log untuk detail.');
                });
        });
    </script>
@endsection
