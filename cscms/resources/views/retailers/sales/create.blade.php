@extends('retailers.layouts.app')

@section('title', 'Add Sale')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Add Sale</h1>
        <a href="{{ route('retailer.sales.index') }}" class="btn btn-outline" style="margin-left:auto;">Back to Sales</a>
    </div>
    <form action="{{ route('retailer.sales.store') }}" method="POST" class="card" style="max-width:600px; margin:auto;">
        @csrf
        <div class="form-group">
            <label for="date">Date <span style="color:red">*</span></label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
        </div>
        <div class="form-group">
            <label for="product_id">Product <span style="color:red">*</span></label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->product_id }}" @if(old('product_id') == $product->product_id) selected @endif>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity Sold <span style="color:red">*</span></label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="{{ old('quantity', 1) }}" required>
        </div>
        <div class="form-actions" style="text-align:right;">
            <button type="submit" class="btn btn-primary">Save Sale</button>
        </div>
    </form>
@endsection 