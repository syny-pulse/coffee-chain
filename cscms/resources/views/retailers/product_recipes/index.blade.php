@extends('retailers.layouts.app')

@section('title', 'Product Recipes')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Product Recipes</h1>
        <p class="page-subtitle">Manage your product recipes</p>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Components</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>{{ $product->description }}</td>
                <td>
                    @php
                        $components = DB::table('product_composition')->where('product_id', $product->product_id)->get();
                    @endphp
                    <ul>
                        @foreach($components as $component)
                            <li>{{ ucfirst($component->coffee_breed) }} - {{ $component->roast_grade }}: {{ $component->percentage }}%</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection