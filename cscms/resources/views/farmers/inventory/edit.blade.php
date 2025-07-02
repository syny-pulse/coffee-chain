@extends('farmers.layouts.app')

@section('title', 'Edit Inventory Item')
@section('page-title', 'Edit Inventory Item')
@section('page-subtitle', 'Update details for this inventory record')

@section('page-actions')
    <a href="{{ route('farmers.inventory.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Inventory
    </a>
@endsection

@section('content')
    <div class="form-container" style="max-width: 600px; margin: 0 auto;">
        <form action="#" method="POST">
            @csrf
            @method('PUT')
            @include('farmers.partials.form-field', [
                'name' => 'coffee_variety',
                'label' => 'Coffee Variety',
                'type' => 'select',
                'value' => old('coffee_variety', $item->coffee_variety),
                'required' => true,
                'options' => [
                    '' => 'Select Coffee Variety',
                    'arabica' => 'Arabica',
                    'robusta' => 'Robusta'
                ]
            ])
            @include('farmers.partials.form-field', [
                'name' => 'processing_method',
                'label' => 'Processing Method',
                'type' => 'select',
                'value' => old('processing_method', $item->processing_method),
                'required' => true,
                'options' => [
                    '' => 'Select Processing Method',
                    'natural' => 'Natural',
                    'washed' => 'Washed',
                    'honey' => 'Honey'
                ]
            ])
            @include('farmers.partials.form-field', [
                'name' => 'grade',
                'label' => 'Grade',
                'type' => 'select',
                'value' => old('grade', $item->grade),
                'required' => true,
                'options' => [
                    '' => 'Select Grade',
                    'grade_1' => 'Grade 1',
                    'grade_2' => 'Grade 2',
                    'grade_3' => 'Grade 3',
                    'grade_4' => 'Grade 4',
                    'grade_5' => 'Grade 5'
                ]
            ])
            @include('farmers.partials.form-field', [
                'name' => 'quantity_kg',
                'label' => 'Total Quantity (kg)',
                'type' => 'number',
                'value' => old('quantity_kg', $item->quantity_kg),
                'placeholder' => 'Enter total harvest quantity',
                'required' => true,
                'step' => '0.01',
                'min' => '0'
            ])
            @include('farmers.partials.form-field', [
                'name' => 'available_quantity_kg',
                'label' => 'Available Quantity (kg)',
                'type' => 'number',
                'value' => old('available_quantity_kg', $item->available_quantity_kg),
                'placeholder' => 'Enter available quantity for sale',
                'required' => true,
                'step' => '0.01',
                'min' => '0'
            ])
            @include('farmers.partials.form-field', [
                'name' => 'harvest_date',
                'label' => 'Harvest Date',
                'type' => 'date',
                'value' => old('harvest_date', $item->harvest_date ? $item->harvest_date->format('Y-m-d') : ''),
                'required' => true
            ])
            @include('farmers.partials.form-field', [
                'name' => 'quality_notes',
                'label' => 'Quality Notes',
                'type' => 'textarea',
                'value' => old('quality_notes', $item->quality_notes),
                'placeholder' => 'Add any notes about the harvest quality, conditions, etc.',
                'rows' => '4'
            ])
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Inventory
                </button>
                <a href="{{ route('farmers.inventory.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
@endsection 