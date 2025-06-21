@extends('farmers.layouts.app')

@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')
@section('page-subtitle', 'View your monthly revenue and order summary')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-file-invoice"></i> Financial Reports</h2>
        </div>
        <div class="card-body">
            <h4>Monthly Revenue</h4>
            <ul>
                @foreach($reports['monthly_revenue'] as $month => $amount)
                    <li><strong>{{ $month }}:</strong> UGX {{ number_format($amount, 2) }}</li>
                @endforeach
            </ul>
            <hr>
            <h4>Order Summary</h4>
            <ul>
                <li>Total Orders: {{ $reports['total_orders'] }}</li>
                <li>Completed Orders: {{ $reports['completed_orders'] }}</li>
                <li>Pending Orders: {{ $reports['pending_orders'] }}</li>
            </ul>
        </div>
    </div>
@endsection 