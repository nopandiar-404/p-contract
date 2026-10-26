@extends('layouts.dashboard')

@section('title', 'Jenis Kontrak')
@section('subtitle', 'Kategori kontrak yang tersedia')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <div>
                <h5 class="mb-1"><i class="fas fa-layer-group text-primary me-2"></i>Jenis Kontrak</h5>
                <small class="text-muted">Jenis yang dapat dipilih pada setiap kontrak.</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary px-3 py-2 fs-7">{{ count($contractTypes) }} jenis</span>
                <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalAddContractType">
                    <i class="fas fa-plus me-1"></i> Tambah
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px">No.</th>
                        <th>Jenis Kontrak</th>
                        <th>Deskripsi</th>
                        <th style="width: 170px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contractTypes as $type)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-primary-subtle text-primary border">{{ $type->name }}</span></td>
                            <td>{{ $type->description ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning btn-edit-contract-type" 
                                            title="Edit"
                                            data-id="{{ $type->id }}"
                                            data-name="{{ $type->name }}"
                                            data-description="{{ $type->description }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('contract-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis kontrak &quot;{{ $type->name }}&quot;?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada jenis kontrak.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Jenis Kontrak -->
    <div class="modal fade" id="modalAddContractType" tabindex="-1" aria-labelledby="modalAddContractTypeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('contract-types.store') }}" method="POST" class="w-100">
                @csrf
                <div class="modal-content shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddContractTypeLabel">
                            <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Jenis Kontrak
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="add_name" class="form-label fw-semibold">Nama Jenis Kontrak <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_name" name="name" required placeholder="Contoh: Kontrak Pengadaan">
                        </div>
                        <div class="mb-3">
                            <label for="add_description" class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" id="add_description" name="description" rows="3" placeholder="Deskripsi singkat jenis kontrak..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Jenis Kontrak -->
    <div class="modal fade" id="modalEditContractType" tabindex="-1" aria-labelledby="modalEditContractTypeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formEditContractType" method="POST" class="w-100">
                @csrf
                @method('PUT')
                <div class="modal-content shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditContractTypeLabel">
                            <i class="fas fa-edit text-warning me-2"></i>Edit Jenis Kontrak
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label fw-semibold">Nama Jenis Kontrak <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Perbarui</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModalEl = document.getElementById('modalEditContractType');
        if (!editModalEl) return;
        
        const editModal = new bootstrap.Modal(editModalEl);
        const formEdit = document.getElementById('formEditContractType');
        const nameInput = document.getElementById('edit_name');
        const descInput = document.getElementById('edit_description');
        const baseActionUrl = "{{ url('/contract-types') }}";

        document.querySelectorAll('.btn-edit-contract-type').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || '';
                const desc = this.getAttribute('data-description') || '';

                formEdit.action = baseActionUrl + '/' + id;
                nameInput.value = name;
                descInput.value = desc;

                editModal.show();
            });
        });
    });
</script>
@endpush
