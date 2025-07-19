@extends('retailers.layouts.app')

@section('title', 'Product Recipe Details')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Product Recipe Details</h1>
        <p class="page-subtitle">View recipe information</p>
        <div class="page-actions">
            <a href="{{ route('retailer.product_recipes.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Recipes
            </a>
            <a href="{{ route('retailer.product_recipes.edit', $recipe->recipe_id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Recipe
            </a>
        </div>
    </div>

    <div class="recipe-details">
        <!-- Recipe Information Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-list"></i> Recipe Information
                </h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Recipe ID</label>
                        <span class="value badge badge-info">#{{ $recipe->recipe_id }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Product Name</label>
                        <span class="value product-name">{{ $recipe->formatted_product_name }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Recipe Name</label>
                        <span class="value">{{ $recipe->recipe_name }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Coffee Variety</label>
                        <span class="value badge badge-outline">{{ $recipe->formatted_coffee_variety }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Processing Method</label>
                        <span class="value">{{ $recipe->formatted_processing_method }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Required Grade</label>
                        <span class="value badge badge-secondary">{{ $recipe->formatted_required_grade }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Percentage Composition</label>
                        <div class="value">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" style="width: {{ $recipe->percentage_composition }}%">
                                    {{ $recipe->percentage_composition }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <label>Created</label>
                        <span class="value">{{ $recipe->created_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label>Last Updated</label>
                        <span class="value">{{ $recipe->updated_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recipe Statistics Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i> Recipe Statistics
                </h3>
            </div>
            <div class="card-body">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $recipe->percentage_composition }}%</div>
                            <div class="stat-label">Composition</div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-coffee"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $recipe->formatted_coffee_variety }}</div>
                            <div class="stat-label">Coffee Type</div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $recipe->formatted_required_grade }}</div>
                            <div class="stat-label">Quality Grade</div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $recipe->formatted_processing_method }}</div>
                            <div class="stat-label">Processing</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tools"></i> Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="actions-grid">
                    <a href="{{ route('retailer.product_recipes.edit', $recipe->recipe_id) }}" class="action-btn btn-primary">
                        <i class="fas fa-edit"></i>
                        <span>Edit Recipe</span>
                    </a>
                    
                    <form action="{{ route('retailer.product_recipes.destroy', $recipe->recipe_id) }}" 
                          method="POST" style="display: inline;" 
                          onsubmit="return confirm('Are you sure you want to delete this recipe? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-danger">
                            <i class="fas fa-trash"></i>
                            <span>Delete Recipe</span>
                        </button>
                    </form>
                    
                    <a href="{{ route('retailer.product_recipes.index') }}" class="action-btn btn-secondary">
                        <i class="fas fa-list"></i>
                        <span>View All Recipes</span>
                    </a>
                    
                    <a href="{{ route('retailer.product_recipes.create') }}" class="action-btn btn-success">
                        <i class="fas fa-plus"></i>
                        <span>Create New Recipe</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
.recipe-details {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
    background-color: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.card-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-body {
    padding: 1.5rem;
}

.page-actions {
    display: flex;
    gap: 0.5rem;
    margin-left: auto;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item .value {
    font-size: 1rem;
    color: #495057;
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.stat-icon {
    width: 50px;
    height: 50px;
    background-color: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    font-weight: 500;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    text-decoration: none;
}

.action-btn i {
    font-size: 1.5rem;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #545b62;
    color: white;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-success:hover {
    background-color: #1e7e34;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
    color: white;
}

.progress {
    background-color: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    background-color: #007bff;
    color: white;
    text-align: center;
    line-height: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: width 0.6s ease;
}

.badge {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.25rem;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-outline {
    background-color: transparent;
    border: 1px solid #007bff;
    color: #007bff;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .page-actions {
        flex-direction: column;
        margin-left: 0;
        margin-top: 1rem;
    }
}
</style> 