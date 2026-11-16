@extends('layouts.dashboard')

@section('title', 'BOQ Kontrak')
@section('subtitle', 'Rincian kuantitas dan harga seluruh kontrak')

@push('styles')
    <style>
        .boq-page {
            --boq-navy: #102a43;
            --boq-blue: #1d4e89;
            --boq-pelindo: #0072bc;
            --boq-muted: #627d98;
            --boq-line: #dce7f1;
            padding: clamp(0.75rem, 1.8vw, 1.5rem);
            border: 1px solid #e4ebf3;
            border-radius: 8px;
            background: #f3f7fa;
            color: var(--boq-navy);
        }

        .boq-stat-card {
            border: 1px solid var(--boq-line) !important;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(16, 42, 67, 0.05) !important;
        }

        .boq-stat-card .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.1rem 1.2rem;
        }

        .boq-stat-label {
            color: var(--boq-muted);
            font-size: 0.85rem;
        }

        .boq-stat-value {
            margin-top: 0.2rem;
            color: var(--boq-navy);
            font-size: 1.4rem;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .boq-stat-icon {
            display: grid;
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            place-items: center;
            border-radius: 6px;
            background: #e8f4fb;
            color: var(--boq-pelindo);
            font-size: 1.1rem;
        }

        .boq-panel {
            overflow: hidden;
            border: 1px solid var(--boq-line) !important;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(16, 42, 67, 0.05) !important;
        }

        .boq-panel-header {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--boq-line);
            background: #fff !important;
            color: var(--boq-navy);
        }

        .boq-panel-header h5 {
            color: var(--boq-navy);
            font-weight: 700;
        }

        .boq-panel-header h5 i {
            color: var(--boq-pelindo);
        }

        .boq-panel-header small {
            color: var(--boq-muted) !important;
        }

        .boq-search {
            width: min(100%, 460px);
        }

        .boq-search .form-control {
            min-width: 0;
            border-color: #cbd9e6;
        }

        .boq-search .form-control:focus {
            border-color: var(--boq-pelindo);
            box-shadow: 0 0 0 0.2rem rgba(0, 114, 188, 0.14);
        }

        .boq-search .btn {
            min-width: 42px;
            border-color: var(--boq-pelindo);
            color: var(--boq-pelindo);
        }

        .boq-search .btn:hover {
            background: var(--boq-pelindo);
            color: #fff;
        }

        .boq-desktop-table {
            overflow-x: auto;
        }

        .boq-table {
            min-width: 1120px;
            color: var(--boq-navy);
        }

        .boq-table thead th {
            padding: 0.85rem 0.75rem;
            border-bottom: 1px solid var(--boq-line);
            background: #edf5fa;
            color: var(--boq-blue);
            font-size: 0.82rem;
            white-space: nowrap;
        }

        .boq-table tbody td {
            padding: 0.85rem 0.75rem;
            border-color: #edf2f7;
            vertical-align: middle;
            overflow-wrap: anywhere;
            word-break: normal;
        }

        .boq-table tbody tr:hover > * {
            background: #f4fbfe;
        }

        .boq-table a,
        .boq-mobile-item a {
            color: var(--boq-pelindo);
        }

        .boq-action-view,
        .boq-action-edit {
            border-color: var(--boq-pelindo);
            color: var(--boq-pelindo);
        }

        .boq-action-view:hover,
        .boq-action-edit:hover {
            border-color: var(--boq-pelindo);
            background: var(--boq-pelindo);
            color: #fff;
        }

        .boq-mobile-list {
            background: #fff;
        }

        .boq-mobile-item {
            padding: 1rem;
            border-bottom: 1px solid var(--boq-line);
        }

        .boq-mobile-item:last-child {
            border-bottom: 0;
        }

        .boq-mobile-item h6 {
            overflow-wrap: anywhere;
        }

        .boq-mobile-label {
            display: block;
            margin-bottom: 0.2rem;
            color: var(--boq-muted);
            font-size: 0.75rem;
        }

        .boq-mobile-facts {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem 1rem;
            margin-top: 1rem;
        }

        .boq-mobile-fact {
            min-width: 0;
        }

        .boq-mobile-fact-value {
            overflow-wrap: anywhere;
            font-weight: 600;
            color: var(--boq-navy);
        }

        .boq-mobile-contract {
            grid-column: 1 / -1;
        }

        .boq-mobile-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .boq-panel-footer {
            padding: 0.85rem 1rem;
            border-top: 1px solid var(--boq-line);
            background: #fff;
        }

        .boq-pagination-meta {
            color: var(--boq-muted);
            font-size: 0.85rem;
        }

        .boq-per-page-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .boq-per-page-form label {
            color: var(--boq-muted);
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .boq-per-page-form select {
            width: auto;
            min-width: 76px;
            border-color: #cbd9e6;
        }

        .boq-pagination-links .pagination {
            margin-bottom: 0;
        }

        .boq-panel-footer .page-link {
            color: var(--boq-blue);
        }

        .boq-panel-footer .active > .page-link {
            border-color: var(--boq-pelindo);
            background: var(--boq-pelindo);
            color: #fff;
        }

        @media (max-width: 1199.98px) {
            .boq-search {
                width: 100%;
            }

            .boq-search .form-control {
                min-width: 0;
                flex: 1;
            }
        }
    </style>
@endpush

@section('content')
    <div class="boq-page">
    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <div class="card boq-stat-card h-100">
                <div class="card-body">
                    <div>
                        <div class="boq-stat-label">Jumlah item sesuai filter</div>
                        <div class="boq-stat-value">{{ number_format($totalItems, 0, ',', '.') }}</div>
                    </div>
                    <span class="boq-stat-icon"><i class="fas fa-list-ol"></i></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card boq-stat-card h-100">
                <div class="card-body">
                    <div>
                        <div class="boq-stat-label">Total nilai BOQ sesuai filter</div>
                        <div class="boq-stat-value">Rp {{ number_format((float) $totalValue, 2, ',', '.') }}</div>
                    </div>
                    <span class="boq-stat-icon"><i class="fas fa-coins"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card boq-panel">
        <div class="card-header boq-panel-header d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div>
                <h5 class="mb-1"><i class="fas fa-list-ol text-primary me-2"></i>Daftar BOQ Kontrak</h5>
                <small class="text-muted">Setiap item terhubung langsung ke kontrak sumber.</small>
            </div>
            <form method="GET" action="{{ route('boq.index') }}" class="boq-search d-flex gap-2">
                <input type="search" name="q" class="form-control" value="{{ $search }}"
                    placeholder="Cari item, kontrak, vendor" aria-label="Cari BOQ">
                <button type="submit" class="btn btn-outline-primary" title="Cari BOQ" aria-label="Cari BOQ">
                    <i class="fas fa-search"></i>
                </button>
                @if ($search !== '')
                    <a href="{{ route('boq.index') }}" class="btn btn-outline-secondary" title="Bersihkan pencarian" aria-label="Bersihkan pencarian">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
        <div class="boq-desktop-table d-none d-xl-block table-responsive">
            <table class="boq-table table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Kontrak</th>
                        <th>Kontrak</th>
                        <th>Vendor</th>
                        <th>Uraian</th>
                        <th>Satuan</th>
                        <th class="text-end">Kuantitas</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>
                                @if ($item->contract)
                                    <a href="{{ route('contracts.show', $item->contract) }}" class="fw-semibold text-decoration-none">
                                        {{ $item->contract->contract_number }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->contract->contract_name ?? 'Kontrak tidak tersedia' }}</td>
                            <td>{{ $item->contract->client->name ?? '-' }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->unit }}</td>
                            <td class="text-end">{{ $item->formatted_quantity }}</td>
                            <td class="text-end text-nowrap">Rp {{ number_format((float) $item->unit_price, 2, ',', '.') }}</td>
                            <td class="text-end text-nowrap fw-semibold">Rp {{ number_format((float) $item->subtotal, 2, ',', '.') }}</td>
                            <td class="text-center text-nowrap">
                                @if ($item->contract)
                                    <a href="{{ route('contracts.show', $item->contract) }}" class="btn btn-sm boq-action-view"
                                        title="Lihat kontrak" aria-label="Lihat kontrak {{ $item->contract->contract_number }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('contracts.edit', $item->contract) }}" class="btn btn-sm boq-action-edit"
                                        title="Edit kontrak dan BOQ" aria-label="Edit kontrak {{ $item->contract->contract_number }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-list-ol fa-2x text-muted mb-2"></i>
                                <div class="fw-semibold">Belum ada item BOQ</div>
                                <small class="text-muted">Tambahkan rincian BOQ melalui form kontrak.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="boq-mobile-list d-xl-none">
            @forelse ($items as $item)
                <article class="boq-mobile-item">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="min-w-0">
                            <span class="boq-mobile-label">Uraian</span>
                            <h6 class="mb-0">{{ $item->description }}</h6>
                        </div>
                        <div class="text-end">
                            <span class="boq-mobile-label">Satuan</span>
                            <strong>{{ $item->unit ?: '-' }}</strong>
                        </div>
                    </div>

                    <div class="boq-mobile-facts">
                        <div class="boq-mobile-fact boq-mobile-contract">
                            <span class="boq-mobile-label">Kontrak</span>
                            <div class="boq-mobile-fact-value">
                                @if ($item->contract)
                                    <a href="{{ route('contracts.show', $item->contract) }}" class="text-decoration-none">
                                        {{ $item->contract->contract_number }}
                                    </a>
                                    <span class="text-muted">{{ $item->contract->contract_name }}</span>
                                @else
                                    <span class="text-muted">Kontrak tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                        <div class="boq-mobile-fact">
                            <span class="boq-mobile-label">Vendor</span>
                            <div class="boq-mobile-fact-value">{{ $item->contract->client->name ?? '-' }}</div>
                        </div>
                        <div class="boq-mobile-fact">
                            <span class="boq-mobile-label">Kuantitas</span>
                            <div class="boq-mobile-fact-value">{{ $item->formatted_quantity }}</div>
                        </div>
                        <div class="boq-mobile-fact">
                            <span class="boq-mobile-label">Harga satuan</span>
                            <div class="boq-mobile-fact-value">Rp {{ number_format((float) $item->unit_price, 2, ',', '.') }}</div>
                        </div>
                        <div class="boq-mobile-fact">
                            <span class="boq-mobile-label">Jumlah</span>
                            <div class="boq-mobile-fact-value">Rp {{ number_format((float) $item->subtotal, 2, ',', '.') }}</div>
                        </div>
                    </div>

                    @if ($item->contract)
                        <div class="boq-mobile-actions">
                            <a href="{{ route('contracts.show', $item->contract) }}" class="btn btn-sm boq-action-view"
                                title="Lihat kontrak" aria-label="Lihat kontrak {{ $item->contract->contract_number }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('contracts.edit', $item->contract) }}" class="btn btn-sm boq-action-edit"
                                title="Edit kontrak dan BOQ" aria-label="Edit kontrak {{ $item->contract->contract_number }}">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    @endif
                </article>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-list-ol fa-2x text-muted mb-2"></i>
                    <div class="fw-semibold">Belum ada item BOQ</div>
                    <small class="text-muted">Tambahkan rincian BOQ melalui form kontrak.</small>
                </div>
            @endforelse
        </div>
        @if ($items->total() > 0)
            <div class="card-footer boq-panel-footer">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <small class="boq-pagination-meta">
                        Menampilkan {{ $items->firstItem() ?? 0 }}-{{ $items->lastItem() ?? 0 }} dari
                        {{ number_format($items->total(), 0, ',', '.') }} item
                    </small>

                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-lg-end gap-3">
                        <form method="GET" action="{{ route('boq.index') }}" class="boq-per-page-form">
                            @if ($search !== '')
                                <input type="hidden" name="q" value="{{ $search }}">
                            @endif
                            <label for="boq-per-page">Baris</label>
                            <select id="boq-per-page" name="per_page" class="form-select form-select-sm"
                                onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $pageSize)
                                    <option value="{{ $pageSize }}" {{ $items->perPage() === $pageSize ? 'selected' : '' }}>
                                        {{ $pageSize }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        @if ($items->hasPages())
                            <nav class="boq-pagination-links" aria-label="Navigasi halaman BOQ">
                                {{ $items->links('pagination::bootstrap-5') }}
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>
@endsection