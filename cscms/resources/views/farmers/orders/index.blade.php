@extends('farmers.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Coffee Orders')
@section('page-subtitle', 'Manage and track your coffee orders')

@section('page-actions')
    <a href="{{ route('farmers.orders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Add New Order
    </a>
@endsection

@section('content')
    @if(isset($orders) && count($orders) > 0)
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-clipboard-list"></i>
                    All Orders
                </h2>
            </div>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Variety</th>
                            <th>Grade</th>
                            <th>Quantity (kg)</th>
                            <th>Unit Price</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @include('farmers.partials.table-row', [
                                'item' => $order,
                                'columns' => [
                                    ['field' => 'order_id'],
                                    ['field' => 'coffee_variety'],
                                    ['field' => 'grade'],
                                    ['field' => 'quantity_kg', 'type' => 'number'],
                                    ['field' => 'unit_price', 'type' => 'currency'],
                                    ['field' => 'total_amount', 'type' => 'currency'],
                                    ['field' => 'order_status', 'type' => 'status']
                                ],
                                'actions' => [
                                    ['type' => 'link', 'url' => route('farmers.orders.show', $order['order_id']), 'icon' => 'eye', 'style' => 'outline', 'title' => 'View'],
                                    ['type' => 'link', 'url' => route('farmers.orders.edit', $order['order_id']), 'icon' => 'edit', 'style' => 'outline', 'title' => 'Edit'],
                                    ['type' => 'delete', 'url' => route('farmers.orders.destroy', $order['order_id'])]
                                ]
                            ])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>No Orders Yet</h3>
                <p>You haven't created any orders yet. Start by creating your first order.</p>
                <a href="{{ route('farmers.orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create Your First Order
                </a>
            </div>
        </div>
    @endif
@endsection