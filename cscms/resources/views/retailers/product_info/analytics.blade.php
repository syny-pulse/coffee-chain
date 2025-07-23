@extends('retailers.layouts.app')

@section('title', 'Product Analytics')

@section('content')
<div class="page-header">
    <h1 class="page-title">Product Analytics: {{ $product->name }}</h1>
    <a href="{{ route('retailer.product_info.index') }}" class="btn btn-outline">Back to Products</a>
</div>
<div class="card" style="margin-bottom:2rem;">
    <div class="card-header">
        <h2 class="card-title">Overview</h2>
    </div>
    <div style="padding:1.5rem;">
        <div><strong>Total Sales:</strong> {{ $sales }} units</div>
        <div><strong>Total Revenue:</strong> ${{ number_format($revenue, 2) }}</div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Customer Reviews</h2>
    </div>
    <table class="table">
        <thead>
            <tr><th>Rating</th><th>Comment</th><th>Date</th></tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>{{ $review->rating ?? '-' }}</td>
                <td>{{ $review->comment ?? '-' }}</td>
                <td>{{ $review->created_at ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="3">No reviews yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 