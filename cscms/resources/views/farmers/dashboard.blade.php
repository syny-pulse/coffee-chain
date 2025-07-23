@extends('farmers.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Welcome to Your Farm Dashboard')
@section('page-subtitle', 'Monitor your coffee production, track orders, and manage your business efficiently')

@section('content')
    <!-- Notifications -->
    @include('farmers.partials.notifications')
    
    <!-- Stats Grid -->
    <div class="stats-grid">
        @include('farmers.partials.stat-card', [
            'title' => 'Total Harvest (kg)',
            'value' => number_format($stats['total_harvest'] ?? 0),
            'icon' => 'wheat-awn',
            'trend' => ($trends['total_harvest'] >= 0 ? '+' : '') . $trends['total_harvest'] . '%',
            'trendType' => $trends['total_harvest'] >= 0 ? 'positive' : 'negative'
        ])
        @include('farmers.partials.stat-card', [
            'title' => 'Available Stock (kg)',
            'value' => number_format($stats['available_stock'] ?? 0),
            'icon' => 'boxes-stacked',
            'trend' => ($trends['available_stock'] >= 0 ? '+' : '') . $trends['available_stock'] . '%',
            'trendType' => $trends['available_stock'] >= 0 ? 'positive' : 'negative'
        ])
        @include('farmers.partials.stat-card', [
            'title' => 'Total Revenue',
            'value' => 'UGX ' . number_format($stats['total_revenue'] ?? 0, 2),
            'icon' => 'coins',
            'trend' => ($trends['total_revenue'] >= 0 ? '+' : '') . $trends['total_revenue'] . '%',
            'trendType' => $trends['total_revenue'] >= 0 ? 'positive' : 'negative'
        ])
        @include('farmers.partials.stat-card', [
            'title' => 'Pending Orders',
            'value' => $stats['pending_orders'] ?? 0,
            'icon' => 'clipboard-list',
            'trend' => ($trends['pending_orders'] >= 0 ? '+' : '') . $trends['pending_orders'],
            'trendType' => $trends['pending_orders'] >= 0 ? 'positive' : 'negative'
        ])
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <div class="section-title">Quick Actions</div>
        <div class="actions-grid">
            <a href="{{ route('farmers.harvests.create') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-plus"></i></div>
                <div class="action-title">Add Harvest</div>
                <div class="action-desc">Record new harvest data and track production</div>
            </a>
            <a href="{{ route('farmers.orders.index') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="action-title">View Orders</div>
                <div class="action-desc">Check order status and manage deliveries</div>
            </a>
            <a href="{{ route('farmers.inventory.index') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-boxes-stacked"></i></div>
                <div class="action-title">Inventory</div>
                <div class="action-desc">Manage stock levels and track storage</div>
            </a>
            <a href="{{ route('farmers.financials.pricing') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-tags"></i></div>
                <div class="action-title">Pricing</div>
                <div class="action-desc">Update coffee prices and market rates</div>
            </a>
            <a href="{{ route('farmers.analytics.reports') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-chart-line"></i></div>
                <div class="action-title">Reports</div>
                <div class="action-desc">View analytics and performance insights</div>
            </a>
            <a href="{{ route('messages.index') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-comments"></i></div>
                <div class="action-title">Messages</div>
                <div class="action-desc">Communicate with buyers and partners</div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
        <div class="section-title">Recent Activity</div>
        @if(isset($recent_activity) && count($recent_activity) > 0)
            @foreach($recent_activity as $activity)
                <a href="{{ $activity['link'] }}" class="activity-item" style="text-decoration: none; color: inherit;">
                    <div class="activity-icon">
                        @if($activity['type'] === 'order')
                            <i class="fas fa-clipboard-list"></i>
                        @elseif($activity['type'] === 'harvest')
                            <i class="fas fa-wheat-awn"></i>
                        @elseif($activity['type'] === 'payment')
                            <i class="fas fa-coins"></i>
                        @elseif($activity['type'] === 'message')
                            <i class="fas fa-comments"></i>
                        @endif
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">{{ $activity['title'] }}</div>
                        <div class="activity-time">{{ $activity['human_time'] }}</div>
                    </div>
                </a>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-info-circle"></i>
                <h3>No recent activity</h3>
                <p>Recent activity will appear here as you use the system.</p>
            </div>
        @endif
    </div>
@endsection