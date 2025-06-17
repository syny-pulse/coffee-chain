@extends('farmers.layouts.app')

@section('title', 'Manage Pricing')

@section('content')
    <h1><i class="fas fa-tags"></i> Manage Pricing</h1>
    @include('farmers.partials.errors')
    <form action="{{ route('farmers.financials.pricing.update') }}" method="POST">
        @csrf
        @foreach ($pricing as $index => $price)
            <div class="form-group">
                <h3>{{ $price['coffee_variety'] }} - {{ $price['grade'] }}</h3>
                <input type="hidden" name="prices[{{ $index }}][coffee_variety]" value="{{ $price['coffee_variety'] }}">
                <input type="hidden" name="prices[{{ $index }}][grade]" value="{{ $price['grade'] }}">
                <label for="unit_price_{{ $index }}">Unit Price</label>
                <input type="number" name="prices[{{ $index }}][unit_price]" id="unit_price_{{ $index }}" step="0.01" value="{{ $price['unit_price'] }}" required>
            </div>
        @endforeach
        <button type="submit" class="btn"><i class="fas fa-save"></i> Update Pricing</button>
    </form>
    <a href="{{ route('farmers.financials.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection