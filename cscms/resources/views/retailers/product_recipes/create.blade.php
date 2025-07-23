@extends('retailers.layouts.app')

@section('title', 'Create Product Recipe')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Create Product Recipe</h1>
        <p class="page-subtitle">Add a new product recipe</p>
        <a href="{{ route('retailer.product_recipes.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Recipes
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recipe Information</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('retailer.product_recipes.store') }}" method="POST" id="productRecipeForm">
                @csrf
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="product_name">Product Name<span class="text-danger">*</span></label>
                        <select name="product_name" id="product_name" class="form-control" required>
                            <option value="">Select Product</option>
                            @foreach(App\Models\ProductRecipe::PRODUCT_NAMES as $key => $value)
                                <option value="{{ $key }}" {{ old('product_name') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Choose the type of product this recipe is for</small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="recipe_name">Recipe Name<span class="text-danger">*</span></label>
                        <input type="text" name="recipe_name" id="recipe_name" class="form-control" 
                               value="{{ old('recipe_name') }}" required maxlength="100">
                        <small class="form-text text-muted">Give your recipe a unique name</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="coffee_variety">Coffee Variety<span class="text-danger">*</span></label>
                        <select name="coffee_variety" id="coffee_variety" class="form-control" required>
                            <option value="">Select Variety</option>
                            @foreach(App\Models\ProductRecipe::COFFEE_VARIETIES as $key => $value)
                                <option value="{{ $key }}" {{ old('coffee_variety') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="processing_method">Processing Method<span class="text-danger">*</span></label>
                        <select name="processing_method" id="processing_method" class="form-control" required>
                            <option value="">Select Method</option>
                            @foreach(App\Models\ProductRecipe::PROCESSING_METHODS as $key => $value)
                                <option value="{{ $key }}" {{ old('processing_method') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label for="required_grade">Required Grade<span class="text-danger">*</span></label>
                        <select name="required_grade" id="required_grade" class="form-control" required>
                            <option value="">Select Grade</option>
                            @foreach(App\Models\ProductRecipe::REQUIRED_GRADES as $key => $value)
                                <option value="{{ $key }}" {{ old('required_grade') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="percentage_composition">Percentage Composition<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="percentage_composition" id="percentage_composition" 
                               class="form-control" value="{{ old('percentage_composition') }}" 
                               min="0" max="100" step="0.01" required>
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">Percentage of this component in the final product (0-100%)</small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('retailer.product_recipes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Recipe
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@parent
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('productRecipeForm');
            const percentageInput = document.getElementById('percentage_composition');
            
            // Validate percentage on input
            percentageInput.addEventListener('input', function() {
                const value = parseFloat(this.value);
                if (value < 0) {
                    this.value = 0;
                } else if (value > 100) {
                    this.value = 100;
                }
            });
            
            // Form validation
            form.addEventListener('submit', function(e) {
                const percentage = parseFloat(percentageInput.value);
                
                if (percentage < 0 || percentage > 100) {
                    e.preventDefault();
                    alert('Percentage composition must be between 0 and 100.');
                    return false;
                }
                
                if (isNaN(percentage)) {
                    e.preventDefault();
                    alert('Please enter a valid percentage.');
                    return false;
                }
            });
        });
    </script>
@endsection

<style>
.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
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
}

.card-body {
    padding: 1.5rem;
}

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    flex: 1;
}

.col-md-4 {
    flex: 0 0 33.333333%;
}

.col-md-6 {
    flex: 0 0 50%;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #dee2e6;
}

.input-group {
    display: flex;
}

.input-group-append {
    display: flex;
}

.input-group-text {
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-left: none;
    padding: 0.375rem 0.75rem;
    border-radius: 0 0.25rem 0.25rem 0;
}

.form-control {
    border-radius: 0.25rem;
    border: 1px solid #ced4da;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.text-danger {
    color: #dc3545;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}
</style>