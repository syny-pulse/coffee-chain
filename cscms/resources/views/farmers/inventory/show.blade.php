@extends('farmers.layouts.app')

@section('title', 'Inventory Item')
@section('page-title', 'Inventory Item Details')
@section('page-subtitle', 'View details for this inventory record')

@section('page-actions')
    <a href="{{ route('farmers.inventory.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Inventory
    </a>
@endsection

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-boxes-stacked"></i> Inventory Item #{{ $item->harvest_id }}
            </h2>
        </div>
        <div class="details-grid">
            <div class="detail-section">
                <div class="detail-item"><span class="detail-label">Coffee Variety:</span> <span class="detail-value">{{ ucfirst($item->coffee_variety) }}</span></div>
                <div class="detail-item"><span class="detail-label">Grade:</span> <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $item->grade)) }}</span></div>
                <div class="detail-item"><span class="detail-label">Processing Method:</span> <span class="detail-value">{{ ucfirst($item->processing_method) }}</span></div>
                <div class="detail-item"><span class="detail-label">Total Quantity (kg):</span> <span class="detail-value">{{ number_format($item->quantity_kg, 1) }}</span></div>
                <div class="detail-item"><span class="detail-label">Available (kg):</span> <span class="detail-value">{{ number_format($item->available_quantity_kg, 1) }}</span></div>
                <div class="detail-item"><span class="detail-label">Reserved (kg):</span> <span class="detail-value">{{ number_format($item->quantity_kg - $item->available_quantity_kg, 1) }}</span></div>
                <div class="detail-item"><span class="detail-label">Harvest Date:</span> <span class="detail-value">{{ \Carbon\Carbon::parse($item->harvest_date)->format('M d, Y') }}</span></div>
                <div class="detail-item"><span class="detail-label">Status:</span> <span class="detail-value">{{ ucfirst($item->availability_status) }}</span></div>
                <div class="detail-item"><span class="detail-label">Quality Notes:</span> <span class="detail-value">{{ $item->quality_notes ?: '-' }}</span></div>
            </div>
        </div>
    </div>
@endsection 