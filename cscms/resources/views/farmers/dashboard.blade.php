@extends('farmers.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Welcome to Your Farm Dashboard')
@section('page-subtitle', 'Monitor your coffee production, track orders, and manage your business efficiently')

@section('content')
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

    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-clipboard-list"></i>
                Recent Orders
            </h2>
            @include('farmers.partials.action-buttons', [
                'actions' => [
                    ['type' => 'link', 'url' => route('farmers.orders.index'), 'text' => 'View All', 'style' => 'outline', 'icon' => 'eye']
                ]
            ])
        </div>
        
        @if(isset($orders) && count($orders) > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Variety</th>
                            <th>Quantity (kg)</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @include('farmers.partials.table-row', [
                                'item' => $order,
                                'columns' => [
                                    ['field' => 'order_id'],
                                    ['field' => 'coffee_variety'],
                                    ['field' => 'quantity_kg', 'type' => 'number'],
                                    ['field' => 'order_status', 'type' => 'status'],
                                    ['field' => 'created_at', 'type' => 'date']
                                ],
                                'actions' => [
                                    ['type' => 'link', 'url' => route('farmers.orders.show', $order['order_id']), 'icon' => 'eye', 'style' => 'outline', 'title' => 'View']
                                ]
                            ])
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>No Orders Yet</h3>
                <p>You haven't received any orders yet. They will appear here when they come in.</p>
                <a href="{{ route('farmers.orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create Order
                </a>
            </div>
        @endif
    </div>

    <!-- Recent Harvests -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-wheat-awn"></i>
                Recent Harvests
            </h2>
            @include('farmers.partials.action-buttons', [
                'actions' => [
                    ['type' => 'link', 'url' => route('farmers.harvests.index'), 'text' => 'View All', 'style' => 'outline', 'icon' => 'eye']
                ]
            ])
        </div>
        
        @if(isset($harvests) && count($harvests) > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Variety</th>
                            <th>Quantity (kg)</th>
                            <th>Quality Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($harvests as $harvest)
                            @include('farmers.partials.table-row', [
                                'item' => $harvest,
                                'columns' => [
                                    ['field' => 'harvest_date', 'type' => 'date'],
                                    ['field' => 'coffee_variety'],
                                    ['field' => 'quantity_kg', 'type' => 'number'],
                                    ['field' => 'grade']
                                ],
                                'actions' => [
                                    ['type' => 'link', 'url' => route('farmers.harvests.edit', $harvest['harvest_id']), 'icon' => 'edit', 'style' => 'outline', 'title' => 'Edit']
                                ]
                            ])
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-wheat-awn"></i>
                <h3>No Harvests Recorded</h3>
                <p>Start tracking your coffee harvests to monitor your production.</p>
                <a href="{{ route('farmers.harvests.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Record Harvest
                </a>
            </div>
        @endif
    </div>

    <div class="activity-title">Payment received: UGX2,400 from last shipment</div>
@endsection