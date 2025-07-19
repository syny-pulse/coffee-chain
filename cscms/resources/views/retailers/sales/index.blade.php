@extends('retailers.layouts.app')

@section('title', 'Sales')

@section('page-title')
    Sales
@endsection

@section('page-subtitle')
    View, filter, and manage your sales records
@endsection

@section('page-actions')
    <form method="GET" action="" style="display:inline-block; margin-right:1rem;">
        <input type="date" name="date_from" value="{{ request('date_from') }}" style="margin-right:0.5rem;">
        <input type="date" name="date_to" value="{{ request('date_to') }}" style="margin-right:0.5rem;">
        <select name="product_id" style="margin-right:0.5rem;">
            <option value="">All Products</option>
            @foreach($products as $product)
                <option value="{{ $product->product_id }}" @if(request('product_id') == $product->product_id) selected @endif>{{ $product->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-outline">Filter</button>
    </form>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Sales Data</h1>
        <a href="{{ route('retailer.sales.create') }}" class="btn btn-primary" style="margin-left:auto;">Add Sale</a>
    </div>
    <div class="card" style="margin-bottom:2rem;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="card-title">Sales Trends</h2>
            <div>
                <a href="{{ route('retailer.sales.index', array_merge(request()->all(), ['export' => 'csv'])) }}" class="btn btn-info" style="margin-right:0.5rem;">Export CSV</a>
            </div>
        </div>
        <div class="card-body">
            <canvas id="salesChart" height="80"></canvas>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2 style="margin-top:2rem;">Recent Sales</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Current Inventory</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->date }}</td>
                <td>{{ $sale->product_name }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>{{ $inventory[$sale->product_id]->quantity ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No sales recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Chart.js sales chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesByDay = @json($salesByDay ?? []);
    const labels = salesByDay.map(row => row.date);
    const data = salesByDay.map(row => row.total_sold);
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Units Sold',
                data: data,
                borderColor: '#8B7355',
                backgroundColor: 'rgba(139, 115, 85, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
<style>
.modal { display: none; /* ...other modal styles... */ }
.modal.show { display: flex !important; }
</style>
@endsection