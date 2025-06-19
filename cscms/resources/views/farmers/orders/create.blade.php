@extends('farmers.layouts.app')

@section('title', 'Add Order')

@section('content')
    <h1><i class="fas fa-shopping-cart"></i> Add New Order</h1>
    @include('farmers.partials.errors')
    <form action="{{ route('farmers.orders.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="coffee_variety">Coffee Variety</label>
            <select name="coffee_variety" id="coffee_variety" required>
                <option value="arabica">Arabica</option>
                <option value="robusta">Robusta</option>
            </select>
        </div>
        <div class="form-group">
            <label for="processing_method">Processing Method</label>
            <select name="processing_method" id="processing_method" required>
                <option value="natural">Natural</option>
                <option value="washed">Washed</option>
                <option value="honey">Honey</option>
            </select>
        </div>
        <div class="form-group">
            <label for="grade">Grade</label>
            <select name="grade" id="grade" required>
                <option value="grade_1">Grade 1</option>
                <option value="grade_2">Grade 2</option>
                <option value="grade_3">Grade 3</option>
                <option value="grade_4">Grade 4</option>
                <option value="grade_5">Grade 5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity_kg">Quantity (kg)</label>
            <input type="number" name="quantity_kg" id="quantity_kg" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="number" name="total_amount" id="total_amount" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="expected_delivery_date">Expected Delivery Date</label>
            <input type="date" name="expected_delivery_date" id="expected_delivery_date" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes"></textarea>
        </div>
        <button type="submit" class="btn"><i class="fas fa-save"></i> Save</button>
    </form>
    <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection