@extends('layouts.processor')

@section('title', 'View Retailer Order')

@section('content')
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-shopping-cart"></i>
                <span>Retailer Order #{{ $order->order_number }}</span>
            </div>
            <a href="{{ route('processor.order.retailer_order.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="table-container">
            <table class="table">
                <tr>
                    <th>Shipping Address</th>
                    <td>{{ $order->shipping_address ?? 'N/A' }}</td>
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
        <div class="content-section fade-in">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-list"></i>
                    <span>Order Items</span>
                </div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name ?? 'N/A' }}</td>
                            <td>{{ $item->product_variant ?? 'N/A' }}</td>
                            <td>{{ $item->quantity_units ?? 0 }}</td>
                            <td>UGX {{ number_format($item->unit_price ?? 0) }}</td>
                            <td>UGX {{ number_format($item->line_total ?? 0) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No items found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <form action="{{ route('processor.order.retailer_order.update', $order->order_id) }}" method="POST">
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