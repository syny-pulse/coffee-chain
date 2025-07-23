@extends('layouts.processor')

@section('title', 'Edit Finished Good')

@section('content')
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-edit"></i>
            <div>
                <h1>Edit Finished Good</h1>
                <p>Update the details for this inventory item.</p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.inventory.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Inventory
            </a>
        </div>
    </div>

    <div class="content-section fade-in">
        <form action="{{ route('processor.inventory.update', $item->inventory_id) }}" method="POST" class="form-container">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="product_name">Product Type</label>
                <select id="product_name" name="product_name" class="form-control" required>
                    <option value="">Select Product Type</option>
                    @foreach($product_types as $type)
                        <option value="{{ $type }}" {{ $item->product_name == $type ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
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
                <input type="text" id="product_variant" name="product_variant" class="form-control" value="{{ $item->product_variant }}" required>
            </div>

            <div class="form-group">
                <label for="current_stock_units">Current Stock (units)</label>
                <input type="number" id="current_stock_units" name="current_stock_units" class="form-control" value="{{ $item->current_stock_units }}" min="0" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label for="production_cost_per_unit">Production Cost per Unit (UGX)</label>
                <input type="number" id="production_cost_per_unit" name="production_cost_per_unit" class="form-control" value="{{ $item->production_cost_per_unit }}" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="selling_price_per_unit">Selling Price per Unit (UGX)</label>
                <input type="number" id="selling_price_per_unit" name="selling_price_per_unit" class="form-control" value="{{ $item->selling_price_per_unit }}" min="0" step="0.01" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Finished Good
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const productNameSelect = document.getElementById('product_name');
        const recipeSelect = document.getElementById('recipe_id');

        function fetchRecipes(productType, selectedRecipeId = null) {
            if (!productType) {
                recipeSelect.innerHTML = '<option value="">Select Product Type First</option>';
                recipeSelect.disabled = true;
                return;
            }
            
            recipeSelect.innerHTML = '<option value="">Loading recipes...</option>';
            recipeSelect.disabled = false;

            fetch(`{{ route('processor.inventory.fetchRecipes') }}?product_name=${productType}`)
                .then(response => response.json())
                .then(data => {
                    if (data.recipes && data.recipes.length > 0) {
                        recipeSelect.innerHTML = '<option value="">Select Recipe</option>';
                        data.recipes.forEach(recipe => {
                            const option = document.createElement('option');
                            option.value = recipe.recipe_id;
                            option.textContent = `${recipe.recipe_name} (${recipe.coffee_variety}, ${recipe.processing_method}, ${recipe.required_grade})`;
                            if (selectedRecipeId && recipe.recipe_id == selectedRecipeId) {
                                option.selected = true;
                            }
                            recipeSelect.appendChild(option);
                        });
                        recipeSelect.disabled = false;
                    } else {
                        recipeSelect.innerHTML = '<option value="">No recipes found for this product type</option>';
                        recipeSelect.disabled = true;
                    }
                })
                .catch(() => {
                    recipeSelect.innerHTML = '<option value="">Error loading recipes</option>';
                    recipeSelect.disabled = true;
                });
        }

        // Fetch recipes on page load for the current product type
        const initialProductType = productNameSelect.value;
        const initialRecipeId = "{{ $item->recipe_id }}";
        fetchRecipes(initialProductType, initialRecipeId);

        // Fetch recipes when product type changes
        productNameSelect.addEventListener('change', function() {
            fetchRecipes(this.value);
        });
    });
    </script>
@endsection

@section('scripts')
<script>
    // Auto-hide roast level field for green beans
    document.getElementById('product_type').addEventListener('change', function() {
        const roastLevelField = document.getElementById('roast_level').parentElement;
        if (this.value === 'green_beans') {
            roastLevelField.style.display = 'none';
        } else {
            roastLevelField.style.display = 'block';
        }
    });

    // Trigger on page load
    document.addEventListener('DOMContentLoaded', function() {
        const productType = document.getElementById('product_type').value;
        const roastLevelField = document.getElementById('roast_level').parentElement;
        if (productType === 'green_beans') {
            roastLevelField.style.display = 'none';
        }
    });
</script>

<style>
    /* Form Styles */
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
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
        background: rgba(255, 255, 255, 0.8);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--coffee-medium);
        box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
    }

    .form-control::placeholder {
        color: var(--text-light);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .auth-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(111, 78, 55, 0.1);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(111, 78, 55, 0.3);
    }

    .btn-outline {
        background: transparent;
        color: var(--coffee-medium);
        border: 2px solid var(--coffee-medium);
    }

    .btn-outline:hover {
        background: var(--coffee-medium);
        color: white;
        transform: translateY(-2px);
    }
</style> 