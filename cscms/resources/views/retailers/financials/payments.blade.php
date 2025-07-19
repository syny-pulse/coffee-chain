@extends('retailers.layouts.app')

@section('title', 'Payments')

@section('content')
<div class="page-header">
    <h1 class="page-title">Payments</h1>
    <a href="{{ route('retailer.financials.payments.store') }}" class="btn btn-primary">Record Payment</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="GET" class="filter-form" style="margin-bottom:1.5rem; display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">
    <div>
        <label for="payer">Payer</label>
        <input type="text" name="payer" id="payer" class="form-control" value="{{ $filters['payer'] ?? '' }}">
    </div>
    <div>
        <label for="payee">Payee</label>
        <input type="text" name="payee" id="payee" class="form-control" value="{{ $filters['payee'] ?? '' }}">
    </div>
    <div>
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            <option value="completed" @if(($filters['status'] ?? '')=='completed') selected @endif>Completed</option>
            <option value="pending" @if(($filters['status'] ?? '')=='pending') selected @endif>Pending</option>
            <option value="refunded" @if(($filters['status'] ?? '')=='refunded') selected @endif>Refunded</option>
        </select>
    </div>
    <div>
        <label for="date_from">From</label>
        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
    </div>
    <div>
        <label for="date_to">To</label>
        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
    </div>
    <div>
        <label for="min_amount">Min Amount</label>
        <input type="number" name="min_amount" id="min_amount" class="form-control" step="0.01" value="{{ $filters['min_amount'] ?? '' }}">
    </div>
    <div>
        <label for="max_amount">Max Amount</label>
        <input type="number" name="max_amount" id="max_amount" class="form-control" step="0.01" value="{{ $filters['max_amount'] ?? '' }}">
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Payments</h2>
    </div>
    <table class="table">
        <thead>
            <tr><th>Date</th><th>Payer/Payee</th><th>Amount</th><th>Method</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->date }}</td>
                <td>{{ $payment->payer }} / {{ $payment->payee }}</td>
                <td>UGX{{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->method }}</td>
                <td><span class="status-badge status-{{ $payment->status == 'completed' ? 'delivered' : ($payment->status == 'refunded' ? 'cancelled' : 'pending') }}">{{ ucfirst($payment->status) }}</span></td>
                <td>
                    <a href="#" class="btn btn-sm btn-outline">View</a>
                    @if($payment->status == 'completed')
                    <a href="#" class="btn btn-sm btn-danger">Refund</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 