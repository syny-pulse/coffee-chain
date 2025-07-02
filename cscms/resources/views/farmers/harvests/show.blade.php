@extends('farmers.layouts.app')

@section('title', 'Harvest Details')
@section('page-title', 'Harvest Details')

@section('content')
    <div class="harvest-details-card">
        <h2>Harvest Details</h2>
        <table class="table table-bordered">
            <tr><th>ID</th><td>{{ $harvest->id }}</td></tr>
            <tr><th>Coffee Variety</th><td>{{ $harvest->coffee_variety }}</td></tr>
            <tr><th>Processing Method</th><td>{{ $harvest->processing_method }}</td></tr>
            <tr><th>Grade</th><td>{{ $harvest->grade }}</td></tr>
            <tr><th>Total Quantity (kg)</th><td>{{ $harvest->quantity_kg }}</td></tr>
            <tr><th>Available Quantity (kg)</th><td>{{ $harvest->available_quantity_kg }}</td></tr>
            <tr><th>Harvest Date</th><td>{{ $harvest->harvest_date }}</td></tr>
            <tr><th>Quality Notes</th><td>{{ $harvest->quality_notes }}</td></tr>
            <tr><th>Status</th><td>{{ ucfirst($harvest->availability_status) }}</td></tr>
        </table>
        <a href="{{ route('farmers.harvests.index') }}" class="btn btn-secondary mt-3">Back to Harvests</a>
    </div>
@endsection 