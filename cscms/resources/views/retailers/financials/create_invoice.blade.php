@extends('retailers.layouts.app')
@section('title', 'Create Invoice')
@section('content')
<div class="page-header">
    <h1 class="page-title">Create Invoice</h1>
</div>
<form action="{{ route('retailer.financials.invoices.store') }}" method="POST" class="card" style="padding:2rem; max-width:600px; margin:auto;">
    @csrf
    <div class="form-group">
        <label for="number">Invoice Number</label>
        <input type="text" name="number" id="number" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" name="date" id="date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="customer">Customer</label>
        <input type="text" name="customer" id="customer" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="amount">Amount (UGX)</label>
        <input type="number" name="amount" id="amount" class="form-control" min="0" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="unpaid">Unpaid</option>
            <option value="paid">Paid</option>
        </select>
    </div>
    <div class="form-group">
        <label for="due_date">Due Date</label>
        <input type="date" name="due_date" id="due_date" class="form-control">
    </div>
    <div class="form-actions">
        <a href="{{ route('retailer.financials.invoices') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Invoice</button>
    </div>
</form>
@endsection 