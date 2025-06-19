@extends('farmers.layouts.app')

@section('title', 'Financials')

@section('content')
    <h1><i class="fas fa-money-bill"></i> Financials</h1>
    <div class="financial-stats">
        <div class="stat-item">
            <span class="stat-number">${{ $totalRevenue }}</span>
            <span class="stat-label">Total Revenue</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $profitMargin }}%</span>
            <span class="stat-label">Profit Margin</span>
        </div>
    </div>
    <h2><i class="fas fa-transaction"></i> Transactions</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction['order_id'] }}</td>
                    <td>${{ $transaction['amount'] }}</td>
                    <td>{{ $transaction['payment_status'] }}</td>
                    <td>{{ $transaction['created_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('farmers.financials.pricing') }}" class="btn"><i class="fas fa-tags"></i> Manage Pricing</a>
@endsection