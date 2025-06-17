@extends('farmers.layouts.app')

@section('title', 'Add Harvest')

@section('content')
    <h1><i class="fas fa-seedling"></i> Add New Harvest</h1>
    @include('farmers.partials.errors')
    <form action="{{ route('farmers.harvests.store') }}" method="POST">
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
            <label for="available_quantity_kg">Available Quantity (kg)</label>
            <input type="number" name="available_quantity_kg" id="available_quantity_kg" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="harvest_date">Harvest Date</label>
            <input type="date" name="harvest_date" id="harvest_date" required>
        </div>
        <div class="form-group">
            <label for="quality_notes">Quality Notes</label>
            <textarea name="quality_notes" id="quality_notes"></textarea>
        </div>
        <button type="submit" class="btn"><i class="fas fa-save"></i> Save</button>
    </form>
    <a href="{{ route('farmers.harvests.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection