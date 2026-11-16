<!-- resources/views/dashboard/reports.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Laporan')
@section('subtitle', 'Analisis dan laporan kontrak')
@section('content')
    <style>
        .reports-page {
            --report-navy: #102a43;
            --report-blue: #1d4e89;
            --report-pelindo: #0072bc;
            --report-muted: #627d98;
            --report-line: #e5edf5;
            color: var(--report-navy);
        }

        .reports-page .report-panel,
        .reports-page .summary-card {
            overflow: hidden;
            border: 1px solid var(--report-line) !important;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(16, 42, 67, 0.05) !important;
        }

        .reports-page .report-toolbar {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--report-line);
            background: #fff;
        }

        .reports-page .report-toolbar h5,
        .reports-page .report-section-title {
            color: var(--report-navy);
            font-weight: 700;
        }

        .reports-page .report-toolbar h5 i,
        .reports-page .report-section-title i {
            color: var(--report-blue);
        }

        .reports-page .report-outline-button {
            border-color: var(--report-blue);
            color: var(--report-blue);
        }

        .reports-page .report-outline-button:hover {
            border-color: var(--report-blue);
            background: var(--report-blue);
            color: #fff;
        }

        .reports-page .card-body {
            padding: 1.1rem;
        }

        .reports-page .summary-card {
            height: 100%;
        }

        .reports-page .summary-card .card-body {
            min-height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.5rem;
        }

        .reports-page .summary-card .fa-3x {
            color: var(--report-blue) !important;
            font-size: 1.8rem;
        }

        .reports-page .summary-card h5 {
            color: var(--report-navy);
            font-size: 1rem;
            font-weight: 700;
        }

        .reports-page .summary-card p,
        .reports-page .summary-card small {
            margin-bottom: 0;
        }

        .reports-page .summary-item {
            min-height: 82px;
            border: 1px solid var(--report-line);
            border-radius: 7px !important;
            background: #f7f9fc !important;
        }

        .reports-page .summary-item .text-muted,
        .reports-page .text-muted {
            color: var(--report-muted) !important;
        }

        .reports-page .form-label {
            color: var(--report-navy);
            font-weight: 600;
        }

        .reports-page .form-control,
        .reports-page .form-select {
            min-height: 40px;
            border-color: #cbd8e5;
            border-radius: 6px;
        }

        .reports-page .form-control:focus,
        .reports-page .form-select:focus {
            border-color: var(--report-blue);
            box-shadow: 0 0 0 0.2rem rgba(29, 78, 137, 0.14);
        }

        .reports-page .report-filter-actions .btn {
            min-height: 40px;
        }

        .reports-page .report-chart {
            width: 100%;
            height: 280px;
        }

        .reports-page .report-table {
            margin-bottom: 0;
        }

        .reports-page .report-table thead th {
            border-bottom: 1px solid var(--report-line);
            background: #f4f7fb;
            color: var(--report-navy);
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .reports-page .report-table td {
            vertical-align: middle;
        }

        .reports-page .table-responsive {
            overflow-x: auto;
        }

        .reports-page .report-table .btn {
            white-space: nowrap;
        }

        .reports-page .progress {
            height: 8px;
            border-radius: 999px;
            background: #e8eef5;
        }

        .reports-page .progress-bar {
            border-radius: 999px;
        }

        @media (max-width: 768px) {
            .reports-page .report-toolbar {
                align-items: stretch !important;
                flex-direction: column;
            }

            .reports-page .report-chart {
                height: 250px;
            }

            .reports-page .report-table thead {
                display: none;
            }

            .reports-page .report-table tbody tr {
                display: block;
                margin-bottom: 0.8rem;
                padding: 0.5rem 0.8rem;
                border: 1px solid var(--report-line);
                border-radius: 8px;
                background: #fff;
            }

            .reports-page .report-table tbody tr:last-child {
                margin-bottom: 0;
            }

            .reports-page .report-table tbody td {
                display: grid;
                grid-template-columns: minmax(7rem, 38%) minmax(0, 1fr);
                gap: 0.5rem;
                padding: 0.55rem 0;
                border: 0;
                text-align: left !important;
                overflow-wrap: anywhere;
            }

            .reports-page .report-table tbody td::before {
                content: attr(data-label);
                color: var(--report-muted);
                font-size: 0.82rem;
                font-weight: 600;
            }

            .reports-page .report-table tbody td[colspan] {
                display: block;
            }

            .reports-page .report-table tbody td[colspan]::before {
                content: none;
            }
        }

        @media (max-width: 420px) {
            .reports-page .report-toolbar,
            .reports-page .card-body {
                padding: 0.9rem;
            }

            .reports-page .summary-card .card-body {
                min-height: 125px;
                padding: 0.8rem;
            }

            .reports-page .report-table tbody td {
                grid-template-columns: minmax(6.5rem, 40%) minmax(0, 1fr);
            }
        }

        @media print {
            .reports-page .report-filter-actions {
                display: none !important;
            }

            .reports-page .report-panel,
            .reports-page .summary-card {
                box-shadow: none !important;
            }
        }
    </style>

    <div class="reports-page">
    <div class="row g-4">
        <!-- Ringkasan Statistik -->
        <div class="col-12 fade-in">
            <div class="row g-3">
                <div class="col-6 col-md-4">
                    <div class="card border-0 shadow-sm summary-card">
                        <div class="card-body text-center">
                            <i class="fas fa-file-contract fa-3x text-primary mb-3"></i>
                            <h5>Total Kontrak</h5>
                            @php
                                $totalActive = $totalByStatus['active'] ?? null;
                                $totalGrace = $totalByStatus['grace_period'] ?? null;
                                $totalExpired = $totalByStatus['expired'] ?? null;
                                $totalCount = null;
                                if ($totalActive !== null || $totalGrace !== null || $totalExpired !== null) {
                                    $totalCount = ($totalActive ?? 0) + ($totalGrace ?? 0) + ($totalExpired ?? 0);
                                } else {
                                    $totalCount = \App\Models\Contract::count();
                                }
                            @endphp
                            <p class="text-muted">{{ $totalCount }}</p>
                            <div class="d-flex justify-content-center">
                                <span class="badge bg-success me-2">Aktif:
                                    {{ $totalActive ?? \App\Models\Contract::where('status', 'active')->count() }}</span>
                                <span class="badge bg-warning">Masa Tenggang:
                                    {{ $totalGrace ?? \App\Models\Contract::where('status', 'grace_period')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4">
                    <div class="card border-0 shadow-sm summary-card">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                            <h5>Akan Berakhir</h5>
                            <p class="text-muted">{{ $expiringSoon->count() ?? 0 }} kontrak</p>
                            <small>Dalam 60 hari ke depan</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4">
                    <div class="card border-0 shadow-sm summary-card">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <h5>Perlu Perhatian</h5>
                            <p class="text-muted">{{ $gracePeriodContracts->count() ?? 0 }} kontrak</p>
                            <small>Dalam masa tenggang</small>
                        </div>
                    </div>
                </div>

                <!-- Analisis card removed as requested -->
            </div>
        </div>

        <!-- Gabungkan: Filter, Grafik Distribusi, dan Statistik Singkat dalam satu card -->
        <div class="col-12 fade-in">
            <div class="card report-panel border-0 shadow-sm">
                <div class="card-header report-toolbar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Laporan & Statistik</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="report-section-title mb-0"><i class="fas fa-filter me-2"></i> Filter Laporan</h6>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-12 col-sm-6 col-lg-4">
                                            <label class="form-label small">Status</label>
                                            <select class="form-select form-select-sm" id="filterStatus">
                                                <option value="all">Semua Status</option>
                                                <option value="active">Aktif</option>
                                                <option value="grace_period">Masa Tenggang</option>
                                                <option value="expired">Kadaluarsa</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-sm-6 col-lg-4">
                                            <label class="form-label small">Tanggal Mulai</label>
                                            <input type="date" class="form-control form-control-sm" id="filterStartDate">
                                        </div>
                                        <div class="col-12 col-sm-6 col-lg-4">
                                            <label class="form-label small">Tanggal Akhir</label>
                                            <input type="date" class="form-control form-control-sm" id="filterEndDate">
                                        </div>
                                    </div>
                                    <div class="mt-3 d-flex flex-column flex-sm-row gap-2 report-filter-actions">
                                        <button class="btn btn-primary flex-grow-1" type="button" onclick="filterReports()"><i
                                                class="fas fa-search me-2"></i> <span class="d-none d-sm-inline">Terapkan</span> Filter</button>
                                        <button class="btn report-outline-button flex-grow-1" type="button" onclick="showAnalytics()"><i
                                                class="fas fa-chart-line me-2"></i> <span class="d-none d-sm-inline">Detail</span> Analisis</button>
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="report-chart">
                                        <canvas id="statusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 order-1 order-lg-2">
                            <div class="report-summary-panel">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                        <div>
                                            <h6 class="report-section-title mb-1"><i class="fas fa-info-circle me-2"></i> Statistik Singkat</h6>
                                        </div>

                                    </div>
                                                            <div class="row row-cols-1 row-cols-sm-2 g-2 report-summary-list">
                                        <div class="col">
                                            <div class="summary-item p-3 rounded-3 bg-light d-flex justify-content-between align-items-start gap-2">
                                                <div>
                                                    <div class="text-muted small">Total Kontrak</div>
                                                    <div class="fw-semibold fs-5">{{ ($totalByStatus['active'] ?? 0) + ($totalByStatus['grace_period'] ?? 0) + ($totalByStatus['expired'] ?? 0) }}</div>
                                                </div>
                                                <span class="badge bg-primary rounded-pill align-self-start">Total</span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="summary-item p-3 rounded-3 bg-light d-flex justify-content-between align-items-start gap-2">
                                                <div>
                                                    <div class="text-muted small">Aktif</div>
                                                    <div class="fw-semibold fs-5">{{ $totalByStatus['active'] ?? 0 }}</div>
                                                </div>
                                                <span class="badge bg-success rounded-pill align-self-start">Aktif</span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="summary-item p-3 rounded-3 bg-light d-flex justify-content-between align-items-start gap-2">
                                                <div>
                                                    <div class="text-muted small">Masa Tenggang</div>
                                                    <div class="fw-semibold fs-5">{{ $totalByStatus['grace_period'] ?? 0 }}</div>
                                                </div>
                                                <span class="badge bg-warning text-dark rounded-pill align-self-start">Tenggang</span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="summary-item p-3 rounded-3 bg-light d-flex justify-content-between align-items-start gap-2">
                                                <div>
                                                    <div class="text-muted small">Kadaluarsa</div>
                                                    <div class="fw-semibold fs-5">{{ $totalByStatus['expired'] ?? 0 }}</div>
                                                </div>
                                                <span class="badge bg-danger rounded-pill align-self-start">Expired</span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="summary-item p-3 rounded-3 bg-light d-flex justify-content-between align-items-start gap-2">
                                                <div>
                                                    <div class="text-muted small">Rata-rata Masa Tenggang</div>
                                                    <div class="fw-semibold fs-5">60 hari</div>
                                                </div>
                                                <span class="badge bg-info rounded-pill align-self-start">Rata-rata</span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="summary-item p-3 rounded-3 bg-light d-flex justify-content-between align-items-start gap-2">
                                                <div>
                                                    <div class="text-muted small">Kontrak Akan Berakhir</div>
                                                    <div class="fw-semibold fs-5">{{ $expiringSoon->count() ?? 0 }}</div>
                                                </div>
                                                <span class="badge bg-info rounded-pill align-self-start">Segera</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ ($totalByStatus['active'] ?? 0) > 0 ? (($totalByStatus['active'] ?? 0) / (($totalByStatus['active'] ?? 0) + ($totalByStatus['grace_period'] ?? 0) + ($totalByStatus['expired'] ?? 0))) * 100 : 0 }}%">
                                        </div>
                                        <div class="progress-bar bg-warning"
                                            style="width: {{ ($totalByStatus['grace_period'] ?? 0) > 0 ? (($totalByStatus['grace_period'] ?? 0) / (($totalByStatus['active'] ?? 0) + ($totalByStatus['grace_period'] ?? 0) + ($totalByStatus['expired'] ?? 0))) * 100 : 0 }}%">
                                        </div>
                                        <div class="progress-bar bg-danger"
                                            style="width: {{ ($totalByStatus['expired'] ?? 0) > 0 ? (($totalByStatus['expired'] ?? 0) / (($totalByStatus['active'] ?? 0) + ($totalByStatus['grace_period'] ?? 0) + ($totalByStatus['expired'] ?? 0))) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">Distribusi: Aktif (Hijau), Masa Tenggang (Kuning),
                                            Kadaluarsa (Merah)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Kontrak Akan Berakhir -->
        <div class="col-12 fade-in mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-times text-info me-2"></i> Kontrak Akan Berakhir (60
                        Hari)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Kontrak</th>
                                    <th>Nama Kontrak</th>
                                    <th>Klien</th>
                                    <th>Tanggal Berakhir</th>
                                    <th>Sisa Hari</th>
                                    <th>Status</th>
                                    <th>Rekomendasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expiringSoon ?? [] as $contract)
                                    @php
                                        $daysRemaining = $contract->days_remaining ?? 0;
                                        $recommendation = 'Monitor Rutin';
                                        $recClass = 'success';

                                        if ($daysRemaining < 7) {
                                            $recommendation = 'Perpanjang Segera';
                                            $recClass = 'danger';
                                        } elseif ($daysRemaining < 14) {
                                            $recommendation = 'Persiapan Perpanjangan';
                                            $recClass = 'warning';
                                        }
                                    @endphp
                                    <tr>
                                        <td data-label="No. Kontrak"><strong class="text-primary">#{{ $contract->contract_number }}</strong></td>
                                        <td data-label="Nama Kontrak">{{ $contract->contract_name }}</td>
                                        <td data-label="Klien">
                                            <div>{{ $contract->client->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $contract->client->email ?? '' }}</small>
                                        </td>
                                        <td data-label="Tanggal Berakhir">
                                            <div>{{ optional($contract->end_date)->format('d/m/Y') ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ optional($contract->end_date)->diffForHumans() ?? '' }}</small>
                                        </td>
                                        <td data-label="Sisa Hari">
                                            <span class="badge bg-{{ $daysRemaining < 7 ? 'danger' : ($daysRemaining < 14 ? 'warning' : 'info') }}">
                                                {{ number_format($daysRemaining, 0) }} hari
                                            </span>
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Aktif
                                            </span>
                                        </td>
                                        <td data-label="Rekomendasi">
                                            <span class="badge bg-{{ $recClass }}">
                                                {{ $recommendation }}
                                            </span>
                                        </td>
                                        <td data-label="Aksi">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('contracts.show', $contract) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-calendar-check text-success fa-3x mb-3"></i>
                                            <h5 class="text-muted">Tidak ada kontrak yang akan berakhir</h5>
                                            <p class="text-muted mb-0">Tidak ada kontrak yang berakhir dalam 60 hari ke
                                                depan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Kontrak Kadaluarsa -->
        <div class="col-12 fade-in mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-times-circle text-danger me-2"></i> Kontrak Kadaluarsa</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Kontrak</th>
                                    <th>Nama Kontrak</th>
                                    <th>Klien</th>
                                    <th>Tanggal Berakhir</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expiredContracts ?? [] as $contract)
                                    <tr>
                                        <td data-label="No. Kontrak"><strong class="text-danger">#{{ $contract->contract_number }}</strong></td>
                                        <td data-label="Nama Kontrak">{{ $contract->contract_name }}</td>
                                        <td data-label="Klien">{{ $contract->client->name ?? 'N/A' }}</td>
                                        <td data-label="Tanggal Berakhir">
                                            <div>{{ optional($contract->end_date)->format('d/m/Y') ?? 'N/A' }}</div>
                                            <small class="text-muted">Telah kadaluarsa
                                                {{ optional($contract->end_date)->diffForHumans() ?? '' }}</small>
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i> Kadaluarsa
                                            </span>
                                        </td>
                                        <td data-label="Keterangan">
                                            <small
                                                class="text-muted">{{ $contract->termination_reason ?? 'Tidak ada keterangan' }}</small>
                                        </td>
                                        <td data-label="Aksi">
                                            <a href="{{ route('contracts.show', $contract) }}"
                                                class="btn btn-sm btn-outline-primary w-100 w-md-auto">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-check text-success fa-3x mb-3"></i>
                                            <h5 class="text-muted">Tidak ada kontrak kadaluarsa</h5>
                                            <p class="text-muted mb-0">Semua kontrak dalam kondisi baik</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan dan Summary -->

    </div>

    <style>
        /* Responsive tweaks for reports page */
        .summary-card .card-body {
            min-height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
        }

        .summary-card .badge,
        .card .card-body .badge {
            white-space: normal;
            word-break: break-word;
        }

        .summary-card .card-body p,
        .summary-card .card-body small {
            margin-bottom: 0;
        }

        .card-header h5 {
            font-size: 1rem;
            margin-bottom: 0;
        }

        .card-header .btn {
            white-space: nowrap;
        }

        .filter-section {
            gap: 1rem;
        }

        .row.g-3 {
            align-items: stretch;
        }

        .summary-card {
            height: 100%;
        }

        .table-responsive {
            overflow-x: hidden;
        }

        @media (max-width: 992px) {
            .summary-card .fa-3x {
                font-size: 1.8rem;
            }

            .summary-card h5 {
                font-size: 1.05rem;
            }

            .summary-card .card-body {
                min-height: 140px;
            }
        }

        @media (max-width: 768px) {
            .card-header,
            .card-body {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .card-header .d-flex,
            .card-body .d-flex {
                flex-direction: column;
                align-items: stretch;
            }

            .card-header .d-flex .btn,
            .card-header .dropdown,
            .card-header .dropdown .btn,
            .card-body .btn {
                width: 100%;
            }

            .summary-card .card-body {
                min-height: auto;
                padding: 1rem;
            }

            .report-summary-list {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .report-summary-list .summary-item {
                background: #f8f9fa;
                padding: 1rem;
                border-radius: .95rem;
                border: 1px solid rgba(0,0,0,.06);
            }

            .report-summary-list .summary-item .text-muted {
                display: block;
                margin-bottom: .35rem;
            }

            .report-summary-list .summary-item .badge {
                align-self: flex-start;
            }

            .table-responsive {
                font-size: 0.95rem;
            }

            .table-responsive table tbody tr {
                padding: 1rem .75rem;
            }

            .table-responsive .btn {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .summary-card .fa-3x {
                font-size: 1.4rem;
            }

            .summary-card h5 {
                font-size: 0.98rem;
            }

            .summary-card .card-body {
                min-height: 120px;
                padding: 1rem;
            }

            #statusChart {
                height: 220px !important;
            }

            .btn-group,
            .d-flex.gap-2 {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .table-responsive {
                font-size: 0.92rem;
            }

            .table-responsive table tbody tr td {
                flex-direction: column;
                align-items: stretch;
            }

            .table-responsive table tbody tr td::before {
                margin-bottom: .35rem;
            }
        }
    </style>

    @section('scripts')
        <script>
            // Chart untuk distribusi status (cek keberadaan elemen terlebih dahulu)
            const statusCanvas = document.getElementById('statusChart');

            @php
                $activeCount = $totalByStatus['active'] ?? 0;
                $graceCount = $totalByStatus['grace_period'] ?? 0;
                $expiredCount = $totalByStatus['expired'] ?? 0;
                $totalCount = $activeCount + $graceCount + $expiredCount;
            @endphp

            if (statusCanvas) {
                const statusCtx = statusCanvas.getContext('2d');

                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Aktif', 'Masa Tenggang', 'Kadaluarsa'],
                        datasets: [{
                            data: [{{ $activeCount }}, {{ $graceCount }}, {{ $expiredCount }}],
                            backgroundColor: [
                                'rgba(46, 204, 113, 0.8)',
                                'rgba(243, 156, 18, 0.8)',
                                'rgba(231, 76, 60, 0.8)'
                            ],
                            borderColor: [
                                '#2ecc71',
                                '#f39c12',
                                '#e74c3c'
                            ],
                            borderWidth: 2,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const percentage =
                                            {{ $totalCount > 0 ? 'Math.round((value / ' . $totalCount . ') * 100)' : '0' }};
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Filter Reports — submit filters as query params so backend can return filtered distribution
            function filterReports() {
                const status = document.getElementById('filterStatus').value;
                const startDate = document.getElementById('filterStartDate').value;
                const endDate = document.getElementById('filterEndDate').value;

                // Build query string and navigate — server should handle these params and return filtered data
                const params = new URLSearchParams();
                if (status && status !== 'all') params.set('status', status);
                if (startDate) params.set('start_date', startDate);
                if (endDate) params.set('end_date', endDate);

                const base = window.location.pathname;
                showLoading('Menerapkan filter...');
                // navigate after brief delay to allow loading indicator to show
                setTimeout(() => {
                    window.location.href = `${base}?${params.toString()}`;
                }, 250);
            }

            function sendReminder(contractId) {
                if (confirm('Kirim reminder untuk kontrak ini?')) {
                    showLoading('Mengirim reminder...');

                    // Simulate API call
                    setTimeout(() => {
                        hideLoading();
                        showToast('Reminder berhasil dikirim', 'success');
                    }, 1000);
                }
            }

            function showAnalytics() {
                // Redirect to analytics page
                window.location.href = '{{ route("analytics") }}';
            }

            // Utility Functions
            function showLoading(message) {
                let loading = document.getElementById('loadingOverlay');
                if (!loading) {
                    loading = document.createElement('div');
                    loading.id = 'loadingOverlay';
                    loading.className =
                        'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
                    loading.style.backgroundColor = 'rgba(0,0,0,0.7)';
                    loading.style.zIndex = '9999';
                    loading.innerHTML = `
                <div class="text-center text-white">
                    <div class="spinner-border mb-3" role="status"></div>
                    <p class="mb-0">${message}</p>
                </div>
            `;
                    document.body.appendChild(loading);
                }
            }

            function hideLoading() {
                const loading = document.getElementById('loadingOverlay');
                if (loading) {
                    loading.remove();
                }
            }

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
                    
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }

            // Set default dates for filters (use URL params when present)
            document.addEventListener('DOMContentLoaded', function() {
                const params = new URLSearchParams(window.location.search);
                const today = new Date();
                const lastMonth = new Date();
                lastMonth.setMonth(today.getMonth() - 1);

                const startParam = params.get('start_date');
                const endParam = params.get('end_date');
                const statusParam = params.get('status');

                document.getElementById('filterStartDate').value = startParam || lastMonth.toISOString().split('T')[0];
                document.getElementById('filterEndDate').value = endParam || today.toISOString().split('T')[0];
                if (statusParam) {
                    const sel = document.getElementById('filterStatus');
                    try {
                        sel.value = statusParam;
                    } catch (e) {}
                }

            });
        </script>
    @endsection
@endsection
