<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Retailer Inventory Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
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

        /* Inventory Summary */
        .inventory-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .inventory-card {
            background: var(--bg-primary);
            border-radius: var(--radius);
            padding: 1rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            text-align: center;
        }

        .inventory-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .inventory-card p {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        /* Transactions Table */
        .table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-primary);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .table th, .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .table th {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: var(--bg-secondary);
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
                    <a href="{{ route('retailer.dashboard') }}" class="nav-link">
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
                    <a href="{{ route('retailer.inventory.index') }}" class="nav-link active">
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
            <h1 class="page-title">Inventory Management</h1>
            <p class="page-subtitle">View inventory summary and transaction history</p>
        </div>

        <!-- Inventory Summary -->
        <div class="inventory-summary">
            @foreach($remainingStock as $item)
            <div class="inventory-card">
                <h3>{{ ucfirst($item['coffee_breed']) }} - Grade {{ $item['roast_grade'] }}</h3>
                <p>Remaining: {{ $item['remaining_quantity'] }} kg</p>
                <p>Total Stock: {{ $item['total_quantity'] }} kg</p>
            </div>
            @endforeach
        </div>

        <!-- Ordered Products Summary -->
        <div class="inventory-summary" style="margin-bottom: 2rem;">
            @foreach($orderedProducts as $order)
            <div class="inventory-card" style="background-color: #f0f0f0;">
                <h3>{{ ucfirst($order->coffee_breed) }} - Grade {{ $order->roast_grade }}</h3>
                <p>Total Ordered: {{ $order->total_ordered }} kg</p>
            </div>
            @endforeach
        </div>

        <!-- Transactions Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Transaction Type</th>
                    <th>Coffee Breed</th>
                    <th>Roast Grade</th>
                    <th>Quantity</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($transaction->transaction_type) }}</td>
                    <td>{{ ucfirst($transaction->coffee_breed) }}</td>
                    <td>{{ $transaction->roast_grade }}</td>
                    <td>{{ $transaction->quantity }} kg</td>
                    <td>{{ $transaction->notes }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
