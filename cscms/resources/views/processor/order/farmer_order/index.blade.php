@extends('layouts.processor')

@section('title', 'Farmer Orders')

@section('content')
    <!-- Farmer Orders Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-seedling"></i>
            <div>
                <h1>Farmer Order Management</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Manage farmer orders as of {{ now()->format('h:i A T, l, F d, Y') }}
                </p>
            </div>
        </div>

        <div class="dashboard-actions">
            <a href="{{ route('processor.order.farmer_order.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i>
                New Farmer Order
            </a>
        </div>
    </div>
    <!-- Success Message -->
    @if (session('success'))
        <div class="alert status-success fade-in auto-dismiss"
            style="padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="alert status-error fade-in auto-dismiss"
            style="padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
    @endif
    <!-- Warning Message -->
    @if (session('warning'))
        <div class="alert status-warning fade-in auto-dismiss"
            style="padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
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
                        <th>Coffee Variety</th>
                        <th>Processing Method</th>
                        <th>Grade</th>
                        <th>Quantity (kg)</th>
                        <th>Total Amount</th>
                        <th>Expected Delivery</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->coffee_variety ?? 'N/A' }}</td>
                            <td>{{ $order->processing_method ?? 'N/A' }}</td>
                            <td>{{ $order->grade ?? 'N/A' }}</td>
                            <td>{{ $order->quantity_kg ?? 0 }}</td>
                            <td>UGX {{ number_format($order->total_amount ?? 0) }}</td>
                            <td>{{ $order->expected_delivery_date ?? 'N/A' }}</td>
                            <td><span
                                    class="status-badge status-{{ $order->order_status ?? 'pending' }}">{{ ucfirst($order->order_status ?? 'Pending') }}</span>
                            </td>
                            <td>
                                <a href="{{ route('processor.order.farmer_order.show', $order->order_id) }}" class="btn"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--info); color: white;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('processor.order.farmer_order.edit', $order->order_id) }}" class="btn"
                                    style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--warning); color: white;">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No farmer orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
