@extends('farmers.layouts.app')

@section('title', 'View Order')
@section('page-title', 'Order Details')
@section('page-subtitle', 'View complete order information')

@section('page-actions')
    <a href="{{ route('farmers.orders.edit', $order->order_id) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i>
        Update Order Status
    </a>
    <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i>
        Back to Orders
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-clipboard-list"></i>
                Order #{{ $order->order_id }}
            </h2>
            <span class="status-badge {{ $order->order_status }}">
                {{ ucfirst($order->order_status) }}
            </span>
        </div>
        
        <div class="order-details">
            <div class="details-grid">
                <div class="detail-section">
                    <h3>Order Information</h3>
                    <div class="detail-item">
                        <span class="detail-label">Order ID:</span>
                        <span class="detail-value">{{ $order->order_id }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">
                            <span class="status-badge {{ $order->order_status }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Created:</span>
                        <span class="detail-value">{{ $order->created_at ? $order->created_at->format('M d, Y H:i') : 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Product Details</h3>
                    <div class="detail-item">
                        <span class="detail-label">Coffee Variety:</span>
                        <span class="detail-value">{{ ucfirst($order->coffee_variety) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Processing Method:</span>
                        <span class="detail-value">{{ ucfirst($order->processing_method) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Grade:</span>
                        <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $order->grade)) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Quantity:</span>
                        <span class="detail-value">{{ number_format($order->quantity_kg, 2) }} kg</span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Financial Information</h3>
                    <div class="detail-item">
                        <span class="detail-label">Unit Price:</span>
                        <span class="detail-value">UGX{{ number_format($order->unit_price, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Total Amount:</span>
                        <span class="detail-value">UGX{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Delivery Information</h3>
                    <div class="detail-item">
                        <span class="detail-label">Expected Delivery:</span>
                        <span class="detail-value">{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Actual Delivery:</span>
                        <span class="detail-value">{{ $order->actual_delivery_date ? $order->actual_delivery_date->format('M d, Y') : 'Not delivered yet' }}</span>
                    </div>
                </div>
            </div>
            
            @if($order->notes)
                <div class="notes-section">
                    <h3>Notes</h3>
                    <div class="notes-content">
                        {{ $order->notes }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection