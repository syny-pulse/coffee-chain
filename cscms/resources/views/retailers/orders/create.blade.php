@extends('retailers.layouts.app')
@section('title', 'Create Order')
@section('content')
<div class="page-header">
    <h1 class="page-title">Create New Order</h1>
</div>
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('retailer.orders.store') }}" method="POST" class="card" style="padding:2rem; max-width:600px; margin:auto;">
    @csrf
    <div class="form-group">
        <label for="processor_company_id">Select Processor</label>
        <select name="processor_company_id" id="processor_company_id" class="form-control" required>
            <option value="">Select Processor</option>
            @foreach($processors as $processor)
                <option value="{{ $processor->company_id }}">{{ $processor->company_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="coffee_breed">Coffee Breed</label>
        <select name="coffee_breed" id="coffee_breed" class="form-control" required>
            <option value="arabica">Arabica</option>
            <option value="robusta">Robusta</option>
        </select>
    </div>
    <div class="form-group">
        <label for="roast_grade">Roast Grade</label>
        <select name="roast_grade" id="roast_grade" class="form-control" required>
            <option value="1">Grade 1</option>
            <option value="2">Grade 2</option>
            <option value="3">Grade 3</option>
            <option value="4">Grade 4</option>
            <option value="5">Grade 5</option>
        </select>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity (kg)</label>
        <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
    </div>
    <div class="form-group">
        <label for="expected_delivery_date">Expected Delivery Date</label>
        <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="shipping_address">Shipping Address</label>
        <input type="text" name="shipping_address" id="shipping_address" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="notes">Notes (optional)</label>
        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Any special instructions or notes..."></textarea>
    </div>
    <div class="form-actions">
        <a href="{{ route('retailer.orders.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Order</button>
    </div>
</form>
@endsection 