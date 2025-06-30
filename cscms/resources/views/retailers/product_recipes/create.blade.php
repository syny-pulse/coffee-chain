<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
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

        .alert ul {
            padding-left: 1.5rem;
            margin-top: 0.5rem;
        }

        .alert li {
            margin-bottom: 0.25rem;
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

        .btn-secondary {
            background: var(--primary-light);
        }

        .btn-secondary:hover {
            background: var(--primary);
        }

        .btn-info {
            background: var(--info);
        }

        .btn-info:hover {
            background: #3182ce;
        }

        .btn-danger {
            background: var(--danger);
        }

        .btn-danger:hover {
            background: #e53e3e;
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

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -0.5rem;
            margin-left: -0.5rem;
        }

        .form-group.col-md-3 {
            padding: 0 0.5rem;
            flex: 0 0 25%;
            max-width: 25%;
        }

        .d-flex {
            display: flex;
        }

        .align-items-end {
            align-items: flex-end;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        hr {
            border: 0;
            border-top: 1px solid var(--border);
            margin: 1.5rem 0;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .form-group.col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

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

            .form-group.col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
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
                    <a href="{{ route('retailer.product_recipes.index') }}" class="nav-link active">
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
                    <a href="{{ route('retailer.orders.index') }}" class="nav-link">
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
                <h1 class="page-title">Create New Product Recipe</h1>
                <p class="page-subtitle">Define a new product recipe with its components</p>
            </div>
            <a href="{{ route('retailer.product_recipes.index') }}" class="btn btn-secondary">Back to Product Recipes</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('retailer.product_recipes.store') }}" method="POST" id="productRecipeForm">
            @csrf
            <div class="form-group">
                <label for="product_name">Product Name<span style="color:red">*</span></label>
                <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name') }}" required>
            </div>

            <div class="form-group">
                <label for="price">Price<span style="color:red">*</span></label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <hr>

            <h3>Components</h3>
            <div id="componentsContainer">
                <div class="component-item">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Coffee Breed<span style="color:red">*</span></label>
                            <select name="components[0][coffee_breed]" class="form-control" required>
                                <option value="">Select Breed</option>
                                <option value="arabica">Arabica</option>
                                <option value="robusta">Robusta</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Roast Grade<span style="color:red">*</span></label>
                            <select name="components[0][roast_grade]" class="form-control" required>
                                <option value="">Select Grade</option>
                                <option value="Grade 1">Grade 1</option>
                                <option value="Grade 2">Grade 2</option>
                                <option value="Grade 3">Grade 3</option>
                                <option value="Grade 4">Grade 4</option>
                                <option value="Grade 5">Grade 5</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Percentage<span style="color:red">*</span></label>
                            <input type="number" name="components[0][percentage]" class="form-control component-percentage" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="form-group col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-component-btn">Remove</button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="addComponentBtn" class="btn btn-info mt-2">Add Component</button>

            <div class="form-group mt-3">
                <button type="submit" class="btn">Save Product Recipe</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let componentIndex = 1;

            document.getElementById('addComponentBtn').addEventListener('click', function () {
                const container = document.getElementById('componentsContainer');
                const newComponent = document.querySelector('.component-item').cloneNode(true);

                // Reset values
                newComponent.querySelectorAll('select, input').forEach(input => {
                    input.value = '';
                    // Update name attributes with new index
                    if (input.name) {
                        input.name = input.name.replace(/components\[\d+\]/, 'components[' + componentIndex + ']');
                    }
                });

                container.appendChild(newComponent);
                componentIndex++;

                attachRemoveListeners();
            });

            function attachRemoveListeners() {
                document.querySelectorAll('.remove-component-btn').forEach(button => {
                    button.removeEventListener('click', removeComponent);
                    button.addEventListener('click', removeComponent);
                });
            }

            function removeComponent(event) {
                const componentItems = document.querySelectorAll('.component-item');
                if (componentItems.length > 1) {
                    event.target.closest('.component-item').remove();
                } else {
                    alert('At least one component is required.');
                }
            }

            attachRemoveListeners();

            document.getElementById('productRecipeForm').addEventListener('submit', function (e) {
                const percentages = Array.from(document.querySelectorAll('.component-percentage')).map(input => parseFloat(input.value) || 0);
                const total = percentages.reduce((a, b) => a + b, 0);

                if (total !== 100) {
                    e.preventDefault();
                    alert('Total percentage of components must be exactly 100%. Current total: ' + total + '%.');
                }
            });
        });
    </script>
</body>
</html>