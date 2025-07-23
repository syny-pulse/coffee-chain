@extends('retailers.layouts.app')
@section('title', 'Invoice Details')
@section('content')
<div class="page-header">
    <h1 class="page-title">Invoice #{{ $invoice->number }}</h1>
    <a href="{{ route('retailer.financials.invoices') }}" class="btn btn-outline">Back to Invoices</a>
</div>
<div class="card" style="max-width:600px;margin:auto;">
    <div class="card-header"><h2 class="card-title">Invoice Details</h2></div>
    <div style="padding:2rem;">
        <div><strong>Number:</strong> {{ $invoice->number }}</div>
        <div><strong>Date:</strong> {{ $invoice->date }}</div>
        <div><strong>Customer:</strong> {{ $invoice->customer }}</div>
        <div><strong>Amount:</strong> UGX{{ number_format($invoice->amount, 2) }}</div>
        <div><strong>Status:</strong> <span class="status-badge status-{{ $invoice->status == 'paid' ? 'delivered' : 'pending' }}">{{ ucfirst($invoice->status) }}</span></div>
        <div><strong>Due Date:</strong> {{ $invoice->due_date }}</div>
        <div><strong>Paid At:</strong> {{ $invoice->paid_at }}</div>
        <div><strong>Created At:</strong> {{ $invoice->created_at }}</div>
        <div><strong>Updated At:</strong> {{ $invoice->updated_at }}</div>
    </div>
</div>
@endsection 