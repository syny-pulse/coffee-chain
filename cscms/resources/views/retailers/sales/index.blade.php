@extends('retailers.layouts.app')

@section('title', 'Sales')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Sales</h1>
        <p class="page-subtitle">View and manage your sales records</p>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('retailer.sales.store') }}" method="POST" id="salesForm">
        @csrf
        <div class="form-group">
            <label for="date">Date<span style="color:red">*</span></label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity Sold</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>
                        <input type="hidden" name="sales[{{ $loop->index }}][product_id]" value="{{ $product->product_id }}">
                        <input type="number" name="sales[{{ $loop->index }}][quantity]" class="form-control" min="0" value="{{ old('sales.' . $loop->index . '.quantity', 0) }}" required>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Save Sales Data</button>
    </form>
@endsection
@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('salesForm').addEventListener('submit', function(e) {
        let valid = true;
        document.querySelectorAll('input[type="number"]').forEach(function(input) {
            if (parseInt(input.value) < 0) {
                valid = false;
            }
        });
        if (!valid) {
            e.preventDefault();
            alert('Quantities must be zero or positive.');
        }
    });
});
</script>
@endsection