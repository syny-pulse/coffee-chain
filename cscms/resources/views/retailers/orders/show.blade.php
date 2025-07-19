@extends('retailers.layouts.app')
@section('title', 'Order Details')
@section('content')
<div class="page-header">
    <h1 class="page-title">Order Details</h1>
</div>
<div class="card" style="max-width:600px; margin:auto; padding:2rem;">
    <dl class="row">
        <dt class="col-sm-4">Order ID</dt>
        <dd class="col-sm-8">{{ $order->id ?? $order->order_id }}</dd>
        <dt class="col-sm-4">Processor</dt>
        <dd class="col-sm-8">
            @php
                $proc = $processors->firstWhere('company_id', $order->processor_company_id);
            @endphp
            {{ $proc ? $proc->company_name : 'N/A' }}
        </dd>
        <dt class="col-sm-4">Coffee Breed</dt>
        <dd class="col-sm-8">{{ ucfirst($order->coffee_breed) }}</dd>
        <dt class="col-sm-4">Roast Grade</dt>
        <dd class="col-sm-8">Grade {{ $order->roast_grade }}</dd>
        <dt class="col-sm-4">Quantity (kg)</dt>
        <dd class="col-sm-8">{{ $order->quantity }}</dd>
        <dt class="col-sm-4">Expected Delivery</dt>
        <dd class="col-sm-8">{{ $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') : '-' }}</dd>
        <dt class="col-sm-4">Notes</dt>
        <dd class="col-sm-8">{{ $order->notes ?? '-' }}</dd>
        <dt class="col-sm-4">Status</dt>
        <dd class="col-sm-8">{{ ucfirst($order->order_status) }}</dd>
        <dt class="col-sm-4">Created</dt>
        <dd class="col-sm-8">{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') : '-' }}</dd>
    </dl>
    <a href="{{ route('retailer.orders.index') }}" class="btn btn-outline">Back to Orders</a>
</div>
@endsection 