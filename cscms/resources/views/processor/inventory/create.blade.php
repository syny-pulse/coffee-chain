@extends('layouts.processor')

@section('title', 'Add Inventory Item')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-plus"></i>
            <div>
                <h1>Add New Inventory Item</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Add a new item to your inventory
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

    <!-- Inventory Form -->
    <div class="content-section fade-in">
        <form action="{{ route('processor.inventory.store') }}" method="POST" class="form-container">
            @csrf
            <div class="form-group">
                <label for="inventory_type">Inventory Type</label>
                <select id="inventory_type" name="inventory_type" class="form-control" required>
                    <option value="">Select Inventory Type</option>
                    <option value="raw_material">Raw Material</option>
                    <option value="finished_good">Finished Good</option>
                </select>
            </div>

            <!-- Raw Material Fields -->
            <div id="raw_material_fields" style="display: none;">
                <div class="form-group">
                    <label for="coffee_variety">Coffee Variety</label>
                    <select id="coffee_variety" name="coffee_variety" class="form-control">
                        <option value="">Select Variety</option>
                        <option value="arabica">Arabica</option>
                        <option value="robusta">Robusta</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="processing_method">Processing Method</label>
                    <select id="processing_method" name="processing_method" class="form-control">
                        <option value="">Select Method</option>
                        <option value="natural">Natural</option>
                        <option value="washed">Washed</option>
                        <option value="honey">Honey</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="grade">Grade</label>
                    <select id="grade" name="grade" class="form-control">
                        <option value="">Select Grade</option>
                        <option value="grade_1">Grade 1</option>
                        <option value="grade_2">Grade 2</option>
                        <option value="grade_3">Grade 3</option>
                        <option value="grade_4">Grade 4</option>
                        <option value="grade_5">Grade 5</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="current_stock_kg">Current Stock (kg)</label>
                    <input type="number" id="current_stock_kg" name="current_stock_kg" class="form-control" step="0.01" min="0" 
                           placeholder="Enter current stock in kilograms">
                </div>

                <div class="form-group">
                    <label for="average_cost_per_kg">Average Cost per kg (UGX)</label>
                    <input type="number" id="average_cost_per_kg" name="average_cost_per_kg" class="form-control" step="0.01" min="0" 
                           placeholder="Enter average cost per kilogram">
                </div>
            </div>

            <!-- Finished Goods Fields -->
            <div id="finished_goods_fields" style="display: none;">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <select id="product_name" name="product_name" class="form-control">
                        <option value="">Select Product</option>
                        <option value="drinking_coffee">Drinking Coffee</option>
                        <option value="roasted_coffee">Roasted Coffee</option>
                        <option value="coffee_scents">Coffee Scents</option>
                        <option value="coffee_soap">Coffee Soap</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="product_variant">Product Variant</label>
                    <input type="text" id="product_variant" name="product_variant" class="form-control" maxlength="100" 
                           placeholder="Enter product variant">
                </div>

                <div class="form-group">
                    <label for="recipe_id">Recipe</label>
                    <select id="recipe_id" name="recipe_id" class="form-control">
                        <option value="">Select Recipe</option>
                        <!-- This should be populated from the database -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="current_stock_units">Current Stock (units)</label>
                    <input type="number" id="current_stock_units" name="current_stock_units" class="form-control" step="0.01" min="0" 
                           placeholder="Enter current stock in units">
                </div>

                <div class="form-group">
                    <label for="production_cost_per_unit">Production Cost per Unit (UGX)</label>
                    <input type="number" id="production_cost_per_unit" name="production_cost_per_unit" class="form-control" step="0.01" min="0" 
                           placeholder="Enter production cost per unit">
                </div>

                <div class="form-group">
                    <label for="selling_price_per_unit">Selling Price per Unit (UGX)</label>
                    <input type="number" id="selling_price_per_unit" name="selling_price_per_unit" class="form-control" step="0.01" min="0" 
                           placeholder="Enter selling price per unit">
                </div>
            </div>

            <div class="auth-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save Item
                </button>
                <a href="{{ route('processor.inventory.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById('inventory_type').addEventListener('change', function() {
        const rawMaterialFields = document.getElementById('raw_material_fields');
        const finishedGoodsFields = document.getElementById('finished_goods_fields');
        
        if (this.value === 'raw_material') {
            rawMaterialFields.style.display = 'block';
            finishedGoodsFields.style.display = 'none';
        } else if (this.value === 'finished_good') {
            rawMaterialFields.style.display = 'none';
            finishedGoodsFields.style.display = 'block';
        } else {
            rawMaterialFields.style.display = 'none';
            finishedGoodsFields.style.display = 'none';
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

    .auth-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: flex-start;
    }
</style>
@endsection 