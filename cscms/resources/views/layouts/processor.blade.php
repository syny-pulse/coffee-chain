<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Coffee Chain - @yield('title')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --coffee-dark: #2D1B0E;
            --coffee-medium: #6F4E37;
            --coffee-light: #A0702A;
            --cream: #F5F1EB;
            --accent: #D4A574;
            --text-dark: #2D1B0E;
            --text-light: #666;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --sidebar-width: 220px;
            --sidebar-collapsed: 60px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: linear-gradient(135deg, var(--cream) 0%, #FAFAF8 100%);
            overflow-x: hidden;
            position: relative;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: rgba(245, 241, 235, 0.95);
            backdrop-filter: blur(10px);
            color: var(--text-dark);
            padding: 1rem 0;
            transition: width 0.3s ease;
            z-index: 999;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(111, 78, 55, 0.1);
            border-right: 1px solid rgba(111, 78, 55, 0.1);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: calc(var(--sidebar-width) - 20px);
            background: var(--coffee-medium);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.3s ease, left 0.3s ease;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.3);
            z-index: 1000;
        }

        .sidebar.collapsed .sidebar-toggle {
            left: calc(var(--sidebar-collapsed) - 20px);
        }

        .sidebar-toggle:hover {
            background: var(--coffee-light);
            transform: scale(1.1);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 0.5rem;
            flex-grow: 1;
            margin-top: 4rem;
        }

        .sidebar-menu li {
            margin-bottom: 0.25rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.8rem;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .sidebar-menu a:hover {
            background: rgba(111, 78, 55, 0.1);
            transform: translateX(3px);
            color: var(--coffee-medium);
        }

        .sidebar-menu a.active {
            background: var(--coffee-medium);
            color: white;
            box-shadow: 0 2px 8px rgba(111, 78, 55, 0.2);
        }

        .sidebar-menu a i {
            width: 18px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar.collapsed .sidebar-menu a span {
            display: none;
        }

        .sidebar.collapsed .sidebar-menu a {
            justify-content: center;
            padding: 0.6rem;
        }

        /* Sidebar Logo */
        .sidebar-logo {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(111, 78, 55, 0.1);
            position: absolute;
            top: 0;
            width: 100%;
        }

        .sidebar-logo a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: var(--coffee-dark);
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .sidebar-logo i {
            color: var(--coffee-medium);
            font-size: 1.4rem;
        }

        .sidebar.collapsed .sidebar-logo a span {
            display: none;
        }

        .sidebar.collapsed .sidebar-logo a {
            justify-content: center;
        }

        /* Sidebar Profile */
        .sidebar-profile {
            padding: 1rem;
            border-top: 1px solid rgba(111, 78, 55, 0.1);
            margin-top: auto;
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--coffee-medium);
            color: white;
            padding: 0.5rem 0.8rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .profile-btn img.avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent);
        }

        .profile-btn:hover {
            background: var(--coffee-light);
            transform: scale(1.05);
        }

        .profile-dropdown-content {
            display: none;
            position: absolute;
            left: 0;
            bottom: 100%;
            background: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(111, 78, 55, 0.2);
            border-radius: 8px;
            z-index: 1001;
            margin-bottom: 5px;
            border: 1px solid rgba(111, 78, 55, 0.1);
        }

        .profile-dropdown-content.show {
            display: block;
        }

        .profile-dropdown-content a {
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            text-decoration: none;
            display: block;
            font-size: 0.85rem;
            transition: background 0.3s ease;
        }

        .profile-dropdown-content a:hover {
            background: var(--cream);
        }

        .sidebar.collapsed .profile-btn span,
        .sidebar.collapsed .profile-btn i.fa-chevron-down {
            display: none;
        }

        .sidebar.collapsed .profile-btn {
            justify-content: center;
            padding: 0.5rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .sidebar.collapsed+.main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* Dashboard Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(111, 78, 55, 0.1);
        }

        .dashboard-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .dashboard-title i {
            font-size: 2rem;
            color: var(--coffee-medium);
        }

        .dashboard-title h1 {
            font-size: 1.8rem;
            color: var(--coffee-dark);
            margin-bottom: 0.25rem;
        }

        .dashboard-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 12px;
            padding: 1.25rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(111, 78, 55, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(111, 78, 55, 0.15);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .stat-card-title {
            font-size: 0.9rem;
            color: var(--text-light);
            font-weight: 500;
        }

        .stat-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }

        .stat-card-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 0.5rem;
        }

        .stat-card-change {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
        }

        .change-positive {
            color: var(--success);
        }

        .change-negative {
            color: var(--danger);
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        /* Content Sections */
        .content-section {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 12px;
            padding: 1.25rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(111, 78, 55, 0.1);
            margin-bottom: 1.5rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--coffee-dark);
        }

        .section-title i {
            color: var(--coffee-medium);
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .table th {
            background: rgba(111, 78, 55, 0.05);
            color: var(--coffee-dark);
            font-weight: 600;
            padding: 0.75rem;
            text-align: left;
            border-bottom: 2px solid rgba(111, 78, 55, 0.1);
        }

        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid rgba(111, 78, 55, 0.05);
        }

        .table tbody tr:hover {
            background: rgba(111, 78, 55, 0.02);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-inactive {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .status-low {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .status-medium {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-high {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 25px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(111, 78, 55, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #20c997 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--coffee-medium);
            border: 2px solid var(--coffee-medium);
        }

        .btn-outline:hover {
            background: var(--coffee-medium);
            color: white;
            transform: translateY(-2px);
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

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

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
            }

            .dashboard-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }

        /* Text utilities */
        .text-center {
            text-align: center;
        }

        .text-muted {
            color: var(--text-light);
        }

        /* Status Select */
        .status-select {
            padding: 0.5rem;
            border: 1px solid rgba(111, 78, 55, 0.2);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8);
            color: var(--text-dark);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .status-select:hover {
            border-color: var(--coffee-medium);
            background: var(--cream);
        }

        .status-select:focus {
            outline: none;
            border-color: var(--coffee-light);
            box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
        }

        .status-accepted {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-rejected {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .status-visit_scheduled {
            background: rgba(23, 162, 184, 0.1);
            color: var(--info);
        }

        /* Fade Out Animation */
        .fade-out {
            animation: fadeOut 0.6s ease-in-out forwards;
        }

        /* Custom CSS for PDF Badge */
        .pdf-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            /* Blue color to match a professional look */
            transition: background-color 0.2s ease;
        }

        .pdf-badge:hover {
            background-color: #0056b3;
            /* Darker blue on hover */
            text-decoration: none;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-20px);
                display: none;
                /* Note: display won't animate, handled in JS */
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="sidebar-logo">
            <a href="{{ route('processor.dashboard') }}">
                <i class="fas fa-seedling"></i>
                <span>Coffee Chain</span>
            </a>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('processor.dashboard') }}"
                    class="{{ request()->routeIs('processor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('processor.employee.index') }}"
                    class="{{ request()->routeIs('processor.employee.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Employees</span>
                </a>
            </li>
            <li>
                <a href="{{ route('processor.company.index') }}"
                    class="{{ request()->routeIs('processor.company.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Companies</span>
                </a>
            </li>
            <li>
                <a href="{{ route('processor.inventory.index') }}"
                    class="{{ request()->routeIs('processor.inventory.*') ? 'active' : '' }}">
                    <i class="fas fa-warehouse"></i>
                    <span>Inventory</span>
                </a>
            </li>
            <li>
                <a href="{{ route('processor.order.farmer_order.index') }}"
                    class="{{ request()->routeIs('processor.order.farmer_order.*') ? 'active' : '' }}">
                    <i class="fas fa-seedling"></i>
                    <span>Farmer Orders</span>
                </a>
            </li>
            <li>
                <a href="{{ route('processor.order.retailer_order.index') }}"
                    class="{{ request()->routeIs('processor.order.retailer_order.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Retailer Orders</span>
                </a>
            </li>
            <li>
                <a href="{{ route('processor.message.index') }}"
                    class="{{ request()->routeIs('processor.message.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                </a>
            </li>
            <li>
                <a href="{{ route('processor.analytics.index') }}"
                    class="{{ request()->routeIs('processor.analytics.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-profile">
            <div class="profile-dropdown">
                <div class="profile-btn" onclick="toggleProfileDropdown()">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ auth()->user()->name }}</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="profile-dropdown-content" id="profileDropdown">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const profileBtn = document.querySelector('.profile-btn');

            if (!profileBtn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
        // Auto-hide success alert after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.classList.remove('fade-in');
                    successAlert.classList.add('fade-out');
                    setTimeout(() => {
                        successAlert.style.display = 'none';
                    }, 600); // Match the animation duration
                }, 5000); // Show for 5 seconds
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
