@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
          <!-- Start Page Title -->
          <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 brand-heading">Forecast overview</h4>
                </div>
            </div>
        </div>
        <!-- End Page Title -->
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Forecast Overview</h4>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Forecasted Sales</h5>
                    <h2 class="card-text">$12,345</h2>
                    <p class="text-muted">Next 30 Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Average Daily Sales</h5>
                    <h2 class="card-text">$411</h2>
                    <p class="text-muted">Last 7 Days</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top Selling Product</h5>
                    <h2 class="card-text">Product A</h2>
                    <p class="text-muted">500 Units Sold</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sales Trends</h5>
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Demand Prediction</h5>
                    <canvas id="demandPredictionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container-fluid -->
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Trends Chart
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    const salesTrendChart = new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Sales',
                data: [1200, 1900, 3000, 2500, 2000, 3000, 4000],
                borderColor: '#635bff',
                fill: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                }
            }
        }
    });

    // Demand Prediction Chart
    const demandPredictionCtx = document.getElementById('demandPredictionChart').getContext('2d');
    const demandPredictionChart = new Chart(demandPredictionCtx, {
        type: 'bar',
        data: {
            labels: ['Product A', 'Product B', 'Product C', 'Product D'],
            datasets: [{
                label: 'Demand',
                data: [500, 300, 400, 600],
                backgroundColor: '#635bff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                }
            }
        }
    });
</script>
@endsection
