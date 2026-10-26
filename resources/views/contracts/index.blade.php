<!-- resources/views/contracts/index.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Data Kontrak')
@section('subtitle', 'Manajemen kontrak dengan Vendor')

@section('content')
    <style>
        .responsive-contract-table td {
            vertical-align: middle;
        }

        .responsive-contract-table tbody tr.contract-row-selected > td {
            background-color: #eff6ff;
        }

        .responsive-contract-table .contract-icon {
            min-width: 36px;
            min-height: 36px;
        }

        .responsive-contract-table .contract-edit-action {
            border-color: #1d4e89;
            background-color: transparent;
            color: #102a43;
        }

        .responsive-contract-table .contract-edit-action:hover,
        .responsive-contract-table .contract-edit-action:focus {
            border-color: #102a43;
            background: #102a43;
            color: #fff;
        }

        .contract-term-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            min-height: 32px;
            padding: 0.35rem 0.7rem;
            border: 1px solid #b8dce8;
            border-radius: 999px;
            background: #eff8fb;
            color: #17647b;
            font-size: 0.8125rem;
            font-weight: 600;
            line-height: 1;
            white-space: nowrap;
        }

        .contract-term-badge i {
            color: #2386a1;
            font-size: 0.75rem;
        }

        .contract-page-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }

        .contract-page-actions .dropdown,
        .contract-page-actions .btn {
            min-height: 38px;
            transition: all 0.3s ease;
        }

        .contract-page-actions .btn:hover,
        .contract-page-actions .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        /* Filter chips responsive layout */
        .filter-chips-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            overflow-x: auto;
            padding-bottom: 0.25rem;
        }

        .filter-chip {
            white-space: nowrap;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .filter-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .filter-chip a {
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .filter-chip a:hover {
            opacity: 1;
        }

        /* Dropdown optimizations */
        .dropdown-menu {
            max-height: 70vh;
            overflow-y: auto;
        }

        .dropdown-menu .dropdown-item {
            padding: 0.75rem 1rem;
        }

        /* Quick stats responsive */
        .quick-stats-container {
            display: flex;
            gap: 1rem;
            margin-left: auto;
        }

        .quick-stat {
            text-align: center;
            flex-shrink: 0;
        }

        /* Tablet Layout (769px - 991px) */
        @media (min-width: 769px) and (max-width: 991px) {
            .card-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .card-header > div:first-child {
                margin-bottom: 0;
            }

            .contract-page-actions {
                width: 100%;
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
                justify-content: flex-start;
            }

            .contract-page-actions .dropdown,
            .contract-page-actions .btn {
                min-height: 36px;
                font-size: 0.875rem;
                padding: 0.4rem 0.75rem;
            }

            .contract-page-actions .me-2 {
                margin-right: 0.35rem !important;
            }
        }

        /* Mobile Layout (≤ 768px) */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 1rem !important;
                gap: 0.75rem;
            }

            .card-header > div:first-child {
                margin-bottom: 0;
                align-items: flex-start !important;
            }

            .card-header > div:first-child .bg-white {
                flex-shrink: 0;
                width: 40px;
                height: 40px;
            }

            .contract-page-actions {
                width: 100%;
                flex-direction: row;
                align-items: center;
                gap: 0.4rem;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .contract-page-actions .dropdown,
            .contract-page-actions .btn,
            .contract-page-actions .btn-light {
                min-height: 36px;
                font-size: 0.8rem;
                padding: 0.35rem 0.6rem;
                flex: 0 0 auto;
                white-space: nowrap;
            }

            .contract-page-actions .dropdown-menu {
                min-width: auto;
                left: 0 !important;
                right: 0 !important;
                margin: 0.5rem 0;
                max-height: 50vh;
                max-width: 100vw;
            }

            .contract-page-actions .dropdown-menu .dropdown-item {
                padding: 0.6rem 0.8rem;
                font-size: 0.8rem;
            }

            .contract-page-actions .me-2 {
                margin-right: 0 !important;
            }

            .contract-page-actions .btn-light {
                background-color: #fff;
                color: #0d6efd;
                border: 1px solid #0d6efd;
            }

            .contract-page-actions .btn-light:hover {
                background-color: #0d6efd;
                color: #fff;
            }

            /* Mobile filter chips improvements */
            .filter-chips-container {
                width: 100%;
                gap: 0.3rem;
                padding-right: 0.25rem;
            }

            .filter-chip {
                font-size: 0.75rem;
                padding: 0.35rem 0.6rem !important;
                border-radius: 4px;
            }

            .filter-chip a {
                font-size: 0.75rem;
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .filter-chip span {
                font-size: 0.7rem;
            }

            .filter-chip i {
                font-size: 0.65rem;
            }

            .quick-stats-container {
                margin-left: 0 !important;
                margin-top: 0.75rem;
                width: 100%;
                justify-content: space-around;
                gap: 0.5rem;
            }

            .quick-stat {
                flex: 1;
                min-width: auto;
                text-align: center;
            }

            .quick-stat small {
                font-size: 0.6rem;
                display: block;
                margin-top: 0.2rem;
            }

            .quick-stat .badge {
                font-size: 0.65rem;
                padding: 0.3rem 0.4rem;
            }
        }

        /* Extra Small Mobile (≤ 576px) */
        @media (max-width: 576px) {
            .card-header {
                padding: 0.75rem !important;
            }

            .card-header > div:first-child {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .card-header > div:first-child .bg-white {
                width: 36px;
                height: 36px;
                margin-right: 0.75rem;
            }

            .contract-page-actions {
                gap: 0.3rem;
            }

            .contract-page-actions .dropdown,
            .contract-page-actions .btn,
            .contract-page-actions .btn-light {
                min-height: 32px;
                font-size: 0.75rem;
                padding: 0.3rem 0.5rem;
                flex: 0 0 calc(25% - 0.3rem);
            }

            .contract-page-actions .dropdown-menu {
                width: calc(100vw - 1rem);
                left: 0.5rem !important;
                right: auto !important;
            }

            .filter-chip {
                font-size: 0.7rem;
                padding: 0.3rem 0.5rem !important;
            }

            .filter-chip a {
                font-size: 0.7rem;
            }

            .quick-stat {
                font-size: 0.7rem;
            }

            .quick-stat small {
                font-size: 0.55rem;
            }

            .quick-stat .badge {
                font-size: 0.6rem;
                padding: 0.25rem 0.35rem;
            }
        }

        @media (max-width: 1280px) {
            .responsive-contract-table thead {
                display: none;
            }

            .responsive-contract-table tbody,
            .responsive-contract-table tr,
            .responsive-contract-table td {
                display: block;
                width: 100%;
            }

            .responsive-contract-table tr {
                margin-bottom: 0.75rem;
                border: 1px solid #e9ecef;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 2px 8px rgba(16, 42, 67, 0.05);
                padding: 0.5rem 0.75rem;
            }

            .responsive-contract-table td {
                padding: 0.6rem 0;
                border: 0;
                overflow-wrap: anywhere;
            }

            .responsive-contract-table tbody td.contract-selection-cell {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                padding: 0 0 0.5rem;
                margin-bottom: 0.5rem;
                border-bottom: 1px solid #e9ecef;
            }

            .responsive-contract-table td[data-label]:before {
                content: attr(data-label);
                font-weight: 700;
                color: #495057;
                display: block;
                width: auto;
                min-width: 0;
                margin-bottom: 0.25rem;
            }

            .responsive-contract-table td[data-label="Aksi"] {
                text-align: right;
                padding-bottom: 1rem;
            }

            .responsive-contract-table td[data-label="Aksi"] .btn-group {
                width: 100%;
                justify-content: flex-end;
            }

            .responsive-contract-table td[data-label="Nama Kontrak"] small {
                display: block;
                margin-top: 0.35rem;
            }

            .responsive-contract-table td[data-label="Tanggal"] .badge,
            .responsive-contract-table td[data-label="Status"] .badge {
                width: 100%;
            }

            .responsive-contract-table td.text-center.d-none.d-md-table-cell {
                display: none !important;
            }

            .responsive-contract-table tbody td[colspan] {
                display: block;
                width: 100%;
            }
        }

        @media (max-width: 1100px) {
            .card-body.py-2.border-bottom.bg-light > .d-flex {
                align-items: stretch !important;
                flex-wrap: wrap;
            }

            .card-body.py-2.border-bottom.bg-light .filter-chips-container {
                flex: 1 1 100%;
                min-width: 0;
                overflow: visible;
            }

            .card-body.py-2.border-bottom.bg-light .quick-stats-container {
                flex: 1 1 100%;
                justify-content: flex-start;
                margin-left: 0;
                gap: 0.75rem;
            }

            .card-body.py-2.border-bottom.bg-light .quick-stat {
                min-width: 58px;
            }
        }
    </style>

    <div class="row">
        <div class="col-12">
            <style>
                /* Contracts list: mobile responsive tweaks */
                @media (max-width: 768px) {
                    /* Stack header actions and make controls full-width */
                    .card-header .contract-page-actions {
                        display: flex !important;
                        flex-direction: column;
                        align-items: stretch;
                        gap: 8px;
                        width: 100%;
                    }

                    .card-header .contract-page-actions .dropdown,
                    .card-header .contract-page-actions a.btn,
                    .card-header .contract-page-actions .input-group {
                        width: 100% !important;
                    }

                    /* Quick stats wrap */
                    .quick-stats-container {
                        display: flex;
                        gap: 8px;
                        flex-wrap: wrap;
                        margin-top: 6px;
                    }

                    /* Responsive table -> stacked cards */
                    .responsive-contract-table thead {
                        display: none;
                    }

                    .responsive-contract-table tbody tr {
                        display: block;
                        border: 1px solid rgba(0,0,0,0.06);
                        border-radius: 8px;
                        margin-bottom: 12px;
                        padding: 10px;
                        background: #fff;
                        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
                    }

                    .responsive-contract-table tbody td {
                        display: block;
                        padding: 6px 0;
                        border: none;
                        text-align: left;
                    }

                    .responsive-contract-table tbody td::before {
                        content: attr(data-label);
                        display: block;
                        font-weight: 700;
                        color: #495057;
                        font-size: 12px;
                        margin-bottom: 4px;
                        text-transform: none;
                    }

                    /* Hide other desktop-only columns while keeping contract selection available. */
                    .responsive-contract-table tbody td.text-center.d-none.d-md-table-cell:not(.contract-selection-cell) {
                        display: none !important;
                    }

                    /* Make contract icon smaller on phones */
                    .contract-icon {
                        width: 44px !important;
                        height: 44px !important;
                        min-width: 44px !important;
                    }

                    /* Action buttons full width and aligned */
                    .responsive-contract-table tbody td[data-label="Aksi"] {
                        display: flex;
                        gap: 8px;
                        justify-content: flex-end;
                        align-items: center;
                        flex-wrap: wrap;
                    }
                }

                @media (max-width: 576px) {
                    .card-header .contract-page-actions {
                        gap: 6px;
                    }

                    .responsive-contract-table tbody tr {
                        padding: 8px;
                    }

                    .responsive-contract-table tbody td::before {
                        font-size: 11px;
                    }

                    .quick-stat span.badge { font-size: 12px; }
                }

                .contract-page-actions {
                    gap: 0.625rem;
                }

                .contract-page-actions .contract-control {
                    min-height: 42px;
                    padding: 0.55rem 0.9rem;
                    border-radius: 0.625rem;
                    font-weight: 600;
                    white-space: nowrap;
                    transition: background-color 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
                }

                .contract-page-actions .contract-search-trigger,
                .contract-page-actions .contract-status-trigger {
                    color: #fff;
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.62);
                }

                .contract-page-actions .contract-search-trigger:hover,
                .contract-page-actions .contract-search-trigger:focus-visible,
                .contract-page-actions .contract-status-trigger:hover,
                .contract-page-actions .contract-status-trigger:focus-visible {
                    color: #fff;
                    background: rgba(255, 255, 255, 0.22);
                    border-color: #fff;
                }

                .contract-page-actions .contract-create-action {
                    color: #0f234d;
                    background: #fff;
                    border: 1px solid #fff;
                }

                .contract-page-actions .contract-create-action:hover,
                .contract-page-actions .contract-create-action:focus-visible {
                    color: #0f234d;
                    background: #eaf2ff;
                    border-color: #eaf2ff;
                    transform: translateY(-1px);
                }

                .contract-page-actions .contract-search-dropdown {
                    flex: 0 1 auto;
                }

                .contract-page-actions .contract-search-dropdown .dropdown-menu {
                    width: min(360px, calc(100vw - 2rem));
                    max-width: calc(100vw - 2rem);
                }

                .contract-filter-tools {
                    display: flex;
                    align-items: center;
                    flex: 0 0 auto;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                }

                .contract-filter-label {
                    margin: 0 !important;
                    white-space: nowrap;
                }

                .contract-select-all-control {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.45rem;
                    min-height: 36px;
                    padding: 0.25rem 0.65rem;
                    border: 1px solid #dbe3ee;
                    border-radius: 0.5rem;
                    background: #fff;
                    color: #102a43;
                    cursor: pointer;
                    font-size: 0.875rem;
                    font-weight: 600;
                    white-space: nowrap;
                }

                .contract-select-all-control .form-check-input {
                    width: 1rem;
                    height: 1rem;
                    margin: 0;
                    accent-color: #2563eb;
                    cursor: pointer;
                }

                .contract-select-all-control .form-check-input:disabled {
                    cursor: not-allowed;
                }

                .contract-selected-count {
                    color: #667085;
                    font-size: 0.8rem;
                    white-space: nowrap;
                }

                .contract-bulk-actions .btn {
                    min-height: 36px;
                    padding: 0.4rem 0.75rem;
                    border-radius: 0.5rem;
                    font-weight: 600;
                    white-space: nowrap;
                }

                @media (max-width: 768px) {
                    .contract-page-actions {
                        display: grid !important;
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                        width: 100%;
                    }

                    .contract-page-actions .contract-search-dropdown {
                        grid-column: 1 / -1;
                    }

                    .contract-page-actions .contract-search-dropdown,
                    .contract-page-actions .contract-status-dropdown,
                    .contract-page-actions .contract-control {
                        width: 100%;
                    }

                    .contract-page-actions .contract-control {
                        justify-content: center;
                        padding-inline: 0.5rem;
                    }

                    .contract-page-actions .contract-search-dropdown .dropdown-menu {
                        left: 0 !important;
                        right: auto !important;
                    }
                }
    </style>
            <div class="card border-0 shadow-sm">
                <!-- Modern Header -->
                <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 py-3 px-3">
                    <div class="d-flex align-items-center flex-shrink-0">
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px; min-width: 40px;">
                            <i class="fas fa-file-contract text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">List Contract</h5>
                            <small class="opacity-75">Manajemen kontrak dengan vendor</small>
                        </div>
                    </div>

                    <div class="contract-page-actions d-flex align-items-center">
                        <!-- Advanced Search -->
                        <div class="dropdown contract-search-dropdown">
                            <button class="btn contract-control contract-search-trigger dropdown-toggle d-flex align-items-center"
                                type="button" id="searchDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-search me-2"></i>
                                <span>Pencarian &amp; Filter</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 300px;"
                                aria-labelledby="searchDropdown">
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="fas fa-hashtag me-1"></i> Nomor Kontrak
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="searchContractNumber"
                                        placeholder="CTR-001-2024" value="{{ request('contract_number', '') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="fas fa-file-signature me-1"></i> Nama Kontrak
                                    </label>
                                    <input type="text" class="form-control form-control-sm" id="searchContractName"
                                        placeholder="Nama kontrak..." value="{{ request('search', '') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="fas fa-building me-1"></i> Vendor
                                    </label>
                                    <select class="form-select form-select-sm" id="searchClient" name="client_id">
                                        <option value="">Semua Vendor</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}"
                                                {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1" for="searchContractType">
                                        <i class="fas fa-layer-group me-1"></i> Jenis Kontrak
                                    </label>
                                    <select class="form-select form-select-sm" id="searchContractType">
                                        <option value="">Semua Jenis Kontrak</option>
                                        @foreach (config('contract_types.types', []) as $typeKey => $type)
                                            <option value="{{ $typeKey }}" {{ request('contract_type') === $typeKey ? 'selected' : '' }}>
                                                {{ $type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        id="clearContractSearch">
                                        <i class="fas fa-eraser me-1"></i> Bersihkan
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary" id="applyContractSearch">
                                        <i class="fas fa-search me-1"></i> Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- <!-- Quick Search -->
                        <div class="input-group input-group-sm me-2" style="width: 200px;">
                            <input type="text"
                                class="form-control border-light bg-white bg-opacity-10 text-white placeholder-white"
                                id="quickContractSearch" placeholder="Cari kontrak..." aria-label="Quick Search"
                                value="{{ request('search', '') }}" style="border-color: rgba(255,255,255,0.2);">
                            <button class="btn btn-outline-light" type="button" id="quickContractSearchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div> --}}

                        <!-- Status Filter -->
                        <div class="dropdown contract-status-dropdown">
                            <button class="btn contract-control contract-status-trigger dropdown-toggle d-flex align-items-center"
                                type="button" id="statusFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-2"></i>
                                <span>
                                    @php
                                        $statusValue = request('status');
                                        $statusLabels = [
                                            'active' => 'Aktif',
                                            'grace_period' => 'Masa Tenggang',
                                            'expired' => 'Kadaluarsa',
                                            'terminated' => 'Dihentikan',
                                        ];
                                    @endphp
                                    @if ($statusValue && $statusValue !== 'all')
                                        <span class="badge {{ getStatusBadgeClass($statusValue) }} me-1">
                                            {{ $statusLabels[$statusValue] ?? ucfirst($statusValue) }}
                                        </span>
                                    @else
                                        Status
                                    @endif
                                </span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="statusFilter">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between {{ request('status') === 'all' || !request('status') ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}">
                                        <div>
                                            <i class="fas fa-layer-group text-muted me-2"></i>
                                            Semua Status
                                        </div>
                                        @if (request('status') === 'all' || !request('status'))
                                            <i class="fas fa-check text-primary"></i>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-2">
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between {{ request('status') == 'active' ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}">
                                        <div>
                                            <span
                                                class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 me-2">A</span>
                                            <span>Kontrak Aktif</span>
                                            <small class="text-muted d-block ms-4">Kontrak yang sedang berjalan</small>
                                        </div>
                                        @if (request('status') == 'active')
                                            <i class="fas fa-check text-success"></i>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between {{ request('status') == 'grace_period' ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['status' => 'grace_period']) }}">
                                        <div>
                                            <span
                                                class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 me-2">M</span>
                                            <span>Masa Tenggang</span>
                                            <small class="text-muted d-block ms-4">Dalam periode tenggang</small>
                                        </div>
                                        @if (request('status') == 'grace_period')
                                            <i class="fas fa-check text-warning"></i>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between {{ request('status') == 'expired' ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['status' => 'expired']) }}">
                                        <div>
                                            <span
                                                class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 me-2">K</span>
                                            <span>Kadaluarsa</span>
                                            <small class="text-muted d-block ms-4">Kontrak telah berakhir</small>
                                        </div>
                                        @if (request('status') == 'expired')
                                            <i class="fas fa-check text-danger"></i>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between {{ request('status') == 'terminated' ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['status' => 'terminated']) }}">
                                        <div>
                                            <span
                                                class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25 me-2">T</span>
                                            <span>Dihentikan</span>
                                            <small class="text-muted d-block ms-4">Kontrak dihentikan lebih awal</small>
                                        </div>
                                        @if (request('status') == 'terminated')
                                            <i class="fas fa-check text-secondary"></i>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <a href="{{ route('contracts.create') }}" class="btn contract-control contract-create-action d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Kontrak
                        </a>
                    </div>
                </div>

                <!-- Filter Chips -->
                @php
                    function getStatusBadgeClass($status)
                    {
                        $classes = [
                            'active' => 'bg-success',
                            'grace_period' => 'bg-warning',
                            'expired' => 'bg-danger',
                            'terminated' => 'bg-secondary',
                        ];
                        return $classes[$status] ?? 'bg-secondary';
                    }
                @endphp

                <div class="card-body py-2 border-bottom bg-light">
                    <div class="d-flex align-items-center flex-column flex-md-row gap-2">
                        <div class="contract-filter-tools">
                            <small class="text-muted contract-filter-label">
                                <i class="fas fa-sliders-h me-1"></i> Filter:
                            </small>
                            <label class="contract-select-all-control" for="selectAllContractsToolbar">
                                <input type="checkbox" class="form-check-input" id="selectAllContractsToolbar"
                                    aria-label="Pilih semua kontrak">
                                <span>Pilih Semua</span>
                            </label>
                            <div class="dropdown d-none contract-bulk-actions" id="bulkContractActions">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Aksi Massal
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item text-danger"
                                            id="deleteSelectedContracts">
                                            <i class="fas fa-trash me-2"></i>Hapus kontrak terpilih
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <small class="contract-selected-count" aria-live="polite">
                                <span id="selectedContractCount">0</span> kontrak dipilih
                            </small>
                        </div>

                        <div class="filter-chips-container w-100 w-md-auto">
                            @php
                                $statusValue = request('status');
                            @endphp
                            @if ($statusValue && $statusValue !== 'all')
                                <div
                                    class="filter-chip {{ getStatusBadgeClass($statusValue) }}-subtle border border-{{ str_replace('bg-', '', getStatusBadgeClass($statusValue)) }} border-opacity-25 rounded-pill px-2 px-md-3 py-1 d-flex align-items-center gap-1">
                                    <span class="text-nowrap">
                                        <i
                                            class="fas fa-filter text-{{ str_replace('bg-', '', getStatusBadgeClass($statusValue)) }}"></i>
                                        <span class="d-none d-md-inline">Status: {{ ucfirst(str_replace('_', ' ', $statusValue)) }}</span>
                                        <span class="d-md-none">{{ substr(ucfirst(str_replace('_', ' ', $statusValue)), 0, 6) }}...</span>
                                    </span>
                                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
                                        class="text-{{ str_replace('bg-', '', getStatusBadgeClass($statusValue)) }} text-decoration-none">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif

                            @if (request()->has('client_id') && request('client_id') != '')
                                @php
                                    $selectedClient = $clients->firstWhere('id', request('client_id'));
                                @endphp
                                <div
                                    class="filter-chip bg-info-subtle border border-info border-opacity-25 rounded-pill px-2 px-md-3 py-1 d-flex align-items-center gap-1">
                                    <span class="text-nowrap">
                                        <i class="fas fa-building text-info"></i>
                                        <span class="d-none d-md-inline">Vendor: {{ $selectedClient->name ?? 'Unknown' }}</span>
                                        <span class="d-md-none">{{ substr($selectedClient->name ?? 'Unknown', 0, 6) }}...</span>
                                    </span>
                                    <a href="{{ request()->fullUrlWithQuery(['client_id' => '']) }}"
                                        class="text-info text-decoration-none">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif

                            @if (request()->has('search') && request('search') != '')
                                <div
                                    class="filter-chip bg-primary-subtle border border-primary border-opacity-25 rounded-pill px-2 px-md-3 py-1 d-flex align-items-center gap-1">
                                    <span class="text-nowrap">
                                        <i class="fas fa-search text-primary"></i>
                                        <span class="d-none d-md-inline">Pencarian: "{{ request('search') }}"</span>
                                        <span class="d-md-none">"{{ substr(request('search'), 0, 4) }}..."</span>
                                    </span>
                                    <a href="{{ request()->fullUrlWithQuery(['search' => '']) }}"
                                        class="text-primary text-decoration-none">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif

                            @if (request()->has('contract_number') && request('contract_number') != '')
                                <div
                                    class="filter-chip bg-dark-subtle border border-dark border-opacity-25 rounded-pill px-2 px-md-3 py-1 d-flex align-items-center gap-1">
                                    <span class="text-nowrap">
                                        <i class="fas fa-hashtag text-dark"></i>
                                        <span class="d-none d-md-inline">No. Kontrak: {{ request('contract_number') }}</span>
                                        <span class="d-md-none">{{ substr(request('contract_number'), 0, 6) }}...</span>
                                    </span>
                                    <a href="{{ request()->fullUrlWithQuery(['contract_number' => '']) }}"
                                        class="text-dark text-decoration-none">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif

                            @if (request('contract_type') && isset(config('contract_types.types')[request('contract_type')]))
                                <div class="filter-chip bg-success-subtle border border-success border-opacity-25 rounded-pill px-2 px-md-3 py-1 d-flex align-items-center gap-1">
                                    <span class="text-nowrap">
                                        <i class="fas fa-layer-group text-success"></i>
                                        <span>Jenis: {{ config('contract_types.types.' . request('contract_type') . '.name') }}</span>
                                    </span>
                                    <a href="{{ request()->fullUrlWithQuery(['contract_type' => '']) }}" class="text-success text-decoration-none" aria-label="Hapus filter jenis kontrak">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if (request()->has('status') ||
                                request()->has('client_id') ||
                                request()->has('search') ||
                                request()->has('contract_number') ||
                                request()->has('contract_type'))
                            <a href="{{ route('contracts.index') }}"
                                class="btn btn-sm btn-outline-secondary rounded-pill d-flex align-items-center flex-shrink-0">
                                <i class="fas fa-times me-1"></i> <span class="d-none d-md-inline">Reset Filter</span><span class="d-md-none">Reset</span>
                            </a>
                        @endif

                        <!-- Quick Stats -->
                        <div class="quick-stats-container">
                            <div class="quick-stat">
                                <small class="text-muted d-block">Total</small>
                                <span class="badge bg-primary rounded-pill">{{ $contracts->total() }}</span>
                            </div>
                            <div class="quick-stat">
                                <small class="text-muted d-block">Aktif</small>
                                <span class="badge bg-success rounded-pill">{{ $activeCount ?? 0 }}</span>
                            </div>
                            <div class="quick-stat d-none d-sm-block">
                                <small class="text-muted d-block">Tenggang</small>
                                <span class="badge bg-warning rounded-pill">{{ $gracePeriodCount ?? 0 }}</span>
                            </div>
                            <div class="quick-stat d-none d-md-block">
                                <small class="text-muted d-block">Kadaluarsa</small>
                                <span class="badge bg-danger rounded-pill">{{ $expiredCount ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- @include('dashboard.partials.whatsapp-health') --}}
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle responsive-contract-table" id="contractsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="text-center">
                                        <input type="checkbox" class="form-check-input" id="selectAllContracts">
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'contract_number', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-decoration-none text-dark d-flex align-items-center">
                                            <span>No. Kontrak</span>
                                            @if (request('sort') == 'contract_number')
                                                <i
                                                    class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted opacity-50"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Nama Kontrak</th>
                                    <th>Jenis Kontrak</th>
                                    <th>Termin Kontrak</th>
                                    <th>Vendor</th>
                                    @if(auth()->user() && auth()->user()->role === 'admin')
                                        <th>Pemilik</th>
                                    @endif
                                    <th>Total BOQ</th>
                                    <th>Tanggal</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}"
                                            class="text-decoration-none text-dark d-flex align-items-center">
                                            <span>Status</span>
                                            @if (request('sort') == 'status')
                                                <i
                                                    class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                            @else
                                                <i class="fas fa-sort ms-1 text-muted opacity-50"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th width="120" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $contract)
                                    <tr class="contract-row" data-contract-id="{{ $contract->id }}">
                                        <td class="text-center d-none d-md-table-cell contract-selection-cell"
                                            data-label="Pilih Kontrak">
                                            <input type="checkbox" class="form-check-input contract-checkbox"
                                                value="{{ $contract->id }}">
                                        </td>
                                        <td data-label="No. Kontrak">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <div class="contract-icon bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center rounded-2"
                                                        style="width: 36px; height: 36px;">
                                                        <i class="fas fa-file-contract"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong class="d-block">{{ $contract->contract_number }}</strong>
                                                    <small class="text-muted">
                                                        @if ($contract->created_by && $contract->creator)
                                                            <i class="fas fa-user-circle fa-xs me-1"></i>
                                                            {{ $contract->creator->name }}
                                                        @else
                                                            <i class="fas fa-user-circle fa-xs me-1"></i>
                                                            System
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Nama Kontrak">
                                            <a href="{{ route('contracts.show', $contract) }}"
                                                class="text-decoration-none text-dark fw-semibold">
                                                {{ $contract->contract_name }}
                                            </a>
                                            @if ($contract->description)
                                                <br>
                                                <small class="text-muted">
                                                    {{ Str::limit($contract->description, 50) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td data-label="Jenis Kontrak">
                                            @if ($contract->contract_type)
                                                <span class="badge bg-primary-subtle text-primary border">
                                                    {{ config('contract_types.types.' . $contract->contract_type . '.name', 'Tidak diketahui') }}
                                                </span>
                                            @else
                                                <span class="text-muted">Belum ditentukan</span>
                                            @endif
                                        </td>
                                        <td data-label="Termin Kontrak">
                                            @php
                                                $contractTermLabels = [
                                                    'termin_1' => 'Termin I',
                                                    'termin_2' => 'Termin II',
                                                    'termin_3' => 'Termin III',
                                                    'termin_4' => 'Termin IV',
                                                ];
                                            @endphp
                                            @if ($contract->contract_term)
                                                <span class="contract-term-badge" title="Tahap pembayaran: {{ $contractTermLabels[$contract->contract_term] ?? 'Termin kontrak' }}">
                                                    <i class="fas fa-layer-group" aria-hidden="true"></i>
                                                    {{ $contractTermLabels[$contract->contract_term] ?? '-' }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td data-label="Vendor">
                                            @if ($contract->client)
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <div class="avatar-circle-sm bg-info text-white d-flex align-items-center justify-content-center rounded-circle"
                                                            style="width: 28px; height: 28px; font-size: 12px;">
                                                            {{ strtoupper(substr($contract->client->name, 0, 2)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('clients.show', $contract->client) }}"
                                                            class="text-decoration-none text-dark">
                                                            {{ $contract->client->name }}
                                                        </a>
                                                        @if ($contract->client->email)
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-envelope fa-xs me-1"></i>
                                                                {{ $contract->client->email }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Vendor tidak ditemukan</span>
                                            @endif
                                        </td>
                                        @if(auth()->user() && auth()->user()->role === 'admin')
                                            <td data-label="Pemilik">
                                                @if ($contract->creator)
                                                    <div class="small">{{ $contract->creator->name }}</div>
                                                    <div class="text-muted small">{{ $contract->creator->email ?? '' }}</div>
                                                @else
                                                    <span class="text-muted">System</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td data-label="Total BOQ">
                                            @if ($contract->boqItems->isNotEmpty())
                                                <span class="badge bg-secondary bg-opacity-10 text- border" style="font-size: 15px;">Rp {{ number_format($contract->boqItems->sum('subtotal'), 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td data-label="Tanggal">
                                            <div class="d-flex flex-column">
                                                <div class="mb-1">
                                                    <small class="text-muted d-block">Mulai</small>
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="fas fa-play text-success me-1"></i>
                                                        {{ $contract->start_date->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Berakhir</small>
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="fas fa-stop text-danger me-1"></i>
                                                        {{ $contract->end_date->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Status">
                                            <div class="d-flex flex-column gap-1">
                                                <span
                                                    class="badge {{ $contract->status === 'active' ? 'bg-success' : ($contract->status === 'grace_period' ? 'bg-warning' : ($contract->status === 'expired' ? 'bg-danger' : 'bg-secondary')) }}">
                                                    @if ($contract->status === 'active')
                                                        <i class="fas fa-check-circle me-1"></i> Aktif
                                                    @elseif($contract->status === 'grace_period')
                                                        <i class="fas fa-clock me-1"></i> Masa Tenggang
                                                    @elseif($contract->status === 'expired')
                                                        <i class="fas fa-times-circle me-1"></i> Kadaluarsa
                                                    @else
                                                        <i class="fas fa-ban me-1"></i> Dihentikan
                                                    @endif
                                                </span>

                                                @if ($contract->days_remaining !== null && $contract->status === 'active' && $contract->days_remaining <= 60)
                                                    <div class="progress mt-1" style="height: 4px;">
                                                        @php
                                                            $percentage = min(
                                                                100,
                                                                max(0, ((60 - $contract->days_remaining) / 60) * 100),
                                                            );
                                                            $color =
                                                                $contract->days_remaining <= 7
                                                                    ? 'bg-danger'
                                                                    : ($contract->days_remaining <= 14
                                                                        ? 'bg-warning'
                                                                        : 'bg-info');
                                                        @endphp
                                                        <div class="progress-bar {{ $color }}" role="progressbar"
                                                            style="width: {{ $percentage }}%"
                                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                            aria-valuemax="100"
                                                            title="Sisa {{ number_format($contract->days_remaining, 0) }} hari">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">
                                                        Sisa <strong
                                                            class="text-{{ $contract->days_remaining <= 7 ? 'danger' : ($contract->days_remaining <= 14 ? 'warning' : 'info') }}">
                                                            {{ number_format($contract->days_remaining, 0) }} hari
                                                        </strong>
                                                    </small>
                                                @endif

                                                @if ($contract->status === 'grace_period' && $contract->remaining_grace_period_days !== null)
                                                    <span
                                                        class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25">
                                                        <i class="fas fa-hourglass-half me-1"></i>
                                                        {{ number_format($contract->remaining_grace_period_days, 0) }} hari tersisa
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td data-label="Aksi" class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary open-boq-modal"
                                                    title="Lihat BOQ" aria-label="Lihat BOQ kontrak {{ $contract->contract_number }}"
                                                    data-bs-toggle="tooltip"
                                                    data-contract-number="{{ $contract->contract_number }}"
                                                    data-contract-name="{{ $contract->contract_name }}"
                                                    data-edit-url="{{ route('contracts.edit', $contract) }}"
                                                    data-boq-items="{{ $contract->boqItems->map(fn ($item) => ['description' => $item->description, 'unit' => $item->unit, 'quantity' => $item->quantity, 'unit_price' => $item->unit_price, 'subtotal' => $item->subtotal])->values()->toJson(JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}">
                                                    <i class="fas fa-list-ol"></i>
                                                </button>
                                                <a href="{{ route('contracts.show', $contract) }}"
                                                    class="btn btn-outline-info" title="Detail" data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('contracts.edit', $contract) }}"
                                                    class="btn contract-edit-action" title="Edit kontrak"
                                                    aria-label="Edit kontrak {{ $contract->contract_number }}"
                                                    data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger open-delete-modal"
                                                    title="Hapus" data-bs-toggle="tooltip"
                                                    data-action="{{ route('contracts.destroy', $contract) }}"
                                                    data-id="{{ $contract->id }}" data-status="{{ $contract->status }}"
                                                    data-name="{{ $contract->contract_name }}"
                                                    aria-label="Hapus kontrak {{ $contract->contract_name }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                {{-- Gmail Reminder Button --}}
                                                @if($contract->client && ($contract->client->email || $contract->client->pic_email))
                                                <button type="button"
                                                    class="btn btn-gmail-reminder"
                                                    style="background:#ea4335;border-color:#ea4335;color:#fff;"
                                                    title="Kirim Reminder Gmail"
                                                    data-bs-toggle="tooltip"
                                                    data-contract-id="{{ $contract->id }}"
                                                    data-contract-number="{{ $contract->contract_number }}"
                                                    data-contract-name="{{ $contract->contract_name }}"
                                                    data-client-email="{{ $contract->client->pic_email ?? $contract->client->email }}"
                                                    data-send-url="{{ route('contracts.send-gmail-reminder', $contract) }}"
                                                    aria-label="Kirim reminder Gmail untuk kontrak {{ $contract->contract_number }}">
                                                    <i class="fab fa-google"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ (auth()->user() && auth()->user()->role === 'admin') ? 11 : 10 }}" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-file-contract fa-3x mb-3"></i>
                                                <h4 class="h5">Tidak ada data kontrak</h4>
                                                <p class="mb-3">Mulai dengan membuat kontrak baru</p>
                                                <a href="{{ route('contracts.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus-circle me-2"></i> Buat Kontrak Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination (responsive + per-page selector) -->
                    @if ($contracts->total() > $contracts->perPage())
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-2">
                            <div class="text-center text-md-start w-100 w-md-auto">
                                <small class="text-muted d-block d-md-inline">
                                    Menampilkan {{ $contracts->firstItem() ?? 0 }}-{{ $contracts->lastItem() ?? 0 }} dari
                                    {{ $contracts->total() }} kontrak
                                </small>
                            </div>

                            <div class="d-flex align-items-center w-100 justify-content-center">
                                <!-- Per-page selector (hidden on extra-small screens) -->
                                <div class="me-3 d-none d-sm-block">
                                    <form id="perPageForm" method="GET" action="{{ route('contracts.index') }}">
                                        @foreach (request()->except(['page', 'per_page']) as $k => $v)
                                            @if (is_array($v))
                                                @foreach ($v as $sub)
                                                    <input type="hidden" name="{{ $k }}[]"
                                                        value="{{ $sub }}">
                                                @endforeach
                                            @else
                                                <input type="hidden" name="{{ $k }}"
                                                    value="{{ $v }}">
                                            @endif
                                        @endforeach

                                        <div class="input-group input-group-sm">
                                            <select name="per_page" id="per_page" class="form-select"
                                                onchange="this.form.submit()">
                                                @foreach ([10, 25, 50, 100] as $n)
                                                    <option value="{{ $n }}"
                                                        {{ request('per_page', $contracts->perPage()) == $n ? 'selected' : '' }}>
                                                        {{ $n }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>

                                <!-- Desktop pagination (full) -->
                                <nav aria-label="Page navigation" class="w-100">
                                    <div class="d-none d-md-block">
                                        {{ $contracts->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                                    </div>

                                    <!-- Mobile compact pagination (prev / page / next) -->
                                    <div class="d-block d-md-none w-100">
                                        <ul class="pagination pagination-sm justify-content-center mb-0">
                                            @if ($contracts->onFirstPage())
                                                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                            @else
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $contracts->previousPageUrl() }}">&laquo;</a></li>
                                            @endif

                                            <li class="page-item disabled"><span class="page-link">Halaman
                                                    {{ $contracts->currentPage() }} / {{ $contracts->lastPage() }}</span>
                                            </li>

                                            @if ($contracts->hasMorePages())
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $contracts->nextPageUrl() }}">&raquo;</a></li>
                                            @else
                                                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                                            @endif
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="contractBoqModal" tabindex="-1" aria-labelledby="contractBoqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="contractBoqModalLabel"><i class="fas fa-list-ol text-primary me-2"></i>BOQ Kontrak</h5>
                        <div class="small text-muted" id="boqContractMeta"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" id="boqTableContainer">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light"><tr><th>No.</th><th>Uraian pekerjaan/barang</th><th>Satuan</th><th class="text-end">Kuantitas</th><th class="text-end">Harga satuan</th><th class="text-end">Jumlah</th></tr></thead>
                            <tbody id="contractBoqRows"></tbody>
                            <tfoot><tr><th colspan="5" class="text-end">Total BOQ</th><th class="text-end" id="contractBoqTotal">Rp 0</th></tr></tfoot>
                        </table>
                    </div>
                    <div class="alert alert-light border mb-0 d-none" id="boqEmptyState">Belum ada rincian BOQ pada kontrak ini.</div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary" id="boqEditLink"><i class="fas fa-edit me-1"></i>Edit BOQ</a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bulkDeleteContractsModal" tabindex="-1" aria-labelledby="bulkDeleteContractsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('contracts.bulk-delete') }}" id="bulkDeleteContractsForm">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="bulkDeleteContractsModalLabel"><i class="fas fa-triangle-exclamation me-2"></i>Hapus kontrak terpilih</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Kontrak yang dipilih: <strong id="bulkDeleteSelectedCount">0</strong>.</p>
                        <p class="text-muted small mb-3">Kontrak aktif dan masa tenggang dilewati secara default. Item BOQ terkait juga akan dihapus.</p>
                        @foreach (request()->only(['status', 'client_id', 'search', 'contract_number', 'contract_type']) as $filterName => $filterValue)
                            @if ($filterValue !== '')
                                <input type="hidden" name="{{ $filterName }}" value="{{ $filterValue }}">
                            @endif
                        @endforeach
                        <div id="selectedContractIds"></div>
                        <input type="hidden" name="force" id="bulkDeleteForceValue" value="0">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="bulkDeleteForceCheckbox">
                            <label class="form-check-label" for="bulkDeleteForceCheckbox">Hapus juga kontrak aktif dan masa tenggang</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="bulkDeleteConfirmCheckbox" required>
                            <label class="form-check-label text-danger fw-semibold" for="bulkDeleteConfirmCheckbox">Saya memahami penghapusan ini tidak dapat dibatalkan.</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" id="bulkDeleteSubmit" disabled><i class="fas fa-trash me-1"></i>Hapus kontrak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteContractModal" tabindex="-1" aria-labelledby="deleteContractModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteContractModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kontrak <strong id="contractNameToDelete"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Peringatan:</strong> Data yang sudah dihapus tidak dapat dikembalikan.
                    </div>
                    <div id="forceDeleteWarning" class="alert alert-danger d-none">
                        <i class="fas fa-skull-crossbones me-2"></i>
                        <strong>Kontrak masih aktif atau dalam masa tenggang.</strong>
                        <p class="mb-0">Centang kotak di bawah ini untuk memaksa penghapusan. Ini akan menghapus kontrak
                            meskipun statusnya masih aktif.</p>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="deleteForceConfirm">
                            <label class="form-check-label" for="deleteForceConfirm">
                                Saya mengerti dan ingin <strong>memaksa hapus</strong> kontrak ini.
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <form id="deleteContractModalForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="contract_id" id="deleteContractId" value="">
                        <input type="hidden" name="force" id="deleteForceInput" value="0">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success popup will be shown using SweetAlert2 -->
    <!-- Removed Bootstrap toast — using popup instead -->
    @if (session('success'))
        <div id="contractSuccessMessage" data-message="{{ session('success') }}" hidden></div>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            const contractBoqModalEl = document.getElementById('contractBoqModal');
            const contractBoqModal = contractBoqModalEl ? new bootstrap.Modal(contractBoqModalEl) : null;
            const contractBoqRows = document.getElementById('contractBoqRows');
            const boqTableContainer = document.getElementById('boqTableContainer');
            const boqEmptyState = document.getElementById('boqEmptyState');
            const boqCurrency = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });
            const boqQuantity = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 3 });

            document.querySelectorAll('.open-boq-modal').forEach(button => {
                button.addEventListener('click', function() {
                    let items = [];
                    try {
                        items = JSON.parse(this.dataset.boqItems || '[]');
                    } catch (error) {
                        items = [];
                    }

                    contractBoqRows.replaceChildren();
                    let total = 0;
                    items.forEach((item, index) => {
                        const row = document.createElement('tr');
                        const values = [
                            index + 1,
                            item.description,
                            item.unit,
                            boqQuantity.format(Number(item.quantity)),
                            `Rp ${boqCurrency.format(Number(item.unit_price))}`,
                            `Rp ${boqCurrency.format(Number(item.subtotal))}`
                        ];
                        values.forEach((value, cellIndex) => {
                            const cell = document.createElement('td');
                            cell.textContent = value;
                            if (cellIndex >= 3) cell.className = 'text-end text-nowrap';
                            row.appendChild(cell);
                        });
                        contractBoqRows.appendChild(row);
                        total += Number(item.subtotal) || 0;
                    });

                    document.getElementById('boqContractMeta').textContent = `${this.dataset.contractNumber} - ${this.dataset.contractName}`;
                    document.getElementById('boqEditLink').href = this.dataset.editUrl;
                    document.getElementById('contractBoqTotal').textContent = `Rp ${boqCurrency.format(total)}`;
                    boqTableContainer.classList.toggle('d-none', items.length === 0);
                    boqEmptyState.classList.toggle('d-none', items.length !== 0);
                    contractBoqModal.show();
                });
            });

            // Quick Search Functionality
            const quickContractSearch = document.getElementById('quickContractSearch');
            const quickContractSearchBtn = document.getElementById('quickContractSearchBtn');

            function performQuickSearch() {
                if (!quickContractSearch) return;
                const searchValue = quickContractSearch.value.trim();
                const url = new URL(window.location);

                if (searchValue) {
                    url.searchParams.set('search', searchValue);
                } else {
                    url.searchParams.delete('search');
                }

                window.location.href = url.toString();
            }

            if (quickContractSearchBtn) {
                quickContractSearchBtn.addEventListener('click', performQuickSearch);
            }

            if (quickContractSearch) {
                quickContractSearch.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performQuickSearch();
                    }
                });
            }

            // Advanced Search Functionality
            const searchContractNumber = document.getElementById('searchContractNumber');
            const searchContractName = document.getElementById('searchContractName');
            const searchClient = document.getElementById('searchClient');
            const searchContractType = document.getElementById('searchContractType');
            const applyContractSearch = document.getElementById('applyContractSearch');
            const clearContractSearch = document.getElementById('clearContractSearch');

            if (applyContractSearch) {
                applyContractSearch.addEventListener('click', function() {
                    const url = new URL(window.location);

                    // Clear previous search params
                    url.searchParams.delete('contract_number');
                    url.searchParams.delete('search');
                    url.searchParams.delete('client_id');
                    url.searchParams.delete('contract_type');

                    // Add new search params
                    if (searchContractNumber && searchContractNumber.value.trim()) {
                        url.searchParams.set('contract_number', searchContractNumber.value.trim());
                    }
                    if (searchContractName && searchContractName.value.trim()) {
                        url.searchParams.set('search', searchContractName.value.trim());
                    }
                    if (searchClient && searchClient.value) {
                        url.searchParams.set('client_id', searchClient.value);
                    }
                    if (searchContractType && searchContractType.value) {
                        url.searchParams.set('contract_type', searchContractType.value);
                    }

                    window.location.href = url.toString();
                });
            }

            if (clearContractSearch) {
                clearContractSearch.addEventListener('click', function() {
                    if (searchContractNumber) searchContractNumber.value = '';
                    if (searchContractName) searchContractName.value = '';
                    if (searchClient) searchClient.value = '';
                    if (searchContractType) searchContractType.value = '';

                    const url = new URL(window.location);
                    ['contract_number', 'search', 'client_id', 'contract_type', 'page'].forEach(key => url.searchParams.delete(key));
                    window.location.href = url.toString();
                });
            }

            // Pre-fill advanced search fields from URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('contract_number')) {
                searchContractNumber.value = urlParams.get('contract_number');
            }
            if (urlParams.has('search') && !urlParams.has('contract_number')) {
                if (searchContractName) searchContractName.value = urlParams.get('search');
                if (quickContractSearch) quickContractSearch.value = urlParams.get('search');
            }
            if (urlParams.has('client_id')) {
                searchClient.value = urlParams.get('client_id');
            }
            if (urlParams.has('contract_type') && searchContractType) {
                searchContractType.value = urlParams.get('contract_type');
            }

            // Delete: open confirmation modal and submit shared DELETE form
            const deleteModalEl = document.getElementById('deleteContractModal');
            const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;
            const deleteModalForm = document.getElementById('deleteContractModalForm');
            const deleteContractId = document.getElementById('deleteContractId');
            const contractNameToDelete = document.getElementById('contractNameToDelete');

            document.querySelectorAll('.open-delete-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    const name = this.getAttribute('data-name');
                    const id = this.getAttribute('data-id');
                    const status = this.getAttribute('data-status');

                    // Prefer explicit action if provided
                    if (deleteModalForm) {
                        if (action) {
                            try {
                                const parsed = new URL(action, window.location.origin);
                                // force same-origin path to ensure cookies are sent with fetch
                                deleteModalForm.action = parsed.pathname + parsed.search;
                            } catch (e) {
                                deleteModalForm.action = action;
                            }
                        } else if (id) {
                            // Fallback to RESTful destroy path when action not set
                            deleteModalForm.action = `/contracts/${id}`;
                        }

                        // store id on the form (dataset) and hidden input for convenience
                        if (id) {
                            deleteModalForm.dataset.contractId = id;
                            if (deleteContractId) deleteContractId.value = id;
                        }
                    }

                    // Force-delete handling UI
                    const forceWarning = document.getElementById('forceDeleteWarning');
                    const forceCheckbox = document.getElementById('deleteForceConfirm');
                    const forceInput = document.getElementById('deleteForceInput');
                    const submitBtn = deleteModalForm ? deleteModalForm.querySelector(
                        'button[type="submit"]') : null;

                    if (forceWarning && forceCheckbox && forceInput && submitBtn) {
                        // reset state
                        forceWarning.classList.add('d-none');
                        forceCheckbox.checked = false;
                        forceInput.value = '0';
                        if (submitBtn) submitBtn.disabled = false;

                        if (status === 'active' || status === 'grace_period') {
                            // show warning and require checkbox to enable submit
                            forceWarning.classList.remove('d-none');
                            forceCheckbox.checked = false;
                            forceInput.value = '0';
                            if (submitBtn) submitBtn.disabled = true;

                            // Attach change listener once to toggle submit enabled
                            const onChange = function() {
                                forceInput.value = this.checked ? '1' : '0';
                                if (submitBtn) submitBtn.disabled = !this.checked;
                            };
                            forceCheckbox.removeEventListener('change', onChange);
                            forceCheckbox.addEventListener('change', onChange);
                        }
                    }

                    if (contractNameToDelete) {
                        contractNameToDelete.textContent = name || '';
                    }
                    if (deleteModal) {
                        deleteModal.show();
                    }
                });
            });

            // Popup helper using SweetAlert2 (fallback to alert)
            function showPopup(icon, message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: icon,
                        title: icon === 'success' ? 'Berhasil' : 'Gagal',
                        text: message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert(message);
                }
            }

            // If server-side session success exists, show popup
            const successMessage = document.getElementById('contractSuccessMessage')?.dataset.message;
            if (successMessage) {
                showPopup('success', successMessage);
            }

            // Intercept modal form submission to perform AJAX delete and show toast
            if (deleteModalForm) {
                deleteModalForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const action = this.action || (this.dataset.contractId ?
                        `/contracts/${this.dataset.contractId}` : null);
                    const id = this.dataset.contractId || (document.getElementById('deleteContractId') ?
                        document.getElementById('deleteContractId').value : null);
                    if (!action) {
                        // fallback: submit normally
                        this.submit();
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    // Ensure URL is same-origin absolute (avoid accidental cross-origin requests)
                    let fetchUrl = action;
                    try {
                        const parsed = new URL(fetchUrl, window.location.origin);
                        if (parsed.origin !== window.location.origin) {
                            // Use same-origin path to preserve session cookies for CSRF
                            fetchUrl = window.location.origin + parsed.pathname + parsed.search;
                        } else {
                            fetchUrl = parsed.href;
                        }
                    } catch (e) {
                        // if URL parsing fails, fall back to previous logic
                        if (fetchUrl && fetchUrl.startsWith('/')) {
                            fetchUrl = window.location.origin + fetchUrl;
                        }
                    }

                    // Append force flag when present in modal form
                    try {
                        const forceInput = document.getElementById('deleteForceInput');
                        if (forceInput && forceInput.value === '1') {
                            const sep = fetchUrl.includes('?') ? '&' : '?';
                            fetchUrl = fetchUrl + sep + 'force=1';
                        }
                    } catch (e) {
                        // ignore
                    }

                    // Log the request target for easier debugging when network fails
                    console.debug('DELETE request to', fetchUrl);

                    fetch(fetchUrl, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken || '',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) return response.json().catch(() => ({
                            success: true
                        }));
                        return response.text().then(text => {
                            const body = text || response.statusText || '';
                            const err = new Error(
                                `Delete failed: ${response.status} ${body}`);
                            err.status = response.status;
                            err.body = body;
                            throw err;
                        });
                    }).then(data => {
                        // hide modal
                        if (deleteModal) deleteModal.hide();

                        // show success popup
                        const message = (data && data.message) ? data.message :
                            'Kontrak berhasil dihapus.';
                        showPopup('success', message);

                        // remove row from table if present
                        if (id) {
                            const row = document.querySelector(
                                `.contract-row[data-contract-id="${id}"]`);
                            if (row) row.remove();
                        }

                        // Optionally update counts/badges if present
                    }).catch(err => {
                        console.error('Delete request failed', err);
                        const serverMessage = err && (err.body || err.message) ? (err.body || err
                            .message) : 'Gagal menghapus kontrak. Cek log untuk detail.';
                        showPopup('error', serverMessage);
                    });
                });
            }

            // Add subtle animation to Add Contract button on page load via CSS only

            // Filter chip hover effects
            document.querySelectorAll('.filter-chip').forEach(chip => {
                chip.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
                });

                chip.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K for search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    quickContractSearch.focus();
                    quickContractSearch.select();
                }

                // Ctrl/Cmd + N for new contract
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = "{{ route('contracts.create') }}";
                }
            });

            // Add keyboard shortcut hint
            const isMac = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
            const shortcutHint = isMac ? '⌘N' : 'Ctrl+N';

            if (addContractBtn) {
                addContractBtn.setAttribute('title', `Buat kontrak baru (${shortcutHint})`);
                addContractBtn.setAttribute('data-bs-toggle', 'tooltip');
                new bootstrap.Tooltip(addContractBtn);
            }

            // Export functionality
            document.getElementById('exportCSV')?.addEventListener('click', function(e) {
                e.preventDefault();
                exportData('csv');
            });

            document.getElementById('exportPDF')?.addEventListener('click', function(e) {
                e.preventDefault();
                exportData('pdf');
            });

            document.getElementById('exportExcel')?.addEventListener('click', function(e) {
                e.preventDefault();
                exportData('excel');
            });

            function exportData(type) {
                let url = "{{ route('contracts.export') }}?type=" + type;

                // Add current filters to export URL
                const currentParams = new URLSearchParams(window.location.search);
                currentParams.forEach((value, key) => {
                    url += '&' + key + '=' + encodeURIComponent(value);
                });

                window.location.href = url;
            }

            // Contract row click - navigate to show page
            document.querySelectorAll('.contract-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Don't navigate if clicking on checkbox, button, or link
                    if (e.target.matches('input[type="checkbox"]') ||
                        e.target.closest('.btn') ||
                        e.target.closest('a')) {
                        return;
                    }

                    const contractId = this.getAttribute('data-contract-id');
                    if (contractId) {
                        window.location.href = `/contracts/${contractId}`;
                    }
                });
            });

            // Highlight rows with urgent contracts
            document.querySelectorAll('.contract-row').forEach(row => {
                const daysRemaining = row.querySelector('.progress-bar');
                if (daysRemaining && daysRemaining.classList.contains('bg-danger')) {
                    row.classList.add('table-danger', 'bg-danger-subtle');
                } else if (daysRemaining && daysRemaining.classList.contains('bg-warning')) {
                    row.classList.add('table-warning', 'bg-warning-subtle');
                }
            });
        });
    </script>

    <script>
        (() => {
            const selectAllCheckboxes = [
                document.getElementById('selectAllContracts'),
                document.getElementById('selectAllContractsToolbar'),
            ].filter(Boolean);
            const contractCheckboxes = Array.from(document.querySelectorAll('.contract-checkbox'));
            const selectedCount = document.getElementById('selectedContractCount');
            const bulkActions = document.getElementById('bulkContractActions');

            if (!selectAllCheckboxes.length) return;

            const updateSelection = () => {
                const checkedCount = contractCheckboxes.filter((checkbox) => checkbox.checked).length;
                const allChecked = contractCheckboxes.length > 0 && checkedCount === contractCheckboxes.length;
                const partiallyChecked = checkedCount > 0 && !allChecked;

                contractCheckboxes.forEach((checkbox) => {
                    checkbox.closest('tr')?.classList.toggle('contract-row-selected', checkbox.checked);
                });

                selectAllCheckboxes.forEach((checkbox) => {
                    checkbox.checked = allChecked;
                    checkbox.indeterminate = partiallyChecked;
                    checkbox.disabled = contractCheckboxes.length === 0;
                });

                if (selectedCount) selectedCount.textContent = checkedCount;
                bulkActions?.classList.toggle('d-none', checkedCount === 0);
            };

            selectAllCheckboxes.forEach((selectAllCheckbox) => {
                selectAllCheckbox.addEventListener('change', () => {
                    contractCheckboxes.forEach((checkbox) => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                    updateSelection();
                });
            });

            contractCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', updateSelection);
            });

            updateSelection();

            const confirmCheckbox = document.getElementById('bulkDeleteConfirmCheckbox');
            const forceCheckbox = document.getElementById('bulkDeleteForceCheckbox');
            const forceValue = document.getElementById('bulkDeleteForceValue');
            const submitButton = document.getElementById('bulkDeleteSubmit');
            const deleteForm = document.getElementById('bulkDeleteContractsForm');
            const modalElement = document.getElementById('bulkDeleteContractsModal');
            const bulkDeleteModal = modalElement && window.bootstrap?.Modal
                ? new window.bootstrap.Modal(modalElement)
                : null;
            const selectedDeleteButton = document.getElementById('deleteSelectedContracts');

            confirmCheckbox?.addEventListener('change', () => {
                if (submitButton) submitButton.disabled = !confirmCheckbox.checked;
            });

            forceCheckbox?.addEventListener('change', () => {
                if (forceValue) forceValue.value = forceCheckbox.checked ? '1' : '0';
            });

            deleteForm?.addEventListener('submit', (event) => {
                if (!confirmCheckbox?.checked) {
                    event.preventDefault();
                    return;
                }

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menghapus...';
                }
            });

            selectedDeleteButton?.addEventListener('click', () => {
                const selectedIds = contractCheckboxes
                    .filter((checkbox) => checkbox.checked)
                    .map((checkbox) => checkbox.value);
                const idsContainer = document.getElementById('selectedContractIds');
                const countElement = document.getElementById('bulkDeleteSelectedCount');

                if (!selectedIds.length || !idsContainer || !bulkDeleteModal) return;

                idsContainer.replaceChildren(...selectedIds.map((id) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'contract_ids[]';
                    input.value = id;
                    return input;
                }));

                if (countElement) countElement.textContent = selectedIds.length;
                if (forceCheckbox) forceCheckbox.checked = false;
                if (forceValue) forceValue.value = '0';
                if (confirmCheckbox) confirmCheckbox.checked = false;
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-trash me-1"></i>Hapus kontrak terpilih';
                }

                bulkDeleteModal.show();
            });
        })();
    </script>

    <style>
        /* Modern Button Styles */
        #addContractBtn {
            background: linear-gradient(135deg, #20c997 0%, #198754 100%);
            border: none;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        #addContractBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        }

        /* Contract Icon */
        .contract-icon {
            transition: all 0.3s ease;
        }

        .contract-row:hover .contract-icon {
            transform: scale(1.1);
            background-color: #0d6efd !important;
            color: white !important;
        }

        /* Avatar Circle */
        .avatar-circle-sm {
            font-weight: bold;
            background: linear-gradient(135deg, #17a2b8, #0dcaf0);
            color: white;
        }

        /* Progress Bar for Days Remaining */
        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* Table Hover Effects */
        .contract-row {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .contract-row:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transform: translateX(2px);
        }

        /* Card Header Gradient */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
        }

        /* Filter Chip */
        .filter-chip {
            transition: all 0.2s ease;
            cursor: default;
        }

        .filter-chip:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .filter-chip a:hover {
            opacity: 0.8;
        }

        /* Status badge improvements */
        .badge.bg-success {
            background-color: #198754 !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        .badge.bg-secondary {
            background-color: #6726ff !important;
        }

        /* Placeholder color for search */
        .bg-white.bg-opacity-10::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .bg-white.bg-opacity-10::-webkit-input-placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .bg-white.bg-opacity-10::-moz-placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .bg-white.bg-opacity-10:-ms-input-placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header .d-flex {
                flex-direction: column;
                align-items: stretch !important;
            }

            .card-header .contract-page-actions {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr);
                align-items: stretch !important;
                gap: 0.5rem !important;
                width: 100% !important;
            }

            .card-header .contract-page-actions .contract-search-dropdown,
            .card-header .contract-page-actions .contract-status-dropdown,
            .card-header .contract-page-actions .contract-create-action {
                grid-column: 1 / -1;
            }

            .card-header .contract-page-actions .contract-search-dropdown,
            .card-header .contract-page-actions .contract-status-dropdown,
            .card-header .contract-page-actions .contract-control {
                width: 100% !important;
                min-width: 0;
            }

            .card-header .contract-page-actions .contract-control {
                min-height: 42px;
                justify-content: center;
                padding-inline: 0.5rem;
                font-size: 0.85rem;
            }

            .card-header .d-flex>div:last-child {
                margin-top: 1rem;
                width: 100%;
            }

            .card-header .d-flex>div:last-child>* {
                margin-bottom: 0.5rem;
                width: 100%;
                justify-content: center;
            }

            .input-group {
                width: 100% !important;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .contract-icon {
                width: 30px !important;
                height: 30px !important;
            }

            .avatar-circle-sm {
                width: 24px !important;
                height: 24px !important;
                font-size: 10px !important;
            }

            .card-footer .d-flex {
                flex-direction: column;
                gap: 0.5rem;
            }

            .card-footer .text-end {
                text-align: left !important;
            }
        }

        /* Animation for new contract button */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        #addContractBtn {
            animation: pulse 2s infinite;
        }

        /* Improved table styling */
        .table thead th {
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody td {
            vertical-align: middle;
            border-bottom: 1px solid #eef1f5;
        }

        /* Urgent contract styling */
        .table-danger {
            background-color: rgba(220, 53, 69, 0.05) !important;
        }

        .table-warning {
            background-color: rgba(255, 193, 7, 0.05) !important;
        }
    </style>


    {{-- ===== Modal Konfirmasi Gmail Reminder ===== --}}
    <div class="modal fade" id="gmailReminderModal" tabindex="-1" aria-labelledby="gmailReminderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background:#ea4335;color:#fff;">
                    <h5 class="modal-title" id="gmailReminderModalLabel">
                        <i class="fab fa-google me-2"></i>Kirim Reminder via Gmail
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div id="gmailReminderInfo">
                        <p class="mb-2">Anda akan mengirim email reminder ke:</p>
                        <ul class="list-unstyled ms-2">
                            <li><i class="fas fa-file-contract text-muted me-2"></i><strong>Kontrak:</strong> <span id="gmailModalContractNumber">-</span></li>
                            <li><i class="fas fa-envelope text-muted me-2"></i><strong>Email:</strong> <span id="gmailModalClientEmail">-</span></li>
                        </ul>
                        <p class="text-muted small mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Email dikirim melalui Gmail API menggunakan akun yang dikonfigurasi di sistem.
                        </p>
                    </div>
                    <div id="gmailReminderResult" class="d-none mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="gmailModalCancelBtn">Batal</button>
                    <button type="button" class="btn text-white" style="background:#ea4335;border-color:#ea4335;" id="gmailModalSendBtn">
                        <span id="gmailSendSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        <i class="fab fa-google me-1" id="gmailSendIcon"></i> Kirim Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        'use strict';

        let gmailSendUrl = '';

        // Open Gmail Reminder Modal
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-gmail-reminder');
            if (!btn) return;

            gmailSendUrl = btn.dataset.sendUrl || '';
            const contractNumber = btn.dataset.contractNumber || '-';
            const clientEmail    = btn.dataset.clientEmail    || '-';

            document.getElementById('gmailModalContractNumber').textContent = contractNumber;
            document.getElementById('gmailModalClientEmail').textContent    = clientEmail;

            // Reset state
            document.getElementById('gmailReminderResult').classList.add('d-none');
            document.getElementById('gmailReminderResult').innerHTML = '';
            document.getElementById('gmailReminderInfo').classList.remove('d-none');
            document.getElementById('gmailModalSendBtn').disabled = false;
            document.getElementById('gmailModalCancelBtn').textContent = 'Batal';

            const modal = new bootstrap.Modal(document.getElementById('gmailReminderModal'));
            modal.show();
        });

        // Send Gmail Reminder
        document.getElementById('gmailModalSendBtn')?.addEventListener('click', async function () {
            if (!gmailSendUrl) return;

            const sendBtn   = this;
            const spinner   = document.getElementById('gmailSendSpinner');
            const sendIcon  = document.getElementById('gmailSendIcon');
            const resultDiv = document.getElementById('gmailReminderResult');

            sendBtn.disabled = true;
            spinner.classList.remove('d-none');
            sendIcon.classList.add('d-none');

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const response  = await fetch(gmailSendUrl, {
                    method:  'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept':       'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({}),
                });

                const data = await response.json();

                resultDiv.classList.remove('d-none');
                document.getElementById('gmailReminderInfo').classList.add('d-none');

                if (data.success) {
                    resultDiv.innerHTML =
                        '<div class="alert alert-success mb-0"><i class="fas fa-check-circle me-2"></i>' +
                        escapeHtml(data.message) + '</div>';
                    document.getElementById('gmailModalCancelBtn').textContent = 'Tutup';
                } else {
                    resultDiv.innerHTML =
                        '<div class="alert alert-danger mb-0"><i class="fas fa-exclamation-circle me-2"></i>' +
                        escapeHtml(data.message || 'Terjadi kesalahan.') + '</div>';
                    sendBtn.disabled = false;
                }
            } catch (err) {
                resultDiv.classList.remove('d-none');
                document.getElementById('gmailReminderInfo').classList.add('d-none');
                resultDiv.innerHTML =
                    '<div class="alert alert-danger mb-0"><i class="fas fa-exclamation-circle me-2"></i>' +
                    'Gagal terhubung ke server. Coba lagi.' + '</div>';
                sendBtn.disabled = false;
            } finally {
                spinner.classList.add('d-none');
                sendIcon.classList.remove('d-none');
            }
        });

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }
    })();
    </script>

@endsection
