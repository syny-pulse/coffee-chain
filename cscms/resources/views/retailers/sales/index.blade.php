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
                <td>{{ $inventoryMap[$sale->product_id] ?? 0 }}</td>
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
    // Chart.js multi-series sales chart by product
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesByProductDay = @json($salesByProductDay ?? []);
    // Group data by product
    const productNames = [...new Set(salesByProductDay.map(row => row.product_name))];
    const dates = [...new Set(salesByProductDay.map(row => row.date))];
    // Build datasets for each product
    const datasets = productNames.map((product, idx) => {
        const color = [
            '#8B7355', '#B8956A', '#6B8E23', '#A0522D', '#708090', '#CD853F', '#3A2B1F', '#5D4E37'
        ][idx % 8];
        return {
            label: product,
            data: dates.map(date => {
                const found = salesByProductDay.find(row => row.product_name === product && row.date === date);
                return found ? found.total_sold : 0;
            }),
            borderColor: color,
            backgroundColor: color + '33', // semi-transparent
            fill: false,
            tension: 0.3
        };
    });
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: { enabled: true }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
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