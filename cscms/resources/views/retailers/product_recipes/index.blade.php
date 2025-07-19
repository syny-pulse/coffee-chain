@extends('retailers.layouts.app')

@section('title', 'Product Recipes')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Product Recipes</h1>
        <p class="page-subtitle">Manage your product recipes and compositions</p>
        <div class="page-actions">
            <a href="{{ route('retailer.product_recipes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Recipe
            </a>
            <button class="btn btn-secondary" onclick="showCreateProductModal()">
                <i class="fas fa-coffee"></i> Create Product
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Product Recipes Section -->
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-clipboard-list"></i> Product Recipes
            <span class="badge badge-primary">{{ $recipes->count() }}</span>
        </h2>
        
        @if($recipes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Recipe ID</th>
                            <th>Product Name</th>
                            <th>Recipe Name</th>
                            <th>Coffee Variety</th>
                            <th>Processing Method</th>
                            <th>Required Grade</th>
                            <th>Composition %</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recipes as $recipe)
                        <tr>
                            <td><span class="badge badge-info">#{{ $recipe->recipe_id }}</span></td>
                            <td>
                                <span class="product-name">{{ $recipe->formatted_product_name }}</span>
                            </td>
                            <td>{{ $recipe->recipe_name }}</td>
                            <td>
                                <span class="badge badge-outline">{{ $recipe->formatted_coffee_variety }}</span>
                            </td>
                            <td>{{ $recipe->formatted_processing_method }}</td>
                            <td>
                                <span class="badge badge-secondary">{{ $recipe->formatted_required_grade }}</span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" style="width: {{ $recipe->percentage_composition }}%">
                                        {{ $recipe->percentage_composition }}%
                                    </div>
                                </div>
                            </td>
                            <td>{{ $recipe->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('retailer.product_recipes.show', $recipe->recipe_id) }}" 
                                       class="btn btn-sm btn-outline" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('retailer.product_recipes.edit', $recipe->recipe_id) }}" 
                                       class="btn btn-sm btn-outline" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('retailer.product_recipes.destroy', $recipe->recipe_id) }}" 
                                          method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this recipe?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-clipboard-list fa-3x text-muted"></i>
                <h3>No Product Recipes</h3>
                <p>Create your first product recipe to get started.</p>
                <a href="{{ route('retailer.product_recipes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Recipe
                </a>
            </div>
        @endif
    </div>

    <!-- Retailer Products Section -->
    <div class="section">
        <h2 class="section-title">
            <i class="fas fa-coffee"></i> Retailer Products
            <span class="badge badge-success">{{ $products->count() }}</span>
        </h2>
        
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Price per KG</th>
                            <th>Description</th>
                            <th>Components</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><span class="badge badge-success">#{{ $product->product_id }}</span></td>
                            <td>
                                <span class="product-name">{{ $product->name }}</span>
                            </td>
                            <td>
                                <span class="price">UGX {{ number_format($product->price_per_kg, 2) }}</span>
                            </td>
                            <td>{{ Str::limit($product->description, 50) }}</td>
                            <td>
                                @php
                                    $components = DB::table('product_composition')->where('product_id', $product->product_id)->get();
                                @endphp
                                @if($components->count() > 0)
                                    <div class="components-list">
                                        @foreach($components as $component)
                                            <span class="badge badge-light">
                                                {{ ucfirst($component->coffee_breed) }} - {{ $component->roast_grade }}: {{ $component->percentage }}%
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No components</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($product->created_at)->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-coffee fa-3x text-muted"></i>
                <h3>No Retailer Products</h3>
                <p>Create your first retailer product with composition.</p>
                <button class="btn btn-primary" onclick="showCreateProductModal()">
                    <i class="fas fa-plus"></i> Create Product
                </button>
            </div>
        @endif
    </div>

    <!-- Create Product Modal -->
    <div id="createProductModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Create New Product</h2>
                <span class="close" onclick="closeCreateProductModal()">&times;</span>
            </div>
            <form action="{{ route('retailer.product_recipes.createRetailerProduct') }}" method="POST" id="createProductForm">
                @csrf
                <div class="form-group">
                    <label for="product_name">Product Name<span class="text-danger">*</span></label>
                    <input type="text" name="product_name" id="product_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="price_per_kg">Price per KG (UGX)<span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price_per_kg" id="price_per_kg" class="form-control" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>
                
                <hr>
                <h3>Components</h3>
                <div id="componentsContainer">
                    <div class="component-item">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Coffee Breed<span class="text-danger">*</span></label>
                                <select name="components[0][coffee_breed]" class="form-control" required>
                                    <option value="">Select Breed</option>
                                    <option value="arabica">Arabica</option>
                                    <option value="robusta">Robusta</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Roast Grade<span class="text-danger">*</span></label>
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
                                <label>Percentage<span class="text-danger">*</span></label>
                                <input type="number" name="components[0][percentage]" class="form-control component-percentage" min="0" max="100" step="0.01" required>
                            </div>
                            <div class="form-group col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-component-btn">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="addComponentBtn" class="btn btn-info mt-2">
                    <i class="fas fa-plus"></i> Add Component
                </button>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeCreateProductModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@parent
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let componentIndex = 1;
            
            // Add component functionality
            document.getElementById('addComponentBtn').addEventListener('click', function () {
                const container = document.getElementById('componentsContainer');
                const newComponent = document.querySelector('.component-item').cloneNode(true);
                
                newComponent.querySelectorAll('select, input').forEach(input => {
                    input.value = '';
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
            
            // Form validation
            document.getElementById('createProductForm').addEventListener('submit', function (e) {
                const percentages = Array.from(document.querySelectorAll('.component-percentage'))
                    .map(input => parseFloat(input.value) || 0);
                const total = percentages.reduce((a, b) => a + b, 0);
                
                if (total !== 100) {
                    e.preventDefault();
                    alert('Total percentage of components must be exactly 100%. Current total: ' + total + '%.');
                }
            });
        });
        
        function showCreateProductModal() {
            document.getElementById('createProductModal').style.display = 'flex';
        }
        
        function closeCreateProductModal() {
            document.getElementById('createProductModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('createProductModal');
            if (event.target === modal) {
                closeCreateProductModal();
            }
        });
    </script>
@endsection

<style>
.section {
    margin-bottom: 2rem;
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-actions {
    display: flex;
    gap: 0.5rem;
    margin-left: auto;
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
}

.price {
    font-weight: 600;
    color: #27ae60;
}

.components-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-state i {
    margin-bottom: 1rem;
}

.empty-state h3 {
    margin-bottom: 0.5rem;
    color: #495057;
}

.progress {
    background-color: #e9ecef;
    border-radius: 4px;
}

.progress-bar {
    background-color: #007bff;
    color: white;
    text-align: center;
    line-height: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-outline {
    background-color: transparent;
    border: 1px solid #007bff;
    color: #007bff;
}

.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: white;
    margin: auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.close {
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    color: #aaa;
}

.close:hover {
    color: #000;
}

.form-actions {
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.component-item {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.form-row {
    display: flex;
    gap: 1rem;
    align-items: end;
}

.form-group {
    flex: 1;
}

.col-md-3 {
    flex: 0 0 25%;
}
</style>