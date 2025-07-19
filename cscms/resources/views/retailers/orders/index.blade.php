@extends('retailers.layouts.app')

@section('title', 'Orders')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Orders</h1>
        <p class="page-subtitle">Manage your orders efficiently</p>
        <button id="createOrderBtn" class="btn btn-primary" style="margin-left:auto;">Create New Order</button>
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

    <table class="table">
        <thead>
            <tr>
                <th>Date Placed</th>
                <th>Product</th>
                <th>Coffee Breed</th>
                <th>Roast Grade</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2023-10-15</td>
                <td>Espresso</td>
                <td>Arabica</td>
                <td>Grade 4</td>
                <td>25 kg</td>
                <td><span class="status-badge status-pending">Pending</span></td>
                <td>
                    <button class="btn btn-secondary editBtn" data-id="1" data-status="pending">Edit</button>
                </td>
            </tr>
            <tr>
                <td>2023-10-12</td>
                <td>Iced Latte</td>
                <td>Arabica</td>
                <td>Grade 2</td>
                <td>15 kg</td>
                <td><span class="status-badge status-delivered">Delivered</span></td>
                <td>
                    <button class="btn btn-secondary editBtn" data-id="2" data-status="delivered">Edit</button>
                </td>
            </tr>
            <tr>
                <td>2023-10-08</td>
                <td>Black Coffee</td>
                <td>Robusta</td>
                <td>Grade 5</td>
                <td>30 kg</td>
                <td><span class="status-badge status-cancelled">Cancelled</span></td>
                <td>
                    <button class="btn btn-secondary editBtn" data-id="3" data-status="cancelled">Edit</button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Edit Order Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Order Status</h2>
                <span id="closeModal" class="close">&times;</span>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
                    <button type="submit" class="btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Order Modal -->
    <div id="createModal" class="modal">
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
            const editModal = document.getElementById('editModal');
            const createModal = document.getElementById('createModal');
            const closeModal = document.getElementById('closeModal');
            const closeCreateModal = document.getElementById('closeCreateModal');
            const cancelEdit = document.getElementById('cancelEdit');
            const cancelCreate = document.getElementById('cancelCreate');
            const editForm = document.getElementById('editForm');
            const createForm = document.getElementById('createForm');
            const createOrderBtn = document.getElementById('createOrderBtn');
            const refreshPredictionsBtn = document.getElementById('refreshPredictions');
            const productSelect = document.getElementById('product');
            const monthSelect = document.getElementById('month');
            const yearSelect = document.getElementById('year');
            const quantityInput = document.getElementById('quantity');
            const predictedQuantitySpan = document.getElementById('predictedQuantity');
            
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

            // Edit order functionality
            document.querySelectorAll('.editBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-id');
                    const status = this.getAttribute('data-status');
                    // Use the correct retailer prefix for the update route
                    editForm.action = `/retailer/orders/${orderId}`;
                    document.getElementById('status').value = status;
                    editModal.style.display = 'flex';
                });
            });

            // Create order functionality
            createOrderBtn.addEventListener('click', function() {
                createModal.style.display = 'flex';
            });

            // Refresh predictions
            refreshPredictionsBtn.addEventListener('click', function() {
                // Simulate loading
                refreshPredictionsBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
                
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
                    
                    refreshPredictionsBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
                }, 1500);
            });

            // Update predicted quantity when product, month or year changes
            function updatePredictedQuantity() {
                const product = productSelect.value;
                const month = monthSelect.value;
                const year = yearSelect.value;
                
                if (product && month && year) {
                    // Show loading
                    document.getElementById('mlPredictedQuantity').textContent = '...';
                    fetch(`/retailer/orders/predict?product=${encodeURIComponent(product)}&month=${encodeURIComponent(month)}&year=${encodeURIComponent(year)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.prediction) {
                                document.getElementById('mlPredictedQuantity').textContent = data.prediction + ' kg';
                                quantityInput.value = data.prediction;
                            } else {
                                document.getElementById('mlPredictedQuantity').textContent = 'N/A';
                            }
                        })
                        .catch(() => {
                            document.getElementById('mlPredictedQuantity').textContent = 'Error';
                        });
                } else {
                    document.getElementById('mlPredictedQuantity').textContent = '-';
                }
            }
            
            // Set up event listeners
            productSelect.addEventListener('change', updatePredictedQuantity);
            monthSelect.addEventListener('change', updatePredictedQuantity);
            yearSelect.addEventListener('change', updatePredictedQuantity);

            // Close modals
            function closeAllModals() {
                editModal.style.display = 'none';
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

            // Close modals when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === editModal || event.target === createModal) {
                    closeAllModals();
                }
            });
        });
    </script>
@endsection