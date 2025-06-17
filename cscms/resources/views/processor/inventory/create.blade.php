@extends('layouts.processor')

@section('title', 'Add Inventory Item')

@section('content')
<section class="section" id="inventory">
    <div class="section-container">
        <div class="section-header">
            <h2>Add New Inventory Item</h2>
            <p>Add a new item to your inventory</p>
        </div>

        <form action="{{ route('processor.inventory.store') }}" method="POST" class="form-container">
            @csrf
            <div class="form-group">
                <label for="item_type">Item Type</label>
                <select id="item_type" name="item_type" class="form-control" required>
                    <option value="">Select Item Type</option>
                    <option value="raw_material">Raw Material</option>
                    <option value="finished_good">Finished Good</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Item Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group raw-material-fields" style="display: none;">
                <label for="coffee_variety">Coffee Variety</label>
                <select id="coffee_variety" name="coffee_variety" class="form-control">
                    <option value="">Select Variety</option>
                    <option value="arabica">Arabica</option>
                    <option value="robusta">Robusta</option>
                    <option value="liberica">Liberica</option>
                    <option value="excelsa">Excelsa</option>
                </select>
            </div>

            <div class="form-group raw-material-fields" style="display: none;">
                <label for="processing_method">Processing Method</label>
                <select id="processing_method" name="processing_method" class="form-control">
                    <option value="">Select Method</option>
                    <option value="washed">Washed</option>
                    <option value="natural">Natural</option>
                    <option value="honey">Honey</option>
                    <option value="wet_hulled">Wet Hulled</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="form-control" required min="0">
            </div>

            <div class="form-group">
                <label for="unit">Unit</label>
                <select id="unit" name="unit" class="form-control" required>
                    <option value="">Select Unit</option>
                    <option value="kg">Kilograms (kg)</option>
                    <option value="g">Grams (g)</option>
                    <option value="units">Units</option>
                </select>
            </div>

            <div class="form-group">
                <label for="unit_price">Unit Price (UGX)</label>
                <input type="number" id="unit_price" name="unit_price" class="form-control" required min="0">
            </div>

            <div class="form-group">
                <label for="supplier">Supplier</label>
                <input type="text" id="supplier" name="supplier" class="form-control">
            </div>

            <div class="form-group">
                <label for="location">Storage Location</label>
                <input type="text" id="location" name="location" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="notes">Additional Notes</label>
                <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
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
</section>

@section('scripts')
<script>
    document.getElementById('item_type').addEventListener('change', function() {
        const rawMaterialFields = document.querySelectorAll('.raw-material-fields');
        if (this.value === 'raw_material') {
            rawMaterialFields.forEach(field => field.style.display = 'block');
        } else {
            rawMaterialFields.forEach(field => field.style.display = 'none');
        }
    });
</script>
@endsection 