@extends('layouts.processor')

@section('title', 'View Farmer Order')

@section('content')
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-seedling"></i>
                <span>Farmer Order #{{ $order->order_id }}</span>
            </div>
            <a href="{{ route('processor.order.farmer_order.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="table-container">
            <table class="table">
                <tr>
                    <th>Coffee Variety</th>
                    <td>{{ $order->coffee_variety ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Processing Method</th>
                    <td>{{ $order->processing_method ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Grade</th>
                    <td>{{ $order->grade ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Quantity (kg)</th>
                    <td>{{ $order->quantity_kg ?? 0 }}</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>UGX {{ number_format($order->total_amount ?? 0) }}</td>
                </tr>
                <tr>
                    <th>Expected Delivery</th>
                    <td>{{ $order->expected_delivery_date ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Actual Delivery</th>
                    <td>{{ $order->actual_delivery_date ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="status-badge status-{{ $order->order_status ?? 'pending' }}">{{ ucfirst($order->order_status ?? 'Pending') }}</span></td>
                </tr>
                <tr>
                    <th>Notes</th>
                    <td>{{ $order->notes ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <form action="{{ route('processor.order.farmer_order.update', $order->order_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="order_status">Status</label>
                <select name="order_status" class="form-control" required>
                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="form-group">
                <label for="actual_delivery_date">Actual Delivery Date</label>
                <input type="date" name="actual_delivery_date" class="form-control" value="{{ $order->actual_delivery_date ?? '' }}" {{ $order->order_status !== 'delivered' ? 'disabled' : '' }}>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Update Status</button>
            </div>
        </form>
    </div>
@endsection