{{-- @extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Demand Prediction</h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="demandFilterForm">
                        <div class="row">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="forecastPeriod">Forecast Period</label>
                                    <select class="form-control" id="forecastPeriod" name="forecastPeriod">
                                        <option value="7">Next 7 Days</option>
                                        <option value="30">Next 30 Days</option>
                                        <option value="90">Next year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="algorithm">Algorithm</label>
                                    <select class="form-control" id="algorithm" name="algorithm">
                                        <option value="linear_regression">Linear Regression</option>
                                        <option value="moving_average">Moving Average</option>
                                        <option value="exponential_smoothing">Exponential Smoothing</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate Prediction</button>
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
                    <h5 class="card-title">Demand Prediction</h5>
                    <canvas id="demandPredictionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize the chart
    const demandPredictionCtx = document.getElementById('demandPredictionChart').getContext('2d');
    const demandPredictionChart = new Chart(demandPredictionCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Demand',
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
    $('#demandFilterForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: "{{ route('forecast.demand.data') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                demandPredictionChart.data.labels = response.labels;
                demandPredictionChart.data.datasets[0].data = response.data;
                demandPredictionChart.update();
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

<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between page-title-box">
                    <h4 class="page-title"><i class="fas fa-chart-line me-2"></i> Demand Prediction</h4>
                    <button class="btn btn-success"><i class="fas fa-download me-1"></i> Export</button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="demandFilterForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="product"><i class="fas fa-box me-1"></i> Product</label>
                                    <select class="form-control" id="product" name="product">
                                        <option value="">All Products</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="forecastPeriod"><i class="fas fa-calendar-alt me-1"></i> Forecast Period</label>
                                    <select class="form-control" id="forecastPeriod" name="forecastPeriod">
                                        <option value="7">Next 7 Days</option>
                                        <option value="30">Next 30 Days</option>
                                        <option value="90">Next Year</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="algorithm"><i class="fas fa-brain me-1"></i> Algorithm</label>
                                    <select class="form-control" id="algorithm" name="algorithm">
                                        <option value="linear_regression">Linear Regression</option>
                                        <option value="moving_average">Moving Average</option>
                                        <option value="exponential_smoothing">Exponential Smoothing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-play me-1"></i> Generate Prediction</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center"><i class="fas fa-chart-area me-1"></i> Demand Prediction</h5>
                        <canvas id="demandPredictionChart" style="height: 380px; width: 90%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize the chart
    const ctx = document.getElementById('demandPredictionChart').getContext('2d');
    const demandPredictionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Demand',
                data: [],
                borderColor: '#635bff',
                backgroundColor: 'rgba(99, 91, 255, 0.2)',
                borderWidth: 2,
                pointRadius: 5,
                pointBackgroundColor: '#635bff',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: '#e5e5e5' }, beginAtZero: true }
            }
        }
    });

    // Fetch data on form submission
    // $('#demandFilterForm').on('submit', function(e) {
    //     e.preventDefault();
    //     const formData = $(this).serialize();

    //     $.ajax({
    //         url: "{{ route('forecast.demand.data') }}",
    //         method: "POST",
    //         data: formData,
    //         success: function(response) {
    //             demandPredictionChart.data.labels = response.labels;
    //             demandPredictionChart.data.datasets[0].data = response.data;
    //             demandPredictionChart.update();
    //         },
    //         error: function() {
    //             alert('An error occurred while fetching data.');
    //         }
    //     });
    // });

    $('#demandFilterForm').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
        url: "{{ route('forecast.demand.data') }}",
        method: "POST",
        data: formData,
        success: function(response) {
            demandPredictionChart.data.labels = response.labels;
            demandPredictionChart.data.datasets[0].data = response.data;
            demandPredictionChart.update();
        },
        error: function() {
            alert('An error occurred while fetching data.');
        }
    });
});
</script>
@endsection
