

@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box text-center">
                <h3 class="page-title font-weight-bold text-primary">📊 Monthly Sales Forecast</h3>
                <p class="text-muted">Analyze sales trends and predict future demand efficiently.</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">🔍 Filter Forecast Data</h5>
                    <form id="monthlyForecastForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="startDate" class="font-weight-bold">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="endDate" class="font-weight-bold">End Date</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="product" class="font-weight-bold">Select Product</label>
                                    <select class="form-control" id="product" name="product">
                                        <option value="">All Products</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary btn-block">🚀 Generate Forecast</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow border-info">
                <div class="card-body">
                    <h5 class="card-title text-info">📈 Monthly Sales Chart</h5>
                    <canvas id="monthlyForecastChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <!-- Forecast Results -->
            <div class="card shadow border-success">
                <div class="card-body">
                    <h5 class="card-title " style="color: rgb(87, 187, 5)">📅 Forecasted Sales Data</h5>
                    <table class="table table-hover table-bordered">
                        <thead class="bg-success text-white" >
                            <tr>
                                <th>Month</th>
                                <th>Product</th>
                                <th>Predicted Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="monthlyForecastResults">
                            <!-- Results will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Forecast Summary -->
            <div class="card shadow border-warning">
                <div class="card-body">
                    <h5 class="card-title" style="color: rgb(97, 9, 192)">📊 Sales Forecast Summary</h5>
                    <table class="table table-striped">
                        <thead class="table-header">
                            <tr>
                                <th>Metric</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody id="forecastSummary">
                            <!-- Summary will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<!-- Chart.js for the Forecast Graph -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize the chart with smaller bars
    const monthlyForecastCtx = document.getElementById('monthlyForecastChart').getContext('2d');
    const monthlyForecastChart = new Chart(monthlyForecastCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Monthly Sales',
                data: [],
                backgroundColor: '#28a745',
                borderRadius: 5,
                barThickness: 40,  // Adjust the bar size
                maxBarThickness: 50
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false,
                }
            }
        }
    });

    // Fetch data on form submission
    $('#monthlyForecastForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: "{{ route('forecast.monthly.data') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                monthlyForecastChart.data.labels = response.labels;
                monthlyForecastChart.data.datasets[0].data = response.data;
                monthlyForecastChart.update();

                $('#monthlyForecastResults').html(response.table);
                $('#forecastSummary').html(response.summary);
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
</script>

@endsection
