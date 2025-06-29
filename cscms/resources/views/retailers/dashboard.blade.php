<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2D3748;
            --primary-light: #4A5568;
            --accent: #8B7355;
            --accent-light: #A68B5B;
            --success: #48BB78;
            --warning: #ED8936;
            --danger: #F56565;
            --info: #4299E1;
            
            --bg-primary: #FFFFFF;
            --bg-secondary: #F7FAFC;
            --bg-tertiary: #EDF2F7;
            
            --text-primary: #2D3748;
            --text-secondary: #4A5568;
            --text-muted: #718096;
            
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            
            --radius-sm: 6px;
            --radius: 8px;
            --radius-lg: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg-secondary);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-primary);
            border-right: 1px solid var(--border);
            padding: 2rem 0;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 0 2rem;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--accent);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .nav-menu {
            flex: 1;
            padding: 0 1rem;
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
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .nav-link:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--accent);
            color: white;
        }

        .nav-link .icon {
            margin-right: 0.75rem;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .user-section {
            padding: 1rem;
            border-top: 1px solid var(--border);
            margin-top: auto;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--bg-secondary);
            border-radius: var(--radius);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        /* Stats Grid - Updated for compact single line */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--bg-primary);
            border-radius: var(--radius);
            padding: 1rem;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
            min-width: 0;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            color: white;
        }

        .stat-icon.harvest { background: var(--success); }
        .stat-icon.inventory { background: var(--info); }
        .stat-icon.revenue { background: var(--accent); }
        .stat-icon.orders { background: var(--warning); }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.625rem;
            font-weight: 600;
            padding: 0.125rem 0.375rem;
            border-radius: 12px;
        }

        .stat-trend.positive {
            background: rgba(72, 187, 120, 0.1);
            color: var(--success);
        }

        .stat-trend.negative {
            background: rgba(245, 101, 101, 0.1);
            color: var(--danger);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.75rem;
            line-height: 1.3;
        }

        /* Quick Actions */
        .quick-actions {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-card {
            background: var(--bg-primary);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            border: 1px solid var(--border);
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            border-color: var(--accent);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            background: var(--accent);
        }

        .action-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .action-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* Recent Activity */
        .recent-activity {
            background: var(--bg-primary);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            border: 1px solid var(--border);
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: var(--radius);
            transition: all 0.2s ease;
        }

        .activity-item:hover {
            background: var(--bg-tertiary);
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.875rem;
            color: white;
            background: var(--accent);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .activity-time {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1rem 0;
                order: 2;
            }

            .main-content {
                order: 1;
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page-title {
                font-size: 1.5rem;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-mug-hot"></i>
            </div>
            <div class="logo-text">Coffee Shop</div>
        </div>
        
        <nav class="nav-menu">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <div class="nav-item">
                    <a href="{{ route('retailer.dashboard') }}" class="nav-link  active">
                        <span class="icon"><i class="fas fa-grid-2"></i></span>
                        Dashboard
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Shop Management</div>
                <div class="nav-item">
                    <a href="{{ route('retailer.sales.index') }}" class="nav-link">
                        <span class="icon"><i class="fas fa-chart-bar"></i></span>
                        Sales Data
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('retailer.product_recipes.index') }}" class="nav-link">
                        <span class="icon"><i class="fas fa-utensils"></i></span>
                        Product Recipes
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('retailer.inventory.index') }}" class="nav-link">
                        <span class="icon"><i class="fas fa-warehouse"></i></span>
                        Inventory
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('retailer.orders.index') }}" class="nav-link">
                        <span class="icon"><i class="fas fa-shopping-bag"></i></span>
                        Orders
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Business</div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-dollar-sign"></i></span>
                        Financials
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-chart-line"></i></span>
                        Analytics
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Communication</div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-message"></i></span>
                        Messages
                    </a>
                </div>
            </div>
        </nav>

        <div class="user-section">
            <div class="user-profile">
                <div class="user-avatar">JD</div>
                <div class="user-info">
                    <div class="user-name">John Doe</div>
                    <div class="user-role">Shop Owner</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Monitor your coffee shop performance and manage operations</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon harvest">
                        <i class="fas fa-coffee"></i>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        +12%
                    </div>
                </div>
                <div class="stat-value">2,450</div>
                <div class="stat-label">Cups Sold Today</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon inventory">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        +8%
                    </div>
                </div>
                <div class="stat-value">890</div>
                <div class="stat-label">Stock Level (kg)</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        +15%
                    </div>
                </div>
                <div class="stat-value">$8,420</div>
                <div class="stat-label">Daily Revenue</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-trend negative">
                        <i class="fas fa-arrow-down"></i>
                        -3
                    </div>
                </div>
                <div class="stat-value">12</div>
                <div class="stat-label">Pending Orders</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2 class="section-title">Quick Actions</h2>
            <div class="actions-grid">
                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="action-title">New Sale</div>
                    <div class="action-desc">Record a new sale transaction</div>
                </a>
                
                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="action-title">Orders</div>
                    <div class="action-desc">View and manage customer orders</div>
                </a>
                
                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="action-title">Inventory</div>
                    <div class="action-desc">Check stock levels and supplies</div>
                </a>
                
                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="action-title">Menu</div>
                    <div class="action-desc">Update menu items and recipes</div>
                </a>
                
                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="action-title">Reports</div>
                    <div class="action-desc">View sales and performance analytics</div>
                </a>
                
                <a href="#" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="action-title">Settings</div>
                    <div class="action-desc">Configure shop preferences</div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h2 class="section-title">Recent Activity</h2>
            
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Large catering order received - $850</div>
                        <div class="activity-time">15 minutes ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Coffee beans delivery completed - 50kg Arabica</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Daily sales target achieved - $8,000</div>
                        <div class="activity-time">4 hours ago</div>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New 5-star review received on Google</div>
                        <div class="activity-time">6 hours ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple interaction enhancements
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Simulate real-time updates
        function updateStats() {
            const cupsSold = document.querySelector('.stat-value');
            if (cupsSold) {
                setInterval(() => {
                    const current = parseInt(cupsSold.textContent.replace(',', ''));
                    const updated = current + Math.floor(Math.random() * 5);
                    cupsSold.textContent = updated.toLocaleString();
                }, 30000); // Update every 30 seconds
            }
        }

        updateStats();
    </script>
</body>
</html>