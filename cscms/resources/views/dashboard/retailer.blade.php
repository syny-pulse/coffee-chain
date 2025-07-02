<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard - Coffee Supply Chain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --coffee-dark: #3A2B1F;
            --coffee-medium: #5D4E37;
            --coffee-light: #8B7355;
            --coffee-lighter: #A68B5B;
            --cream: #F8F6F0;
            --cream-light: #FDFCF8;
            --accent: #B8956A;
            --accent-light: #D4C4A8;
            --text-dark: #2C1810;
            --text-medium: #4A3429;
            --text-light: #6B5B4F;
            --success: #6B8E23;
            --warning: #CD853F;
            --danger: #A0522D;
            --info: #708090;
            
            --bg-primary: var(--cream);
            --bg-secondary: var(--cream-light);
            --bg-tertiary: #F1F5F9;
            
            --text-primary: var(--text-dark);
            --text-secondary: var(--text-medium);
            --text-muted: var(--text-light);
            
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Header */
        .top-header {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1001;
            box-shadow: var(--shadow-sm);
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--coffee-dark);
            text-decoration: none;
            letter-spacing: -0.025em;
        }

        .header-brand i {
            font-size: 1.8rem;
            color: var(--accent);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: var(--bg-tertiary);
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--coffee-medium), var(--coffee-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .logout-btn {
            padding: 0.5rem 1rem;
            background: var(--bg-secondary);
            color: var(--text-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .logout-btn:hover {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
        }

        /* Content Wrapper */
        .content-wrapper {
            display: flex;
            flex: 1;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, var(--coffee-dark), var(--coffee-medium));
            color: white;
        }

        .sidebar-header h1 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-header .user-info {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
            flex: 1;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 0 1.5rem;
            margin-bottom: 0.75rem;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .nav-link:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--coffee-light);
            color: white;
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--coffee-light);
        }

        .nav-link .icon {
            margin-right: 0.75rem;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border);
            background: var(--bg-tertiary);
        }

        .sidebar-footer .logout-btn {
            width: 100%;
            justify-content: center;
            background: var(--bg-secondary);
            color: var(--text-secondary);
            border: 1px solid var(--border);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-footer .logout-btn:hover {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background: var(--bg-primary);
            padding: 2rem;
            overflow-y: auto;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
            padding: 2rem;
            background: var(--bg-secondary);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .page-header h1 {
            color: var(--text-primary);
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .page-subtitle {
            color: var(--text-secondary);
            margin-top: 0.5rem;
            font-size: 1rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--coffee-medium), var(--accent));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            background: linear-gradient(135deg, var(--coffee-medium), var(--coffee-light));
            box-shadow: var(--shadow-md);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
        }

        .stat-trend.positive {
            background: rgba(107, 142, 35, 0.1);
            color: var(--success);
        }

        .stat-trend.negative {
            background: rgba(160, 82, 45, 0.1);
            color: var(--danger);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        /* Quick Actions */
        .quick-actions {
            margin-bottom: 2rem;
        }

        .section-title {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: -0.025em;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
        }

        .action-card {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 1.75rem;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--coffee-medium), var(--coffee-light));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .action-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
            border-color: var(--coffee-medium);
        }

        .action-card:hover::before {
            opacity: 0.05;
        }

        .action-card > * {
            position: relative;
            z-index: 2;
        }

        .action-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: linear-gradient(135deg, var(--coffee-medium), var(--coffee-light));
            box-shadow: var(--shadow-md);
        }

        .action-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .action-desc {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        /* Recent Activity */
        .recent-activity {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 1.25rem 0;
            border-bottom: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: var(--bg-tertiary);
            margin: 0 -2rem;
            padding: 1.25rem 2rem;
            border-radius: 8px;
        }

        .activity-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.125rem;
            color: white;
            background: linear-gradient(135deg, var(--coffee-light), var(--accent));
            box-shadow: var(--shadow-sm);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .activity-time {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Footer */
        .footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
            padding: 2rem;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .footer-section p {
            margin-bottom: 1rem;
            color: var(--text-secondary);
            line-height: 1.6;
            font-size: 0.875rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s ease;
            font-size: 0.875rem;
        }

        .footer-links a:hover {
            color: var(--coffee-medium);
        }

        .footer-bottom {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .content-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                order: 2;
            }

            .main-content {
                order: 1;
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .user-profile {
                display: none;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        /* Loading Animation */
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

        .stat-card,
        .action-card,
        .recent-activity {
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.2s; }
        .stat-card:nth-child(4) { animation-delay: 0.3s; }

        .action-card:nth-child(2) { animation-delay: 0.2s; }
        .action-card:nth-child(3) { animation-delay: 0.4s; }
        .action-card:nth-child(4) { animation-delay: 0.6s; }
        .action-card:nth-child(5) { animation-delay: 0.8s; }
        .action-card:nth-child(6) { animation-delay: 1.0s; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
    </style>
</head>
<body>
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>
                    <i class="fas fa-seedling"></i>
                    Coffee Shop
                </h1>
                <div class="user-info">
                    Welcome back, John! 🌱
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.dashboard') }}" class="nav-link active">
                            <span class="icon"><i class="fas fa-home"></i></span>
                            Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Farm Management</div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.harvests.index') }}" class="nav-link">
                            <span class="icon"><i class="fas fa-wheat-awn"></i></span>
                            Harvests
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.inventory.index') }}" class="nav-link">
                            <span class="icon"><i class="fas fa-boxes-stacked"></i></span>
                            Inventory
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Business</div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.orders.index') }}" class="nav-link">
                            <span class="icon"><i class="fas fa-clipboard-list"></i></span>
                            Orders
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.financials.index') }}" class="nav-link">
                            <span class="icon"><i class="fas fa-coins"></i></span>
                            Financials
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.analytics.reports') }}" class="nav-link">
                            <span class="icon"><i class="fas fa-chart-line"></i></span>
                            Analytics
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <div class="nav-item">
                        <a href="{{ route('farmers.communication.index') }}" class="nav-link">
                            <span class="icon"><i class="fas fa-comments"></i></span>
                            Messages
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1>Welcome to Your Retailer Dashboard</h1>
                <p class="page-subtitle">Monitor your coffee production, track orders, and manage your business efficiently</p>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-wheat-awn"></i>
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            +15%
                        </div>
                    </div>
                    <div class="stat-value">1,250</div>
                    <div class="stat-label">Total Harvest (kg)</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            +8%
                        </div>
                    </div>
                    <div class="stat-value">890</div>
                    <div class="stat-label">Available Stock (kg)</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i>
                            +22%
                        </div>
                    </div>
                    <div class="stat-value">$12,450</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stat-trend negative">
                            <i class="fas fa-arrow-down"></i>
                            -2
                        </div>
                    </div>
                    <div class="stat-value">7</div>
                    <div class="stat-label">Pending Orders</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h2 class="section-title">Quick Actions</h2>
                <div class="actions-grid">
                    <a href="{{ route('farmers.harvests.create') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-title">Add Harvest</div>
                        <div class="action-desc">Record new harvest data and track production</div>
                    </a>
                    
                    <a href="{{ route('farmers.orders.index') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="action-title">View Orders</div>
                        <div class="action-desc">Check order status and manage deliveries</div>
                    </a>
                    
                    <a href="{{ route('farmers.inventory.index') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <div class="action-title">Inventory</div>
                        <div class="action-desc">Manage stock levels and track storage</div>
                    </a>
                    
                    <a href="{{ route('farmers.financials.pricing') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="action-title">Pricing</div>
                        <div class="action-desc">Update coffee prices and market rates</div>
                    </a>
                    
                    <a href="{{ route('farmers.analytics.reports') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="action-title">Reports</div>
                        <div class="action-desc">View analytics and performance insights</div>
                    </a>
                    
                    <a href="{{ route('farmers.communication.index') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="action-title">Messages</div>
                        <div class="action-desc">Communicate with buyers and partners</div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="recent-activity">
                <h2 class="section-title">Recent Activity</h2>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New order received from Premium Coffee Co.</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-wheat-awn"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Harvest recorded: 150kg Arabica beans</div>
                        <div class="activity-time">5 hours ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Payment received: $2,400 from last shipment</div>
                        <div class="activity-time">1 day ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New message from Mountain View Processors</div>
                        <div class="activity-time">2 days ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Simulate dynamic data updates
        function updateStats() {
            const statValues = document.querySelectorAll('.stat-value');
            
            // Simulate real-time updates with small variations
            setTimeout(() => {
                const harvestValue = statValues[0];
                const currentValue = parseInt(harvestValue.textContent.replace(',', ''));
                const newValue = currentValue + Math.floor(Math.random() * 10) - 5;
                harvestValue.textContent = newValue.toLocaleString();
            }, 5000);
        }

        // Initialize updates
        updateStats();

        // Add click effect to action cards for better UX
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Add a subtle click effect
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Set active navigation based on current route
        function setActiveNavigation() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                
                // Simple path matching - check if current path contains parts of the expected route
                if (href) {
                    // For dashboard, check if we're on /farmer/dashboard
                    if (href.includes('farmers.dashboard') && currentPath === '/farmer/dashboard') {
                        link.classList.add('active');
                    }
                    // For other routes, check if current path matches
                    else if (href.includes('harvests') && currentPath.includes('harvests')) {
                        link.classList.add('active');
                    }
                    else if (href.includes('inventory') && currentPath.includes('inventory')) {
                        link.classList.add('active');
                    }
                    else if (href.includes('orders') && currentPath.includes('orders')) {
                        link.classList.add('active');
                    }
                    else if (href.includes('financials') && currentPath.includes('financials')) {
                        link.classList.add('active');
                    }
                    else if (href.includes('analytics') && currentPath.includes('analytics')) {
                        link.classList.add('active');
                    }
                    else if (href.includes('communication') && currentPath.includes('communication')) {
                        link.classList.add('active');
                    }
                }
            });
        }

        // Initialize active navigation
        setActiveNavigation();

        // Add hover effects for activity items
        document.querySelectorAll('.activity-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    </script>
</body>
</html>