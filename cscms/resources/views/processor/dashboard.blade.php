@extends('layouts.processor')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Hero Section -->
    <div class="welcome-hero fade-in">
        <div class="hero-content">
            <div class="welcome-text">
                <h1>Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p>Here's what's happening in your coffee processing operations today</p>
                <div class="current-time">{{ now()->format('l, F d, Y') }} • {{ now()->format('h:i A') }}</div>
            </div>
            <div class="hero-visual">
                <div class="coffee-animation">
                    <i class="fas fa-coffee"></i>
                    <div class="steam steam-1"></div>
                    <div class="steam steam-2"></div>
                    <div class="steam steam-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions fade-in">
        <div class="action-card" onclick="window.location.href='{{ route('processor.order.farmer_order.create') }}'">
            <div class="action-icon">
                <i class="fas fa-seedling"></i>
                </div>
            <div class="action-content">
                <h3>Order from Farmers</h3>
                <p>Place new orders for raw coffee</p>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>

        <div class="action-card" onclick="window.location.href='{{ route('processor.inventory.create') }}'">
            <div class="action-icon">
                <i class="fas fa-industry"></i>
                </div>
            <div class="action-content">
                <h3>Add Finished Goods</h3>
                <p>Record processed products</p>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>

        <div class="action-card" onclick="window.location.href='{{ route('processor.message.create') }}'">
            <div class="action-icon">
                <i class="fas fa-envelope"></i>
                </div>
            <div class="action-content">
                <h3>Send Message</h3>
                <p>Communicate with partners</p>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>

        <div class="action-card" onclick="window.location.href='{{ route('processor.analytics.index') }}'">
            <div class="action-icon">
                <i class="fas fa-chart-line"></i>
                </div>
            <div class="action-content">
                <h3>View Analytics</h3>
                <p>Check performance metrics</p>
            </div>
            <div class="action-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>

    <!-- Key Metrics with Animations -->
    <div class="metrics-section fade-in">
        <div class="metrics-header">
            <h2>Today's Key Metrics</h2>
            <div class="metrics-refresh">
                <i class="fas fa-sync-alt"></i>
                <span>Live Data</span>
            </div>
        </div>
        
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="metric-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+12%</span>
                    </div>
                </div>
                <div class="metric-value">{{ $total_farmer_orders ?? 24 }}</div>
                <div class="metric-label">Farmer Orders</div>
                <div class="metric-progress">
                    <div class="progress-bar" style="width: 75%"></div>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon">
                        <i class="fas fa-warehouse"></i>
                </div>
                    <div class="metric-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+8%</span>
                                    </div>
                </div>
                <div class="metric-value">{{ $total_inventory_items ?? 156 }}</div>
                <div class="metric-label">Inventory Items</div>
                <div class="metric-progress">
                    <div class="progress-bar" style="width: 85%"></div>
            </div>
        </div>

            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="metric-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+15%</span>
                    </div>
                </div>
                <div class="metric-value">{{ $total_retailer_orders ?? 18 }}</div>
                <div class="metric-label">Retailer Orders</div>
                <div class="metric-progress">
                    <div class="progress-bar" style="width: 60%"></div>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="metric-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+22%</span>
                    </div>
                </div>
                <div class="metric-value">UGX {{ number_format($total_revenue ?? 2500000) }}</div>
                <div class="metric-label">Monthly Revenue</div>
                <div class="metric-progress">
                    <div class="progress-bar" style="width: 90%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="dashboard-grid fade-in">
        <!-- Production Overview -->
        <div class="dashboard-card production-overview">
            <div class="card-header">
                <h3><i class="fas fa-industry"></i> Production Overview</h3>
                <div class="card-actions">
                    <button class="btn-icon" onclick="refreshChart()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="productionChart" width="400" height="200"></canvas>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <span class="legend-color" style="background: var(--coffee-medium);"></span>
                    <span>Raw Materials</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: var(--coffee-light);"></span>
                    <span>Finished Goods</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="dashboard-card recent-activity">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Recent Activity</h3>
                <a href="#" class="btn-link">View All</a>
            </div>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New Farmer Order</div>
                        <div class="activity-desc">Order #FO-2024-001 received from Bukomansimbi Farm</div>
                        <div class="activity-time">2 minutes ago</div>
    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Production Complete</div>
                        <div class="activity-desc">500kg of Arabica coffee processed into finished goods</div>
                        <div class="activity-time">15 minutes ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Retailer Order</div>
                        <div class="activity-desc">Café Javas placed order for 200kg roasted coffee</div>
                        <div class="activity-time">1 hour ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Message Received</div>
                        <div class="activity-desc">Quality feedback from Bukomansimbi Farm</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Indicators -->
    <div class="performance-section fade-in">
        <div class="section-header">
            <h2>Performance Indicators</h2>
            <p>Real-time monitoring of key operational metrics</p>
                </div>
        
        <div class="performance-grid">
            <div class="performance-card">
                <div class="performance-header">
                    <h4>Processing Efficiency</h4>
                    <div class="performance-value">94.2%</div>
                </div>
                <div class="performance-chart">
                    <svg width="120" height="120" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="rgba(111, 78, 55, 0.1)" stroke-width="8"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="var(--coffee-medium)" stroke-width="8" 
                                stroke-dasharray="314" stroke-dashoffset="18.8" stroke-linecap="round" 
                                style="transform: rotate(-90deg); transform-origin: 60px 60px;"/>
                    </svg>
                    <div class="chart-center">
                        <span>94%</span>
                </div>
        </div>
    </div>

            <div class="performance-card">
                <div class="performance-header">
                    <h4>Order Fulfillment</h4>
                    <div class="performance-value">98.7%</div>
                </div>
                <div class="performance-chart">
                    <svg width="120" height="120" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="rgba(111, 78, 55, 0.1)" stroke-width="8"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="var(--coffee-light)" stroke-width="8" 
                                stroke-dasharray="314" stroke-dashoffset="4.1" stroke-linecap="round" 
                                style="transform: rotate(-90deg); transform-origin: 60px 60px;"/>
                    </svg>
                    <div class="chart-center">
                        <span>99%</span>
                </div>
        </div>
    </div>

            <div class="performance-card">
                <div class="performance-header">
                    <h4>Quality Score</h4>
                    <div class="performance-value">96.5%</div>
                </div>
                <div class="performance-chart">
                    <svg width="120" height="120" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="rgba(111, 78, 55, 0.1)" stroke-width="8"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="var(--success)" stroke-width="8" 
                                stroke-dasharray="314" stroke-dashoffset="11.0" stroke-linecap="round" 
                                style="transform: rotate(-90deg); transform-origin: 60px 60px;"/>
                    </svg>
                    <div class="chart-center">
                        <span>97%</span>
                </div>
        </div>
    </div>

            <div class="performance-card">
                <div class="performance-header">
                    <h4>Customer Satisfaction</h4>
                    <div class="performance-value">4.8/5</div>
                </div>
                <div class="performance-chart">
                    <svg width="120" height="120" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="rgba(111, 78, 55, 0.1)" stroke-width="8"/>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="var(--accent)" stroke-width="8" 
                                stroke-dasharray="314" stroke-dashoffset="12.6" stroke-linecap="round" 
                                style="transform: rotate(-90deg); transform-origin: 60px 60px;"/>
                    </svg>
                    <div class="chart-center">
                        <span>4.8</span>
                    </div>
                </div>
                </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Production Chart
    const ctx = document.getElementById('productionChart').getContext('2d');
    const productionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Raw Materials', 'Finished Goods', 'In Processing'],
            datasets: [{
                data: [45, 35, 20],
                backgroundColor: [
                    'var(--coffee-medium)',
                    'var(--coffee-light)',
                    'var(--accent)'
                ],
                borderWidth: 0,
                cutout: '70%'
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
            animation: {
                animateRotate: true,
                duration: 2000
            }
        }
    });

    // Animate progress bars
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });
    }

    // Refresh chart function
    function refreshChart() {
        productionChart.update();
        animateProgressBars();
    }

    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(animateProgressBars, 1000);
    });
</script>

<style>
    /* Welcome Hero */
    .welcome-hero {
        background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .welcome-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.3;
    }

    .hero-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .welcome-text h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .welcome-text p {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .current-time {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .hero-visual {
        position: relative;
    }

    .coffee-animation {
        position: relative;
        font-size: 4rem;
        animation: float 3s ease-in-out infinite;
    }

    .steam {
        position: absolute;
        width: 4px;
        height: 20px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 2px;
        animation: steam 2s ease-in-out infinite;
    }

    .steam-1 { left: 20px; top: -10px; animation-delay: 0s; }
    .steam-2 { left: 30px; top: -15px; animation-delay: 0.5s; }
    .steam-3 { left: 40px; top: -12px; animation-delay: 1s; }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    @keyframes steam {
        0% { transform: translateY(0) scaleY(1); opacity: 0.6; }
        50% { transform: translateY(-20px) scaleY(1.5); opacity: 0.3; }
        100% { transform: translateY(-40px) scaleY(0.5); opacity: 0; }
    }

    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .action-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid rgba(111, 78, 55, 0.1);
        backdrop-filter: blur(10px);
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(111, 78, 55, 0.15);
        background: rgba(255, 255, 255, 0.95);
    }

    .action-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .action-content {
        flex: 1;
    }

    .action-content h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--coffee-dark);
        margin-bottom: 0.25rem;
    }

    .action-content p {
        font-size: 0.9rem;
        color: var(--text-light);
        margin: 0;
    }

    .action-arrow {
        color: var(--coffee-medium);
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .action-card:hover .action-arrow {
        transform: translateX(5px);
    }

    /* Metrics Section */
    .metrics-section {
        margin-bottom: 2rem;
    }

    .metrics-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .metrics-header h2 {
        font-size: 1.5rem;
        color: var(--coffee-dark);
        margin: 0;
    }

    .metrics-refresh {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--coffee-medium);
        font-size: 0.9rem;
    }

    .metrics-refresh i {
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .metric-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(111, 78, 55, 0.1);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-3px);
    }

    .metric-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .metric-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .metric-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .metric-trend.positive {
        color: var(--success);
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--coffee-dark);
        margin-bottom: 0.5rem;
    }

    .metric-label {
        font-size: 0.9rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }

    .metric-progress {
        height: 6px;
        background: rgba(111, 78, 55, 0.1);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
        border-radius: 3px;
        transition: width 1s ease;
    }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(111, 78, 55, 0.1);
        backdrop-filter: blur(10px);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .card-header h3 {
        font-size: 1.2rem;
        color: var(--coffee-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border: none;
        background: rgba(111, 78, 55, 0.1);
        border-radius: 6px;
        color: var(--coffee-medium);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-icon:hover {
        background: var(--coffee-medium);
        color: white;
    }

    .btn-link {
        color: var(--coffee-medium);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .chart-container {
        height: 200px;
        margin-bottom: 1rem;
    }

    .chart-legend {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-light);
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
    }

    /* Activity List */
    .activity-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .activity-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(111, 78, 55, 0.05);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        background: rgba(111, 78, 55, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--coffee-medium);
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: var(--coffee-dark);
        margin-bottom: 0.25rem;
    }

    .activity-desc {
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 0.25rem;
    }

    .activity-time {
        font-size: 0.75rem;
        color: var(--text-light);
    }

    /* Performance Section */
    .performance-section {
        margin-bottom: 2rem;
    }

    .performance-section .section-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .performance-section .section-header h2 {
        font-size: 1.8rem;
        color: var(--coffee-dark);
        margin-bottom: 0.5rem;
    }

    .performance-section .section-header p {
        color: var(--text-light);
        font-size: 1rem;
    }

    .performance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .performance-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        border: 1px solid rgba(111, 78, 55, 0.1);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }

    .performance-card:hover {
        transform: translateY(-5px);
    }

    .performance-header {
        margin-bottom: 1.5rem;
    }

    .performance-header h4 {
        font-size: 1rem;
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }

    .performance-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--coffee-dark);
    }

    .performance-chart {
        position: relative;
        display: inline-block;
    }

    .chart-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--coffee-dark);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-content {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .welcome-text h1 {
            font-size: 2rem;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }

        .metrics-grid {
            grid-template-columns: 1fr;
        }

        .performance-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection