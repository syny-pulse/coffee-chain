@extends('layouts.processor')

@section('title', 'Retailer Orders')

@section('content')
    <!-- Retailer Orders Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-shopping-cart"></i>
            <div>
                <h1>Retailer Order Management</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    View retailer orders as of {{ now()->format('h:i A T, l, F d, Y') }}
                </p>
            </div>
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
                        <th>Order #</th>
                        <th>Shipping Address</th>
                        <th>Total Amount</th>
                        <th>Expected Delivery</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->order_number ?? 'N/A' }}</td>
                        <td>{{ $order->shipping_address ?? 'N/A' }}</td>
                        <td>UGX {{ number_format($order->total_amount ?? 0) }}</td>
                        <td>{{ $order->expected_delivery_date ?? 'N/A' }}</td>
                        <td><span class="status-badge status-{{ $order->order_status ?? 'pending' }}">{{ ucfirst($order->order_status ?? 'Pending') }}</span></td>
                        <td>
                            <a href="{{ route('processor.order.retailer_order.show', $order->order_id) }}" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--info); color: white;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('processor.order.retailer_order.update', $order->order_id) }}" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--warning); color: white;">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No retailer orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection