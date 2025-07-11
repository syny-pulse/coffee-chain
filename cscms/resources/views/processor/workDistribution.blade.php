@extends('layouts.processor')

@section('title', 'Work Distribution')


<!-- Dashboard Header -->
<div class="dashboard-header fade-in">
    <div class="dashboard-title">
        <i class="fas fa-cogs"></i>
        <div>
            <h1>Work Distribution</h1>
            <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                View assigned farmer and retailer orders
            </p>
        </div>
    </div>
</div>
@section('content')
    <!-- Alerts -->
    @if (session('success'))
        <div class="alert status-success auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert status-error auto-dismiss">
            {{ session('error') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert status-warning auto-dismiss">
            {{ session('warning') }}
        </div>
    @endif

    <!-- Farmer Orders Table -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-seedling"></i>
                <span>Farmer Orders</span>
            </div>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Order Number</th>
                        <th>Farmer Company</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($farmerOrders as $order)
                        <tr>
                            <td>{{ $order->employee_name }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->company_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No assigned farmer orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Retailer Orders Table -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-shopping-cart"></i>
                <span>Retailer Orders</span>
            </div>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Order Number</th>
                        <th>Retailer Company</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($retailerOrders as $order)
                        <tr>
                            <td>{{ $order->employee_name }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->company_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No assigned retailer orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
