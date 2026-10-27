<<<<<<< HEAD
<!-- resources/views/contracts/edit.blade.php -->
=======
﻿<!-- resources/views/contracts/edit.blade.php -->
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8
@extends('layouts.dashboard')

@section('title', 'Edit Kontrak')
@section('subtitle', 'Perbarui data kontrak dengan tampilan modern dan responsif')

@push('styles')
    <style>
        .card-header.bg-gradient-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }

        .section-card {
            background: #ffab4a;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 1rem;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.05);
        }

        .section-card h6 {
            font-size: 0.95rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .section-subtitle {
            color: #6b7280;
            font-size: 0.92rem;
            margin-bottom: 1.25rem;
        }

        .form-label i {
            width: 1.2rem;
            display: inline-block;
            text-align: center;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.22rem rgba(59, 130, 246, 0.18);
        }

        .list-group-item {
            border-radius: 0.85rem;
            border: 1px solid rgba(108, 117, 125, 0.12);
            margin-bottom: 0.85rem;
            background: #f8fafc;
        }

        .pdf-file-item {
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .pdf-file-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 26px rgba(15, 23, 42, 0.08);
        }

        .existing-files-counter {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .alert-custom {
            border-radius: 0.85rem;
            border: none;
        }

        .alert-info {
            background: #eef6ff;
            border-color: rgba(59, 130, 246, 0.24);
        }

        .btn-warning {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-warning:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        @media (max-width: 767.98px) {
            .action-buttons {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-gradient-primary text-white border-0" style="background-color: #ebb917;">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                        <div>
                            <h5 class="mb-1"><i class="fas fa-edit me-2"></i> Edit Kontrak</h5>
                            <p class="text-white-75 mb-0">Perbarui semua detail kontrak, klien, jadwal, dan dokumen PDF dalam satu halaman.</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-white text-primary">{{ $contract->contract_number }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('contracts.update', $contract) }}" method="POST" id="contractForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border section-card">
                            <h6><i class="fas fa-info-circle text-primary me-2"></i> Informasi Umum Kontrak</h6>
                            <p class="section-subtitle">Lengkapi nama, nomor, jenis kontrak, deskripsi, dan rincian BOQ.</p>

                            <div class="row">
<<<<<<< HEAD
                                <div class="col-md-4 mb-3">
=======
                                <div class="col-md-6 mb-3">
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8
                                    <label for="contract_number" class="form-label fw-semibold">
                                        <i class="fas fa-hashtag text-danger me-1"></i> Nomor Kontrak <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('contract_number') is-invalid @enderror" id="contract_number" name="contract_number" value="{{ old('contract_number', $contract->contract_number) }}" placeholder="CTR-001-2024" required>
                                        <button type="button" class="btn btn-outline-secondary" id="generateContractNumberBtn" title="Generate nomor kontrak">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    @error('contract_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format: CTR-XXX-YYYY</small>
                                </div>

<<<<<<< HEAD
                                <div class="col-md-4 mb-3">
                                    <label for="nomor_surat" class="form-label fw-semibold">
                                        <i class="fas fa-envelope-open-text text-secondary me-1"></i> Nomor Surat
                                    </label>
                                    <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $contract->nomor_surat) }}" placeholder="Contoh: 001/DIR/X/2026" maxlength="10000">
                                    @error('nomor_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format huruf / teks bebas (maks. 10.000 karakter)</small>
                                </div>

                                <div class="col-md-4 mb-3">
=======
                                <div class="col-md-6 mb-3">
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8
                                    <label for="contract_name" class="form-label">
                                        <i class="fas fa-file-signature text-primary me-1"></i> Nama Kontrak <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('contract_name') is-invalid @enderror" id="contract_name" name="contract_name" value="{{ old('contract_name', $contract->contract_name) }}" placeholder="Kontrak Kerja Sama" required>
                                    @error('contract_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left text-info me-1"></i> Deskripsi Kontrak
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Deskripsi singkat tentang kontrak...">{{ old('description', $contract->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contract_term" class="form-label">Termin Kontrak</label>
                                <select class="form-select @error('contract_term') is-invalid @enderror" id="contract_term" name="contract_term">
                                    <option value="">Pilih Termin</option>
                                    <option value="termin_1" {{ old('contract_term', $contract->contract_term) == 'termin_1' ? 'selected' : '' }}>Termin I</option>
                                    <option value="termin_2" {{ old('contract_term', $contract->contract_term) == 'termin_2' ? 'selected' : '' }}>Termin II</option>
                                    <option value="termin_3" {{ old('contract_term', $contract->contract_term) == 'termin_3' ? 'selected' : '' }}>Termin III</option>
                                    <option value="termin_4" {{ old('contract_term', $contract->contract_term) == 'termin_4' ? 'selected' : '' }}>Termin IV</option>
                                </select>
                                @error('contract_term')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contract_type" class="form-label">
                                        <i class="fas fa-layer-group me-1"></i> Jenis Kontrak
                                    </label>
                                    <select class="form-select @error('contract_type') is-invalid @enderror" id="contract_type" name="contract_type" required>
                                        <option value="">-- Pilih Jenis Kontrak --</option>
                                        @foreach ($contractTypes as $typeKey => $type)
                                            <option value="{{ $typeKey }}" {{ old('contract_type', $contract->contract_type) === $typeKey ? 'selected' : '' }}>
                                                {{ $type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contract_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Pilihan: Kontrak Barang, Kontrak Jasa, atau License.</small>
                                </div>

                            </div>

<<<<<<< HEAD
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
                                            class="form-control @error('tahun_1') is-invalid @enderror" 
                                            id="tahun_1" name="tahun_1" 
                                            value="{{ old('tahun_1', $contract->tahun_1) }}" 
                                            placeholder="Contoh: 50000000">
                                        @error('tahun_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tahun_2" class="form-label fw-semibold">
                                            <i class="fas fa-calendar-alt text-secondary me-1"></i> Tahun II (Rp)
                                        </label>
                                        <input type="number" step="0.01" min="0" 
                                            class="form-control @error('tahun_2') is-invalid @enderror" 
                                            id="tahun_2" name="tahun_2" 
                                            value="{{ old('tahun_2', $contract->tahun_2) }}" 
                                            placeholder="Contoh: 50000000">
                                        @error('tahun_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                            class="form-control @error('pr') is-invalid @enderror" 
                                            id="pr" name="pr" 
                                            value="{{ old('pr', $contract->pr) }}" 
                                            placeholder="Contoh: 10023456">
                                        @error('pr')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="po" class="form-label fw-semibold">
                                            <i class="fas fa-hashtag text-secondary me-1"></i> No. PO
                                        </label>
                                        <input type="number" 
                                            class="form-control @error('po') is-invalid @enderror" 
                                            id="po" name="po" 
                                            value="{{ old('po', $contract->po) }}" 
                                            placeholder="Contoh: 20034567">
                                        @error('po')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ttb" class="form-label fw-semibold">
                                            <i class="fas fa-hashtag text-secondary me-1"></i> No. TTB
                                        </label>
                                        <input type="number" 
                                            class="form-control @error('ttb') is-invalid @enderror" 
                                            id="ttb" name="ttb" 
                                            value="{{ old('ttb', $contract->ttb) }}" 
                                            placeholder="Contoh: 30045678">
                                        @error('ttb')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="berita_acara" class="form-label fw-semibold">
                                            <i class="fas fa-file-alt text-secondary me-1"></i> Berita Acara
                                        </label>
                                        <input type="text" 
                                            class="form-control @error('berita_acara') is-invalid @enderror" 
                                            id="berita_acara" name="berita_acara" 
                                            value="{{ old('berita_acara', $contract->berita_acara) }}" 
                                            placeholder="Nomor atau teks Berita Acara...">
                                        @error('berita_acara')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="payment_status" class="form-label fw-semibold">
                                            <i class="fas fa-money-check-alt text-secondary me-1"></i> Status Pembayaran
                                        </label>
                                        <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status">
                                            <option value="BELUM BAYAR" {{ old('payment_status', $contract->payment_status ?? 'BELUM BAYAR') == 'BELUM BAYAR' ? 'selected' : '' }}>BELUM BAYAR</option>
                                            <option value="SUDAH BAYAR" {{ old('payment_status', $contract->payment_status ?? 'BELUM BAYAR') == 'SUDAH BAYAR' ? 'selected' : '' }}>SUDAH BAYAR</option>
                                        </select>
                                        @error('payment_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="payment_date" class="form-label fw-semibold">
                                            <i class="fas fa-calendar-check text-secondary me-1"></i> Tanggal Pembayaran
                                        </label>
                                        <input type="date" 
                                            class="form-control @error('payment_date') is-invalid @enderror" 
                                            id="payment_date" name="payment_date" 
                                            value="{{ old('payment_date', $contract->payment_date ? $contract->payment_date->format('Y-m-d') : '') }}">
                                        @error('payment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label for="keterangan" class="form-label fw-semibold">
                                        <i class="fas fa-sticky-note text-secondary me-1"></i> Keterangan Tambahan
                                    </label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                        id="keterangan" name="keterangan" rows="2" 
                                        placeholder="Keterangan tambahan terkait termin, pembayaran, dsb...">{{ old('keterangan', $contract->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

=======
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8
                            @php
                                $boqRows = old('boq_items', $contract->boqItems->toArray());
                                if (empty($boqRows)) {
                                    $boqRows = [['description' => '', 'unit' => '', 'quantity' => '', 'unit_price' => '']];
                                }
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

                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border section-card">
                            <h6><i class="fas fa-users text-success me-2"></i> Informasi Klien</h6>
                            <p class="section-subtitle">Pilih klien dan lihat detail kontak secara otomatis di samping.</p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="client_id" class="form-label">
                                        <i class="fas fa-user-tie text-success me-1"></i> Pilih Klien <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" data-clients="{{ json_encode($clients->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}" required>
                                        <option value="">-- Pilih Klien --</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', $contract->client_id) == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }} - {{ $client->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted"><a href="{{ route('clients.create') }}" class="text-decoration-none">Tambah klien baru</a></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card bg-info bg-opacity-10 border-info h-100">
                                        <div class="card-body p-3">
                                            <small class="text-muted d-block mb-2">Info Klien Terpilih:</small>
                                            <div id="clientInfo" class="text-muted">
                                                @if ($contract->client)
                                                    <div class="mb-2"><strong>{{ $contract->client->name }}</strong></div>
                                                    <div class="mb-1"><i class="fas fa-envelope fa-sm me-1"></i> {{ $contract->client->email }}</div>
                                                    @if ($contract->client->phone)
                                                        <div class="mb-1"><i class="fas fa-phone fa-sm me-1"></i> {{ $contract->client->phone }}</div>
                                                    @endif
                                                    @if ($contract->client->address)
                                                        <div class="mb-1"><i class="fas fa-map-marker-alt fa-sm me-1"></i> {{ Str::limit($contract->client->address, 60) }}</div>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Pilih klien untuk melihat detail</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border section-card">
                            <h6><i class="fas fa-calendar-alt text-warning me-2"></i> Periode Kontrak</h6>
                            <p class="section-subtitle">Atur tanggal mulai, berakhir, durasi, dan masa tenggang secara jelas.</p>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">
                                        <i class="fas fa-play-circle text-success me-1"></i> Tanggal Mulai <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">
                                        <i class="fas fa-stop-circle text-danger me-1"></i> Tanggal Berakhir <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $contract->end_date->format('Y-m-d')) }}" required>
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
                                    <input type="number" class="form-control @error('grace_period_days') is-invalid @enderror" id="grace_period_days" name="grace_period_days" value="{{ old('grace_period_days', $contract->grace_period_days) }}" min="1" max="90">
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

                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border section-card">
                            <h6><i class="fas fa-file-upload text-info me-2"></i> Upload File Kontrak (PDF)</h6>
                            <p class="section-subtitle">Unggah dokumen PDF kontrak dan kelola file tersimpan dengan mudah.</p>

                            <div class="mb-3">
                                <label for="pdf_files" class="form-label">
                                    <i class="fas fa-file-pdf text-danger me-1"></i> Dokumen PDF
                                </label>
                                <div class="input-group">
                                    <input type="file" class="form-control @error('pdf_files.*') is-invalid @enderror" id="pdf_files" name="pdf_files[]" accept=".pdf" multiple data-max-size="50000000">
                                    <button class="btn btn-outline-secondary" type="button" id="clearAllPdfBtn" style="display: none;"><i class="fas fa-times"></i> Hapus Semua</button>
                                </div>
                                @error('pdf_files.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Format: PDF • Ukuran max per file: 50 MB</small>
                            </div>

                            <div id="newFilesPreview" class="mb-3" style="display:none;">
                                <label class="form-label mb-2"><i class="fas fa-paperclip me-1"></i> File Baru yang Dipilih</label>
                                <div class="list-group" id="newFilesList"></div>
                            </div>

                            @if (!empty($contract->pdf_files) && is_array($contract->pdf_files))
                                <div class="mb-3">
                                    <label class="form-label mb-2 d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-list me-1"></i> File PDF yang Tersimpan</span>
                                        <small class="existing-files-counter">({{ count($contract->pdf_files) }})</small>
                                    </label>
                                    <div class="list-group" id="existingPdfsContainer">
                                        @foreach ($contract->pdf_files as $pdfPath)
                                            @php
                                                if (!is_string($pdfPath) || empty($pdfPath)) {
                                                    continue;
                                                }
                                                $fileName = basename($pdfPath);
                                                $fileSize = 0;
                                                $fileSizeFormat = 'Unknown';
                                                try {
                                                    if (\Storage::disk('public')->exists($pdfPath)) {
                                                        $fileSize = \Storage::disk('public')->size($pdfPath);
                                                        $fileSizeFormat = $fileSize > 0 ? number_format($fileSize / 1024 / 1024, 2) . ' MB' : 'Unknown';
                                                    }
                                                } catch (\Exception $e) {
                                                    $fileSizeFormat = 'Error';
                                                }
                                            @endphp
                                            <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center pdf-file-item" data-pdf-path="{{ $pdfPath }}">
                                                <div class="flex-grow-1 mb-3 mb-md-0">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                                        <div>
                                                            <div class="fw-semibold text-truncate">{{ $fileName }}</div>
                                                            <small class="text-muted">Ukuran: {{ $fileSizeFormat }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <a href="{{ route('contracts.download-pdf', $contract) }}?file={{ urlencode($fileName) }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="fas fa-download me-1"></i> Download</a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-pdf-btn" data-pdf-path="{{ $pdfPath }}" data-file-name="{{ $fileName }}"><i class="fas fa-trash me-1"></i> Hapus</button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="existing_pdf_files[]" value="{{ $pdfPath }}">
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div id="removedPdfFilesContainer"></div>

                            <div class="alert alert-info alert-custom" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                Unggah file PDF untuk menyimpan dokumen kontrak dengan lengkap. Anda bisa memilih beberapa file sekaligus.
                            </div>
                        </div>

                        <div class="mb-4 px-3 py-3 rounded-3 bg-light border section-card">
                            <h6><i class="fas fa-cogs text-secondary me-2"></i> Informasi Tambahan</h6>
                            <p class="section-subtitle">Atur status kontrak, tanggal akhir aktual, dan alasan penghentian jika diperlukan.</p>

                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    <i class="fas fa-tag me-1"></i> Status Kontrak
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="active" {{ old('status', $contract->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="grace_period" {{ old('status', $contract->status) == 'grace_period' ? 'selected' : '' }}>Masa Tenggang</option>
                                    <option value="expired" {{ old('status', $contract->status) == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                    <option value="terminated" {{ old('status', $contract->status) == 'terminated' ? 'selected' : '' }}>Dihentikan</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- <div class="mb-3">
                                <label for="contract_term" class="form-label">Termin Kontrak</label>
                                <select class="form-select @error('contract_term') is-invalid @enderror" id="contract_term" name="contract_term">
                                    <option value="">Pilih termin (opsional)</option>
                                    <option value="termin_1" {{ old('contract_term', $contract->contract_term) == 'termin_1' ? 'selected' : '' }}>Termin I</option>
                                    <option value="termin_2" {{ old('contract_term', $contract->contract_term) == 'termin_2' ? 'selected' : '' }}>Termin II</option>
                                    <option value="termin_3" {{ old('contract_term', $contract->contract_term) == 'termin_3' ? 'selected' : '' }}>Termin III</option>
                                    <option value="termin_4" {{ old('contract_term', $contract->contract_term) == 'termin_4' ? 'selected' : '' }}>Termin IV</option>
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
                                    <input type="date" class="form-control @error('actual_end_date') is-invalid @enderror" id="actual_end_date" name="actual_end_date" value="{{ old('actual_end_date', optional($contract->actual_end_date)->format('Y-m-d')) }}">
                                    @error('actual_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="termination_reason" class="form-label">
                                        <i class="fas fa-comment-dots text-warning me-1"></i> Alasan Penghentian
                                    </label>
                                    <input type="text" class="form-control @error('termination_reason') is-invalid @enderror" id="termination_reason" name="termination_reason" value="{{ old('termination_reason', $contract->termination_reason) }}" placeholder="Alasan penghentian kontrak...">
                                    @error('termination_reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 border-top pt-4 action-buttons">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                                <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload();">
                                    <i class="fas fa-redo me-2"></i> Reset
                                </button>
                            </div>
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <button type="button" class="btn btn-danger" onclick="showDeleteModal()">
                                    <i class="fas fa-trash me-2"></i> Hapus
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-info bg-opacity-10 border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-white h-100">
                                <h6 class="mb-3"><i class="fas fa-lightbulb text-warning me-2"></i> Tips</h6>
                                <ul class="mb-0 text-muted" style="font-size: 0.95rem;">
                                    <li>Pastikan nomor kontrak tetap unik</li>
                                    <li>Periksa kembali tanggal mulai dan berakhir</li>
                                    <li>Gunakan masa tenggang sesuai kebijakan</li>
                                    <li>Unggah dokumen PDF sesuai kebutuhan</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-white h-100">
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

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kontrak ini?</p>
                    <div class="alert alert-warning alert-custom d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div><strong>Perhatian:</strong> Data yang dihapus tidak dapat dikembalikan.</div>
                    </div>
                    <p><strong>Kontrak:</strong> {{ $contract->contract_number }} - {{ $contract->contract_name }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteContractBtn" data-action="{{ route('contracts.destroy', $contract) }}" data-index-url="{{ route('contracts.index') }}" data-id="{{ $contract->id }}">Hapus Kontrak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

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
            refreshBoq();
        })();
<<<<<<< HEAD

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
=======
>>>>>>> 8913ad198d6cee56e8e071c1e7ad62e855d82fe8
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clientSelect = document.getElementById('client_id');
            const clientInfo = document.getElementById('clientInfo');
            const durationDisplay = document.getElementById('durationDisplay');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const contractNumberInput = document.getElementById('contract_number');
            const generateButton = document.getElementById('generateContractNumberBtn');
            const pdfFilesInput = document.getElementById('pdf_files');
            const newFilesPreview = document.getElementById('newFilesPreview');
            const newFilesList = document.getElementById('newFilesList');
            const clearAllPdfBtn = document.getElementById('clearAllPdfBtn');
            const existingPdfsContainer = document.getElementById('existingPdfsContainer');
            const contractForm = document.getElementById('contractForm');
            const submitBtn = document.getElementById('submitBtn');
            const contractNumberValid = document.getElementById('contractNumberValid');
            const clientValid = document.getElementById('clientValid');
            const datesValid = document.getElementById('datesValid');
            const durationValid = document.getElementById('durationValid');
            const removedPdfFilesContainer = document.getElementById('removedPdfFilesContainer');
            const clientsData = JSON.parse(clientSelect.dataset.clients || '[]');
            let removedPdfPaths = [];

            function updateClientInfo() {
                const selectedClientId = parseInt(clientSelect.value);
                const client = clientsData.find(c => c.id === selectedClientId);

                if (client) {
                    clientInfo.innerHTML = `
                        <div class="mb-2"><strong>${client.name}</strong></div>
                        <div class="mb-1"><i class="fas fa-envelope fa-sm me-1"></i> ${client.email}</div>
                        ${client.phone ? `<div class="mb-1"><i class="fas fa-phone fa-sm me-1"></i> ${client.phone}</div>` : ''}
                        ${client.address ? `<div class="mb-1"><i class="fas fa-map-marker-alt fa-sm me-1"></i> ${client.address}</div>` : ''}
                    `;
                    clientValid.className = 'text-success';
                    clientValid.textContent = '✓';
                } else {
                    clientInfo.innerHTML = '<span class="text-muted">Pilih klien untuk melihat detail</span>';
                    clientValid.className = 'text-muted';
                    clientValid.textContent = '○';
                }
            }

            function calculateDuration() {
                if (!startDateInput.value || !endDateInput.value) {
                    durationDisplay.innerHTML = '<span class="text-muted">Pilih tanggal untuk menghitung durasi</span>';
                    datesValid.className = 'text-muted';
                    datesValid.textContent = '○';
                    durationValid.className = 'text-muted';
                    durationValid.textContent = '○';
                    return;
                }

                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (startDate >= endDate) {
                    durationDisplay.innerHTML = '<span class="text-danger">Tanggal berakhir harus setelah tanggal mulai</span>';
                    datesValid.className = 'text-danger';
                    datesValid.textContent = '✗';
                    durationValid.className = 'text-danger';
                    durationValid.textContent = '✗';
                    return;
                }

                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const years = Math.floor(diffDays / 365);
                const months = Math.floor((diffDays % 365) / 30);
                const days = diffDays % 30;
                let durationText = `${diffDays} hari`;
                if (years > 0) {
                    durationText = `${years} tahun, ${months} bulan, ${days} hari`;
                } else if (months > 0) {
                    durationText = `${months} bulan, ${days} hari`;
                }

                durationDisplay.innerHTML = `<strong>${durationText}</strong>`;
                datesValid.className = 'text-success';
                datesValid.textContent = '✓';
                durationValid.className = 'text-success';
                durationValid.textContent = '✓';
            }

            function updateContractNumberStatus() {
                if (contractNumberInput.value.trim()) {
                    contractNumberValid.className = 'text-success';
                    contractNumberValid.textContent = '✓';
                } else {
                    contractNumberValid.className = 'text-danger';
                    contractNumberValid.textContent = '✗';
                }
            }

            function displayNewFiles() {
                const files = Array.from(pdfFilesInput.files);
                newFilesList.innerHTML = '';
                if (!files.length) {
                    newFilesPreview.style.display = 'none';
                    clearAllPdfBtn.style.display = 'none';
                    return;
                }

                clearAllPdfBtn.style.display = 'inline-flex';
                files.forEach(file => {
                    const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                    const item = document.createElement('div');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center';
                    item.innerHTML = `
                        <div>
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            <span class="fw-semibold">${file.name}</span>
                        </div>
                        <small class="text-muted">${sizeMB} MB</small>
                    `;
                    newFilesList.appendChild(item);
                });
                newFilesPreview.style.display = 'block';
            }

            function updateRemovedInputs() {
                // Clear existing hidden inputs
                removedPdfFilesContainer.innerHTML = '';
                
                // Create new hidden inputs for each removed file
                removedPdfPaths.forEach(path => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'remove_pdf_files[]';
                    input.value = path;
                    removedPdfFilesContainer.appendChild(input);
                });
                
                // Update counter display
                const counter = document.querySelector('.existing-files-counter');
                if (counter) {
                    const remaining = document.querySelectorAll('.pdf-file-item:not(.deleted)').length;
                    counter.textContent = `(${remaining})`;
                }
            }

            function resetPdfSelection() {
                pdfFilesInput.value = '';
                displayNewFiles();
            }

            clientSelect.addEventListener('change', updateClientInfo);
            startDateInput.addEventListener('change', function() {
                if (this.value) {
                    endDateInput.min = this.value;
                }
                calculateDuration();
            });
            endDateInput.addEventListener('change', calculateDuration);
            contractNumberInput.addEventListener('blur', updateContractNumberStatus);
            generateButton.addEventListener('click', function() {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                contractNumberInput.value = `CTR-${year}${month}-${random}`;
                updateContractNumberStatus();
            });
            pdfFilesInput.addEventListener('change', displayNewFiles);
            clearAllPdfBtn.addEventListener('click', resetPdfSelection);

            if (existingPdfsContainer) {
                existingPdfsContainer.addEventListener('click', function(event) {
                    const removeBtn = event.target.closest('.remove-pdf-btn');
                    if (!removeBtn) return;
                    const pdfPath = removeBtn.getAttribute('data-pdf-path');
                    const fileName = removeBtn.getAttribute('data-file-name');
                    const fileItem = removeBtn.closest('.pdf-file-item');
                    if (!fileItem) return;
                    if (!confirm(`Hapus file \"${fileName}\" dari kontrak?`)) return;
                    removedPdfPaths.push(pdfPath);
                    fileItem.classList.add('deleted');
                    fileItem.style.display = 'none';
                    updateRemovedInputs();
                });
            }

            contractForm.addEventListener('submit', function(event) {
                // Validate dates
                if (!startDateInput.value || !endDateInput.value) {
                    event.preventDefault();
                    alert('Tanggal mulai dan tanggal berakhir wajib diisi.');
                    return false;
                }
                
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (startDate >= endDate) {
                    event.preventDefault();
                    alert('Tanggal berakhir harus setelah tanggal mulai.');
                    return false;
                }

                // Validate contract number
                if (!contractNumberInput.value.trim()) {
                    event.preventDefault();
                    alert('Nomor kontrak wajib diisi.');
                    return false;
                }
                
                // Validate client
                if (!clientSelect.value) {
                    event.preventDefault();
                    alert('Pilih klien terlebih dahulu.');
                    return false;
                }
                
                // Disable submit button and show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                submitBtn.disabled = true;
                return true;
            });

            updateClientInfo();
            calculateDuration();
            updateContractNumberStatus();
            updateRemovedInputs();

            window.showDeleteModal = function() {
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            };

            const confirmDeleteContractBtn = document.getElementById('confirmDeleteContractBtn');
            if (confirmDeleteContractBtn) {
                confirmDeleteContractBtn.addEventListener('click', function() {
                    const action = this.dataset.action;
                    const indexUrl = this.dataset.indexUrl;
                    if (!action) return;
                    if (!confirm('Hapus kontrak ini? Tindakan ini tidak dapat dibatalkan.')) return;
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menghapus...';
                    fetch(action, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Request failed');
                        return response.json().catch(() => ({}));
                    })
                    .then(() => {
                        window.location.href = indexUrl;
                    })
                    .catch(() => {
                        alert('Gagal menghapus kontrak. Silakan coba lagi.');
                        this.disabled = false;
                        this.innerHTML = 'Hapus Kontrak';
                    });
                });
            }
        });
    </script>
@endsection
