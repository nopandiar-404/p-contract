<!-- resources/views/layouts/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> - SIMOKO</title>

    <!-- Favicon Pelindo -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/icon-pelindo.png')); ?>">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js (fallback-safe) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    <!-- ApexCharts (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --danger-color: #7209b7;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: min(var(--sidebar-width), calc(100vw - 2rem));
            max-width: calc(100vw - 2rem);
            background: linear-gradient(180deg, #142b40 0%, #102438 100%);
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: transform 0.3s ease, width 0.3s ease, background 0.3s ease;
            z-index: 1000;
            box-shadow: 10px 0 30px rgba(10, 34, 54, 0.2);
        }

        .sidebar-brand {
            padding: 18px 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex: 0 0 auto;
        }

        .sidebar-brand h3 {
            margin: 0;
            font-weight: 600;
            color: #fff;
            justify-content: flex-start;
        }

        .sidebar-brand .logo-icon {
            font-size: 24px;
            color: #4361ee;
            margin-right: 10px;
        }

        .sidebar-menu {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior-y: contain;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.25) transparent;
            padding: 16px 0 20px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
            min-height: 48px;
            border-left: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(0, 114, 188, 0.22);
            color: white;
            border-left-color: #0072bc;
            transform: none;
        }

        .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            font-size: 18px;
        }

        .nav-link .badge {
            margin-left: auto;
            background: #e31e24;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            min-width: 0;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }

        /* Header */
        .top-bar {
            background: white;
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .page-title p {
            margin: 5px 0 0;
            color: #6c757d;
            font-size: 14px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 20px;
            color: #6c757d;
            cursor: pointer;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--warning-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card.active::before {
            background: linear-gradient(90deg, #2ecc71, #27ae60);
        }

        .stat-card.grace::before {
            background: linear-gradient(90deg, #f39c12, #d35400);
        }

        .stat-card.expiring::before {
            background: linear-gradient(90deg, #3498db, #2980b9);
        }

        .stat-card.expired::before {
            background: linear-gradient(90deg, #e74c3c, #c0392b);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
            color: white;
        }

        .stat-card.active .stat-icon {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .stat-card.grace .stat-icon {
            background: linear-gradient(135deg, #f39c12, #d35400);
        }

        .stat-card.expiring .stat-icon {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .stat-card.expired .stat-icon {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin: 10px 0;
            color: var(--dark-color);
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
        }

        .stat-trend {
            font-size: 12px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .trend-up {
            color: #2ecc71;
        }

        .trend-down {
            color: #e74c3c;
        }

        /* Tables */
        .data-table {
            background: white;
            border-radius: 15px;
            overflow: scroll;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive .table {
            min-width: 100%;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            border: none;
            padding: 15px 20px;
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Badges */
        .badge {
            padding: 10px 10px;
            font-weight: 500;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-active {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
        }

        .badge-grace {
            background: rgba(243, 156, 18, 0.1);
            color: #d35400;
        }

        .badge-expiring {
            background: rgba(52, 152, 219, 0.1);
            color: #2980b9;
        }

        .badge-expired {
            background: rgba(231, 76, 60, 0.1);
            color: #c0392b;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        /* Progress Bars */
        .progress {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 4px;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            }

            .main-content {
                margin-left: 0;
                padding: 18px 16px 30px;
            }

            .content-wrapper {
                width: 100%;
            }

            .table thead th,
            .table tbody td {
                white-space: normal;
                word-break: break-word;
            }

            .table-responsive {
                margin-bottom: 1rem;
            }

            .search-box {
                width: 100%;
                max-width: 100%;
            }

            .search-box input {
                width: 100%;
            }

            .menu-toggle {
                display: block;
                border: none;
                background: rgba(255, 255, 255, 0.95);
                color: #4361ee;
                padding: 0.55rem 0.75rem;
                border-radius: 999px;
                box-shadow: 0 6px 18px rgba(8, 35, 79, 0.12);
                transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
            }

            .menu-toggle:hover,
            .menu-toggle:focus {
                transform: translateY(-1px);
                background: #ffffff;
                box-shadow: 0 10px 25px rgba(8, 35, 79, 0.16);
                outline: none;
            }

            .menu-toggle i {
                transition: transform 0.25s ease, color 0.25s ease;
            }

            .menu-toggle.open i {
                transform: rotate(90deg);
            }
        }

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                align-items: stretch;
            }

            .user-menu {
                justify-content: center;
                flex-wrap: wrap;
                gap: 10px;
            }

            .search-box {
                width: 100%;
                max-width: none;
            }

            .notification-btn {
                width: auto;
            }

            .page-title h1 {
                font-size: 22px;
            }

            .page-title p {
                font-size: 13px;
            }

            .stat-card {
                margin-bottom: 20px;
            }

            .table-header {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .table-header .d-flex {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .btn-group-actions {
                flex-direction: column;
            }

            .btn-group-actions .btn {
                width: 100%;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Loading Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Search Box */
        .search-box {
            position: relative;
            max-width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        /* Responsive logo */
        .sidebar-brand img.site-logo {
            height: 25px;
            width: auto;
            max-width: 100%;
            display: inline-block;
            vertical-align: middle;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        @media (max-width: 1200px) {
            .sidebar-brand img.site-logo {
                height: 24px;
            }
        }

        @media (max-width: 768px) {
            .sidebar-brand img.site-logo {
                height: 20px;
            }

            .sidebar-brand {
                padding: 12px 10px;
            }

            .page-title h1 {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {

            /* On very small screens keep the logo but hide the text to preserve space */
            .sidebar-brand span {
                display: none;
            }

            .sidebar-brand img.site-logo {
                height: 28px;
            }
        }

        /* Modern UI tweaks */
        :root {
            --card-radius: 14px;
            --card-shadow: 0 8px 30px rgba(8, 35, 79, 0.08);
            --muted: #6c757d;
        }

        .card,
        .data-table {
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
        }

        .data-table {
            overflow: hidden;
        }

        .table thead th {
            background: transparent;
            color: #444;
            font-weight: 700;
        }

        .table tbody tr {
            transition: background .18s ease, transform .12s ease;
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(67, 97, 238, 0.06);
        }

        .btn-outline-info,
        .btn-outline-warning,
        .btn-outline-danger {
            border-radius: 10px;
            padding: .45rem .55rem;
        }

        .contract-avatar {
            font-weight: 700;
        }

        /* Mobile sidebar overlay */
        .mobile-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            border: 0;
            padding: 0;
            z-index: 999;
            opacity: 0;
            display: none;
            pointer-events: none;
            cursor: pointer;
            transition: opacity .18s ease;
        }

        .mobile-sidebar-overlay.show {
            display: block;
            opacity: 1;
            pointer-events: auto;
        }

        body.sidebar-open {
            overflow: hidden;
        }

        /* Show/Hide helpers for responsive widget swapping */
        .desktop-only {
            display: block;
        }

        .mobile-only {
            display: none;
        }

        @media (max-width: 768px) {
            .desktop-only {
                display: none !important;
            }

            .mobile-only {
                display: block !important;
            }
        }

        /* Mobile list card style */
        .mobile-contract-card {
            background: white;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 6px 18px rgba(8, 35, 79, 0.06);
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 12px;
        }

        .mobile-contract-card .meta {
            flex: 1;
        }

        .mobile-contract-card .meta .title {
            font-weight: 700;
        }

        .mobile-contract-card .meta .subtitle {
            font-size: 12px;
            color: var(--muted);
        }

        .mobile-contract-card .actions {
            display: flex;
            gap: 6px;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <?php
        use Carbon\Carbon;

        $graceCount = $gracePeriodContracts ?? null;
        if ($graceCount === null) {
            try {
                $graceCount = \App\Models\Contract::where('status', 'grace_period')->count();
            } catch (\Throwable $e) {
                $graceCount = 0;
            }
        }

        $expiredCount = $expiredContracts ?? null;
        if ($expiredCount === null) {
            try {
                $expiredCount = \App\Models\Contract::where('status', 'expired')->count();
            } catch (\Throwable $e) {
                $expiredCount = 0;
            }
        }

        $expiringCount = $expiringThisMonth ?? null;
        if ($expiringCount === null) {
            try {
                $today = Carbon::now();
                $expiringCount = \App\Models\Contract::where('status', 'active')
                    ->whereDate('end_date', '<=', $today->copy()->addDays(30))
                    ->whereDate('end_date', '>=', $today)
                    ->count();
            } catch (\Throwable $e) {
                $expiringCount = 0;
            }
        }

        // Determine user scope
        $userId = auth()->id();
        $isAdmin = auth()->user() && auth()->user()->role === 'admin';

        // Ensure variables are numeric: support integers, numeric strings, and Collections/arrays
        $toInt = function ($v) {
            if (is_countable($v)) {
                return count($v);
            }
            if (is_numeric($v)) {
                return intval($v);
            }
            return 0;
        };

        $attentionCount = $toInt($graceCount) + $toInt($expiringCount) + $toInt($expiredCount);
        // compute total/active with proper scoping if controller did not provide them
        $total = $totalContracts ?? null;
        if ($total === null) {
            try {
                $totalQuery = \App\Models\Contract::query();
                if (! $isAdmin) $totalQuery->where('created_by', $userId);
                $total = $totalQuery->count();
            } catch (\Throwable $e) {
                $total = 0;
            }
        }

        $active = $activeContracts ?? null;
        if ($active === null) {
            try {
                $activeQuery = \App\Models\Contract::where('status', 'active');
                if (! $isAdmin) $activeQuery->where('created_by', $userId);
                $active = $activeQuery->count();
            } catch (\Throwable $e) {
                $active = 0;
            }
        }

        $percentage = $total > 0 ? ($active / $total) * 100 : 0;
        ?>

    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="dashboardSidebar">
            <div class="sidebar-brand">
                <h3 class="d-flex align-items-center justify-content-center gap-2">
                    <img src="<?php echo e(asset('images/logo-pelindo.png')); ?>" alt="Pelindo" class="site-logo" />
                    <span style="font-weight:600; height:42px;">Contract</span>
                </h3>
            </div>

            <nav class="sidebar-menu" aria-label="Navigasi utama">
                <a href="<?php echo e(route('dashboard')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?php echo e(route('contracts.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('contracts.*') ? 'active' : ''); ?>">
                    <i class="fas fa-file-contract"></i>
                    <span>Kontrak</span>
                    <span class="badge"><?php echo e($active ?? 0); ?></span>
                </a>

                <a href="<?php echo e(route('boq.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('boq.*') ? 'active' : ''); ?>">
                    <i class="fas fa-list-ol"></i>
                    <span>BOQ Kontrak</span>
                </a>

                <a href="<?php echo e(route('clients.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('clients.*') ? 'active' : ''); ?>">
                    <i class="fas fa-users"></i>
                    <span>Vendor</span>
                </a>

                <a href="<?php echo e(route('contract-types.index')); ?>" class="nav-link <?php echo e(request()->routeIs('contract-types.*') ? 'active' : ''); ?>">
                    <i class="fas fa-layer-group"></i>
                    <span>Jenis Kontrak</span>
                    <span class="badge"><?php echo e($contractTypeCount ?? count(config('contract_types.types', []))); ?></span>
                </a>
                <a href="<?php echo e(route('dashboard.grace-period')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('dashboard.grace-period') ? 'active' : ''); ?>">
                    <i class="fas fa-clock"></i>
                    <span>Masa Tenggang</span>

                </a>

                <a href="<?php echo e(route('notifications.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>">
                    <i class="fas fa-bell"></i>
                    <span>Notifikasi</span>
                    <span class="badge"><?php echo e($unreadNotifications ?? 0); ?></span>
                </a>

                <a href="<?php echo e(route('reports')); ?>" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>

                <a href="<?php echo e(route('settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>

                <div class="card bg-dark border-0">
                    <div class="card-body p-3">
                        <small class="text-muted">Total Kontrak</small>
                        <h5 class="mb-0"><?php echo e($total); ?></h5>
                        <div class="progress mt-2" style="height: 3px;">
                            <div class="progress-bar bg-primary dashboard-progress" data-progress="<?php echo e($percentage); ?>"></div>
                        </div>
                        <small class="text-muted"><?php echo e($active); ?> aktif</small>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar fade-in">
                <div class="page-title">
                    <h1><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
                    <p><?php echo $__env->yieldContent('subtitle', 'Monitor masa tenggang kontrak dengan mudah'); ?></p>
                </div>

                <div class="user-menu">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari kontrak..." id="searchInput">
                    </div>

                    <button class="notification-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"><?php echo e($unreadNotifications ?? 0); ?></span>
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-label="Menu pengguna <?php echo e(auth()->user()->name ?? 'Admin'); ?>">
                            <i class="fas fa-user-circle"></i> <span class="user-name"><?php echo e(auth()->user()->name ?? 'Admin'); ?></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>"><i class="fas fa-user me-2"></i> Profile</a>
                            </li>
                            <li><a class="dropdown-item" href="<?php echo e(route('settings.index')); ?>"><i class="fas fa-cog me-2"></i> Settings</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item"><i
                                            class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <button class="btn btn-primary d-lg-none menu-toggle" id="menuToggle" type="button"
                        aria-label="Buka menu navigasi" aria-controls="dashboardSidebar" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="content-wrapper">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

            <!-- Footer -->
            <footer class="mt-5 text-center text-muted">
                <p class="mb-0" style="color: #ffffff;">© <?php echo e(date('Y')); ?> Sistem Informasi Tanjung Priok • Sistem Monitoring Kontrak</p>
                <small style="color: #ffffff;">Last updated: <?php echo e(now()->format('d M Y H:i')); ?></small>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile menu drawer controls
        (function() {
            const toggle = document.getElementById('menuToggle');
            const sidebar = document.querySelector('.sidebar');
            const mobileMenu = window.matchMedia('(max-width: 992px)');

            if (!toggle || !sidebar) {
                return;
            }

            function createOverlay() {
                let overlay = document.querySelector('.mobile-sidebar-overlay');
                if (!overlay) {
                    overlay = document.createElement('button');
                    overlay.type = 'button';
                    overlay.className = 'mobile-sidebar-overlay';
                    overlay.setAttribute('aria-label', 'Tutup menu navigasi');
                    document.body.appendChild(overlay);
                    overlay.addEventListener('click', function() {
                        setSidebarOpen(false);
                    });
                }
                return overlay;
            }

            function updateToggleIcon(isOpen) {
                const icon = toggle.querySelector('i');
                if (!icon) return;

                if (isOpen) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                    toggle.classList.add('open');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                    toggle.classList.remove('open');
                }
            }

            function setSidebarOpen(isOpen) {
                const isActive = mobileMenu.matches && isOpen;
                const overlay = isActive ? createOverlay() : document.querySelector('.mobile-sidebar-overlay');

                sidebar.classList.toggle('active', isActive);
                if (overlay) {
                    overlay.classList.toggle('show', isActive);
                }
                document.body.classList.toggle('sidebar-open', isActive);
                toggle.setAttribute('aria-expanded', String(isActive));
                toggle.setAttribute('aria-label', isActive ? 'Tutup menu navigasi' : 'Buka menu navigasi');
                updateToggleIcon(isActive);

                if (isActive) {
                    sidebar.querySelector('.nav-link')?.focus();
                }
            }

            toggle.addEventListener('click', function() {
                setSidebarOpen(!sidebar.classList.contains('active'));
            });

            sidebar.querySelectorAll('.nav-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    setSidebarOpen(false);
                });
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && sidebar.classList.contains('active')) {
                    setSidebarOpen(false);
                    toggle.focus();
                }
            });

            window.addEventListener('resize', function() {
                if (!mobileMenu.matches) {
                    setSidebarOpen(false);
                }
            });
        })();

        // Search Functionality
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = `/contracts?search=${encodeURIComponent(query)}`;
                }
            }
        });

        // Notification Bell Animation
        const notificationBtn = document.getElementById('notificationBtn');
        notificationBtn.addEventListener('click', function() {
            window.location.href = "<?php echo e(route('notifications.index')); ?>";
        });

        // Auto-refresh data every 60 seconds
        setInterval(function() {
            fetch('/api/dashboard/stats')
                .then(response => {
                    const contentType = (response.headers.get('Content-Type') || '').toLowerCase();
                    if (!response.ok) {
                        console.warn('Auto-refresh: /api/dashboard/stats not OK', response.status);
                        return null;
                    }
                    if (!contentType.includes('application/json')) {
                        console.warn('Auto-refresh: /api/dashboard/stats returned non-JSON response');
                        return null;
                    }
                    return response.json().catch(err => {
                        console.error('Auto-refresh: failed to parse JSON', err);
                        return null;
                    });
                })
                .then(data => {
                    if (data) updateStats(data);
                })
                .catch(error => console.log('Auto-refresh error:', error));
        }, 60000);

        function updateStats(data) {
            // Update stats cards
            document.querySelectorAll('[data-stat]').forEach(element => {
                const stat = element.getAttribute('data-stat');
                if (data[stat]) {
                    element.textContent = data[stat];
                }
            });
        }

        // Real-time notifications with WebSocket simulation
        function checkNewNotifications() {
            fetch('/api/notifications/unread-count')
                .then(response => {
                    const contentType = (response.headers.get('Content-Type') || '').toLowerCase();
                    if (!response.ok) {
                        console.warn('/api/notifications/unread-count not OK', response.status);
                        return null;
                    }
                    if (!contentType.includes('application/json')) {
                        console.warn('/api/notifications/unread-count returned non-JSON response');
                        return null;
                    }
                    return response.json().catch(err => {
                        console.error('Failed to parse notifications JSON', err);
                        return null;
                    });
                })
                .then(data => {
                    if (!data) return;
                    const badge = document.querySelector('.notification-badge');
                    const count = parseInt(data.count || 0, 10);
                    if (count > 0) {
                        badge.textContent = count;
                        badge.style.display = 'flex';

                        // Show notification toast only when count increases
                        if (count > (parseInt(badge.getAttribute('data-last') || '0', 10))) {
                            showNotificationToast('Anda memiliki ' + count + ' notifikasi baru');
                        }
                        badge.setAttribute('data-last', String(count));
                    } else {
                        badge.style.display = 'none';
                        badge.setAttribute('data-last', '0');
                    }
                })
                .catch(err => console.error('checkNewNotifications error', err));
        }

        function showNotificationToast(message) {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 p-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="toast show" role="alert">
                    <div class="toast-header">
                        <i class="fas fa-bell text-primary me-2"></i>
                        <strong class="me-auto">Notifikasi Baru</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">${message}</div>
                </div>
            `;
            document.body.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Check for new notifications every 30 seconds
        setInterval(checkNewNotifications, 30000);

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Dark/Light mode toggle (optional)
        // const themeToggle = document.createElement('button');
        // themeToggle.className = 'btn btn-outline-secondary btn-sm ms-2';
        // themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        // themeToggle.addEventListener('click', function() {
        //     document.documentElement.classList.toggle('dark');
        //     this.innerHTML = document.documentElement.classList.contains('dark') ?
        //         '<i class="fas fa-sun"></i>' :
        //         '<i class="fas fa-moon"></i>';
        // });

        document.querySelector('.user-menu .dropdown').parentNode.insertBefore(themeToggle, document.querySelector(
            '.user-menu .dropdown'));
    </script>

    <script>
        document.querySelectorAll('.dashboard-progress[data-progress]').forEach((progressBar) => {
            const percentage = Number(progressBar.dataset.progress);
            if (Number.isFinite(percentage)) {
                progressBar.style.width = `${Math.min(100, Math.max(0, percentage))}%`;
            }
        });
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if(session('success')): ?>
        <div id="flashSuccessMessage" data-message="<?php echo e(session('success')); ?>" hidden></div>
        <script>
            const flashSuccessMessage = document.getElementById('flashSuccessMessage')?.dataset.message;
            if (flashSuccessMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: flashSuccessMessage,
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        </script>
    <?php endif; ?>
</body>

</html>
<?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>