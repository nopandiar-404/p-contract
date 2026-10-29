<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('subtitle', 'Overview sistem monitoring kontrak')

@section('content')
    <style>
        /* Dashboard specific styles: modern, card-first layout */
        .row.g-4 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }

        .row.g-4>.col-xl-3>.stat-card,
        .row.g-4>.col-lg-6>.stat-card {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .row.g-4>.col-xl-3>.stat-card:hover,
        .row.g-4>.col-lg-6>.stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .row.g-4>.col-xl-3>.stat-card .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            font-size: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
            margin-right: 14px;
            flex-shrink: 0;
        }

        .row.g-4>.col-xl-3>.stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
            color: #212529;
        }

        .row.g-4>.col-xl-3>.stat-card .stat-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 600;
        }

        .row.g-4>.col-xl-3>.stat-card .stat-trend {
            font-size: 12px;
            margin-top: 6px;
        }

        /* Quick actions cards */
        .card.border-0.shadow-sm.h-100 {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card.border-0.shadow-sm.h-100:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        /* Right column widgets */
        .data-table .table {
            margin-bottom: 0;
        }

        /* Chart Container Responsive */
        .data-table canvas {
            max-height: 250px;
        }

        /* Tablet Layout (768px - 991px) */
        @media (max-width: 991px) {
            .row.g-4 {
                --bs-gutter-x: 0.75rem;
                --bs-gutter-y: 0.75rem;
            }

            .row.g-4>.col-xl-3>.stat-card,
            .row.g-4>.col-lg-6>.stat-card {
                flex-direction: row;
                text-align: left;
                padding: 16px;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 22px;
                margin-right: 12px;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-value {
                font-size: 28px;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-label {
                font-size: 12px;
            }
        }

        /* Mobile Layout (≤ 768px) */
        @media (max-width: 768px) {
            .row.g-4 {
                --bs-gutter-x: 0.5rem;
                --bs-gutter-y: 0.5rem;
            }

            .row.g-4>.col-xl-3>.stat-card,
            .row.g-4>.col-lg-6>.stat-card {
                flex-direction: column;
                text-align: center;
                padding: 14px;
                gap: 12px;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-icon {
                width: 56px;
                height: 56px;
                font-size: 20px;
                margin-right: 0;
                margin-bottom: 0;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-value {
                font-size: 24px;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-label {
                font-size: 11px;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-trend {
                font-size: 10px;
                margin-top: 4px;
            }

            /* Chart responsive on mobile */
            .data-table {
                overflow: hidden;
            }

            .data-table .p-3 {
                padding: 12px !important;
            }

            .data-table canvas {
                max-height: 200px;
            }
        }

        /* Small Mobile Layout (≤ 576px) */
        @media (max-width: 576px) {
            .row.g-4 {
                --bs-gutter-x: 0.5rem;
                --bs-gutter-y: 0.5rem;
            }

            .row.g-4>.col-xl-3>.stat-card,
            .row.g-4>.col-lg-6>.stat-card {
                padding: 12px;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-icon {
                width: 48px;
                height: 48px;
                font-size: 18px;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-value {
                font-size: 20px;
            }

            .row.g-4>.col-xl-3>.stat-card .stat-label {
                font-size: 10px;
            }

            .data-table .table-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .data-table .table-header h5 {
                width: 100%;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .data-table canvas {
                max-height: 160px;
            }
        }

        /* Improve table header look */
        .data-table .table-header {
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .data-table .table-header h5 {
            margin: 0;
            font-weight: 700;
        }

        /* Small tweak for action buttons */
        .btn-group .btn {
            border-radius: 8px;
        }

        /* Grace Period Table - Responsive Mobile Layout */
        @media (max-width: 768px) {
            .grace-period-table .table {
                font-size: 13px;
            }

            .grace-period-table .table thead {
                display: none;
            }

            .grace-period-table .table tbody tr {
                display: block;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                margin-bottom: 12px;
                padding: 12px;
                background: #fff;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            }

            .grace-period-table .table tbody td {
                display: block;
                padding: 8px 0;
                border: none;
                text-align: left;
            }

            .grace-period-table .table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: #495057;
                display: block;
                font-size: 12px;
                margin-bottom: 4px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .grace-period-table .table tbody td:first-child {
                padding-top: 0;
            }

            .grace-period-table .table tbody td:last-child {
                padding-bottom: 0;
            }

            /* Hide less important columns on mobile */
            .grace-period-table .table tbody td:nth-child(3),
            .grace-period-table .table tbody td:nth-child(4) {
                font-size: 12px;
                color: #6c757d;
            }

            /* Improve action buttons on mobile */
            .grace-period-table .btn-group {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 6px;
            }

            .grace-period-table .btn-group .btn {
                flex: 1;
                font-size: 12px;
                padding: 6px 8px;
                white-space: nowrap;
            }

            /* Progress bar on mobile */
            .grace-period-table .progress {
                width: 100% !important;
                height: 4px !important;
            }

            /* Badge styling on mobile */
            .grace-period-table .badge {
                font-size: 11px;
                padding: 4px 6px;
            }

            .grace-period-table .table tbody tr.table-danger {
                background-color: #fff5f5;
                border-color: #dc3545;
            }

            /* Responsive empty state */
            .grace-period-table .table tbody tr td[colspan] {
                display: block;
                text-align: center;
                padding: 24px 12px;
            }
        }

        @media (max-width: 576px) {
            .grace-period-table .table {
                font-size: 12px;
            }

            .grace-period-table .table tbody td {
                padding: 6px 0;
            }

            .grace-period-table .btn-group .btn {
                padding: 5px 6px;
                font-size: 11px;
            }

            .data-table .table-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .data-table .table-header h5 {
                width: 100%;
                margin-bottom: 8px;
            }


        /* Enhanced mobile responsiveness for statistic cards */
        @media (max-width: 768px) {
            .row.g-4 > .col-xl-3,
            .row.g-4 > .col-lg-6,
            .row.g-4 > .col-md-6 {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }

            .row.g-4 > .col-xl-3 > .stat-card,
            .row.g-4 > .col-lg-6 > .stat-card {
                width: 100%;
                padding: 14px;
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 10px;
            }

            .row.g-4 > .col-xl-3 > .stat-card .stat-icon {
                margin: 0 auto;
                box-shadow: 0 4px 12px rgba(67, 97, 238, 0.12);
            }

            .row.g-4 > .col-xl-3 > .stat-card .stat-value {
                font-size: 22px;
                line-height: 1.1;
            }

            .row.g-4 > .col-xl-3 > .stat-card .stat-label {
                font-size: 12px;
            }

            .row.g-4 > .col-xl-3 > .stat-card .stat-trend {
                font-size: 11px;
                margin-top: 6px;
            }
        }

        @media (max-width: 576px) {
            .row.g-4 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .row.g-4 > .col-xl-3 > .stat-card,
            .row.g-4 > .col-lg-6 > .stat-card {
                padding: 10px;
            }

            .row.g-4 > .col-xl-3 > .stat-card .stat-icon {
                width: 48px;
                height: 48px;
                font-size: 18px;
            }

            .row.g-4 > .col-xl-3 > .stat-card .stat-value {
                font-size: 18px;
            }
        }
            .data-table .table-header a {
                width: 100%;
            }
        }

        :root {
            --primary-color: #0072bc;
            --secondary-color: #e31e24;
            --dashboard-blue: #0072bc;
            --dashboard-red: #e31e24;
            --dashboard-ink: #17324d;
            --dashboard-line: #dce8f1;
            --dashboard-shadow: 0 8px 24px rgba(17, 58, 91, 0.08);
        }

        body {
            background: linear-gradient(135deg, #eaf5fc 0%, #f5f8fb 58%, #fff3f2 100%);
            color: var(--dashboard-ink);
        }

        .top-bar {
            border: 1px solid var(--dashboard-line);
            border-left: 4px solid var(--dashboard-blue);
            border-radius: 10px;
            box-shadow: var(--dashboard-shadow);
            margin-bottom: 20px;
        }

        .dashboard-grid {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }

        .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card {
            display: grid;
            grid-template-columns: 48px minmax(0, 1fr);
            grid-template-areas: "icon value" "icon label" "trend trend";
            align-content: center;
            align-items: center;
            gap: 4px 12px;
            min-height: 132px;
            padding: 16px;
            border: 1px solid var(--dashboard-line);
            border-radius: 10px;
            box-shadow: var(--dashboard-shadow);
        }

        .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card .stat-icon {
            grid-area: icon;
            width: 48px;
            height: 48px;
            margin: 0;
            border-radius: 10px;
            font-size: 20px;
        }

        .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card .stat-value {
            grid-area: value;
            margin: 0;
            font-size: 28px;
            line-height: 1;
            color: var(--dashboard-ink);
        }

        .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card .stat-label {
            grid-area: label;
            font-size: 12px;
            line-height: 1.35;
        }

        .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card .stat-trend {
            grid-area: trend;
            margin-top: 4px;
            font-size: 11px;
            line-height: 1.4;
        }

        .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card.active::before {
            background: var(--dashboard-blue);
        }

        .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card.expiring::before {
            background: linear-gradient(90deg, var(--dashboard-blue), #22b8cf);
        }

        .dashboard-grid .data-table {
            border: 1px solid var(--dashboard-line);
            border-radius: 10px;
            box-shadow: var(--dashboard-shadow);
        }

        .dashboard-grid .data-table .table-header {
            border-bottom: 1px solid #e8eff5;
        }

        .dashboard-grid .data-table .table-header h5 {
            color: var(--dashboard-ink);
        }

        .dashboard-grid .grace-period-table .table thead th {
            background: #f1f7fb;
            color: var(--dashboard-ink);
        }

        .main-content > footer,
        .main-content > footer p,
        .main-content > footer small {
            color: #60788c !important;
        }

        @media (max-width: 767.98px) {
            .main-content {
                padding: 14px 12px 24px;
            }

            .top-bar {
                display: grid;
                grid-template-columns: minmax(120px, 0.7fr) minmax(0, 1.3fr);
                align-items: center;
                gap: 12px;
                padding: 14px;
                text-align: left;
            }

            .page-title {
                min-width: 0;
            }

            .page-title h1 {
                font-size: 20px;
            }

            .page-title p {
                font-size: 12px;
            }

            .user-menu {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                grid-template-areas: "search notification" "profile toggle";
                align-items: center;
                gap: 8px 10px;
                width: 100%;
                min-width: 0;
            }

            .user-menu .search-box {
                grid-area: search;
                width: auto;
                max-width: none;
                min-width: 0;
            }

            .user-menu .search-box input {
                width: 100%;
                min-width: 0;
            }

            .user-menu .notification-btn {
                grid-area: notification;
                justify-self: center;
                width: 36px;
                height: 42px;
            }

            .user-menu .dropdown {
                grid-area: profile;
                justify-self: end;
                min-width: 0;
            }

            .user-menu .dropdown > .btn {
                max-width: 100%;
                padding: 8px 10px;
                font-size: 13px;
                white-space: nowrap;
            }

            .user-menu .menu-toggle {
                grid-area: toggle;
                justify-self: end;
                display: grid;
                place-items: center;
                width: 42px;
                height: 42px;
                padding: 0;
            }

        }

        @media (max-width: 480px) {
            .top-bar {
                grid-template-columns: minmax(0, 1fr);
            }

            .user-menu {
                grid-column: 1;
            }
        }

        @media (max-width: 380px) {
            .user-menu .user-name {
                display: none;
            }

            .user-menu .dropdown > .btn {
                display: grid;
                place-items: center;
                width: 42px;
                height: 42px;
                padding: 0;
            }
        }

        @media (max-width: 767.98px) {
            .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 {
                width: 50%;
            }

            .dashboard-grid.row.g-4 > .col-xl-8,
            .dashboard-grid.row.g-4 > .col-xl-4 {
                width: 100%;
            }

            .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card {
                min-height: 138px;
                padding: 12px;
                gap: 4px 8px;
            }

            .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card .stat-value {
                font-size: 24px;
            }

            .dashboard-grid .data-table canvas {
                max-height: 220px;
            }
        }

        @media (max-width: 380px) {
            .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card {
                grid-template-columns: 38px minmax(0, 1fr);
                gap: 4px 6px;
                padding: 10px;
            }

            .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card .stat-icon {
                width: 38px;
                height: 38px;
                font-size: 17px;
            }

            .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card .stat-value {
                font-size: 21px;
            }

            .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card .stat-label,
            .dashboard-grid.row.g-4 > .dashboard-stat.col-xl-3 > .stat-card .stat-trend {
                font-size: 10px;
            }
        }
    </style>

    <div class="row g-4 dashboard-grid">
        <!-- Statistik Cards -->
        <div class="col-xl-3 col-lg-6 col-md-6 dashboard-stat fade-in" style="animation-delay: 0.1s">
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value" data-stat="activeContracts">{{ $activeContracts ?? 0 }}</div>
                <div class="stat-label">Kontrak Aktif</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up trend-up"></i>
                    <span class="text-muted">+12% dari bulan lalu</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 dashboard-stat fade-in" style="animation-delay: 0.2s">
            <div class="stat-card grace">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value" data-stat="gracePeriodContracts">{{ $gracePeriodContracts ?? 0 }}</div>
                <div class="stat-label">Masa Tenggang</div>
                <div class="stat-trend">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    <span class="text-warning">{{ ($gracePeriodContracts ?? 0) > 0 ? 'Perlu perhatian' : 'Aman' }}</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 dashboard-stat fade-in" style="animation-delay: 0.3s">
            <div class="stat-card expiring">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-value" data-stat="expiringThisMonth">{{ $expiringThisMonth ?? 0 }}</div>
                <div class="stat-label">Berakhir Bulan Ini</div>
                <div class="stat-trend">
                    <i class="fas fa-bell text-info"></i>
                    <span class="text-info">{{ ($expiringThisMonth ?? 0) > 0 ? 'Perlu ditindak' : 'Tidak ada' }}</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 dashboard-stat fade-in" style="animation-delay: 0.4s">
            <div class="stat-card expired">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-value" data-stat="expiredContracts">{{ $expiredContracts ?? 0 }}</div>
                <div class="stat-label">Kontrak Kadaluarsa</div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-down trend-down"></i>
                    <span class="text-muted">-5% dari bulan lalu</span>
                </div>
            </div>
        </div>

        <!-- Charts and Main Content -->
        <div class="col-xl-8 col-lg-7 fade-in" style="animation-delay: 0.5s">
            <!-- Chart Container -->
            <div class="data-table mb-4">
                <div class="table-header">
                    <h5><i class="fas fa-chart-line me-2"></i> Tren Kontrak 6 Bulan ke Depan</h5>
                    <div class="dropdown">
                        <button id="contractsChartPeriodBtn" class="btn btn-outline-secondary btn-sm dropdown-toggle"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i> <span class="period-label">Per Bulan</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); changeChartPeriod('monthly')">Per Bulan</a></li>
                            <li><a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); changeChartPeriod('quarterly')">Per Kuartal</a></li>
                        </ul>
                    </div>
                </div>
                <div class="p-3">
                    <canvas id="contractsChart" height="250"
                        data-months="{{ json_encode(isset($monthlyExpiring) && is_array($monthlyExpiring) ? array_column($monthlyExpiring, 'month') : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) }}"
                        data-counts="{{ json_encode(isset($monthlyExpiring) && is_array($monthlyExpiring) ? array_column($monthlyExpiring, 'count') : [0, 0, 0, 0, 0, 0]) }}"></canvas>
                </div>
            </div>

        </div>

        <!-- Sidebar Widgets -->
        <div class="col-xl-4 col-lg-5 fade-in" style="animation-delay: 0.6s">

            <!-- Notifikasi Terbaru -->
            <div class="data-table">
                @php
                    $attentionCount =
                        ($gracePeriodContracts ?? 0) + ($expiringThisMonth ?? 0) + ($expiredContracts ?? 0);
                @endphp
                <div class="table-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0"><i class="fas fa-bell me-2"></i> Notifikasi Terbaru</h5>
                        @if ($attentionCount > 0)
                            <small class="text-muted">Terdapat kontrak yang perlu tindakan</small>
                        @else
                            <small class="text-muted">Status sistem terkini</small>
                        @endif
                    </div>
                    <div>
                        <span
                            class="badge bg-warning">{{ $attentionCount > 0 ? $attentionCount . ' perlu tindakan' : ($notifications ?? collect())->count() . ' baru' }}</span>
                    </div>
                </div>
                <div class="p-3">
                    {{-- Summary badges for attention counts --}}
                    <div class="mb-2">
                        @if (($gracePeriodContracts ?? 0) > 0)
                            <a href="{{ route('dashboard.grace-period') }}"
                                class="badge bg-warning text-decoration-none me-2">Masa Tenggang:
                                {{ $gracePeriodContracts }}</a>
                        @endif
                        @if (($expiringThisMonth ?? 0) > 0)
                            <span class="badge bg-info text-decoration-none me-2">Berakhir Bulan Ini:
                                {{ $expiringThisMonth }}</span>
                        @endif
                        @if (($expiredContracts ?? 0) > 0)
                            <span class="badge bg-danger text-decoration-none">Kadaluarsa: {{ $expiredContracts }}</span>
                        @endif
                    </div>
                    @forelse(($notifications ?? collect())->take(3) as $notification)
                        <div class="notification-item mb-3 pb-3 border-bottom">
                            <div class="d-flex">
                                <div class="notification-icon me-3">
                                    @if (($notification->type ?? '') == 'reminder')
                                        <i class="fas fa-clock text-warning fa-lg"></i>
                                    @elseif(($notification->type ?? '') == 'grace_period_start')
                                        {{ rtrim(rtrim(number_format($remainingGraceDays, 2, '.', ''), '0'), '.') }}
                                    @else
                                        <i class="fas fa-info-circle text-info fa-lg"></i>
                                    @endif
                                </div>
                                <div class="grow">
                                    <p class="mb-1" style="font-size: 14px;">{{ $notification->message ?? '' }}</p>
                                    <small
                                        class="text-muted">{{ optional($notification->created_at)->diffForHumans() ?? '' }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i class="fas fa-bell-slash text-muted fa-2x mb-2"></i>
                            <p class="text-muted mb-0">Tidak ada notifikasi</p>
                        </div>
                    @endforelse

                    @if (($notifications ?? collect())->count() > 0)
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary w-100 mt-2">
                            <i class="fas fa-list me-2"></i> Lihat Semua Notifikasi
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kontrak dalam Masa Tenggang (Full Width) -->
        <div class="col-12 fade-in" style="animation-delay: 0.7s">
            <div class="data-table grace-period-table">
                <div class="table-header">
                    <h5><i class="fas fa-exclamation-triangle me-2 text-warning"></i> Kontrak dalam Masa Tenggang</h5>
                    <a href="{{ route('dashboard.grace-period') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-external-link-alt me-1"></i> Lihat Detail
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" role="table">
                        <thead>
                            <tr>
                                <th>No. Kontrak</th>
                                <th>Nama Kontrak</th>
                                <th>Klien</th>
                                <th>Tanggal Berakhir</th>
                                <th>Masa Tenggang Hingga</th>
                                <th>Sisa Hari</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $inGracePeriodData = $inGracePeriod ?? collect();
                            @endphp

                            @forelse($inGracePeriodData as $contract)
                                @php
                                    $remainingGraceDays = $contract->remaining_grace_period_days ?? 0;
                                    $gracePeriodDays = $contract->grace_period_days ?? 30;
                                    $percentage = $gracePeriodDays > 0 ? (($gracePeriodDays - $remainingGraceDays) / $gracePeriodDays) * 100 : 0;
                                @endphp
                                <tr class="{{ $remainingGraceDays < 3 ? 'table-danger' : '' }}">
                                    <td data-label="No. Kontrak">
                                        <strong>#{{ $contract->contract_number }}</strong>
                                    </td>
                                    <td data-label="Nama Kontrak">
                                        <div>
                                            <strong>{{ $contract->contract_name }}</strong>
                                        </div>
                                        <small class="text-muted d-block">{{ Str::limit($contract->description ?? '', 40) }}</small>
                                    </td>
                                    <td data-label="Klien">
                                        @if ($contract->client)
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <div class="avatar-circle-sm bg-info text-white d-flex align-items-center justify-content-center rounded-circle"
                                                        style="width: 32px; height: 32px; font-size: 12px;">
                                                        {{ strtoupper(substr($contract->client->name, 0, 2)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $contract->client->name }}</div>
                                                    <div class="small text-muted mt-1">
                                                        @if(!empty($contract->client->pic_name))
                                                            <div><i class="fas fa-user me-1"></i> {{ $contract->client->pic_name }}</div>
                                                        @endif
                                                        @if(!empty($contract->client->email))
                                                            <div><i class="fas fa-envelope me-1"></i> <a href="mailto:{{ $contract->client->email }}">{{ $contract->client->email }}</a></div>
                                                        @endif
                                                        @if(!empty($contract->client->phone))
                                                            <div><i class="fas fa-phone me-1"></i> <a href="tel:{{ $contract->client->phone }}">{{ $contract->client->phone }}</a></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td data-label="Tanggal Berakhir" class="d-none d-md-table-cell">
                                        <div>{{ optional($contract->end_date)->format('d/m/Y') ?? 'N/A' }}</div>
                                        <small class="text-muted d-block">{{ optional($contract->end_date)->diffForHumans() ?? '' }}</small>
                                    </td>
                                    <td data-label="Masa Tenggang Hingga">
                                        <div>{{ optional($contract->grace_period_end_date)->format('d/m/Y') ?? 'N/A' }}</div>
                                        <small class="text-muted d-block">{{ optional($contract->grace_period_end_date)->diffForHumans() ?? '' }}</small>
                                    </td>
                                    <td data-label="Sisa Hari">
                                        <div class="progress" style="height: 6px; width: 100px;">
                                            <div class="progress-bar bg-{{ $remainingGraceDays < 7 ? 'danger' : 'warning' }}"
                                                data-progress-width="{{ $percentage }}"></div>
                                        </div>
                                        <span class="badge bg-{{ $remainingGraceDays < 7 ? 'danger' : 'warning' }} mt-1">
                                            {{ number_format($remainingGraceDays, 0) }} hari
                                        </span>
                                    </td>
                                    <td data-label="Status" class="d-none d-md-table-cell">
                                        <span class="badge badge-grace"><i class="fas fa-clock me-1"></i> Masa Tenggang</span>
                                    </td>
                                    <td data-label="Aksi">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('contracts.show', $contract) }}"
                                                class="btn btn-outline-info" title="Detail"><i
                                                    class="fas fa-eye"></i><span class="d-none d-sm-inline ms-1">Detail</span></a>
                                            <a href="{{ route('contracts.edit', $contract) }}"
                                                class="btn btn-outline-warning" title="Edit"><i
                                                    class="fas fa-edit"></i><span class="d-none d-sm-inline ms-1">Edit</span></a>
                                            <button class="btn btn-outline-danger dashboard-send-reminder"
                                                data-reminder-url="{{ route('contracts.send-reminder', $contract) }}"
                                                title="Kirim Reminder"><i class="fas fa-bell"></i><span class="d-none d-sm-inline ms-1">Reminder</span></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                        <h5 class="text-muted">Tidak ada kontrak dalam masa tenggang</h5>
                                        <p class="text-muted">Semua kontrak dalam kondisi baik</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            function renderFallbackLineChart(canvas, labels, values) {
                if (!canvas) return;

                const ctx = canvas.getContext('2d');
                if (!ctx) return;

                const width = canvas.clientWidth || 640;
                const height = canvas.clientHeight || 250;
                const dpr = window.devicePixelRatio || 1;

                canvas.width = width * dpr;
                canvas.height = height * dpr;
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                ctx.clearRect(0, 0, width, height);

                const padding = { top: 20, right: 16, bottom: 32, left: 36 };
                const chartWidth = width - padding.left - padding.right;
                const chartHeight = height - padding.top - padding.bottom;
                const maxValue = Math.max(...values.map(Number), 0) + 1;
                const minValue = 0;

                ctx.strokeStyle = '#dfe7f1';
                ctx.lineWidth = 1;
                for (let i = 0; i <= 4; i++) {
                    const y = padding.top + (chartHeight / 4) * i;
                    ctx.beginPath();
                    ctx.moveTo(padding.left, y);
                    ctx.lineTo(width - padding.right, y);
                    ctx.stroke();
                }

                ctx.strokeStyle = '#aebdcb';
                ctx.beginPath();
                ctx.moveTo(padding.left, padding.top);
                ctx.lineTo(padding.left, height - padding.bottom);
                ctx.lineTo(width - padding.right, height - padding.bottom);
                ctx.stroke();

                const points = values.map((value, index) => {
                    const x = padding.left + (chartWidth / Math.max(labels.length - 1, 1)) * index;
                    const y = padding.top + chartHeight - ((Number(value) - minValue) / Math.max(maxValue - minValue, 1)) * chartHeight;
                    return { x, y, value: Number(value) };
                });

                if (points.length > 0) {
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = '#0072bc';
                    ctx.fillStyle = 'rgba(0, 114, 188, 0.12)';

                    ctx.beginPath();
                    ctx.moveTo(points[0].x, points[0].y);

                    for (let i = 1; i < points.length; i++) {
                        const prev = points[i - 1];
                        const curr = points[i];
                        const cx = (prev.x + curr.x) / 2;
                        ctx.bezierCurveTo(cx, prev.y, cx, curr.y, curr.x, curr.y);
                    }

                    const last = points[points.length - 1];
                    ctx.lineTo(last.x, last.y);
                    ctx.lineTo(last.x, height - padding.bottom);
                    ctx.lineTo(points[0].x, height - padding.bottom);
                    ctx.closePath();
                    ctx.fill();

                    ctx.beginPath();
                    ctx.moveTo(points[0].x, points[0].y);
                    for (let i = 1; i < points.length; i++) {
                        const prev = points[i - 1];
                        const curr = points[i];
                        const cx = (prev.x + curr.x) / 2;
                        ctx.bezierCurveTo(cx, prev.y, cx, curr.y, curr.x, curr.y);
                    }
                    ctx.stroke();

                    ctx.fillStyle = '#0072bc';
                    points.forEach(point => {
                        ctx.beginPath();
                        ctx.arc(point.x, point.y, 4, 0, Math.PI * 2);
                        ctx.fill();
                    });
                }

                ctx.fillStyle = '#5b7088';
                ctx.font = '12px sans-serif';
                labels.forEach((label, index) => {
                    const x = padding.left + (chartWidth / Math.max(labels.length, 1)) * index + (chartWidth / Math.max(labels.length, 1)) / 2;
                    ctx.fillText(label, x - 12, height - 10);
                });
            }

            // Chart Configuration
            const chartCanvas = document.getElementById('contractsChart');
            if (!chartCanvas) {
                throw new Error('Canvas contractsChart tidak ditemukan');
            }

            const ctx = chartCanvas.getContext('2d');
            const months = JSON.parse(chartCanvas.dataset.months || '[]');
            const counts = JSON.parse(chartCanvas.dataset.counts || '[]').map(Number);
            let contractsChart = null;

            if (typeof Chart !== 'undefined') {
                contractsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Kontrak Berakhir',
                            data: counts,
                            borderColor: '#0072bc',
                            backgroundColor: 'rgba(0, 114, 188, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            cubicInterpolationMode: 'monotone',
                            pointBackgroundColor: '#0072bc',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: Math.max(...counts.map(Number), 0) + 1,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            } else {
                renderFallbackLineChart(chartCanvas, months, counts);
            }

            // Chart period control and server-driven time-series
            let currentChartPeriod = 'monthly';

            function updateContractsChart(labels, values) {
                if (!Array.isArray(labels) || !Array.isArray(values)) return;
                const numValues = values.map(v => Number(v) || 0);

                if (contractsChart) {
                    contractsChart.data.labels = labels;
                    if (contractsChart.data.datasets && contractsChart.data.datasets[0]) {
                        contractsChart.data.datasets[0].data = numValues;
                    }
                    if (contractsChart.options && contractsChart.options.scales && contractsChart.options.scales.y) {
                        contractsChart.options.scales.y.suggestedMax = Math.max(...numValues, 0) + 1;
                    }
                    contractsChart.update();
                    return;
                }

                renderFallbackLineChart(chartCanvas, labels, numValues);
            }

            async function fetchTimeSeries(period) {
                try {
                    const url = "{{ route('reports.distribution') }}?period=" + encodeURIComponent(period);
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    if (!res.ok) {
                        console.warn('Gagal memuat data chart dari server. Status:', res.status);
                        return;
                    }

                    const contentType = res.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        console.warn('Respons chart bukan format JSON:', contentType);
                        return;
                    }

                    const json = await res.json();
                    if (!json || !json.success || !json.data) {
                        console.warn('Data chart tidak valid dari server:', json);
                        return;
                    }

                    const payload = json.data;
                    // payload expected { labels: [...], counts: [...] }
                    if (payload && Array.isArray(payload.labels) && Array.isArray(payload.counts)) {
                        updateContractsChart(payload.labels, payload.counts);
                        return;
                    }

                    // fallback: associative counts -> simple pie-like labels
                    if (payload && typeof payload === 'object') {
                        const labels = ['Aktif', 'Masa Tenggang', 'Kadaluarsa'];
                        const counts = [payload.active || 0, payload.grace_period || 0, payload.expired || 0];
                        updateContractsChart(labels, counts);
                        return;
                    }
                } catch (err) {
                    console.error('Kesalahan saat memuat data chart:', err);
                }
            }

            function changeChartPeriod(period) {
                if (!['monthly', 'quarterly'].includes(period)) period = 'monthly';
                currentChartPeriod = period;
                const btn = document.getElementById('contractsChartPeriodBtn');
                if (btn) {
                    const labelSpan = btn.querySelector('.period-label');
                    if (labelSpan) labelSpan.textContent = period === 'monthly' ? 'Per Bulan' : 'Per Kuartal';
                }
                fetchTimeSeries(period);
            }

            // Initialize chart
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[data-progress-width]').forEach(progressBar => {
                    const width = Number(progressBar.dataset.progressWidth);
                    progressBar.style.width = `${Math.min(100, Math.max(0, width))}%`;
                });

                // Chart awal sudah dirender langsung dengan data default (monthly) dari server HTML
                // Muat ulang data bulanan dari server agar rentang bulan selalu 6 bulan ke depan.
                fetchTimeSeries('monthly');
            });

            document.querySelectorAll('.dashboard-send-reminder').forEach(button => {
                button.addEventListener('click', async function() {
                    if (!confirm('Kirim reminder untuk kontrak ini?')) return;

                    this.disabled = true;
                    showLoading('Mengirim reminder...');

                    try {
                        const response = await fetch(this.dataset.reminderUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        });
                        const result = await response.json().catch(() => ({}));
                        showToast(result.message || (response.ok ? 'Reminder berhasil dikirim.' : 'Gagal mengirim reminder.'), response.ok && result.success ? 'success' : 'error');
                    } catch (error) {
                        showToast('Gagal mengirim reminder. Periksa koneksi lalu coba lagi.', 'error');
                    } finally {
                        hideLoading();
                        this.disabled = false;
                    }
                });
            });

            // Rest of JavaScript code...
            // Helper: simple loading overlay and toast for this page
            function showLoading(message = 'Memproses...') {
                let loading = document.getElementById('loadingOverlayIndex');
                if (!loading) {
                    loading = document.createElement('div');
                    loading.id = 'loadingOverlayIndex';
                    loading.className =
                        'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
                    loading.style.backgroundColor = 'rgba(0,0,0,0.5)';
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
                const loading = document.getElementById('loadingOverlayIndex');
                if (loading) loading.remove();
            }

            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = 'position-fixed top-0 end-0 p-3';
                toast.style.zIndex = '9999';
                const bg = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
                toast.innerHTML = `
                    <div class="toast show" role="alert">
                        <div class="toast-header ${bg} text-white"><strong class="me-auto">${type.toUpperCase()}</strong></div>
                        <div class="toast-body">${message}</div>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }

            // Generate and download laporan masa tenggang (PDF) when button clicked
            async function generateReport() {
                const date = new Date().toISOString().split('T')[0];
                const url = `/contracts/export?type=pdf&status=grace_period`;
                showLoading('Mempersiapkan laporan (PDF)...');

                try {
                    const res = await fetch(url, {
                        credentials: 'include'
                    });
                    const contentType = (res.headers.get('Content-Type') || '').toLowerCase();

                    if (!res.ok) {
                        hideLoading();
                        showToast('Gagal menghasilkan laporan', 'error');
                        return;
                    }

                    if (contentType.includes('text/html')) {
                        // Probably redirected to login or error page
                        hideLoading();
                        showToast('Gagal: server mengembalikan HTML. Pastikan Anda sudah login.', 'error');
                        return;
                    }

                    const disposition = res.headers.get('Content-Disposition') || '';
                    let filename = `laporan-masa-tenggang-${date}.pdf`;
                    const m = /filename="?([^";]+)"?/.exec(disposition);
                    if (m && m[1]) filename = m[1];

                    const blob = await res.blob();
                    const blobUrl = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    URL.revokeObjectURL(blobUrl);

                    hideLoading();
                    showToast('Laporan berhasil diunduh', 'success');
                } catch (err) {
                    hideLoading();
                    console.error(err);
                    showToast('Terjadi kesalahan saat mengunduh laporan', 'error');
                }
            }
        </script>
    @endsection
@endsection
