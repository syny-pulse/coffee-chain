@extends('retailers.layouts.app')

@section('title', 'Orders')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Orders</h1>
        <p class="page-subtitle">Manage your orders efficiently</p>
        <a href="{{ route('retailer.orders.create') }}" class="btn btn-primary" style="margin-left:auto;">Create New Order</a>
    </div>

    <!-- Main orders content goes here -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="prediction-section">
        <div class="prediction-header">
            <h2 class="prediction-title">Sales Prediction for Next Month</h2>
            <button id="refreshPredictions" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        
        <div class="prediction-results">
            <div class="prediction-card">
                <div class="prediction-product">Espresso</div>
                <div class="prediction-value">1,240 units</div>
                <div class="prediction-trend positive">+12% vs last month</div>
            </div>
            <div class="prediction-card">
                <div class="prediction-product">Latte</div>
                <div class="prediction-value">980 units</div>
                <div class="prediction-trend positive">+8% vs last month</div>
            </div>
            <div class="prediction-card">
                <div class="prediction-product">Iced Latte</div>
                <div class="prediction-value">1,850 units</div>
                <div class="prediction-trend positive">+18% vs last month</div>
            </div>
            <div class="prediction-card">
                <div class="prediction-product">Black Coffee</div>
                <div class="prediction-value">2,150 units</div>
                <div class="prediction-trend positive">+5% vs last month</div>
            </div>
        </div>
        
        <div class="prediction-chart">
            <canvas id="predictionChart"></canvas>
        </div>
    </div>

    <form method="GET" action="{{ route('retailer.orders.index') }}" class="filter-form" style="display:flex; gap:1rem; align-items:center; margin-bottom:1rem;">
        <div>
            <label for="processor_company_id">Processor:</label>
            <select name="processor_company_id" id="processor_company_id" class="form-control">
                <option value="">All</option>
                @foreach($processors as $processor)
                    <option value="{{ $processor->company_id }}" {{ (isset($filters['processor_company_id']) && $filters['processor_company_id'] == $processor->company_id) ? 'selected' : '' }}>{{ $processor->company_name }}</option>
                @endforeach
            </select>
        </div>
        <!-- Removed product filter -->
        <div>
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="">All</option>
                <option value="pending" @if(($filters['status'] ?? '')=='pending') selected @endif>Pending</option>
                <option value="processing" @if(($filters['status'] ?? '')=='processing') selected @endif>Processing</option>
                <option value="delivered" @if(($filters['status'] ?? '')=='delivered') selected @endif>Delivered</option>
                <option value="cancelled" @if(($filters['status'] ?? '')=='cancelled') selected @endif>Cancelled</option>
            </select>
        </div>
        <div>
            <label for="date_from">From</label>
            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
        </div>
        <div>
            <label for="date_to">To</label>
            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
        </div>
        <button type="submit" class="btn btn-outline">Filter</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Processor</th>
                <th>Coffee Breed</th>
                <th>Roast Grade</th>
                <th>Quantity (kg)</th>
                <th>Expected Delivery</th>
                <th>Notes</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id ?? $order->order_id }}</td>
                    <td>
                        @php
                            $proc = $processors->firstWhere('company_id', $order->processor_company_id);
                        @endphp
                        {{ $proc ? $proc->company_name : 'N/A' }}
                    </td>
                    <td>{{ ucfirst($order->coffee_breed) }}</td>
                    <td>Grade {{ $order->roast_grade }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('Y-m-d') : '-' }}</td>
                    <td>{{ $order->notes ?? '-' }}</td>
                    <td><span class="status-badge status-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span></td>
                    <td>{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') : '-' }}</td>
                    <td>
                        <a href="{{ route('retailer.orders.show', $order->id ?? $order->order_id) }}" class="btn btn-sm btn-outline">View</a>
                        <form action="{{ route('retailer.orders.destroy', $order->order_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>

    <!-- Create Order Modal -->
    <div id="createModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Create New Order</h2>
                <span id="closeCreateModal" class="close">&times;</span>
            </div>
            <form id="createForm" method="POST" action="{{ route('retailer.orders.store') }}">
                @csrf
                <div class="form-group">
                    <label for="product">Product</label>
                    <select name="product" id="product" class="form-control" required>
                        <option value="">Select Product</option>
                        <option value="Espresso">Espresso</option>
                        <option value="Latte">Latte</option>
                        <option value="Iced_Latte">Iced Latte</option>
                        <option value="Black_Coffee">Black Coffee</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="month">Target Month</label>
                    <select name="month" id="month" class="form-control" required>
                        <option value="">Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="year">Target Year</label>
                    <select name="year" id="year" class="form-control" required>
                        <option value="">Select Year</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="coffee_breed">Coffee Breed</label>
                    <select name="coffee_breed" id="coffee_breed" class="form-control" required>
                        <option value="arabica">Arabica</option>
                        <option value="robusta">Robusta</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="roast_grade">Roast Grade</label>
                    <select name="roast_grade" id="roast_grade" class="form-control" required>
                        <option value="1">Grade 1</option>
                        <option value="2">Grade 2</option>
                        <option value="3">Grade 3</option>
                        <option value="4">Grade 4</option>
                        <option value="5">Grade 5</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="quantity">Quantity (kg)</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                    <small class="text-muted">Predicted demand: <span id="mlPredictedQuantity">-</span> kg</small>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelCreate">Cancel</button>
                    <button type="submit" class="btn">Create Order</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createModal = document.getElementById('createModal');
            const closeCreateModal = document.getElementById('closeCreateModal');
            const cancelCreate = document.getElementById('cancelCreate');
            const createForm = document.getElementById('createForm');
            const productSelect = document.getElementById('product');
            const monthSelect = document.getElementById('month');
            const yearSelect = document.getElementById('year');
            const quantityInput = document.getElementById('quantity');
            const predictedQuantitySpan = document.getElementById('predictedQuantity');
            const createOrderSubmitBtn = createForm.querySelector('button[type="submit"]');
            
            // Initialize prediction chart
            const ctx = document.getElementById('predictionChart').getContext('2d');
            const predictionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Espresso', 'Latte', 'Iced Latte', 'Black Coffee'],
                    datasets: [{
                        label: 'Predicted Sales (units)',
                        data: [1240, 980, 1850, 2150],
                        backgroundColor: [
                            'rgba(139, 115, 85, 0.7)',
                            'rgba(72, 187, 120, 0.7)',
                            'rgba(66, 153, 225, 0.7)',
                            'rgba(237, 137, 54, 0.7)'
                        ],
                        borderColor: [
                            'rgb(139, 115, 85)',
                            'rgb(72, 187, 120)',
                            'rgb(66, 153, 225)',
                            'rgb(237, 137, 54)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Units'
                            }
                        }
                    }
                }
            });

            // Refresh predictions
            document.getElementById('refreshPredictions').addEventListener('click', function() {
                // Simulate loading
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
                
                // In a real app, this would fetch new predictions from the server
                setTimeout(() => {
                    // Update chart with new data
                    predictionChart.data.datasets[0].data = [
                        1240 + Math.floor(Math.random() * 100),
                        980 + Math.floor(Math.random() * 100),
                        1850 + Math.floor(Math.random() * 100),
                        2150 + Math.floor(Math.random() * 100)
                    ];
                    predictionChart.update();
                    
                    this.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
                }, 1500);
            });

            // Update predicted quantity when product, month or year changes
            function updatePredictedQuantity() {
                const product = productSelect.value;
                const month = monthSelect.value;
                const year = yearSelect.value;
                
                if (product && month && year) {
                    document.getElementById('mlPredictedQuantity').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    if (createOrderSubmitBtn) createOrderSubmitBtn.disabled = true;
                    fetch(`/retailer/orders/predict?product=${encodeURIComponent(product)}&month=${encodeURIComponent(month)}&year=${encodeURIComponent(year)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.prediction) {
                                document.getElementById('mlPredictedQuantity').textContent = data.prediction + ' kg';
                                quantityInput.value = data.prediction;
                            } else {
                                document.getElementById('mlPredictedQuantity').textContent = 'N/A';
                            }
                            if (createOrderSubmitBtn) createOrderSubmitBtn.disabled = false;
                        })
                        .catch(() => {
                            document.getElementById('mlPredictedQuantity').textContent = 'Error';
                            if (createOrderSubmitBtn) createOrderSubmitBtn.disabled = false;
                        });
                } else {
                    document.getElementById('mlPredictedQuantity').textContent = '-';
                    if (createOrderSubmitBtn) createOrderSubmitBtn.disabled = false;
                }
            }
            
            // Set up event listeners
            productSelect.addEventListener('change', updatePredictedQuantity);
            monthSelect.addEventListener('change', updatePredictedQuantity);
            yearSelect.addEventListener('change', updatePredictedQuantity);

            // Close modals
            function closeAllModals() {
                createModal.style.display = 'none';
            }

            // Fix: robust close for create order modal
            if (closeCreateModal) {
                closeCreateModal.addEventListener('click', function() {
                    createModal.style.display = 'none';
                });
            }
            if (cancelCreate) {
                cancelCreate.addEventListener('click', function() {
                    createModal.style.display = 'none';
                });
            }

            // Add event delegation for demand planning create order button
            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.createOrderFromPlanBtn');
                if (btn) {
                    createModal.style.display = 'flex';
                    // Set product and quantity
                    const product = btn.getAttribute('data-product');
                    const quantity = btn.getAttribute('data-quantity');
                    const month = btn.getAttribute('data-month');
                    const year = btn.getAttribute('data-year');
                    if (productSelect) {
                        productSelect.value = product;
                        productSelect.dispatchEvent(new Event('change'));
                    }
                    if (quantityInput) {
                        quantityInput.value = quantity;
                    }
                    if (monthSelect && month) {
                        monthSelect.value = month;
                    }
                    if (yearSelect && year) {
                        yearSelect.value = year;
                    }
                    // Auto-select breed/grade
                    const productMap = {
                        'Espresso': { breed: 'arabica', grade: '4' },
                        'Latte': { breed: 'arabica', grade: '3' },
                        'Iced_Latte': { breed: 'arabica', grade: '2' },
                        'Black_Coffee': { breed: 'robusta', grade: '5' }
                    };
                    if (productMap[product]) {
                        document.getElementById('coffee_breed').value = productMap[product].breed;
                        document.getElementById('roast_grade').value = productMap[product].grade;
                    }
                    createModal.scrollIntoView({behavior: 'smooth', block: 'center'});
                }
            });

            // Close modals when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === createModal) {
                    closeAllModals();
                }
            });
        });
    </script>
@endsection