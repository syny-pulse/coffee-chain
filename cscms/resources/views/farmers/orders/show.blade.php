@extends('farmers.layouts.app')

@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-subtitle', 'View all information about this coffee order')

@section('page-actions')
    <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i>
        Back to Orders
    </a>
    <a href="{{ route('farmers.orders.edit', $order['order_id']) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i>
        Edit Order
    </a>
@endsection

@section('content')
    <div class="details-grid">
        <div class="detail-section">
            <h3>Order Information</h3>
            <div class="detail-item">
                <span class="detail-label">Order ID:</span>
                <span class="detail-value">{{ $order['order_id'] }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Coffee Variety:</span>
                <span class="detail-value">{{ ucfirst($order['coffee_variety']) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Grade:</span>
                <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $order['grade'])) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Quantity (kg):</span>
                <span class="detail-value">{{ number_format($order['quantity_kg']) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Unit Price (UGX/kg):</span>
                <span class="detail-value">UGX{{ number_format($order['unit_price'], 2) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Total Amount (UGX):</span>
                <span class="detail-value">UGX{{ number_format($order['total_amount'], 2) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Order Status:</span>
                <span class="status-badge {{ $order['order_status'] }}">{{ ucfirst($order['order_status']) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Expected Delivery:</span>
                <span class="detail-value">{{ $order['delivery_date'] ? \Carbon\Carbon::parse($order['delivery_date'])->format('M d, Y') : 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Actual Delivery:</span>
                <span class="detail-value">{{ $order['actual_delivery_date'] ? \Carbon\Carbon::parse($order['actual_delivery_date'])->format('M d, Y') : 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Processor:</span>
                <span class="detail-value">{{ $order->processor ? $order->processor->company_name : '-' }}</span>
            </div>
        </div>
        <div class="detail-section">
            <h3>Notes</h3>
            <div class="notes-content">
                {{ $order['order_notes'] ?? 'No special notes for this order.' }}
            </div>
        </div>
    </div>
@endsection 