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
        <form action="{{ route('processor.inventory.update', $item->inventory_id) }}" method="POST" class="form-container">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="coffee_variety">Coffee Variety</label>
                <select id="coffee_variety" name="coffee_variety" class="form-control" required>
                    <option value="arabica" {{ $item->coffee_variety == 'arabica' ? 'selected' : '' }}>Arabica</option>
                    <option value="robusta" {{ $item->coffee_variety == 'robusta' ? 'selected' : '' }}>Robusta</option>
                </select>
            </div>

            <div class="form-group">
                <label for="processing_method">Processing Method</label>
                <select id="processing_method" name="processing_method" class="form-control" required>
                    <option value="">Select Method</option>
                    <option value="washed" {{ $item->processing_method == 'washed' ? 'selected' : '' }}>Washed</option>
                    <option value="natural" {{ $item->processing_method == 'natural' ? 'selected' : '' }}>Natural</option>
                    <option value="honey" {{ $item->processing_method == 'honey' ? 'selected' : '' }}>Honey</option>
                </select>
            </div>

            <div class="form-group">
                <label for="grade">Grade</label>
                <select id="grade" name="grade" class="form-control" required>
                    <option value="">Select Grade</option>
                    <option value="grade_1" {{ $item->grade == 'grade_1' ? 'selected' : '' }}>Grade 1</option>
                    <option value="grade_2" {{ $item->grade == 'grade_2' ? 'selected' : '' }}>Grade 2</option>
                    <option value="grade_3" {{ $item->grade == 'grade_3' ? 'selected' : '' }}>Grade 3</option>
                    <option value="grade_4" {{ $item->grade == 'grade_4' ? 'selected' : '' }}>Grade 4</option>
                    <option value="grade_5" {{ $item->grade == 'grade_5' ? 'selected' : '' }}>Grade 5</option>
                </select>
            </div>

            <div class="form-group">
                <label for="current_stock_kg">Current Stock (kg)</label>
                <input type="number" id="current_stock_kg" name="current_stock_kg" class="form-control" step="0.01" min="0" value="{{ $item->current_stock_kg }}" required>
            </div>

            <div class="form-group">
                <label for="reserved_stock_kg">Reserved Stock (kg)</label>
                <input type="number" id="reserved_stock_kg" name="reserved_stock_kg" class="form-control" step="0.01" min="0" value="{{ $item->reserved_stock_kg }}" required>
            </div>

            <div class="form-group">
                <label for="available_stock_kg">Available Stock (kg)</label>
                <input type="number" id="available_stock_kg" name="available_stock_kg" class="form-control" step="0.01" min="0" value="{{ $item->available_stock_kg }}" required>
            </div>

            <div class="form-group">
                <label for="average_cost_per_kg">Average Cost per kg (UGX)</label>
                <input type="number" id="average_cost_per_kg" name="average_cost_per_kg" class="form-control" step="0.01" min="0" value="{{ $item->average_cost_per_kg }}" placeholder="Enter average cost per kilogram">
            </div>

            <div class="form-group">
                <label for="last_updated">Last Updated</label>
                <input type="datetime-local" id="last_updated" name="last_updated" class="form-control" value="{{ $item->last_updated ? \Carbon\Carbon::parse($item->last_updated)->format('Y-m-d\TH:i') : '' }}">
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