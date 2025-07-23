@extends('retailers.layouts.app')

@section('title', 'Analytics & Reporting')

@section('content')
<div class="page-header">
    <h1 class="page-title">Sales Analytics</h1>
    <p class="page-subtitle">Analyze your sales, product segmentation, and export reports</p>
</div>

<!-- Filter Form -->
<form method="GET" class="filter-form" style="margin-bottom:2rem; display:flex; gap:1rem; align-items:flex-end;">
    <div>
        <label for="start_date">Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
    </div>
    <div>
        <label for="end_date">End Date</label>
        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
    </div>
    <div>
        <label for="product">Product</label>
        <select name="product" id="product" class="form-control">
            <option value="">All</option>
            @foreach($sales as $row)
                <option value="{{ $row->product_name }}" @if(request('product') == $row->product_name) selected @endif>{{ $row->product_name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
        <div class="stat-value">UGX<?php echo e(number_format($income, 2)); ?></div>
        <div class="stat-label">Total Income (7 days)</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-chart-bar"></i></div>
        </div>
        <div class="stat-value">{{ $sales->sum('total_sold') }}</div>
        <div class="stat-label">Total Units Sold (7 days)</div>
    </div>
</div>

<div class="card" style="margin-bottom:2rem;">
    <div class="card-header">
        <h2 class="card-title">Top Products (All Time)</h2>
    </div>
    <table class="table">
        <thead><tr><th>Product</th><th>Units Sold</th></tr></thead>
        <tbody>
            @foreach($topProducts as $row)
            <tr><td>{{ $row->product_name }}</td><td>{{ $row->total_sold }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="card" style="margin-bottom:2rem;">
    <div class="card-header">
        <h2 class="card-title">Sales Trends (Last 12 Months)</h2>
    </div>
    <div style="padding:1.5rem;">
        <canvas id="salesTrendsChart" height="80"></canvas>
    </div>
</div>
<div class="card" style="margin-bottom:2rem;">
    <div class="card-header">
        <h2 class="card-title">Inventory Turnover (30 days)</h2>
    </div>
    <div style="padding:1.5rem; font-size:1.2rem;">
        <strong>{{ number_format($turnover, 2) }}</strong> times
    </div>
</div>

<div class="card" style="margin-bottom:2rem;">
    <div class="card-header">
        <h2 class="card-title">Product Segmentation (7 days)</h2>
        <div class="card-actions">
            <a href="{{ route('retailer.analytics.export', ['type' => 'csv']) }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&product={{ request('product') }}" class="btn btn-outline btn-sm"><i class="fas fa-file-csv"></i> Export CSV</a>
            <a href="{{ route('retailer.analytics.export', ['type' => 'pdf']) }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}&product={{ request('product') }}" class="btn btn-outline btn-sm"><i class="fas fa-file-pdf"></i> Export PDF</a>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Units Sold</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $row)
            <tr>
                <td>{{ $row->product_name }}</td>
                <td>{{ $row->total_sold }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Sales by Day (7 days)</h2>
    </div>
    <div style="padding:1.5rem;">
        <canvas id="salesByDayChart" height="100"></canvas>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesByDayChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesByDay->pluck('date')) !!},
            datasets: [{
                label: 'Units Sold',
                data: {!! json_encode($salesByDay->pluck('total_sold')) !!},
                backgroundColor: 'rgba(139, 115, 85, 0.7)',
                borderColor: 'rgb(139, 115, 85)',
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

    // Sales Trends Chart
    const trendsCtx = document.getElementById('salesTrendsChart').getContext('2d');
    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesTrends->pluck('month')) !!},
            datasets: [{
                label: 'Units Sold',
                data: {!! json_encode($salesTrends->pluck('total_sold')) !!},
                backgroundColor: 'rgba(66, 153, 225, 0.3)',
                borderColor: 'rgb(66, 153, 225)',
                borderWidth: 2,
                fill: true
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