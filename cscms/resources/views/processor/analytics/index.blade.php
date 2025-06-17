@extends('layouts.processor')

@section('title', 'Analytics')

@section('content')
    <section class="section" id="analytics">
        <div class="section-container">
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

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>Profit Margin</h3>
                    <p>{{ number_format($analytics->profit_margin ?? 'N/A', 2) }}%</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3>Inventory Turnover</h3>
                    <p>{{ number_format($analytics->inventory_turnover ?? 'N/A', 2) }} times/year</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3>Production Efficiency</h3>
                    <p>{{ number_format($analytics->production_efficiency ?? 'N/A', 2) }}%</p>
                </div>
            </div>

            <div style="margin-top: 4rem;">
                <h3>Production Trends</h3>
                <canvas id="productionChart" width="400" height="200"></canvas>
            </div>
        </div>
    </section>
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
                }
            }
        });
    </script>
@endsection