@extends('farmers.layouts.app')

@section('title', 'View Order')

@section('content')
    <h1><i class="fas fa-shopping-cart"></i> Order Details</h1>
    <div class="order-details">
        <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
        <p><strong>Coffee Variety:</strong> {{ $order->coffee_variety }}</p>
        <p><strong>Processing Method:</strong> {{ $order->processing_method }}</p>
        <p><strong>Grade:</strong> {{ $order->grade }}</p>
        <p><strong>Quantity (kg):</strong> {{ $order->quantity_kg }}</p>
        <p><strong>Unit Price:</strong> ${{ $order->unit_price }}</p>
        <p><strong>Total Amount:</strong> ${{ $order->total_amount }}</p>
        <p><strong>Expected Delivery Date:</strong> {{ $order->expected_delivery_date }}</p>
        <p><strong>Actual Delivery Date:</strong> {{ $order->actual_delivery_date ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ $order->order_status }}</p>
        <p><strong>Notes:</strong> {{ $order->notes ?? 'None' }}</p>
    </div>
    <a href="{{ route('farmers.orders.edit', $order->order_id) }}" class="btn btn-outline"><i class="fas fa-edit"></i> Edit</a>
    <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection