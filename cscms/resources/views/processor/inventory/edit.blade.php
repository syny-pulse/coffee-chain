@extends('layouts.processor')

@section('title', 'Edit Inventory Item')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-edit"></i>
            <div>
                <h1>Edit Inventory Item</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Update inventory item details
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
        <form action="{{ route('processor.inventory.update', $product->id) }}" method="POST" class="form-container">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}" required>
            </div>

            <div class="form-group">
                <label for="product_type">Product Type</label>
                <select id="product_type" name="product_type" class="form-control" required>
                    <option value="green_beans" {{ $product->product_type == 'green_beans' ? 'selected' : '' }}>Green Beans</option>
                    <option value="roasted_beans" {{ $product->product_type == 'roasted_beans' ? 'selected' : '' }}>Roasted Beans</option>
                    <option value="ground_coffee" {{ $product->product_type == 'ground_coffee' ? 'selected' : '' }}>Ground Coffee</option>
                </select>
            </div>

            <div class="form-group">
                <label for="origin_country">Origin Country</label>
                <input type="text" id="origin_country" name="origin_country" class="form-control" value="{{ $product->origin_country }}" placeholder="e.g., Uganda, Kenya, Ethiopia">
            </div>

            <div class="form-group">
                <label for="processing_method">Processing Method</label>
                <select id="processing_method" name="processing_method" class="form-control">
                    <option value="">Select Method</option>
                    <option value="washed" {{ $product->processing_method == 'washed' ? 'selected' : '' }}>Washed</option>
                    <option value="natural" {{ $product->processing_method == 'natural' ? 'selected' : '' }}>Natural</option>
                    <option value="honey" {{ $product->processing_method == 'honey' ? 'selected' : '' }}>Honey</option>
                </select>
            </div>

            <div class="form-group">
                <label for="roast_level">Roast Level</label>
                <select id="roast_level" name="roast_level" class="form-control">
                    <option value="">Select Roast Level</option>
                    <option value="light" {{ $product->roast_level == 'light' ? 'selected' : '' }}>Light</option>
                    <option value="medium" {{ $product->roast_level == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="dark" {{ $product->roast_level == 'dark' ? 'selected' : '' }}>Dark</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity_kg">Quantity (kg)</label>
                <input type="number" id="quantity_kg" name="quantity_kg" class="form-control" step="0.01" min="0" value="{{ $product->quantity_kg }}" required>
            </div>

            <div class="form-group">
                <label for="price_per_kg">Price per kg (UGX)</label>
                <input type="number" id="price_per_kg" name="price_per_kg" class="form-control" step="0.01" min="0" value="{{ $product->price_per_kg }}" placeholder="Enter price per kilogram">
            </div>

            <div class="form-group">
                <label for="quality_score">Quality Score (1-10)</label>
                <input type="number" id="quality_score" name="quality_score" class="form-control" min="1" max="10" step="0.1" value="{{ $product->quality_score }}" placeholder="Enter quality score">
            </div>

            <div class="form-group">
                <label for="harvest_date">Harvest Date</label>
                <input type="date" id="harvest_date" name="harvest_date" class="form-control" value="{{ $product->harvest_date ? $product->harvest_date->format('Y-m-d') : '' }}">
            </div>

            <div class="form-group">
                <label for="processing_date">Processing Date</label>
                <input type="date" id="processing_date" name="processing_date" class="form-control" value="{{ $product->processing_date ? $product->processing_date->format('Y-m-d') : '' }}">
            </div>

            <div class="form-group">
                <label for="expiry_date">Expiry Date</label>
                <input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '' }}">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ $product->status == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="sold" {{ $product->status == 'sold' ? 'selected' : '' }}>Sold</option>
                    <option value="expired" {{ $product->status == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter product description">{{ $product->description }}</textarea>
            </div>

            <div class="auth-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Item
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