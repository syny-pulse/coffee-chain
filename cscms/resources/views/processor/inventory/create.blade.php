@extends('layouts.processor')

@section('title', 'Add Finished Good')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-plus"></i>
            <div>
                <h1>Add New Finished Good</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Add a new finished good to your inventory
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.inventory.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Inventory
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger fade-in">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger fade-in">
            {{ session('error') }}
        </div>
    @endif

    <!-- Inventory Form -->
    <div class="content-section fade-in">
        <form action="{{ route('processor.inventory.store') }}" method="POST" class="form-container">
            @csrf
            
            <!-- Product & Recipe -->
            <div class="form-group">
                <label for="product_name">Product Type</label>
                <select id="product_name" name="product_name" class="form-control" required>
                    <option value="">Select Product Type</option>
                    <option value="drinking_coffee">Drinking Coffee</option>
                    <option value="roasted_coffee">Roasted Coffee</option>
                    <option value="coffee_scents">Coffee Scents</option>
                    <option value="coffee_soap">Coffee Soap</option>
                </select>
            </div>

            <div class="form-group">
                <label for="recipe_id">Recipe</label>
                <select id="recipe_id" name="recipe_id" class="form-control" required>
                    <option value="">Select Product Type First</option>
                </select>
            </div>

            <div class="form-group">
                <label for="product_variant">Product Variant</label>
                <input type="text" id="product_variant" name="product_variant" class="form-control" required>
            </div>

            <!-- Stock & Pricing -->
            <div class="form-group">
                <label for="current_stock_units">Current Stock (units)</label>
                <input type="number" id="current_stock_units" name="current_stock_units" class="form-control" min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="production_cost_per_unit">Production Cost per Unit (UGX)</label>
                <input type="number" id="production_cost_per_unit" name="production_cost_per_unit" class="form-control" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="selling_price_per_unit">Selling Price per Unit (UGX)</label>
                <input type="number" id="selling_price_per_unit" name="selling_price_per_unit" class="form-control" min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <small class="form-text text-muted">Reserved stock is set to 0 and available stock is set to current stock automatically.</small>
            </div>
            
            <div class="form-actions">
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-undo"></i>
                    Reset Form
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Finished Good
                </button>
            </div>
        </form>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productNameSelect = document.getElementById('product_name');
    const recipeSelect = document.getElementById('recipe_id');
    
    productNameSelect.addEventListener('change', async function() {
        const productType = this.value;
        
        // Reset and disable the recipe select
        recipeSelect.innerHTML = '<option value="">Loading recipes...</option>';
        recipeSelect.disabled = true;
        
        if (!productType) {
            recipeSelect.innerHTML = '<option value="">Select Product Type First</option>';
            return;
        }
        
        try {
            const response = await fetch(`{{ route('processor.inventory.fetchRecipes') }}?product_name=${productType}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Failed to load recipes');
            }

            recipeSelect.innerHTML = '<option value="">Select Recipe</option>';
            
            if (data.recipes && data.recipes.length > 0) {
                data.recipes.forEach(recipe => {
                    const option = new Option(
                        `${recipe.recipe_name} (${recipe.coffee_variety}, ${recipe.processing_method})`,
                        recipe.recipe_id
                    );
                    recipeSelect.add(option);
                });
                recipeSelect.disabled = false;
            } else {
                recipeSelect.innerHTML = '<option value="">No recipes available</option>';
            }
        } catch (error) {
            console.error('Fetch error:', error);
            recipeSelect.innerHTML = '<option value="">Error loading recipes</option>';
            alert('Failed to load recipes. Please check console for details.');
        }
    });

    // Initialize if there's old input
    @if(old('product_name'))
        productNameSelect.value = "{{ old('product_name') }}";
        productNameSelect.dispatchEvent(new Event('change'));
    @endif
});
</script>

<style>
    .form-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .form-section h3 {
        color: var(--coffee-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-group {
        flex: 1;
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--coffee-dark);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid rgba(111, 78, 55, 0.2);
        border-radius: 8px;
        font-size: 0.9rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--coffee-medium);
        box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #f5c6cb;
    }

    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>