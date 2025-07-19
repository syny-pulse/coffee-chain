<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 2rem; }
        .title { font-size: 2rem; font-weight: bold; }
        .section { margin-bottom: 1.5rem; }
        .label { font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; margin-top: 2rem; }
        .table th, .table td { border: 1px solid #ccc; padding: 0.5rem; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Invoice</div>
        <div>#{{ $invoice->number }}</div>
    </div>
    <div class="section">
        <span class="label">Date:</span> {{ $invoice->date }}<br>
        <span class="label">Customer:</span> {{ $invoice->customer }}<br>
        <span class="label">Status:</span> {{ ucfirst($invoice->status) }}<br>
        <span class="label">Due Date:</span> {{ $invoice->due_date }}<br>
        <span class="label">Paid At:</span> {{ $invoice->paid_at }}<br>
    </div>
    <table class="table">
        <thead>
            <tr><th>Description</th><th>Amount</th></tr>
        </thead>
        <tbody>
            <tr><td>Invoice Total</td><td>UGX{{ number_format($invoice->amount, 2) }}</td></tr>
        </tbody>
    </table>
    <div class="section" style="margin-top:2rem;">
        <span class="label">Created At:</span> {{ $invoice->created_at }}<br>
        <span class="label">Updated At:</span> {{ $invoice->updated_at }}<br>
    </div>
</body>
</html> 