@extends('farmers.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1><i class="fas fa-tachometer-alt"></i> Farmer Dashboard</h1>
    <div class="dashboard-grid">
        <div class="stat-item">
            <span class="stat-number">{{ $totalHarvest }}</span>
            <span class="stat-label">Total Harvest (kg)</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $availableStock }}</span>
            <span class="stat-label">Available Stock (kg)</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">${{ $totalRevenue }}</span>
            <span class="stat-label">Total Revenue</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $profitMargin }}%</span>
            <span class="stat-label">Profit Margin</span>
        </div>
    </div>
    <h2><i class="fas fa-shopping-cart"></i> Recent Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Variety</th>
                <th>Quantity (kg)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_id }}</td>
                    <td>{{ $order->coffee_variety }}</td>
                    <td>{{ $order->quantity_kg }}</td>
                    <td>{{ $order->order_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection