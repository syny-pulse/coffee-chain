@extends('retailers.layouts.app')

@section('title', 'Demand Planning')

@section('content')
<div class="page-header">
    <h1 class="page-title">Demand Planning</h1>
    <p class="page-subtitle">Forecast demand, plan orders, and optimize inventory</p>
</div>
<form method="GET" class="filter-form" style="margin-bottom:2rem; display:flex; gap:1rem; align-items:flex-end;">
    <div>
        <label for="month">Month</label>
        <select name="month" id="month" class="form-control">
            @for($m=1;$m<=12;$m++)
                <option value="{{ $m }}" @if($month == $m) selected @endif>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
            @endfor
        </select>
    </div>
    <div>
        <label for="year">Year</label>
        <select name="year" id="year" class="form-control">
            @for($y = date('Y'); $y <= date('Y')+2; $y++)
                <option value="{{ $y }}" @if($year == $y) selected @endif>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Show Forecast</button>
    </div>
</form>
@if(!empty($mlError))
    <div class="alert alert-warning" style="margin-bottom:1rem;">Demand predictions are currently unavailable. Using fallback values. Please try again later.</div>
@endif
<div class="card" style="margin-bottom:2rem;">
    <div class="card-header">
        <h2 class="card-title">Forecast Table</h2>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Forecast (units)</th>
                <th>Current Inventory (units)</th>
                <th>Suggested Order (units)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $predictions[$product->product_id] }}</td>
                <td>{{ $inventory[$product->product_id]->quantity ?? 0 }}</td>
                <td>{{ max($predictions[$product->product_id] - ($inventory[$product->product_id]->quantity ?? 0), 0) }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary createOrderFromPlanBtn" data-product="{{ $product->name }}" data-quantity="{{ max($predictions[$product->product_id] - ($inventory[$product->product_id]->quantity ?? 0), 0) }}" data-month="{{ $month }}" data-year="{{ $year }}">Create Order</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Forecast Chart</h2>
    </div>
    <div style="padding:1.5rem;">
        <canvas id="forecastChart" height="100"></canvas>
    </div>
</div>
@php
    $inventoryData = [];
    foreach ($products as $p) {
        $inventoryData[] = $inventory[$p->product_id]->quantity ?? 0;
    }
@endphp
@endsection
@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('forecastChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($products->pluck('product_name')) !!},
            datasets: [{
                label: 'Forecast (units)',
                data: {!! json_encode(array_values($predictions)) !!},
                backgroundColor: 'rgba(66, 153, 225, 0.7)',
                borderColor: 'rgb(66, 153, 225)',
                borderWidth: 1
            }, {
                label: 'Current Inventory',
                data: {!! json_encode($inventoryData) !!},
                backgroundColor: 'rgba(72, 187, 120, 0.7)',
                borderColor: 'rgb(72, 187, 120)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Units' }
                }
            }
        }
    });
});
</script>
@endsection 