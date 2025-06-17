@extends('layouts.processor')

@section('title', 'Create Farmer Order')

@section('content')
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-plus"></i>
                <span>Create New Farmer Order</span>
            </div>
        </div>

        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Display error message from controller --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('processor.order.farmer_order.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="coffee_variety">Coffee Variety</label>
                <select name="coffee_variety" class="form-control" required>
                    <option value="arabica">Arabica</option>
                    <option value="robusta">Robusta</option>
                </select>
            </div>

            <div class="form-group">
                <label for="processing_method">Processing Method</label>
                <select name="processing_method" class="form-control" required>
                    <option value="natural">Natural</option>
                    <option value="washed">Washed</option>
                    <option value="honey">Honey</option>
                </select>
            </div>

            <div class="form-group">
                <label for="grade">Grade</label>
                <select name="grade" class="form-control" required>
                    <option value="grade_1">Grade 1</option>
                    <option value="grade_2">Grade 2</option>
                    <option value="grade_3">Grade 3</option>
                    <option value="grade_4">Grade 4</option>
                    <option value="grade_5">Grade 5</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity_kg">Quantity (kg)</label>
                <input type="number" name="quantity_kg" class="form-control" required min="1" value="{{ old('quantity_kg') }}">
            </div>

            <div class="form-group">
                <label for="unit_price">Unit Price (UGX)</label>
                <input type="number" name="unit_price" class="form-control" required min="0" value="{{ old('unit_price') }}">
            </div>

            <div class="form-group">
                <label for="expected_delivery_date">Expected Delivery Date</label>
                <input type="date" name="expected_delivery_date" class="form-control" required value="{{ old('expected_delivery_date') }}">
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Submit Order</button>
            </div>
        </form>
    </div>
@endsection
