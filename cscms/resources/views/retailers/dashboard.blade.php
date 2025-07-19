@extends('retailers.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
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
            <a href="{{ route('retailer.sales.index') }}" class="action-card" id="quick-new-sale">
                <div class="action-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="action-title">New Sale</div>
                <div class="action-desc">Record a new sale transaction</div>
            </a>
            <a href="{{ route('retailer.orders.index') }}" class="action-card" id="quick-orders">
                <div class="action-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="action-title">Orders</div>
                <div class="action-desc">View and manage customer orders</div>
            </a>
            <a href="{{ route('retailer.inventory.index') }}" class="action-card" id="quick-inventory">
                <div class="action-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="action-title">Inventory</div>
                <div class="action-desc">Check stock levels and supplies</div>
            </a>
            <a href="{{ route('retailer.product_recipes.index') }}" class="action-card" id="quick-menu">
                <div class="action-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="action-title">Menu</div>
                <div class="action-desc">Update menu items and recipes</div>
            </a>
            <a href="#" class="action-card" id="quick-reports">
                <div class="action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="action-title">Reports</div>
                <div class="action-desc">View sales and performance analytics</div>
            </a>
            <a href="#" class="action-card" id="quick-settings">
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
@endsection