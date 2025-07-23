@extends('retailers.layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="page-header">
    <h1 class="page-title">Invoices</h1>
    <a href="{{ route('retailer.financials.invoices.store') }}" class="btn btn-primary">Create Invoice</a>
    <a href="{{ route('retailer.financials.invoices.export') }}" class="btn btn-outline btn-sm">Export CSV</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="GET" class="filter-form" style="margin-bottom:1.5rem; display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">
    <div>
        <label for="customer">Customer</label>
        <input type="text" name="customer" id="customer" class="form-control" value="{{ $filters['customer'] ?? '' }}">
    </div>
    <div>
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="">All</option>
            <option value="unpaid" @if(($filters['status'] ?? '')=='unpaid') selected @endif>Unpaid</option>
            <option value="paid" @if(($filters['status'] ?? '')=='paid') selected @endif>Paid</option>
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
        <h2 class="card-title">All Invoices</h2>
    </div>
    <table class="table">
        <thead>
            <tr><th>#</th><th>Date</th><th>Customer</th><th>Amount</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->number }}</td>
                <td>{{ $invoice->date }}</td>
                <td>{{ $invoice->customer }}</td>
                <td>UGX{{ number_format($invoice->amount, 2) }}</td>
                <td><span class="status-badge status-{{ $invoice->status == 'paid' ? 'delivered' : 'pending' }}">{{ ucfirst($invoice->status) }}</span></td>
                <td>
                    <a href="{{ route('retailer.financials.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline">View</a>
                    @if($invoice->status == 'unpaid')
                    <form action="{{ route('retailer.financials.invoices.mark_paid', $invoice->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Mark Paid</button>
                    </form>
                    @endif
                    <a href="{{ route('retailer.financials.invoices.download', $invoice->id) }}" class="btn btn-sm btn-info">Download</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 