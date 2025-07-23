@extends('retailers.layouts.app')

@section('title', 'Financial Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Financial Dashboard</h1>
</div>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
        <div class="stat-value">UGX12,500</div>
        <div class="stat-label">Total Revenue (YTD)</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
        <div class="stat-value">UGX7,200</div>
        <div class="stat-label">Total Expenses (YTD)</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
        </div>
        <div class="stat-value">UGX5,300</div>
        <div class="stat-label">Profit / Loss (YTD)</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        </div>
        <div class="stat-value">UGX1,800</div>
        <div class="stat-label">Outstanding Invoices</div>
    </div>
</div>
<div class="card" style="margin-bottom:2rem;">
    <div class="card-header">
        <h2 class="card-title">Recent Transactions</h2>
        <div class="card-actions">
            <a href="{{ route('retailer.financials.invoices') }}" class="btn btn-outline btn-sm">Invoices</a>
            <a href="{{ route('retailer.financials.payments') }}" class="btn btn-outline btn-sm">Payments</a>
            <a href="{{ route('retailer.financials.profit_loss') }}" class="btn btn-outline btn-sm">Profit/Loss</a>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr><th>Date</th><th>Description</th><th>Type</th><th>Amount</th></tr>
        </thead>
        <tbody>
            <tr><td>2024-07-15</td><td>Invoice #1023</td><td>Invoice</td><td>UGX1,200</td></tr>
            <tr><td>2024-07-14</td><td>Payment from Retailer</td><td>Payment</td><td>UGX800</td></tr>
            <tr><td>2024-07-13</td><td>Supplier Payment</td><td>Expense</td><td>-UGX500</td></tr>
            <tr><td>2024-07-12</td><td>Invoice #1022</td><td>Invoice</td><td>UGX600</td></tr>
        </tbody>
    </table>
</div>
@endsection 