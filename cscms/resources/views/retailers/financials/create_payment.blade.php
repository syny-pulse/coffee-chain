@extends('retailers.layouts.app')
@section('title', 'Record Payment')
@section('content')
<div class="page-header">
    <h1 class="page-title">Record Payment</h1>
</div>
<form action="{{ route('retailer.financials.payments.store') }}" method="POST" class="card" style="padding:2rem; max-width:600px; margin:auto;">
    @csrf
    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" name="date" id="date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="payer">Payer</label>
        <input type="text" name="payer" id="payer" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="payee">Payee</label>
        <input type="text" name="payee" id="payee" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="amount">Amount (UGX)</label>
        <input type="number" name="amount" id="amount" class="form-control" min="0" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="method">Method</label>
        <input type="text" name="method" id="method" class="form-control">
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control">
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="refunded">Refunded</option>
        </select>
    </div>
    <div class="form-group">
        <label for="invoice_id">Linked Invoice (optional)</label>
        <select name="invoice_id" id="invoice_id" class="form-control">
            <option value="">None</option>
            @foreach(App\Models\Invoice::all() as $invoice)
                <option value="{{ $invoice->id }}">{{ $invoice->number }} - UGX{{ number_format($invoice->amount, 2) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-actions">
        <a href="{{ route('retailer.financials.payments') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary">Record Payment</button>
    </div>
</form>
@endsection 