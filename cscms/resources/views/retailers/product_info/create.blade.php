@extends('retailers.layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="page-header">
    <h1 class="page-title">Add New Product</h1>
</div>
<form action="{{ route('retailer.product_info.store') }}" method="POST" enctype="multipart/form-data" class="card" style="padding:2rem; max-width:600px; margin:auto;">
    @csrf
    <div class="form-group">
        <label for="name">Product Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="product_type">Product Type</label>
        <select name="product_type" id="product_type" class="form-control" required>
            <option value="">Select Product Type</option>
            @foreach($productTypes as $type)
                <option value="{{ $type }}" @if(old('product_type') == $type) selected @endif>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="origin_country">Origin Country</label>
        <input type="text" name="origin_country" id="origin_country" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="processing_method">Processing Method</label>
        <select name="processing_method" id="processing_method" class="form-control" required>
            <option value="">Select Processing Method</option>
            @foreach($processingMethods as $method)
                <option value="{{ $method }}" @if(old('processing_method') == $method) selected @endif>{{ $method }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="roast_level">Roast Level</label>
        <select name="roast_level" id="roast_level" class="form-control" required>
            <option value="">Select Roast Level</option>
            @foreach($roastLevels as $level)
                <option value="{{ $level }}" @if(old('roast_level') == $level) selected @endif>{{ $level }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="quantity_kg">Quantity (kg)</label>
        <input type="number" name="quantity_kg" id="quantity_kg" class="form-control" min="0" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="price_per_kg">Price per kg (USD)</label>
        <input type="number" name="price_per_kg" id="price_per_kg" class="form-control" min="0" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="quality_score">Quality Score (1-10)</label>
        <input type="number" name="quality_score" id="quality_score" class="form-control" min="0" max="10" step="0.1" required>
    </div>
    <div class="form-group">
        <label for="harvest_date">Harvest Date</label>
        <input type="date" name="harvest_date" id="harvest_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="processing_date">Processing Date</label>
        <input type="date" name="processing_date" id="processing_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="expiry_date">Expiry Date</label>
        <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control" rows="3" placeholder="e.g. Origin, Roast Level, Notes"></textarea>
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @if(old('status') == $status) selected @endif>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-actions">
        <a href="{{ route('retailer.product_info.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </div>
</form>
@endsection 