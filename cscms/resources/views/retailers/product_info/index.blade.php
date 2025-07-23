@extends('retailers.layouts.app')

@section('title', 'Product Info')

@section('content')
<div class="page-header">
    <h1 class="page-title">Product Info Management</h1>
    <a href="{{ route('retailer.product_info.create') }}" class="btn btn-primary">Add New Product</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Products</h2>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Performance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>UGX{{ number_format($product->price_per_kg ?? 0, 2) }}</td>
                <td>
                    <a href="{{ route('retailer.product_info.analytics', $product->product_id) }}" class="btn btn-sm btn-outline">View Analytics</a>
                </td>
                <td>
                    <a href="{{ route('retailer.product_info.edit', $product->product_id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('retailer.product_info.destroy', $product->product_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 