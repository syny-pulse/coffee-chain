<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            --sidebar-width: 250px;
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
            background: linear-gradient(180deg, var(--coffee-medium) 0%, var(--coffee-dark) 100%);
            color: white;
            padding: 1rem 0;
            transition: width 0.3s ease;
            z-index: 999;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: calc(var(--sidebar-width) - 20px);
            background: var(--accent);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease, left 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
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
            margin-top: 4rem; /* Space below logo */
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .sidebar-menu a:hover {
            background: var(--coffee-light);
            transform: translateX(3px);
        }

        .sidebar-menu a.active {
            background: var(--accent);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar.collapsed .sidebar-menu a span {
            display: none;
        }

        .sidebar.collapsed .sidebar-menu a {
            justify-content: center;
            padding: 0.75rem;
        }

        /* Sidebar Logo */
        .sidebar-logo {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            position: absolute;
            top: 0;
            width: 100%;
        }

        .sidebar-logo a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .sidebar-logo i {
            color: var(--accent);
            font-size: 1.6rem;
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
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: auto;
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--coffee-light);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .profile-btn img.avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent);
        }

        .profile-btn:hover {
            background: var(--accent);
            transform: scale(1.05);
        }

        .profile-dropdown-content {
            display: none;
            position: absolute;
            left: 0;
            bottom: 100%;
            background: white;
            min-width: 180px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1001;
            margin-bottom: 5px;
        }

        .profile-dropdown:hover .profile-dropdown-content {
            display: block;
        }

        .profile-dropdown-content a {
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            text-decoration: none;
            display: block;
            font-size: 0.9rem;
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

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }

        /* Additional Styles from Dashboard */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(111, 78, 55, 0.1);
        }

        .dashboard-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .dashboard-title h1 {
            font-size: 2rem;
            color: var(--coffee-dark);
            font-weight: 700;
        }

        .dashboard-title i {
            font-size: 2.5rem;
            color: var(--coffee-medium);
        }

        .dashboard-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid rgba(111, 78, 55, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(111, 78, 55, 0.2);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-card-title {
            font-size: 0.9rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .stat-card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 0.5rem;
        }

        .stat-card-change {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .change-positive {
            color: var(--success);
        }

        .change-negative {
            color: var(--danger);
        }

        .content-section {
            background: inherit;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(111, 78, 55, 0.0);
            backdrop-filter: inherit;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid rgba(111, 78, 55, 0.1);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--coffee-dark);
        }

        .section-title i {
            color: var(--coffee-medium);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(111, 78, 55, 0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.95);
        }

        .table thead {
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            color: white;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(111, 78, 55, 0.1);
        }

        .table tbody tr:hover {
            background: rgba(111, 78, 55, 0.05);
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-low {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .chart-container {
            height: 300px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(111, 78, 55, 0.1);
        }

        .chart-placeholder {
            text-align: center;
            color: var(--text-dark);
        }

        .chart-placeholder i {
            font-size: 4rem;
            color: var(--coffee-medium);
            margin-bottom: 1rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid rgba(111, 78, 55, 0.2);
            border-radius: 5px;
            font-size: 1rem;
        }

        /* New Styles for Analytics View */
        .section {
            padding: 2rem;
        }

        .section-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header h2 {
            font-size: 2rem;
            color: var(--coffee-dark);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .section-header p {
            color: var(--text-light);
            font-size: 1rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 15px;
            border: 1px solid rgba(111, 78, 55, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon i {
            font-size: 2rem;
            color: var(--coffee-medium);
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.2rem;
            color: var(--coffee-dark);
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            font-size: 1.5rem;
            color: var(--text-dark);
            font-weight: 600;
        }

        /* Message Icon */
        .message-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: var(--coffee-medium);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .message-icon:hover {
            transform: scale(1.1);
            background: var(--coffee-light);
        }

        .message-icon i {
            color: white;
            font-size: 1.5rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar-toggle {
                left: 10px;
                top: 10px;
                z-index: 1001;
            }
            .sidebar.collapsed .sidebar-toggle {
                left: 10px;
            }
            .dashboard-grid {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .message-icon {
                bottom: 10px;
                right: 10px;
                width: 50px;
                height: 50px;
            }
            .message-icon i {
                font-size: 1.2rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.5s ease;
        }

        /* Breadcrumb */
        .breadcrumb {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .breadcrumb a {
            color: var(--coffee-medium);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <a href="{{ route('processor.dashboard') }}">
                <i class="fas fa-seedling"></i>
                <span>Coffee Chain</span>
            </a>
        </div>
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </button>
        <ul class="sidebar-menu">
            <li><a href="{{ route('processor.dashboard') }}" data-section="dashboard"><i class="fas fa-tachometer-alt"></i><span>Processing Overview</span></a></li>
            <li><a href="{{ route('processor.employee.index') }}" data-section="employee"><i class="fas fa-users"></i><span>Workforce Management</span></a></li>
            <li><a href="{{ route('processor.inventory.index') }}" data-section="inventory"><i class="fas fa-warehouse"></i><span>Check Inventory</span></a></li>
            <li><a href="{{ route('processor.order.retailer_order.index') }}" data-section="retailer_order"><i class="fas fa-shopping-cart"></i><span>Retailer Order Management</span></a></li>
            <li><a href="{{ route('processor.order.farmer_order.index') }}" data-section="farmer_order"><i class="fas fa-seedling"></i><span>Farmer Order Management</span></a></li>
            <li><a href="{{ route('processor.message.index') }}" data-section="messages"><i class="fas fa-envelope"></i><span>Messages</span></a></li>
            <li><a href="{{ route('processor.analytics.index') }}" data-section="analytics"><i class="fas fa-chart-line"></i><span>Analytics and Trends</span></a></li>
        </ul>
        <div class="sidebar-profile">
            <div class="profile-dropdown">
                <div class="profile-btn" id="profileBtn">
                    <img src="https://via.placeholder.com/32?text={{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'G' }}" alt="Avatar" class="avatar">
                    <span>{{ auth()->check() ? auth()->user()->name : 'Guest' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="profile-dropdown-content" id="profileMenu">
                    @if(auth()->check())
                        <a href="{{ route('processor.profile.show') }}">Profile</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        @yield('content')
    </div>

    <!-- Quick Actions Modal -->
    <div class="modal" id="quickActionsModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeQuickActions()">×</span>
            <h2 style="margin-bottom: 1.5rem; color: var(--coffee-dark);">Quick Actions</h2>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <a href="{{ route('processor.employee.create') }}" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-user-plus"></i>
                    Add Employee
                </a>
                <a href="{{ route('processor.order.retailer_order.create') }}" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-shopping-cart"></i>
                    New Retailer Order
                </a>
                <a href="{{ route('processor.order.farmer_order.create') }}" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-seedling"></i>
                    New Farmer Order
                </a>
                <a href="{{ route('processor.inventory.create') }}" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-box"></i>
                    Add Stock
                </a>
                <a href="{{ route('processor.message.create') }}" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-envelope"></i>
                    New Message
                </a>
            </div>
        </div>
    </div>

    <!-- Message Icon -->
    <div class="message-icon" id="message-icon">
        <i class="fas fa-envelope"></i>
    </div>

    <!-- Scripts Section -->
    @yield('scripts')

    <!-- JavaScript -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.getElementById('mainContent');
        const messageIcon = document.getElementById('message-icon');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            sidebarToggle.querySelector('i').classList.toggle('fa-chevron-left');
            sidebarToggle.querySelector('i').classList.toggle('fa-chevron-right');
        });

        // Highlight active sidebar item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname.replace(/\/$/, ''); // Remove trailing slash
            const menuItems = document.querySelectorAll('.sidebar-menu a');
            
            menuItems.forEach(item => {
                const itemPath = new URL(item.href, window.location.origin).pathname.replace(/\/$/, '');
                item.classList.remove('active');
                if (currentPath === itemPath || (item.dataset.section && currentPath.includes(item.dataset.section))) {
                    item.classList.add('active');
                }
            });
        });

        // Modal Functions
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Modal Triggers
        window.showAddEmployeeModal = () => showModal('addEmployeeModal');
        window.showAddRawMaterialModal = () => showModal('addRawMaterialModal');
        window.showAddFinishedGoodModal = () => showModal('addFinishedGoodModal');
        window.showAddRetailerOrderModal = () => showModal('addRetailerOrderModal');
        window.showAddFarmerOrderModal = () => showModal('addFarmerOrderModal');
        window.showSendMessageModal = () => showModal('sendMessageModal');

        // Form Submission (Placeholder)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('Form submitted! (Replace with actual submission logic)');
                closeModal(form.closest('.modal').id);
            });
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let modal of modals) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        };

        // Message Icon Click Handler
        messageIcon.addEventListener('click', () => {
            window.location.href = '{{ route('processor.message.index') }}';
        });

        // Quick Actions Modal
        function showQuickActions() {
            document.getElementById('quickActionsModal').style.display = 'flex';
        }

        function closeQuickActions() {
            document.getElementById('quickActionsModal').style.display = 'none';
        }
    </script>
</body>
</html>