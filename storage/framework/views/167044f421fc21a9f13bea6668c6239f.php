<!-- resources/views/layouts/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> - SIMOKO</title>

    <!-- Favicon Pelindo -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/crm-kontrak.png')); ?>">

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
            --sidebar-blue: #0879bd;
            --sidebar-ink: #17324d;
            --sidebar-muted: #60788c;
            --sidebar-line: #dce8f1;
            --glass-surface: rgba(255, 255, 255, 0.94);
            --glass-surface-bright: rgba(255, 255, 255, 0.98);
            --glass-border: rgba(255, 255, 255, 0.94);
            --glass-shadow: 0 24px 60px rgba(31, 39, 112, 0.22), inset 0 1px 0 rgba(255, 255, 255, 0.98), inset 0 -1px 0 rgba(31, 39, 112, 0.08);
            --glass-blur: 24px;
            --dashboard-glossy-background:
                radial-gradient(ellipse at 16% 0%, rgba(255, 255, 255, 0.3), transparent 38%),
                radial-gradient(ellipse at 88% 100%, rgba(255, 255, 255, 0.12), transparent 42%),
                linear-gradient(135deg, #667eea 0%, #7169c8 52%, #764ba2 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--dashboard-glossy-background);
            background-attachment: fixed;
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
            left: 0;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.94), rgba(246, 249, 255, 0.78));
            -webkit-backdrop-filter: blur(24px) saturate(165%);
            backdrop-filter: blur(24px) saturate(165%);
            color: var(--sidebar-ink);
            position: fixed;
            top: 0;
            bottom: 0;
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-right: 1px solid var(--glass-border);
            transition: transform 0.28s cubic-bezier(0.2, 0.75, 0.25, 1), width 0.3s ease, background 0.3s ease;
            z-index: 1000;
            box-shadow: 12px 0 36px rgba(31, 39, 112, 0.14), inset -1px 0 0 rgba(255, 255, 255, 0.72);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.72);
            flex: 0 0 auto;
        }

        .sidebar-brand h3 {
            flex: 1 1 auto;
            min-width: 0;
            height: 50px;
            margin: 0;
            font-weight: 700;
            font-size: 1.35rem;
            color: var(--sidebar-ink);
            justify-content: flex-start;
        }

        .sidebar-brand .brand-name {
            color: #0879bd;
            font-weight: 750;
            letter-spacing: 0.045em;
            background: linear-gradient(110deg, #07518f 0%, #0879bd 34%, #66d5ff 50%, #0879bd 68%, #164da8 100%);
            background-size: 220% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 1px 0 rgba(255, 255, 255, 0.82));
            animation: simokoLiquidShine 7s ease-in-out infinite alternate;
        }

        @keyframes simokoLiquidShine {
            from {
                background-position: 0 50%;
            }

            to {
                background-position: 100% 50%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .sidebar-brand .brand-name {
                animation: none;
            }
        }

        .sidebar-brand .logo-icon {
            font-size: 24px;
            color: var(--sidebar-blue);
            margin-right: 10px;
        }

        .sidebar-menu {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior-y: contain;
            scrollbar-width: thin;
            scrollbar-color: rgba(8, 121, 189, 0.24) transparent;
            padding: 14px 0 20px;
        }

        .nav-link {
            color: #536c80;
            padding: 11px 16px;
            margin: 4px 10px;
            border-radius: 10px;
            transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
            min-height: 46px;
            border-left: 3px solid transparent;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(90deg, #e8f4fb 0%, #f6fbff 100%);
            color: #075d95;
            border-left-color: var(--sidebar-blue);
            transform: none;
            box-shadow: inset 0 0 0 1px rgba(8, 121, 189, 0.06);
        }

        .nav-link.active {
            font-weight: 600;
        }

        .nav-link:focus-visible {
            outline: 3px solid rgba(8, 121, 189, 0.28);
            outline-offset: 2px;
        }

        .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            font-size: 18px;
            color: #6b8aa2;
        }

        .nav-link:hover i,
        .nav-link.active i {
            color: var(--sidebar-blue);
        }

        .nav-link .badge {
            margin-left: auto;
            background: #e31e24;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 7px;
            border-radius: 999px;
            color: #fff;
            font-size: 11px;
            line-height: 1;
        }

        .sidebar .sidebar-summary {
            margin: 18px 12px 0;
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            background: linear-gradient(145deg, rgba(239, 248, 255, 0.86), rgba(221, 240, 252, 0.64));
            -webkit-backdrop-filter: blur(14px) saturate(150%);
            backdrop-filter: blur(14px) saturate(150%);
            box-shadow: 0 10px 24px rgba(31, 39, 112, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.78);
            color: var(--sidebar-ink);
        }

        .sidebar-summary .text-muted {
            color: var(--sidebar-muted) !important;
        }

        .sidebar-summary .progress {
            height: 5px;
            background: #d2e5f2;
        }

        .sidebar-summary .progress-bar {
            background: linear-gradient(90deg, #0879bd, #26a8d1);
        }

        .sidebar-summary h5 {
            color: var(--sidebar-ink);
        }

        .sidebar-close {
            display: none;
            flex: 0 0 auto;
            width: 38px;
            height: 38px;
            place-items: center;
            padding: 0;
            border: 1px solid var(--sidebar-line);
            border-radius: 10px;
            background: #fff;
            color: var(--sidebar-blue);
        }

        .sidebar-close:focus-visible {
            outline: 3px solid rgba(8, 121, 189, 0.28);
            outline-offset: 2px;
        }

        .menu-toggle {
            display: none;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            min-width: 0;
            margin-left: var(--sidebar-width);
            padding: 8px 20px 20px;
            transition: all 0.3s;
        }

        /* Header */
        .top-bar {
            background: linear-gradient(115deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.9) 54%, rgba(255, 255, 255, 0.96));
            padding: 15px 20px;
            border: 1px solid var(--glass-border);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(165%);
            backdrop-filter: blur(var(--glass-blur)) saturate(165%);
            box-shadow: var(--glass-shadow);
            position: relative;
            z-index: 20;
            margin-top: -8px;
            margin-left: -20px;
            margin-right: -20px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            align-items: center;
        }

        .page-title {
            min-width: 0;
        }

        .page-title h1 {
            margin: 0;
            font-size: clamp(20px, 2vw, 24px);
            font-weight: 600;
            color: var(--dark-color);
            overflow-wrap: anywhere;
        }

        .page-title p {
            margin: 5px 0 0;
            color: #6c757d;
            font-size: 14px;
            line-height: 1.4;
        }

        .user-menu {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            min-width: 0;
            flex-wrap: nowrap;
        }

        .user-menu .dropdown {
            flex: 0 0 auto;
        }

        .user-menu .dropdown > .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 42px;
            padding: 8px 12px;
            border-radius: 10px;
            white-space: nowrap;
        }

        .notification-btn {
            position: relative;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            width: 42px;
            height: 42px;
            border: 1px solid #dce8f1;
            border-radius: 10px;
            background: #f7fbff;
            font-size: 18px;
            color: #0879bd;
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease, color 0.2s ease;
        }

        .notification-btn:hover {
            background: #eaf5fc;
            box-shadow: 0 4px 12px rgba(8, 121, 189, 0.12);
        }

        .notification-btn:focus-visible {
            outline: 3px solid rgba(8, 121, 189, 0.24);
            outline-offset: 2px;
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
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.9) 58%, rgba(255, 255, 255, 0.96));
            border: 1px solid var(--glass-border);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(165%);
            backdrop-filter: blur(var(--glass-blur)) saturate(165%);
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--glass-shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 24px 52px rgba(31, 39, 112, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.95);
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
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.92) 58%, rgba(255, 255, 255, 0.96));
            border: 1px solid var(--glass-border);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(165%);
            backdrop-filter: blur(var(--glass-blur)) saturate(165%);
            border-radius: 15px;
            overflow: scroll;
            box-shadow: var(--glass-shadow);
            position: relative;
            z-index: 1;
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
                transform: translateX(-105%);
                visibility: hidden;
                transition: transform 0.28s cubic-bezier(0.2, 0.75, 0.25, 1), visibility 0s linear 0.28s;
            }

            .sidebar.active {
                transform: translateX(0);
                visibility: visible;
                transition: transform 0.28s cubic-bezier(0.2, 0.75, 0.25, 1), visibility 0s;
                box-shadow: 18px 0 50px rgba(7, 38, 72, 0.22);
            }

            .main-content {
                margin-left: 0;
                padding: 8px 16px 30px;
            }

            .main-content > .top-bar {
                margin-left: -16px;
                margin-right: -16px;
                border-radius: 0 0 12px 12px;
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

            .top-bar {
                gap: 14px;
                padding: 14px 16px;
            }

            .user-menu {
                gap: 8px;
                justify-content: flex-end;
            }

            .menu-toggle {
                display: grid;
                place-items: center;
                width: 42px;
                height: 42px;
                padding: 0;
                border: 1px solid #d5e6f2;
                border-radius: 12px;
                background: #fff;
                color: var(--sidebar-blue);
                box-shadow: 0 5px 16px rgba(8, 35, 79, 0.1);
                transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
            }

            .menu-toggle:hover {
                transform: translateY(-1px);
                background: #f6fbff;
                box-shadow: 0 10px 25px rgba(8, 35, 79, 0.16);
            }

            .menu-toggle:focus-visible {
                outline: 3px solid rgba(8, 121, 189, 0.28);
                outline-offset: 2px;
            }

            .menu-toggle i {
                transition: transform 0.25s ease, color 0.25s ease;
            }

            .menu-toggle.open i {
                transform: rotate(90deg);
            }

            .sidebar-close {
                display: grid;
            }
        }

        @media (max-width: 768px) {
            .top-bar {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 12px;
                align-items: stretch;
                padding: 14px 16px;
                text-align: left;
            }

            .user-menu {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto auto auto;
                grid-template-areas: "search notification profile toggle";
                align-items: center;
                justify-content: stretch;
                gap: 8px;
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
                width: 42px;
                height: 42px;
            }

            .user-menu .dropdown {
                grid-area: profile;
                justify-self: end;
                min-width: 0;
            }

            .user-menu .dropdown > .btn {
                max-width: 100%;
                white-space: nowrap;
            }

            .user-menu .menu-toggle {
                grid-area: toggle;
                justify-self: end;
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

        @media (max-width: 480px) {
            .top-bar {
                gap: 10px;
                padding: 14px;
            }

            .user-menu {
                grid-template-columns: minmax(0, 1fr) auto auto;
                grid-template-areas: "search search search" "profile notification toggle";
            }

            .user-menu .dropdown {
                justify-self: start;
            }

            .user-menu .dropdown > .btn {
                padding: 8px 10px;
                font-size: 13px;
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
            height: 46px;
            width: auto;
            max-width: 100%;
            display: inline-block;
            vertical-align: middle;
            object-fit: contain;
            filter: none;
        }

        @media (max-width: 1200px) {
            .sidebar-brand img.site-logo {
                height: 42px;
            }
        }

        @media (max-width: 768px) {
            .sidebar-brand img.site-logo {
                height: 36px;
            }

            .sidebar-brand {
                padding: 14px 12px;
            }

            .page-title h1 {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .sidebar-brand .brand-name {
                font-size: 1.05rem;
                letter-spacing: 0.03em;
            }

            .sidebar-brand img.site-logo {
                height: 36px;
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
            box-shadow: var(--glass-shadow);
        }

        .content-wrapper .card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.92));
            border: 1px solid var(--glass-border);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(165%);
            backdrop-filter: blur(var(--glass-blur)) saturate(165%);
            box-shadow: var(--glass-shadow);
            position: relative;
            z-index: 1;
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
        <aside class="sidebar" id="dashboardSidebar" aria-label="Menu navigasi">
            <div class="sidebar-brand">
                <h3 class="d-flex align-items-center gap-2">
                    <img src="<?php echo e(asset('images/crm-kontrak.png')); ?>" alt="CRM Kontrak" class="site-logo" />
                    <span class="brand-name">SIMOKO</span>
                </h3>
                <button class="sidebar-close" id="sidebarClose" type="button" aria-label="Tutup menu navigasi">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
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
                    <span class="badge" data-unread-notifications><?php echo e($unreadNotifications ?? 0); ?></span>
                </a>

                <a href="<?php echo e(route('reports')); ?>" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>

                <a href="<?php echo e(route('settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>

                <div class="card sidebar-summary">
                    <div class="card-body p-3">
                        <small class="text-muted">Total Kontrak</small>
                        <h5 class="mb-0"><?php echo e($total); ?></h5>
                        <div class="progress mt-2">
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
                        <span class="notification-badge" data-unread-notifications><?php echo e($unreadNotifications ?? 0); ?></span>
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

                    <button class="btn menu-toggle" id="menuToggle" type="button"
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
            const close = document.getElementById('sidebarClose');
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
                const wasOpen = sidebar.classList.contains('active');
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
                    const activeLink = sidebar.querySelector('.nav-link.active') || sidebar.querySelector('.nav-link');
                    activeLink?.focus();
                } else if (wasOpen && mobileMenu.matches) {
                    toggle.focus();
                }
            }

            toggle.addEventListener('click', function() {
                setSidebarOpen(!sidebar.classList.contains('active'));
            });

            close.addEventListener('click', function() {
                setSidebarOpen(false);
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
            fetch("<?php echo e(route('api.dashboard.stats')); ?>")
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
                .catch(error => console.error('Auto-refresh error:', error));
        }, 60000);

        function updateStats(data) {
            // Update stats cards
            document.querySelectorAll('[data-stat]').forEach(element => {
                const stat = element.getAttribute('data-stat');
                if (Object.prototype.hasOwnProperty.call(data, stat)) {
                    element.textContent = String(data[stat]);
                }
            });
        }

        // Real-time notifications with WebSocket simulation
        function checkNewNotifications() {
            fetch("<?php echo e(route('api.notifications.unread-count')); ?>")
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
                    const badge = document.querySelector('.notification-badge[data-unread-notifications]');
                    const count = Number(data.count);
                    if (!badge || !Number.isSafeInteger(count) || count < 0) {
                        console.error('Invalid unread notification count response');
                        return;
                    }

                    const previousCount = Number.parseInt(
                        badge.getAttribute('data-last') || badge.textContent || '0',
                        10
                    );
                    document.querySelectorAll('[data-unread-notifications]').forEach(notificationBadge => {
                        notificationBadge.textContent = String(count);
                    });
                    if (count > 0) {
                        badge.style.display = 'flex';

                        // Show notification toast only when count increases
                        if (count > previousCount) {
                            showNotificationToast('Anda memiliki ' + count + ' notifikasi baru');
                        }
                    } else {
                        badge.style.display = 'none';
                    }
                    badge.setAttribute('data-last', String(count));
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
                    <div class="toast-body"></div>
                </div>
            `;
            toast.querySelector('.toast-body').textContent = message;
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
<?php /**PATH D:\p-contract\resources\views\layouts\dashboard.blade.php ENDPATH**/ ?>