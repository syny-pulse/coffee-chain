@extends('farmers.layouts.app')

@section('title', 'Analytics & Reports')
@section('page-title', 'Analytics & Reports')
@section('page-subtitle', 'Comprehensive insights into your farm performance, harvest trends, and business metrics')

@section('page-actions')
            <button class="btn btn-primary" onclick="exportReport()">
                <i class="fas fa-download"></i> Export Report
            </button>
            <button class="btn btn-outline" onclick="refreshData()">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
@endsection
@section('content')
    <!-- Key Metrics Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +{{ $revenueGrowth }}%
                </div>
            </div>
            <div class="stat-value">UGX{{ number_format($currentRevenue) }}</div>
            <div class="stat-label">Current Revenue</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +{{ $revenueGrowth }}%
                </div>
            </div>
            <div class="stat-value">UGX{{ number_format($projectedSales) }}</div>
            <div class="stat-label">Projected Sales</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +2.1%
                </div>
            </div>
            <div class="stat-value">{{ $profitMargin }}%</div>
            <div class="stat-label">Profit Margin</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    +8.5%
                </div>
            </div>
            <div class="stat-value">UGX{{ number_format($averageOrderValue) }}</div>
            <div class="stat-label">Avg Order Value</div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Performance Overview</h2>
        </div>
        <div class="details-grid">
            <div class="detail-section">
                <h3><i class="fas fa-seedling"></i> Harvest Metrics</h3>
                <div class="detail-item">
                    <span class="detail-label">Total Harvests</span>
                    <span class="detail-value">{{ $performanceMetrics['total_harvests'] }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Quality Rating</span>
                    <span class="detail-value">{{ $performanceMetrics['quality_rating'] }}/10</span>
                </div>
            </div>
            
            <div class="detail-section">
                <h3><i class="fas fa-truck"></i> Delivery Metrics</h3>
                <div class="detail-item">
                    <span class="detail-label">On-Time Delivery</span>
                    <span class="detail-value">{{ $performanceMetrics['on_time_delivery'] }}%</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Customer Satisfaction</span>
                    <span class="detail-value">{{ $performanceMetrics['customer_satisfaction'] }}/5</span>
                </div>
            </div>
            
            <div class="detail-section">
                <h3><i class="fas fa-calendar-alt"></i> Seasonal Analysis</h3>
                <div class="detail-item">
                    <span class="detail-label">Peak Season</span>
                    <span class="detail-value">{{ $seasonalAnalysis['peak_season'] }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Optimal Harvest</span>
                    <span class="detail-value">{{ $seasonalAnalysis['optimal_harvest_time'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Harvest Reports Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Harvest Performance Reports</h2>
            <div class="card-actions right-actions">
                <button class="btn btn-sm btn-outline" onclick="filterReports()">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Coffee Variety</th>
                        <th>Total Quantity (kg)</th>
                        <th>Period</th>
                        <th>Growth Rate (%)</th>
                        <th>Quality Score</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($harvestReports as $report)
                        <tr>
                            <td>
                                <strong>{{ $report['coffee_variety'] }}</strong>
                            </td>
                            <td>{{ number_format($report['total_quantity_kg'], 1) }}</td>
                            <td>{{ $report['period'] }}</td>
                            <td>
                                <span class="status-badge {{ $report['growth_rate'] > 0 ? 'completed' : 'cancelled' }}">
                                    {{ $report['growth_rate'] > 0 ? '+' : '' }}{{ $report['growth_rate'] }}%
                                </span>
                            </td>
                            <td>{{ $report['quality_score'] }}/10</td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn btn-sm btn-outline" onclick="viewReportDetails({{ $report['coffee_variety'] }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline" onclick="exportReportData({{ $report['coffee_variety'] }})">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-chart-bar"></i>
                                    <h3>No Harvest Reports</h3>
                                    <p>Start recording harvests to see analytics here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Buyers Section -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Top Buyers Analysis</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Buyer Name</th>
                        <th>Total Orders</th>
                        <th>Total Value (UGX)</th>
                        <th>Growth Rate (%)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topBuyers as $buyer)
                        <tr>
                            <td>
                                <strong>{{ $buyer['name'] }}</strong>
                            </td>
                            <td>{{ $buyer['orders'] }}</td>
                            <td>UGX{{ number_format($buyer['total_value']) }}</td>
                            <td>
                                <span class="status-badge {{ $buyer['growth'] > 0 ? 'completed' : 'cancelled' }}">
                                    {{ $buyer['growth'] > 0 ? '+' : '' }}{{ $buyer['growth'] }}%
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn btn-sm btn-outline" onclick="viewBuyerDetails('{{ $buyer['name'] }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="details-grid">
        <div class="detail-section">
            <h3><i class="fas fa-chart-pie"></i> Coffee Variety Distribution</h3>
            <div class="chart-container">
                <canvas id="varietyChart" width="300" height="200"></canvas>
            </div>
            <div class="chart-legend">
                @foreach ($coffeeVarieties as $variety => $percentage)
                    <div class="legend-item">
                        <span class="legend-color" style="background-color: {{ $variety === 'Arabica' ? '#6B8E23' : '#CD853F' }}"></span>
                        <span class="legend-label">{{ $variety }}: {{ $percentage }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="detail-section">
            <h3><i class="fas fa-chart-line"></i> Revenue Trends</h3>
            <div class="chart-container">
                <canvas id="revenueChart" width="300" height="200"></canvas>
            </div>
            <div class="chart-info">
                <p>Monthly revenue trends showing consistent growth</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js configurations
document.addEventListener('DOMContentLoaded', function() {
    // Coffee Variety Distribution Chart
    const varietyCtx = document.getElementById('varietyChart').getContext('2d');
    new Chart(varietyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Arabica', 'Robusta'],
            datasets: [{
                data: [{{ $coffeeVarieties['Arabica'] }}, {{ $coffeeVarieties['Robusta'] }}],
                backgroundColor: ['#6B8E23', '#CD853F'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Revenue Trends Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueTrends['labels']),
            datasets: [{
                label: 'Revenue (UGX)',
                data: @json($revenueTrends['data']),
                borderColor: '#6B8E23',
                backgroundColor: 'rgba(107, 142, 35, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'UGX' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});

// Analytics functions
function exportReport() {
    alert('Exporting comprehensive analytics report...');
}

function refreshData() {
    location.reload();
}

function filterReports() {
    alert('Filter options would appear here');
}

function viewReportDetails(variety) {
    alert('Viewing detailed report for ' + variety);
}

function exportReportData(variety) {
    alert('Exporting data for ' + variety);
}

function viewBuyerDetails(buyerName) {
    alert('Viewing details for ' + buyerName);
}

// Initialize analytics page
document.addEventListener('DOMContentLoaded', function() {
    // Highlight analytics nav item
    const analyticsLink = document.querySelector('a[href*="analytics"]');
    if (analyticsLink) {
        analyticsLink.classList.add('active');
    }
});
</script>
@endpush

<style>
/* Chart Styles */
.chart-container {
    position: relative;
    height: 200px;
    margin: 1rem 0;
}

.chart-legend {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.legend-label {
    color: var(--text-secondary);
}

.chart-info {
    margin-top: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border-radius: 8px;
    border: 1px solid var(--border-light);
}

.chart-info p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .chart-container {
        height: 150px;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
}

.right-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    width: 100%;
}
</style>