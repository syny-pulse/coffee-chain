@extends('layouts.processor')

@section('title', 'Analytics')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-chart-line"></i>
            <div>
                <h1>Analytics Dashboard</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Manage analytics as of {{ now()->format('h:i A T, l, F d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Key Performance Metrics -->
    <div class="stats-grid fade-in">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Profit Margin</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($analytics->profit_margin, 2) }}%</div>
            <div class="stat-card-change">
                <i class="fas fa-chart-line"></i>
                <span class="change-positive">Net profit as percentage of revenue</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Inventory Turnover</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($analytics->inventory_turnover, 2) }}</div>
            <div class="stat-card-change">
                <i class="fas fa-chart-line"></i>
                <span class="change-positive">Revenue per kg of coffee</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Production Efficiency</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-industry"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($analytics->production_efficiency, 2) }}%</div>
            <div class="stat-card-change">
                <i class="fas fa-chart-line"></i>
                <span class="change-positive">Revenue per 100kg processed</span>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="stats-grid fade-in" style="margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Revenue</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
            <div class="stat-card-value">UGX {{ number_format($analytics->total_revenue, 0) }}</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-up"></i>
                <span class="change-positive">From delivered orders</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Cost</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="stat-card-value">UGX {{ number_format($analytics->total_cost, 0) }}</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-down"></i>
                <span class="change-negative">From delivered purchases</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Total Processed</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-weight"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($analytics->total_processed_kg, 2) }} kg</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-up"></i>
                <span class="change-positive">Total coffee processed</span>
            </div>
        </div>
    </div>

    <!-- Customer Segments -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-users"></i>
                <span>Customer Segments</span>
            </div>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">High Value</span>
                    <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ $customerSegments['high_value'] }}</div>
                <div class="stat-card-change">
                    <i class="fas fa-arrow-up"></i>
                    <span class="change-positive">Orders over 10M UGX</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Medium Value</span>
                    <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ $customerSegments['medium_value'] }}</div>
                <div class="stat-card-change">
                    <i class="fas fa-minus"></i>
                    <span class="change-positive">Orders 5M-10M UGX</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Low Value</span>
                    <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="stat-card-value">{{ $customerSegments['low_value'] }}</div>
                <div class="stat-card-change">
                    <i class="fas fa-arrow-down"></i>
                    <span class="change-negative">Orders under 5M UGX</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Production Trends Chart -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-chart-line"></i>
                <span>Production Trends</span>
            </div>
        </div>
        <div style="background: rgba(255, 255, 255, 0.6); border-radius: 12px; padding: 1.5rem; backdrop-filter: blur(10px); border: 1px solid rgba(111, 78, 55, 0.1);">
            <canvas id="productionChart" width="400" height="200"></canvas>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('productionChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Production (kg)',
                    data: [500, 600, 550, 700, 650, 800],
                    borderColor: 'var(--coffee-medium)',
                    backgroundColor: 'rgba(111, 78, 55, 0.2)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Production Volume'
                    }
                }
            }
        });
    </script>
@endsection