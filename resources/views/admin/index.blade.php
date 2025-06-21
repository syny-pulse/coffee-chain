@extends('admin.admin_master')
@section('admin')
<style>
    /* Style for custom date inputs */
        #startDate, #endDate {
            background-color: #f0f8f4; /* Light green background */
            border: 2px solid #28a745; /* Light green border */
            transition: all 0.3s ease;
        }

        #startDate:focus, #endDate:focus {
            border: 2px solid #28a745; /* Green border on focus */
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25); /* Green shadow on focus */
        }

        /* Style for select dropdown */
        #salesDateSelect {
            background-color: #f0f8f4; /* Light green background */
            border: 2px solid #28a745; /* Light green border */
        }

        #salesDateSelect option {
            background-color: #ffffff;
        }

        /* Enhancing card and other text */
        .card {
            border-radius: 10px;
        }

        .card-body {
            background-color: #f9f9f9; /* Light background */
            border-radius: 10px;
        }

        .text-muted {
            color: #6c757d !important; /* Muted text color */
        }

        .text-success {
            color: #28a745 !important; /* Green color */
        }

        .font-size-14 {
            font-size: 14px;
        }

        /* Hover effects for the card */
        .card:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        #outstandingBalanceCard .custom-select {
            background-color: #d4f5e5;  /* Light green background */
                border: 1px solid #a5d6a7;  /* Light green border */
                color: #388e3c;  /* Dark green text */
                font-weight: 500;
                transition: all 0.3s ease;
            }

    #outstandingBalanceCard .custom-select:hover {
        border-color: #fdc800;
        box-shadow: 0 0 5px rgba(253, 200, 0, 0.3);
    }

    #outstandingBalanceCard .custom-select:focus {
        border-color: #fdc800;
        box-shadow: 0 0 5px rgba(253, 200, 0, 0.5);
    }

    #outstandingBalanceCard .card-body {
        background-color: #fff;
        padding: 20px;
    }

    #outstandingBalanceCard .card-body .text-muted {
        font-size: 14px;
    }

    #outstandingBalanceCard .card-body h4 {
        font-size: 1.5rem;
        font-weight: 600;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Advanced Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Admin Panel</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Stats Row -->
        <div class="row">
            <!-- Total Sales with Date Filter -->
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0" id="outstandingBalanceCard">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3 align-items-center">
                            <label for="salesDateSelect" class="form-label mb-0 text-muted"><h6>Select Date:</h6></label>
                            <select id="salesDateSelect" class="form-select w-auto border-0 rounded-pill px-3 py-2 custom-select">

                                <option value="Today">Today</option>
                                <option value="This Week">This Week</option>
                                <option value="Last Week">Last Week</option>
                                <option value="This Month">This Month</option>
                                <option value="Last Month">Last Month</option>
                                <option value="Custom Date">Custom Date</option>
                            </select>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-17 mb-2 text-muted"><h5>

                                    <span class="badge bg-info text-white"> Total Sales</span>

                                </h5></p>
                                <h4 id="totalSalesAmount" class="mb-2 text-success">Ugx 0</h4>
                                {{-- <p class="text-muted mb-0">
                                    <span id="salesIncrease" class="text-success fw-bold font-size-12 me-2">
                                        <i class="ri-arrow-up-line align-middle me-1"></i>0%
                                    </span>
                                    Increase
                                </p> --}}
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-circle">
                                    <i class="ri-money-dollar-circle-line font-size-24"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Custom Date Range Picker -->
                        <div id="customDate" class="d-none mt-3">
                            <div class="d-flex justify-content-between">
                                <input type="date" id="startDate" class="form-control w-auto" style="max-width: 45%;" required />
                                <input type="date" id="endDate" class="form-control w-auto" style="max-width: 45%;" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0" id="outstandingBalanceCard">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3 align-items-center">
                            <label for="balancesDateSelect" class="form-label mb-0 text-muted"><h6>Select Date:</h6></label>
                            <select id="balancesDateSelect" class="form-select w-auto border-0 rounded-pill px-3 py-2 custom-select">
                                <option value="Today">Today</option>
                                <option value="This Week">This Week</option>
                                <option value="Last Week">Last Week</option>
                                <option value="This Month">This Month</option>
                                <option value="Last Month" selected>Last Month</option>
                                <option value="Custom Date1">Custom Date</option>
                            </select>
                        </div>
                        <a href="{{route('paid.customer.print.pdf')}}" target="_blank">

                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-17 mb-2 text-muted"><h5>
                                    <span class="badge bg-info text-white"> Outstanding Balance</span>

                                </h5></p>

                                <h4 id="outstandingBalanceAmount" class="mb-2 text-danger">Ugx 25,000</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-danger rounded-circle">
                                    <i class="ri-wallet-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                        </a>

                        <!-- Custom Date Range Picker -->
                        <div id="customDate1" class="d-none mt-3">
                            <div class="d-flex justify-content-between">
                                <input type="date" id="startDate" class="form-control w-auto" style="max-width: 45%;" required />
                                <input type="date" id="endDate" class="form-control w-auto" style="max-width: 45%;" required />
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            @php


                // Get total purchased amount where status is 1 (approved)
                $totalPurchasedAmount = DB::table('purchases')
               ->where('status', 1) // Approved status
               ->sum(DB::raw('buying_price')); // Calculate total amount
            @endphp
           <div class="col-xl-3 col-md-6">
               <div class="card" style="min-height: 185px; display: flex; flex-direction: column; justify-content: space-between;">
                   <div class="card-body">
                       <div class="d-flex align-items-center">
                           <div class="flex-grow-1">
                               <h5 class="text-truncate mb-2" style="font-size: 16px;">
                                   <span class="badge bg-info text-white">Total stock value</span>
                               </h5>
                               <h4 class="mb-2 text-success" style="margin-top: auto;"> <b>Ugx {{number_format($totalPurchasedAmount, 0)}}</b></h4>

                           </div>
                           <div class="avatar-sm">
                               <span class="avatar-title bg-light text-success rounded-circle">
                                   <i class="ri-shopping-cart-line font-size-24"></i>
                               </span>
                           </div>
                       </div>
                   </div>
               </div>
           </div>


            @php
                use Illuminate\Support\Facades\DB;

                $totalApprovedProducts = DB::table('products')->count();


                    // Calculate number of out-of-stock products (quantity = 0)
                $outOfStock = DB::table('products')
                    ->where('quantity', 0)  // Products with 0 quantity (out of stock)
                    ->count();

                // Calculate number of products with 3 or less (warning)
                $warningStock = DB::table('products')
                    ->where('quantity', '<=', 5)  // Products with quantity <= 3
                    ->where('quantity', '>', 0)  // Exclude out of stock (quantity 0)
                    ->count();
            @endphp
            <div class="col-xl-3 col-md-6">
                <a href="{{route('purchase.add')}}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-truncate font-size-14 mb-2">

                                    <span class="badge bg-info text-white"> Total Products</span>

                                    </p>
                                    <h4 class="mb-2">{{$totalApprovedProducts}}</h4> <!-- Example total products number -->
                                    <p class="text-muted mb-3">
                                        <span class="badge bg-danger text-white font-size-12 me-2">
                                            <i class="ri-product-hunt-line align-middle me-1"></i>
                                            Out of Stock: <b>{{$outOfStock}}</b> <!-- Example out of stock number -->
                                        </span>
                                    </p>
                                    <p class="text-muted mb-0">
                                        <span class="badge bg-warning text-dark font-size-12 me-2">
                                            <i class="ri-product-hunt-line align-middle me-1"></i>
                                            Running low: <b>{{$warningStock}}</b> <!-- Example out of stock number -->
                                        </span>
                                    </p>
                                </div>
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-light text-primary rounded-circle">
                                        <i class="ri-file-list-3-line font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>


        <div class="row">
            {{-- <div class="col-xl-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title mb-4">

                            <span class="badge bg-info text-white"> Sales Summary</span>

                        </h3>
                        <div class="d-flex justify-content-between mb-3">
                            <label for="monthSelect" class="form-label">Select Month:</label>
                            <select id="monthSelect" class="form-select w-auto">
                                <option value="This Year">This Year</option>
                                <option value="Last Week">previous Week</option>
                                <option value="This Month">This Month</option>
                                <option value="Last Month">Last month</option>
                                <option value="Last 6Month">Last 6 months</option>
                                <option value="fiscal">Fiscal year to Date</option>
                                <option value="Custom date">Custom date</option>
                            </select>
                        </div>
                        <canvas id="salesChart" style="height: 200px;"></canvas>
                    </div>
                </div>
            </div> --}}

            <div class="col-xl-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="card-title mb-4">
                            <span class="badge bg-info text-white">Sales Summary</span>
                        </h3>
                        <div class="d-flex justify-content-between mb-3">
                            <label for="monthSelect" class="form-label">Select Range:</label>
                            <select id="monthSelect" class="form-select w-auto">
                                <option value="This Year">This Year</option>
                                {{-- <option value="Last Week">Previous Week</option> --}}
                                {{-- <option value="This Month">This Month</option>
                                <option value="Last Month">Last Month</option> --}}
                                <option value="Last 6Month">Last 6 Months</option>
                                <option value="Last Year">Last Year</option>
                                {{-- <option value="Fiscal">Fiscal Year to Date</option>
                                <option value="Custom date">Custom Date</option> --}}
                            </select>
                        </div>
                        <canvas id="salesChart" style="height: 200px;"></canvas>
                    </div>
                </div>
            </div>


            <div class="col-xl-4">
                <!-- Top-Selling Products Card -->
                @php
                    // Fetch top-selling products with total revenue and total paid amount
                    $topSellingProducts = \App\Models\Product::select(
                        'products.id',
                        'products.name',
                        // Add any other specific columns from the products table
                        \DB::raw('SUM(invoice_details.selling_price) as total_revenue'),
                        \DB::raw('SUM(payments.paid_amount) as total_paid_amount')
                    )
                    ->join('invoice_details', 'invoice_details.product_id', '=', 'products.id')
                    ->join('invoices', 'invoices.id', '=', 'invoice_details.invoice_id')
                    ->join('payments', 'payments.invoice_id', '=', 'invoices.id')
                    ->where('invoice_details.status', 1)
                    ->whereIn('payments.paid_status', ['full_paid', 'partial_paid'])
                    ->groupBy('products.id', 'products.name') // Include other selected columns in GROUP BY
                    ->orderByDesc('total_paid_amount') // Sort directly in SQL
                    ->limit(6)
                    ->get();
                @endphp

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Top-Selling Products</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead style="background-color: #635BFF; color:whitesmoke">
                                    <tr>
                                        <th>Product Name</th>
                                        {{-- <th>Expense</th> --}}
                                        <th>Sales </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topSellingProducts as $product)

                                        <tr>
                                            {{-- <td> <b>{{ $product->name }} </b></td> --}}
                                            <td>{{ \Illuminate\Support\Str::limit($product->name, 14, '...') }}</td>

                                            {{-- <td>Ugx {{ number_format($product->total_revenue, 0) }}</td> --}}
                                            <td>
                                                {{-- Ugx {{ number_format($product->total_paid_amount, 0) }} --}}
                                                <span class="badge bg-primary rounded-pill" style="font-size: 12px">Ugx {{ number_format($product->total_paid_amount, 0) }}</span>

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
            @php



                $pendingInvoices = DB::table('invoices')
    ->join('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
    ->join('payments', 'invoices.id', '=', 'payments.invoice_id')
    ->join('customers', 'customers.id', '=', 'payments.customer_id')
    ->join('products', 'invoice_details.product_id', '=', 'products.id')
    ->select(
        'invoices.invoice_no',
        'invoices.status',
        'invoices.date as date',
        'invoices.id as invoiceid',
        'payments.total_amount',
        'customers.name as customer_name',
        DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'), // Combine product names
        DB::raw('GROUP_CONCAT(invoice_details.selling_qty SEPARATOR ", ") as quantities'), // Combine quantities

    )
    ->groupBy(
        'invoices.invoice_no',
        'invoices.status',
        'invoices.date',
        'invoices.id',
        'customers.name',
        'payments.total_amount',

    )
    ->orderBy('invoices.id', 'desc')
    ->orderBy('invoices.date', 'desc')
    ->where('invoices.status', 0)
    ->limit(6)
    ->get();

            @endphp

                <div class="col-xl-8">
                    <div class="card mt-0">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Pending Invoices</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">

                                    <thead style="background-color: #635BFF; color:whitesmoke">
                                        <tr>
                                            <th>Customer</th>
                                            <th>product</th>
                                            <th>QTY</th>
                                            <th>Amount </th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($pendingInvoices as $invoice)
                                            <tr>
                                                <td>{{ $invoice->customer_name }}</td>
                                    <td>{{ \Str::limit($invoice->product_names, 30) }}</td>

                                               <td>{{ \Str::limit($invoice->quantities,10) }} </td>

                                                <td>Ugx {{ number_format($invoice->total_amount, 0) }}</td>

                                                <td>{{ \Carbon\Carbon::parse($invoice->date)->format('d M, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-warning"><b style="color: black">Pending</b></span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('invoice.pending.list') }}" class="btn btn-success sm" title="Approve this invoice">
                                                        <i class="fas fa-check-circle"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    @php
                                    // Fetch products with quantity from 1 to 5 or 0, and have approved purchase status
                                    $products = \App\Models\Product::with(['purchases' => function ($query) {
                                        // Only fetch approved purchases
                                        $query->where('status', 1);
                                    }, 'supplier', 'category'])
                                    ->where(function ($query) {
                                        // Add conditions for stock status: out of stock or low stock (1-5)
                                        $query->where('quantity', 0)
                                            ->orWhereBetween('quantity', [1, 5]);
                                    })
                                    ->limit(6) // Limit the number of products to 10
                                    ->get();
                    @endphp
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"> <b>Stock alerts</b></h4>
                            <ul class="list-group">
                                @foreach($products as $product)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $product->name }}
                                        <span class="badge
                                            @if($product->quantity == 0)
                                                bg-danger
                                            @elseif($product->quantity <= 5)
                                                bg-warning
                                            @else
                                                bg-success
                                            @endif
                                            rounded-pill">
                                            {{ $product->quantity }} remaining
                                        </span>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Add Stock Button with Icon -->
                            <div class="text-center mt-4">
                                <a href="{{ route('purchase.add') }}" class="btn btn-success">
                                    <i class="fa fa-plus-circle"></i> Add Stock
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</div>

<!-- Include Chart.js -->


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


 <script>
    // Initialize Chart.js
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [], // Empty array initially
            datasets: [
                {
                    label: 'Purchases (Ugx)',
                    data: [], // Empty array initially
                    backgroundColor: '#00D4FF',
                    borderColor: '#00D4FF',
                    borderWidth: 1,
                },
                {
                    label: 'Sales (Ugx)',
                    data: [], // Empty array initially
                    backgroundColor: '#FF5C77',
                    borderColor: '#FF5C77',
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                legend: {
                    position: 'top',
                },
            },
        },
    });

    // Trigger the chart to load data for "Last 6 Months" on page load
    $(document).ready(function() {
        // Set default to "Last 6 Months"
        $('#monthSelect').val('Last 6Month');

        // Trigger the change event to load data
        const selectedRange = 'Last 6Month'; // Default filter on page load

        $.ajax({
            url: '/dashboard', // The route for your controller
            data: { filter: selectedRange },
            method: 'GET',
            success: function (data) {
                console.log(data); // Log the returned data for debugging

                // Update chart data
                salesChart.data.labels = data.labels; // Populate the labels
                salesChart.data.datasets[0].data = data.purchases; // Populate purchases data
                salesChart.data.datasets[1].data = data.sales; // Populate sales data
                salesChart.update(); // Redraw the chart with the new data
            },
            error: function (error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    // Update chart when the range changes
    $('#monthSelect').change(function () {
        const selectedRange = this.value;

        // Use AJAX to fetch data from the server based on the selected range
        $.ajax({
            url: '/dashboard',
            data: { filter: selectedRange },
            method: 'GET',
            success: function (data) {
                console.log(data); // Log the returned data for debugging

                // Update chart data
                salesChart.data.labels = data.labels; // Populate the labels
                salesChart.data.datasets[0].data = data.purchases; // Populate purchases data
                salesChart.data.datasets[1].data = data.sales; // Populate sales data
                salesChart.update(); // Redraw the chart with the new data
            },
            error: function (error) {
                console.error('Error fetching data:', error);
            }
        });
    });
</script>



{{-- total sales --}}
<script>
    $(document).ready(function () {
        let previousSales = 0;  // Keep track of previous sales for increase calculation


        // Listen for changes in the date select dropdown
        $('#salesDateSelect').on('change', function () {
            var selectedOption = $(this).val();
            var startDate = null;
            var endDate = null;

            // If 'Custom Date' is selected, show the custom date range fields
            if (selectedOption === 'Custom Date') {
                $('#customDate').removeClass('d-none'); // Show custom date inputs
            } else {
                $('#customDate').addClass('d-none'); // Hide custom date inputs
                // Trigger the route immediately when a predefined option is selected (Today, This Week, etc.)
                updateTotalSales(selectedOption, startDate, endDate);
            }
        });

        // Listen for changes in both start and end date fields for custom date range
        $('#startDate, #endDate').on('change', function () {
            var selectedOption = $('#salesDateSelect').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            // If both start and end dates are selected and 'Custom Date' is the selected option, trigger the route
            if (selectedOption === 'Custom Date' && startDate && endDate) {
                updateTotalSales(selectedOption, startDate, endDate);
            }
        });

        // Function to send AJAX request to the backend and update the total sales
        function updateTotalSales(dateRange, startDate, endDate) {
            // console.log('Updating total sales for:', dateRange, startDate, endDate);

            $.ajax({
                url: "{{ route('get-total-sales') }}",
                method: 'GET',
                data: {
                    date_range: dateRange,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function (response) {

                    // Update the total sales amount on the page
                    $('#totalSalesAmount').text('Ugx' + ' ' + response.totalSales);
                      // Calculate the increase in sales
                        let increase = 0;
                        if (previousSales > 0) {
                            increase = ((totalSales - previousSales) / previousSales) * 100;
                        }

                         // Update the sales increase text dynamically
                    $('#salesIncrease').text(increase.toFixed(2) + '%'); // Shows increase with two decimal points

                        // Update the increase color and icon based on the increase/decrease
                        if (increase >= 0) {
                            $('#salesIncrease').addClass('text-success').removeClass('text-danger');
                            $('#salesIncrease i').removeClass('ri-arrow-down-line').addClass('ri-arrow-up-line');
                        } else {
                            $('#salesIncrease').addClass('text-danger').removeClass('text-success');
                            $('#salesIncrease i').removeClass('ri-arrow-up-line').addClass('ri-arrow-down-line');
                        }

                        // Update previous sales for the next comparison
                        previousSales = totalSales;
                        },
                        error: function (error) {
                        console.log('Error fetching sales data:', error);
                        // Handle error (e.g., display an error message to the user)
                        $('#totalSalesAmount').text('Ugx 0');
                        $('#salesIncrease').text('0%');
                        }
                        });
                        }

                        // Trigger change event on page load to show initial data
                        $('#salesDateSelect').trigger('change');
    });
</script>

{{-- balance --}}
<script>
$(document).ready(function () {
    // Listen for changes in the date select dropdown
    $('#balancesDateSelect').on('change', function () {
        var selectedOption = $(this).val();
        var startDate = null;
        var endDate = null;

        // If 'Custom Date' is selected, show the custom date range fields
        if (selectedOption === 'Custom Date1') {
            $('#customDate1').removeClass('d-none'); // Show custom date inputs
        } else {
            $('#customDate1').addClass('d-none'); // Hide custom date inputs
            // Trigger the route immediately when a predefined option is selected (Today, This Week, etc.)
            updateOutstandingBalance(selectedOption, startDate, endDate);
        }
    });

    // Listen for changes in both start and end date fields for custom date range
    $('#startDate, #endDate').on('change', function () {
        var selectedOption = $('#balancesDateSelect').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();

        // If both start and end dates are selected and 'Custom Date' is the selected option, trigger the route
        if (selectedOption === 'Custom Date1' && startDate && endDate) {
            updateOutstandingBalance(selectedOption, startDate, endDate);
        }
    });

    // Function to send AJAX request to the backend and update the outstanding balance
    function updateOutstandingBalance(dateRange, startDate, endDate) {
        $.ajax({
            url: "{{ route('get-outstanding-balance') }}", // Your route here
            method: 'GET',
            data: {
                date_range: dateRange,
                start_date: startDate,
                end_date: endDate
            },
            success: function (response) {
                // Update the outstanding balance amount on the page
                $('#outstandingBalanceAmount').text('Ugx ' + response.outstandingBalance);
            },
            error: function (error) {
                console.log('Error fetching outstanding balance:', error);
                // Handle error (e.g., display an error message to the user)
                $('#outstandingBalanceAmount').text('Ugx 0');
            }
        });
    }

    // Trigger change event on page load to show initial data
    $('#balancesDateSelect').trigger('change');
});
</script>

@endsection
