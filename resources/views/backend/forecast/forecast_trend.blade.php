{{-- @extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
    </div>

    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="trendFilterForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product">Product</label>
                                    <select class="form-control" id="product" name="product">
                                        <option value="">All Products</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sales Trends</h5>
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize the chart
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    const salesTrendChart = new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Sales',
                data: [],
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

    // Fetch data on form submission
    $('#trendFilterForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: "{{ route('forecast.trend.data') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                salesTrendChart.data.labels = response.labels;
                salesTrendChart.data.datasets[0].data = response.data;
                salesTrendChart.update();
            },
            error: function(xhr) {
                alert('An error occurred while fetching data.');
            }
        });
    });
</script>
@endsection --}}

{{--
@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 brand-heading">
                        <i class="fas fa-chart-line me-2 text-primary"></i> Forecast Overview
                    </h4>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Filters -->
        <div class="row mt-3">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <form id="trendFilterForm">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label for="trendType" class="fw-bold">Select Trend Type</label>
                                    <select class="form-select" id="trendType" name="trendType">
                                        <option value="lastweek">Last week </option>
                                        <option value="lastmonth">Last month </option>
                                        <option value="last6months">Last 6months</option>
                                        <option value="lastyear">Last year </option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="product" class="fw-bold">Product</label>
                                    <select class="form-select" id="product" name="product">
                                        <option value="">All Products</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3" id="customDateRange" style="display: none;">
                                <div class="col-md-6">
                                    <label for="startDate" class="fw-bold">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate">
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="fw-bold">End Date</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate">
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-filter me-2"></i> Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mt-4">
            <div class="col-lg-11 mx-auto">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">
                            <i class="fas fa-chart-bar text-success me-2"></i> Sales Trends
                        </h5>
                        <canvas id="salesTrendChart" ></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Show/hide custom date range fields
    $('#trendType').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#customDateRange').slideDown();
        } else {
            $('#customDateRange').slideUp();
        }
    });

    // Initialize the chart
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    const salesTrendChart = new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Sales',
                data: [],
                borderColor: '#635bff',
                backgroundColor: 'rgba(99, 91, 255, 0.2)',
                borderWidth: 2,
                pointBackgroundColor: '#635bff',
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Fetch data on form submission
    $('#trendFilterForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: "{{ route('forecast.trend.data') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                salesTrendChart.data.labels = response.labels;
                salesTrendChart.data.datasets[0].data = response.data;
                salesTrendChart.update();
            },
            error: function(xhr) {
                alert('An error occurred while fetching data.');
            }
        });
    });
</script>
@endsection --}}
@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 brand-heading">
                        <i class="fas fa-chart-line me-2 text-primary"></i> Forecast Overview
                    </h4>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="yearly-trend-tab" data-bs-toggle="tab" data-bs-target="#yearly-trend" type="button" role="tab" aria-controls="yearly-trend" aria-selected="false">Yearly/Monthly/daily Sales Trend</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="product-trend-tab" data-bs-toggle="tab" data-bs-target="#product-trend" type="button" role="tab" aria-controls="product-trend" aria-selected="true">Product Sales Trend</button>
            </li>

        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Product Sales Trend Tab -->
            <div class="tab-pane fade show active" id="product-trend" role="tabpanel" aria-labelledby="product-trend-tab">
                <div class="row mt-3">
                    <div class="col-lg-8 mx-auto">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <form id="productTrendFilterForm" method="POST">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-6">
                                            <label for="productTrendType" class="fw-bold">Select Trend Type</label>
                                            <select class="form-select" id="productTrendType" name="trendType">
                                                <option value="lastweek">Last week</option>
                                                <option value="lastmonth">Last month</option>
                                                <option value="last6months">Last 6 months</option>
                                                <option value="lastyear">Last year</option>
                                                <option value="custom">Custom Range</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="product" class="fw-bold">Product</label>
                                            <select class="form-select" id="product" name="product">
                                                <option value="">All Products</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3" id="productCustomDateRange" style="display: none;">
                                        <div class="col-md-6">
                                            <label for="productStartDate" class="fw-bold">Start Date</label>
                                            <input type="date" class="form-control" id="productStartDate" name="startDate">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="productEndDate" class="fw-bold">End Date</label>
                                            <input type="date" class="form-control" id="productEndDate" name="endDate">
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-filter me-2"></i> Apply Filters
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-11 mx-auto">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-3">
                                    <i class="fas fa-chart-bar text-success me-2"></i> Product Sales Trends
                                </h5>
                                <canvas id="productSalesTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yearly Sales Trend Tab -->
            <div class="tab-pane fade" id="yearly-trend" role="tabpanel" aria-labelledby="yearly-trend-tab">
                <div class="row mt-3">
                    <div class="col-lg-8 mx-auto">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <form id="yearlyTrendFilterForm">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-6">
                                            <label for="yearlyTrendType" class="fw-bold">Select Trend Type</label>
                                            <select class="form-select" id="yearlyTrendType" name="trendType">
                                                <option value="lastweek">Last week</option>
                                                <option value="lastmonth">Last month</option>
                                                <option value="last6months">Last 6 months</option>
                                                <option value="lastyear">Last year</option>
                                                <option value="custom">Custom Range</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3" id="yearlyCustomDateRange" style="display: none;">
                                        <div class="col-md-6">
                                            <label for="yearlyStartDate" class="fw-bold">Start Date</label>
                                            <input type="date" class="form-control" id="yearlyStartDate" name="startDate">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="yearlyEndDate" class="fw-bold">End Date</label>
                                            <input type="date" class="form-control" id="yearlyEndDate" name="endDate">
                                        </div>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-filter me-2"></i> Apply Filters
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-11 mx-auto">
                        <div class="card shadow-lg border-0">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-3">
                                    <i class="fas fa-chart-bar text-success me-2"></i> Yearly Sales Trends
                                </h5>
                                <canvas id="yearlySalesTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paste the script block here -->
<script>
    // Initialize charts
    const productSalesTrendCtx = document.getElementById('productSalesTrendChart').getContext('2d');
    const productSalesTrendChart = new Chart(productSalesTrendCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Sales',
                data: [],
                borderColor: '#635bff',
                backgroundColor: 'rgba(99, 91, 255, 0.2)',
                borderWidth: 2,
                pointBackgroundColor: '#635bff',
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time Period', // X-axis label
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Revenue ($)', // Y-axis label
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    beginAtZero: true
                }
            }
        }
    });

    const yearlySalesTrendCtx = document.getElementById('yearlySalesTrendChart').getContext('2d');
    const yearlySalesTrendChart = new Chart(yearlySalesTrendCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Sales',
                data: [],
                borderColor: '#ff6384',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                pointBackgroundColor: '#ff6384',
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time Period', // X-axis label
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Revenue ($)', // Y-axis label
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Show/hide custom date range fields for product trend
    $('#productTrendType').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#productCustomDateRange').slideDown();
        } else {
            $('#productCustomDateRange').slideUp();
        }
    });

    // Show/hide custom date range fields for yearly trend
    $('#yearlyTrendType').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#yearlyCustomDateRange').slideDown();
        } else {
            $('#yearlyCustomDateRange').slideUp();
        }
    });

    // Fetch data for product trend
    $('#productTrendFilterForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: "{{ route('forecast.product.trend.data') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                productSalesTrendChart.data.labels = response.labels;
                productSalesTrendChart.data.datasets[0].data = response.data;
                productSalesTrendChart.update();
            },
            error: function(xhr) {
                alert('An error occurred while fetching data.');
            }
        });
    });

    // Fetch data for yearly trend
    $('#yearlyTrendFilterForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: "{{ route('forecast.yearly.trend.data') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                yearlySalesTrendChart.data.labels = response.labels;
                yearlySalesTrendChart.data.datasets[0].data = response.data;
                yearlySalesTrendChart.update();
            },
            error: function(xhr) {
                alert('An error occurred while fetching data.');
            }
        });
    });
</script>
@endsection
