<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop Dashboard - Order Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* All CSS styles from the previous dashboard */
        :root {
            --primary: #2D3748;
            --primary-light: #4A5568;
            --accent: #8B7355;
            --accent-light: #A68B5B;
            --success: #48BB78;
            --warning: #ED8936;
            --danger: #F56565;
            --info: #4299E1;
            
            --bg-primary: #FFFFFF;
            --bg-secondary: #F7FAFC;
            --bg-tertiary: #EDF2F7;
            
            --text-primary: #2D3748;
            --text-secondary: #4A5568;
            --text-muted: #718096;
            
            --border: #E2E8F0;
            --border-light: #F1F5F9;
            
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            
            --radius-sm: 6px;
            --radius: 8px;
            --radius-lg: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: var(--bg-secondary);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-primary);
            border-right: 1px solid var(--border);
            padding: 2rem 0;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 0 2rem;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--accent);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .nav-menu {
            flex: 1;
            padding: 0 1rem;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .nav-link:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .nav-link.active {
            background: var(--accent);
            color: white;
        }

        .nav-link .icon {
            margin-right: 0.75rem;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .user-section {
            padding: 1rem;
            border-top: 1px solid var(--border);
            margin-top: auto;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--bg-secondary);
            border-radius: var(--radius);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            width: 100%;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg-primary);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .table th, .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .table th {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: var(--bg-secondary);
        }

        /* Alert Styles */
        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.1);
            border: 1px solid var(--success);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(245, 101, 101, 0.1);
            border: 1px solid var(--danger);
            color: var(--danger);
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: var(--accent);
            color: white;
            border-radius: var(--radius);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn:hover {
            background: var(--accent-light);
            box-shadow: var(--shadow);
        }

        .btn-primary {
            background: var(--accent);
        }

        .btn-primary:hover {
            background: var(--accent-light);
        }

        .btn-secondary {
            background: var(--primary-light);
        }

        .btn-secondary:hover {
            background: var(--primary);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(139, 115, 85, 0.1);
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            display: none;
        }

        .modal-content {
            background: var(--bg-primary);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 600px;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .close {
            color: var(--text-muted);
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .close:hover {
            color: var(--text-primary);
        }

        /* Prediction Section */
        .prediction-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--bg-secondary);
            border-radius: var(--radius);
        }

        .prediction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .prediction-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .prediction-results {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .prediction-card {
            flex: 1;
            min-width: 200px;
            padding: 1rem;
            background: var(--bg-primary);
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        .prediction-product {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .prediction-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent);
        }

        .prediction-chart {
            height: 300px;
            margin-top: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1rem 0;
                order: 2;
            }

            .main-content {
                order: 1;
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .prediction-results {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-mug-hot"></i>
            </div>
            <div class="logo-text">Coffee Shop</div>
        </div>
        
        <nav class="nav-menu">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <div class="nav-item">
                    <a href="{{ route('retailer.dashboard') }}" class="nav-link">
                        <span class="icon"><i class="fas fa-grid-2"></i></span>
                        Dashboard
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Shop Management</div>
                <div class="nav-item">
                    <a href="{{ route('retailer.sales.index') }}" class="nav-link">
                        <span class="icon"><i class="fas fa-chart-bar"></i></span>
                        Sales Data
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('retailer.product_recipes.index') }}" class="nav-link">
                        <span class="icon"><i class="fas fa-utensils"></i></span>
                        Product Recipes
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('retailer.inventory.index') }}" class="nav-link">
                        <span class="icon"><i class="fas fa-warehouse"></i></span>
                        Inventory
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('retailer.orders.index') }}" class="nav-link  active">
                        <span class="icon"><i class="fas fa-shopping-bag"></i></span>
                        Orders
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Business</div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-dollar-sign"></i></span>
                        Financials
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-chart-line"></i></span>
                        Analytics
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Communication</div>
                <div class="nav-item">
                    <a href="#" class="nav-link">
                        <span class="icon"><i class="fas fa-message"></i></span>
                        Messages
                    </a>
                </div>
            </div>
        </nav>

        <div class="user-section">
            <div class="user-profile">
                <div class="user-avatar">JD</div>
                <div class="user-info">
                    <div class="user-name">John Doe</div>
                    <div class="user-role">Shop Owner</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Order Requests</h1>
                <p class="page-subtitle">Manage your order requests and track their status</p>
            </div>
            <button id="createOrderBtn" class="btn">Create New Order</button>
        </div>

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
                        <small class="text-muted">Predicted quantity: <span id="predictedQuantity">0 kg</span></small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="cancelCreate">Cancel</button>
                        <button type="submit" class="btn">Create Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                    
                    editForm.action = `/orders/${orderId}`;
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
                    // In a real app, this would fetch prediction from the server
                    // For demo, we'll simulate with random values
                    const predictions = {
                        'Espresso': 1240,
                        'Latte': 980,
                        'Iced_Latte': 1850,
                        'Black_Coffee': 2150
                    };
                    
                    // Adjust based on month (dry vs wet seasons)
                    let prediction = predictions[product];
                    
                    // Dry season (Jan-Feb, Jun-Aug) - higher sales for iced drinks
                    const dryMonths = [1, 2, 6, 7, 8];
                    if (dryMonths.includes(parseInt(month))) {
                        if (product === 'Iced_Latte') prediction *= 1.2;
                        if (product === 'Black_Coffee') prediction *= 0.9;
                    }
                    // Wet season (Mar-May, Sep-Nov) - higher sales for hot drinks
                    else {
                        if (product === 'Espresso') prediction *= 1.15;
                        if (product === 'Latte') prediction *= 1.1;
                    }
                    
                    // Convert units to kg (assuming 10g per cup)
                    const kg = (prediction * 10) / 1000;
                    
                    // Set the predicted quantity
                    predictedQuantitySpan.textContent = `${kg.toFixed(1)} kg`;
                    
                    // Auto-fill the quantity field
                    quantityInput.value = kg.toFixed(1);
                    
                    // Auto-set coffee breed and roast grade based on product
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
                } else {
                    predictedQuantitySpan.textContent = '0 kg';
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

            closeModal.addEventListener('click', closeAllModals);
            closeCreateModal.addEventListener('click', closeAllModals);
            cancelEdit.addEventListener('click', closeAllModals);
            cancelCreate.addEventListener('click', closeAllModals);

            // Close modals when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === editModal || event.target === createModal) {
                    closeAllModals();
                }
            });
        });
    </script>
</body>
</html>