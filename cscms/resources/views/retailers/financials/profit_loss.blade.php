@extends('retailers.layouts.app')

@section('title', 'Profit & Loss Statement')

@section('content')
<div class="page-header">
    <h1 class="page-title">Profit & Loss Statement</h1>
</div>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Statement</h2>
    </div>
    <table class="table">
        <thead>
            <tr><th>Date</th><th>Description</th><th>Type</th><th>Amount</th></tr>
        </thead>
        <tbody>
            <tr><td>2024-07-15</td><td>Invoice #1023</td><td>Income</td><td>UGX1,200</td></tr>
            <tr><td>2024-07-14</td><td>Payment to Supplier</td><td>Expense</td><td>-UGX500</td></tr>
            <tr><td>2024-07-13</td><td>Invoice #1022</td><td>Income</td><td>UGX600</td></tr>
        </tbody>
        <tfoot>
            <tr><th colspan="3">Total Income</th><th>UGX1,800</th></tr>
            <tr><th colspan="3">Total Expenses</th><th>-UGX500</th></tr>
            <tr><th colspan="3">Net Profit/Loss</th><th>UGX1,300</th></tr>
        </tfoot>
    </table>
</div>
@endsection 