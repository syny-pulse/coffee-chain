@extends('farmers.layouts.app')

@section('title', 'Edit Harvest')

@section('content')
    <h1><i class="fas fa-seedling"></i> Edit Harvest</h1>
    @include('farmers.partials.errors')
    <form action="{{ route('farmers.harvests.update', $harvest->harvest_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="coffee_variety">Coffee Variety</label>
            <select name="coffee_variety" id="coffee_variety" required>
                <option value="arabica" {{ $harvest->coffee_variety == 'arabica' ? 'selected' : '' }}>Arabica</option>
                <option value="robusta" {{ $harvest->coffee_variety == 'robusta' ? 'selected' : '' }}>Robusta</option>
            </select>
        </div>
        <div class="form-group">
            <label for="processing_method">Processing Method</label>
            <select name="processing_method" id="processing_method" required>
                <option value="natural" {{ $harvest->processing_method == 'natural' ? 'selected' : '' }}>Natural</option>
                <option value="washed" {{ $harvest->processing_method == 'washed' ? 'selected' : '' }}>Washed</option>
                <option value="honey" {{ $harvest->processing_method == 'honey' ? 'selected' : '' }}>Honey</option>
            </select>
        </div>
        <div class="form-group">
            <label for="grade">Grade</label>
            <select name="grade" id="grade" required>
                <option value="grade_1" {{ $harvest->grade == 'grade_1' ? 'selected' : '' }}>Grade 1</option>
                <option value="grade_2" {{ $harvest->grade == 'grade_2' ? 'selected' : '' }}>Grade 2</option>
                <option value="grade_3" {{ $harvest->grade == 'grade_3' ? 'selected' : '' }}>Grade 3</option>
                <option value="grade_4" {{ $harvest->grade == 'grade_4' ? 'selected' : '' }}>Grade 4</option>
                <option value="grade_5" {{ $harvest->grade == 'grade_5' ? 'selected' : '' }}>Grade 5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity_kg">Quantity (kg)</label>
            <input type="number" name="quantity_kg" id="quantity_kg" step="0.01" value="{{ $harvest->quantity_kg }}" required>
        </div>
        <div class="form-group">
            <label for="available_quantity_kg">Available Quantity (kg)</label>
            <input type="number" name="available_quantity_kg" id="available_quantity_kg" step="0.01" value="{{ $harvest->available_quantity_kg }}" required>
        </div>
        <div class="form-group">
            <label for="harvest_date">Harvest Date</label>
            <input type="date" name="harvest_date" id="harvest_date" value="{{ $harvest->harvest_date->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label for="quality_notes">Quality Notes</label>
            <textarea name="quality_notes" id="quality_notes">{{ $harvest->quality_notes }}</textarea>
        </div>
        <button type="submit" class="btn"><i class="fas fa-save"></i> Update</button>
    </form>
    <a href="{{ route('farmers.harvests.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection