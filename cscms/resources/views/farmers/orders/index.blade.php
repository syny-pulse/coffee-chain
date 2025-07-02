@extends('farmers.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Coffee Orders')
@section('page-subtitle', 'Manage and track your coffee orders')

@section('page-actions')
    @if(isset($orders) && count($orders) > 0)
        <a href="{{ route('farmers.orders.edit', $orders->first()->order_id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i>
            Update Order Status
        </a>
    @endif
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
                            <th>Processor</th>
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
                                    ['field' => 'order_status', 'type' => 'status'],
                                    ['field' => 'processor_company_id', 'type' => 'processor']
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
                <p>You haven't created any orders yet. Orders will appear here when a processor makes one for you.</p>
            </div>
        </div>
    @endif
@endsection