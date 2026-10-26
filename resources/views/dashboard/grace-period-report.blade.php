<!-- resources/views/dashboard/grace-period-report.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Masa Tenggang')
@section('subtitle', 'Kontrak yang berada dalam periode tenggang setelah berakhir')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div
                    class="card-header bg-gradient-warning text-dark d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-50 rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-clock text-dark"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Kontrak Masa Tenggang</h5>
                            <small class="opacity-75">Kontrak yang berada dalam periode tenggang setelah berakhir</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-dark btn-sm me-2">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <a href="javascript:window.print()" class="btn btn-dark btn-sm">
                            <i class="fas fa-print me-1"></i> Cetak
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-warning bg-opacity-10 shadow-sm">
                                <div class="card-body text-center">
                                    <h3 class="text-warning">{{ $contracts->count() }}</h3>
                                    <p class="mb-0 text-muted">Total Kontrak</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-danger bg-opacity-10 shadow-sm">
                                <div class="card-body text-center">
                                    <h3 class="text-danger">
                                        {{ $contracts->filter(function ($c) {return ($c->remaining_grace_period_days ?? 0) < 7;})->count() }}
                                    </h3>
                                    <p class="mb-0 text-muted">Kritis (< 7 hari)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-info bg-opacity-10 shadow-sm">
                                <div class="card-body text-center">
                                    <h3 class="text-info">
                                        {{ $contracts->filter(function ($c) {$d = $c->remaining_grace_period_days ?? 0;return $d >= 7 && $d <= 14;})->count() }}
                                    </h3>
                                    <p class="mb-0 text-muted">Perhatian (7-14 hari)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 bg-success bg-opacity-10 shadow-sm">
                                <div class="card-body text-center">
                                    <h3 class="text-success">
                                        {{ $contracts->filter(function ($c) {return ($c->remaining_grace_period_days ?? 0) > 14;})->count() }}
                                    </h3>
                                    <p class="mb-0 text-muted">Aman (> 14 hari)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert -->
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Kontrak dalam masa tenggang memerlukan tindakan segera.
                        Perpanjang atau terminasi kontrak sebelum masa tenggang berakhir.
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-sm" id="gracePeriodTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Kontrak</th>
                                    <th>Nama Kontrak</th>
                                    <th class="d-none d-md-table-cell">Klien</th>
                                    <th class="d-none d-md-table-cell">Tanggal Berakhir</th>
                                    <th class="d-none d-md-table-cell">Masa Tenggang Hingga</th>
                                    <th>Sisa Hari</th>
                                    <th class="d-none d-md-table-cell">Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($contracts as $contract)
                                    @php
                                        $remainingDays = $contract->remaining_grace_period_days ?? 0;
                                        $statusClass =
                                            $remainingDays < 7 ? 'danger' : ($remainingDays < 14 ? 'warning' : 'info');
                                    @endphp
                                    <tr
                                        class="{{ $remainingDays < 7 ? 'table-danger' : ($remainingDays < 14 ? 'table-warning' : '') }}">
                                        <td data-label="No. Kontrak">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <div class="contract-icon bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center rounded-2"
                                                        style="width: 36px; height: 36px;">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong class="d-block">{{ $contract->contract_number }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Nama Kontrak">
                                            <a href="{{ route('contracts.show', $contract) }}"
                                                class="text-decoration-none text-dark fw-semibold">
                                                {{ $contract->contract_name }}
                                            </a>
                                        </td>
                                        <td data-label="Klien" class="d-none d-md-table-cell">
                                            @if ($contract->client)
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <div class="avatar-circle-sm bg-info text-white d-flex align-items-center justify-content-center rounded-circle"
                                                            style="width: 28px; height: 28px; font-size: 12px;">
                                                            {{ strtoupper(substr($contract->client->name, 0, 2)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        {{ $contract->client->name }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td data-label="Tanggal Berakhir" class="d-none d-md-table-cell">
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-calendar-times text-danger me-1"></i>
                                                {{ $contract->end_date->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td data-label="Masa Tenggang Hingga" class="d-none d-md-table-cell">
                                            <span class="badge bg-warning bg-opacity-25 text-dark border border-warning">
                                                <i class="fas fa-calendar-clock me-1"></i>
                                                {{ $contract->grace_period_end_date->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td data-label="Sisa Hari">
                                            <div class="d-flex align-items-center">
                                                <div class="progress grow me-2" style="height: 8px;">
                                                    @php
                                                        $totalDays = $contract->grace_period_days ?? 60;
                                                        if ($totalDays > 0) {
                                                            $percentage = min(100, max(0, (($totalDays - $remainingDays) / $totalDays) * 100));
                                                        } else {
                                                            $percentage = 0;
                                                        }
                                                    @endphp
                                                    <div class="progress-bar bg-{{ $statusClass }}" role="progressbar"
                                                        data-progress="{{ $percentage }}"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ number_format($remainingDays, 0) }} hari
                                                </span>
                                            </div>
                                        </td>
                                        <td data-label="Status" class="d-none d-md-table-cell">
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i> Masa Tenggang
                                            </span>
                                        </td>
                                        <td data-label="Aksi">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('contracts.show', $contract) }}"
                                                    class="btn btn-outline-info" title="Detail" data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('contracts.edit', $contract) }}"
                                                    class="btn btn-outline-warning" title="Perpanjang"
                                                    data-bs-toggle="tooltip">
                                                    <i class="fas fa-sync-alt"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger terminate-btn"
                                                    data-contract-id="{{ $contract->id }}"
                                                    data-contract-name="{{ $contract->contract_name }}" title="Terminasi"
                                                    data-bs-toggle="tooltip">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                                <h4 class="h5">Tidak ada kontrak dalam masa tenggang</h4>
                                                <p class="mb-0">Semua kontrak dalam kondisi baik</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Export Options -->
                    @if ($contracts->count() > 0)
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Laporan dihasilkan pada: {{ now()->format('d M Y H:i') }}
                                </small>
                                <div>
                                    <button class="btn btn-sm btn-outline-secondary me-2" onclick="window.print()">
                                        <i class="fas fa-print me-1"></i> Cetak
                                    </button>
                                    <a href="#" class="btn btn-sm btn-success">
                                        <i class="fas fa-file-excel me-1"></i> Export Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Terminate Modal -->
    <div class="modal fade" id="terminateModal" tabindex="-1" aria-labelledby="terminateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="terminateModalLabel">
                        <i class="fas fa-ban me-2"></i> Terminasi Kontrak
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengakhiri kontrak <strong id="contractNameToTerminate"></strong>?</p>
                    <div class="mb-3">
                        <label for="terminationReason" class="form-label">Alasan Terminasi:</label>
                        <textarea class="form-control" id="terminationReason" rows="3" placeholder="Masukkan alasan terminasi..."
                            required></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Kontrak yang sudah dihentikan tidak dapat dikembalikan ke status sebelumnya.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmTerminate">
                        <i class="fas fa-ban me-1"></i> Terminasi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.progress-bar[data-progress]').forEach((progressBar) => {
            const percentage = Number(progressBar.dataset.progress);
            if (Number.isFinite(percentage)) {
                progressBar.style.width = `${Math.min(100, Math.max(0, percentage))}%`;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltips.map(tooltip => new bootstrap.Tooltip(tooltip));

            // Terminate modal
            const terminateModal = new bootstrap.Modal(document.getElementById('terminateModal'));
            let currentContractId = null;

            document.querySelectorAll('.terminate-btn').forEach(button => {
                button.addEventListener('click', function() {
                    currentContractId = this.getAttribute('data-contract-id');
                    const contractName = this.getAttribute('data-contract-name');

                    document.getElementById('contractNameToTerminate').textContent = contractName;
                    document.getElementById('terminationReason').value = '';
                    terminateModal.show();
                });
            });

            // Confirm terminate
            document.getElementById('confirmTerminate').addEventListener('click', function() {
                const reason = document.getElementById('terminationReason').value.trim();

                if (!reason) {
                    alert('Harap masukkan alasan terminasi');
                    return;
                }

                // Send request to terminate contract
                fetch(`/contracts/${currentContractId}/terminate`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            termination_reason: reason,
                            status: 'terminated'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal menghentikan kontrak: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    });
            });

            // WhatsApp health check is provided by shared partial include

            // Print styles
            const printBtn = document.querySelector('[onclick="window.print()"]');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    // Add print-specific styles
                    const style = document.createElement('style');
                    style.innerHTML = `
                @media print {
                    .card-header { background: #ffc107 !important; color: #000 !important; }
                    .btn, .dropdown { display: none !important; }
                    .table { font-size: 12px !important; }
                    .badge { border: 1px solid #ccc !important; }
                    .progress { height: 6px !important; }
                }
            `;
                    document.head.appendChild(style);
                });
            }
        });
    </script>

    <style>
        /* Grace period specific styles */
        .card-header.bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        .badge-grace {
            background-color: #ffc107;
            color: #000;
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
        }

        .table-danger {
            background-color: rgba(220, 53, 69, 0.05) !important;
        }

        .table-warning {
            background-color: rgba(255, 193, 7, 0.05) !important;
        }

        .contract-icon {
            transition: all 0.3s ease;
        }

        tr:hover .contract-icon {
            transform: scale(1.1);
        }

        /* Progress bar styles */
        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
            min-width: 80px;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* Print styles */
        @media print {
            .card-header {
                background: #ffc107 !important;
                color: #000 !important;
            }

            .btn,
            .dropdown,
            .modal,
            .alert {
                display: none !important;
            }

            .table {
                font-size: 11px !important;
            }

            .badge {
                border: 1px solid #ccc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            a {
                text-decoration: none !important;
                color: #000 !important;
            }

            /* Hide the report header text during print */
            .card-header h5,
            .card-header small {
                display: none !important;
            }
        }

        /* Responsive table tweaks */
        @media (max-width: 768px) {

            /* Hide less important columns for small screens: Client, Tanggal Berakhir, Masa Tenggang Hingga, Status */
            #gracePeriodTable thead th:nth-child(3),
            #gracePeriodTable thead th:nth-child(4),
            #gracePeriodTable thead th:nth-child(5),
            #gracePeriodTable thead th:nth-child(7),
            #gracePeriodTable tbody td:nth-child(3),
            #gracePeriodTable tbody td:nth-child(4),
            #gracePeriodTable tbody td:nth-child(5),
            #gracePeriodTable tbody td:nth-child(7) {
                display: none !important;
            }

            /* Make remaining table cells more compact */
            #gracePeriodTable {
                font-size: 0.95rem;
            }

            /* Ensure action buttons wrap and remain accessible */
            .btn-group {
                flex-wrap: wrap;
                gap: 6px;
            }

            /* Reduce avatar and icon sizes */
            .avatar-circle-sm {
                width: 24px !important;
                height: 24px !important;
                font-size: 10px !important;
            }

            .contract-icon {
                width: 30px !important;
                height: 30px !important;
            }

            /* Make progress bar smaller */
            .progress {
                min-width: 60px;
            }
        }

        /* Additional UI tweaks for icons, padding and buttons */
        .contract-icon i {
            font-size: 14px;
        }

        .avatar-circle-sm {
            width: 28px !important;
            height: 28px !important;
            font-size: 12px !important;
        }

        @media (max-width: 768px) {
            .btn-group .btn {
                padding: .28rem .45rem;
                font-size: .85rem;
                min-width: 36px;
            }

            .badge {
                padding: .25rem .5rem;
                font-size: .78rem;
            }

            .progress {
                height: 6px !important;
            }

            .progress-bar {
                height: 6px !important;
            }

            #gracePeriodTable tr {
                padding: .45rem 0;
            }

            .contract-icon {
                width: 30px !important;
                height: 30px !important;
            }

            .contract-icon i {
                font-size: 12px;
            }

            .avatar-circle-sm {
                width: 22px !important;
                height: 22px !important;
                font-size: 10px !important;
            }
        }

        @media (max-width: 480px) {
            .btn-group .btn {
                padding: .32rem .5rem;
                font-size: .82rem;
            }

            .badge {
                font-size: .75rem;
            }

            .contract-icon {
                width: 26px !important;
                height: 26px !important;
            }

            .contract-icon i {
                font-size: 11px;
            }

            .avatar-circle-sm {
                width: 20px !important;
                height: 20px !important;
                font-size: 9px !important;
            }
        }
    </style>
    <style>
        /* Small-screen stacked table (mobile) */
        @media (max-width: 480px) {
            #gracePeriodTable thead {
                display: none !important;
            }

            #gracePeriodTable,
            #gracePeriodTable tbody,
            #gracePeriodTable tr {
                display: block !important;
                width: 100% !important;
            }

            #gracePeriodTable td:not(.d-none) {
                display: block !important;
                width: 100% !important;
            }

            #gracePeriodTable tr {
                margin-bottom: 0.75rem;
                padding: 0.6rem;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.02);
            }

            #gracePeriodTable td {
                padding: 0.35rem 0;
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px dashed rgba(0, 0, 0, 0.04);
            }

            #gracePeriodTable td::before {
                content: attr(data-label) ": ";
                font-weight: 600;
                color: #495057;
                flex: 0 0 45%;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            #gracePeriodTable td[data-label="Aksi"] {
                justify-content: flex-end;
                border-bottom: none;
            }

            /* ensure buttons are touch-friendly */
            .btn-group .btn {
                padding: .35rem .5rem;
            }
        }
    </style>
@endsection
