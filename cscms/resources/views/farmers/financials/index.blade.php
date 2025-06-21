@extends('farmers.layouts.app')

@section('title', 'Financials')
@section('page-title', 'Financial Overview')
@section('page-subtitle', 'Track your revenue, expenses, and profit margins')

@section('page-actions')
    <a href="{{ route('farmers.financials.pricing') }}" class="btn btn-primary">
        <i class="fas fa-tags"></i>
        Manage Pricing
    </a>
@endsection

@section('content')
    <!-- Financial Stats -->
    <div class="stats-grid">
        @include('farmers.partials.stat-card', [
            'title' => 'Total Revenue',
            'value' => 'UGX ' . number_format($totalRevenue, 2),
            'icon' => 'money-bill',
            'trend' => ($trends['totalRevenue'] >= 0 ? '+' : '') . $trends['totalRevenue'] . '%',
            'trendType' => $trends['totalRevenue'] >= 0 ? 'positive' : 'negative'
        ])
        
        @include('farmers.partials.stat-card', [
            'title' => 'Total Expenses',
            'value' => 'UGX ' . number_format($totalExpenses, 2),
            'icon' => 'receipt',
            'trend' => ($trends['totalExpenses'] >= 0 ? '+' : '') . $trends['totalExpenses'] . '%',
            'trendType' => $trends['totalExpenses'] >= 0 ? 'negative' : 'positive'
        ])
        
        @include('farmers.partials.stat-card', [
            'title' => 'Net Profit',
            'value' => 'UGX ' . number_format($profit, 2),
            'icon' => 'chart-line',
            'trend' => ($trends['profit'] >= 0 ? '+' : '') . $trends['profit'] . '%',
            'trendType' => $trends['profit'] >= 0 ? 'positive' : 'negative'
        ])
        
        @include('farmers.partials.stat-card', [
            'title' => 'Profit Margin',
            'value' => number_format($profitMargin, 1) . '%',
            'icon' => 'percentage',
            'trend' => ($trends['profitMargin'] >= 0 ? '+' : '') . $trends['profitMargin'] . '%',
            'trendType' => $trends['profitMargin'] >= 0 ? 'positive' : 'negative'
        ])
    </div>

    <!-- Recent Transactions -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-exchange-alt"></i>
                Recent Transactions
            </h2>
        </div>
        
        @if(isset($transactions) && count($transactions) > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            @include('farmers.partials.table-row', [
                                'item' => $transaction,
                                'columns' => [
                                    ['field' => 'order_id'],
                                    ['field' => 'amount', 'type' => 'currency'],
                                    ['field' => 'payment_status', 'type' => 'status'],
                                    ['field' => 'created_at', 'type' => 'date']
                                ]
                            ])
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-exchange-alt"></i>
                <h3>No Transactions Yet</h3>
                <p>Your transaction history will appear here once you start receiving payments.</p>
            </div>
        @endif
    </div>

    <!-- Financial Tools -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-cogs"></i>
                Financial Tools
            </h2>
        </div>
        
        <div class="actions-grid">
            <a href="{{ route('farmers.financials.pricing') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="action-title">Manage Pricing</div>
                <div class="action-desc">Update your coffee prices and market rates</div>
            </a>
            
            <a href="{{ route('farmers.financials.reports') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="action-title">Generate Reports</div>
                <div class="action-desc">Create financial reports and statements</div>
            </a>
            
            <a href="{{ route('farmers.analytics.reports') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="action-title">View Analytics</div>
                <div class="action-desc">Analyze your financial performance</div>
            </a>
            
            <a href="{{ route('farmers.financials.expenses') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="action-title">Track Expenses</div>
                <div class="action-desc">Record and monitor your farm expenses</div>
            </a>
            
            <a href="{{ route('farmers.financials.cashflow') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="action-title">Cash Flow</div>
                <div class="action-desc">Monitor your cash flow and liquidity</div>
            </a>
            
            <a href="{{ route('farmers.financials.forecasting') }}" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="action-title">Financial Forecasting</div>
                <div class="action-desc">Plan and predict future financial performance</div>
            </a>
        </div>
    </div>
@endsection