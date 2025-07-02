@extends('farmers.layouts.app')

@section('title', 'Update Order Status')
@section('page-title', 'Update Order Status')
@section('page-subtitle', 'Change the status or mark as delivered')

@section('page-actions')
    <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i>
        Back to Orders
    </a>
@endsection

@section('content')
    <div class="form-container">
        <form action="{{ route('farmers.orders.update', $order->order_id) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('farmers.partials.form-field', [
                'name' => 'order_status',
                'label' => 'Order Status',
                'type' => 'select',
                'value' => old('order_status', $order->order_status),
                'required' => true,
                'options' => [
                    '' => 'Select Status',
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled'
                ]
            ])
            
            @include('farmers.partials.form-field', [
                'name' => 'actual_delivery_date',
                'label' => 'Actual Delivery Date',
                'type' => 'date',
                'value' => old('actual_delivery_date', $order->actual_delivery_date ? $order->actual_delivery_date->format('Y-m-d') : ''),
            ])
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Status
                </button>
                <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection