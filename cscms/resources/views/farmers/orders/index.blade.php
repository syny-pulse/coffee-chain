@extends('farmers.layouts.app')

@section('title', 'Orders')

@section('content')
    <h1><i class="fas fa-shopping-cart"></i> Orders</h1>
    <a href="{{ route('farmers.orders.create') }}" class="btn"><i class="fas fa-plus"></i> Add New Order</a>
    <table>
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
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_id }}</td>
                    <td>{{ $order->coffee_variety }}</td>
                    <td>{{ $order->grade }}</td>
                    <td>{{ $order->quantity_kg }}</td>
                    <td>${{ $order->unit_price }}</td>
                    <td>${{ $order->total_amount }}</td>
                    <td>{{ $order->order_status }}</td>
                    <td>
                        <a href="{{ route('farmers.orders.view', $order->order_id) }}" class="btn btn-outline"><i class="fas fa-eye"></i> View</a>
                        <a href="{{ route('farmers.orders.edit', $order->order_id) }}" class="btn btn-outline"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('farmers.orders.destroy', $order->order_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Cancel</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection