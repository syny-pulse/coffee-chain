@extends('retailers.layouts.app')

@section('title', 'Inventory')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Inventory</h1>
        <p class="page-subtitle">Track and manage your inventory</p>
    </div>
    <!-- Manual Adjustment Form -->
    <div class="card" style="margin-bottom:2rem;">
        <div class="card-header">
            <h2 class="card-title">Manual Inventory Adjustment</h2>
        </div>
        <form action="{{ route('retailer.inventory.adjust') }}" method="POST" style="padding:1.5rem;">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Product Type</label>
                    <select name="product_type" class="form-control" required>
                        <option value="">Select Type</option>
                        @foreach($productTypes as $type)
                        <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Coffee Breed</label>
                    <select name="coffee_breed" class="form-control" required>
                        <option value="">Select Breed</option>
                        <option value="arabica">Arabica</option>
                        <option value="robusta">Robusta</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Roast Grade</label>
                    <select name="roast_grade" class="form-control" required>
                        <option value="">Select Grade</option>
                        <option value="Grade 1">Grade 1</option>
                        <option value="Grade 2">Grade 2</option>
                        <option value="Grade 3">Grade 3</option>
                        <option value="Grade 4">Grade 4</option>
                        <option value="Grade 5">Grade 5</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Quantity (kg)</label>
                    <input type="number" name="quantity" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Reason/Notes</label>
                <input type="text" name="reason" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Adjust Inventory</button>
        </form>
    </div>
    <!-- Inventory Summary -->
    <div class="inventory-summary">
        @foreach($remainingStock as $item)
        <div class="inventory-card">
            <h3>{{ ucwords(str_replace('_', ' ', $item['product_type'] ?? '')) }}<br>{{ ucfirst($item['coffee_breed']) }} - Grade {{ $item['roast_grade'] }}</h3>
            <p>Remaining: {{ $item['remaining_quantity'] }} kg</p>
            <p>Total Stock: {{ $item['total_quantity'] }} kg</p>
        </div>
        @endforeach
    </div>
    <!-- Ordered Products Summary -->
    <div class="inventory-summary" style="margin-bottom: 2rem;">
        @foreach($orderedProducts as $order)
        <div class="inventory-card" style="background-color: #f0f0f0;">
            <h3>{{ ucfirst($order->coffee_breed) }} - Grade {{ $order->roast_grade }}</h3>
            <p>Total Ordered: {{ $order->total_ordered }} kg</p>
        </div>
        @endforeach
    </div>
    <!-- Transactions Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction Type</th>
                <th>Coffee Breed</th>
                <th>Roast Grade</th>
                <th>Quantity</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d') }}</td>
                <td>{{ ucfirst($transaction->transaction_type) }}</td>
                <td>{{ ucfirst($transaction->coffee_breed) }}</td>
                <td>{{ $transaction->roast_grade }}</td>
                <td>{{ $transaction->quantity }} kg</td>
                <td>{{ $transaction->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
