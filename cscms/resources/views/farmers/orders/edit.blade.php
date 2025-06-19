@extends('farmers.layouts.app')

@section('title', 'Edit Order')

@section('content')
    <h1><i class="fas fa-shopping-cart"></i> Edit Order</h1>
    @include('farmers.partials.errors')
    <form action="{{ route('farmers.orders.update', $order->order_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="coffee_variety">Coffee Variety</label>
            <select name="coffee_variety" id="coffee_variety" required>
                <option value="arabica" {{ $order->coffee_variety == 'arabica' ? 'selected' : '' }}>Arabica</option>
                <option value="robusta" {{ $order->coffee_variety == 'robusta' ? 'selected' : '' }}>Robusta</option>
            </select>
        </div>
        <div class="form-group">
            <label for="processing_method">Processing Method</label>
            <select name="processing_method" id="processing_method" required>
                <option value="natural" {{ $order->processing_method == 'natural' ? 'selected' : '' }}>Natural</option>
                <option value="washed" {{ $order->processing_method == 'washed' ? 'selected' : '' }}>Washed</option>
                <option value="honey" {{ $order->processing_method == 'honey' ? 'selected' : '' }}>Honey</option>
            </select>
        </div>
        <div class="form-group">
            <label for="grade">Grade</label>
            <select name="grade" id="grade" required>
                <option value="grade_1" {{ $order->grade == 'grade_1' ? 'selected' : '' }}>Grade 1</option>
                <option value="grade_2" {{ $order->grade == 'grade_2' ? 'selected' : '' }}>Grade 2</option>
                <option value="grade_3" {{ $order->grade == 'grade_3' ? 'selected' : '' }}>Grade 3</option>
                <option value="grade_4" {{ $order->grade == 'grade_4' ? 'selected' : '' }}>Grade 4</option>
                <option value="grade_5" {{ $order->grade == 'grade_5' ? 'selected' : '' }}>Grade 5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity_kg">Quantity (kg)</label>
            <input type="number" name="quantity_kg" id="quantity_kg" step="0.01" value="{{ $order->quantity_kg }}" required>
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" step="0.01" value="{{ $order->unit_price }}" required>
        </div>
        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="number" name="total_amount" id="total_amount" step="0.01" value="{{ $order->total_amount }}" required>
        </div>
        <div class="form-group">
            <label for="expected_delivery_date">Expected Delivery Date</label>
            <input type="date" name="expected_delivery_date" id="expected_delivery_date" value="{{ $order->expected_delivery_date->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label for="actual_delivery_date">Actual Delivery Date</label>
            <input type="date" name="actual_delivery_date" id="actual_delivery_date" value="{{ $order->actual_delivery_date ? $order->actual_delivery_date->format('Y-m-d') : '' }}">
        </div>
        <div class="form-group">
            <label for="order_status">Order Status</label>
            <select name="order_status" id="order_status" required>
                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes">{{ $order->notes }}</textarea>
        </div>
        <button type="submit" class="btn"><i class="fas fa-save"></i> Update</button>
    </form>
    <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection