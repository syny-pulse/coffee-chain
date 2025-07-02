@extends('layouts.processor')

@section('title', 'Create Farmer Order')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-seedling"></i>
            <div>
                <h1>Create New Farmer Order</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Place an order for coffee from farmers
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.order.farmer_order.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Orders
            </a>
        </div>
    </div>

    <!-- Order Form -->
    <div class="content-section fade-in">
        {{-- Display validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Display error message from controller --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('processor.order.farmer_order.store') }}" method="POST" class="form-container">
            @csrf

            <div class="form-group">
                <label for="farmer_company_id">Select Farmer</label>
                <select name="farmer_company_id" id="farmer_company_id" class="form-control" required>
                    <option value="">Select a Farmer</option>
                    @foreach($farmers as $farmer)
                        <option value="{{ $farmer->company_id }}" {{ old('farmer_company_id') == $farmer->company_id ? 'selected' : '' }}>
                            {{ $farmer->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="coffee_variety">Coffee Variety</label>
                <select name="coffee_variety" id="coffee_variety" class="form-control" required>
                    <option value="">Select Variety</option>
                    <option value="arabica" {{ old('coffee_variety') == 'arabica' ? 'selected' : '' }}>Arabica</option>
                    <option value="robusta" {{ old('coffee_variety') == 'robusta' ? 'selected' : '' }}>Robusta</option>
                </select>
            </div>

            <div class="form-group">
                <label for="processing_method">Processing Method</label>
                <select name="processing_method" id="processing_method" class="form-control" required>
                    <option value="">Select Method</option>
                    <option value="natural" {{ old('processing_method') == 'natural' ? 'selected' : '' }}>Natural</option>
                    <option value="washed" {{ old('processing_method') == 'washed' ? 'selected' : '' }}>Washed</option>
                    <option value="honey" {{ old('processing_method') == 'honey' ? 'selected' : '' }}>Honey</option>
                </select>
            </div>

            <div class="form-group">
                <label for="grade">Grade</label>
                <select name="grade" id="grade" class="form-control" required>
                    <option value="">Select Grade</option>
                    <option value="grade_1" {{ old('grade') == 'grade_1' ? 'selected' : '' }}>Grade 1</option>
                    <option value="grade_2" {{ old('grade') == 'grade_2' ? 'selected' : '' }}>Grade 2</option>
                    <option value="grade_3" {{ old('grade') == 'grade_3' ? 'selected' : '' }}>Grade 3</option>
                    <option value="grade_4" {{ old('grade') == 'grade_4' ? 'selected' : '' }}>Grade 4</option>
                    <option value="grade_5" {{ old('grade') == 'grade_5' ? 'selected' : '' }}>Grade 5</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity_kg">Quantity (kg)</label>
                <input type="number" name="quantity_kg" id="quantity_kg" class="form-control" required 
                       min="0.01" step="0.01" value="{{ old('quantity_kg') }}"
                       placeholder="Enter quantity in kilograms"
                       onchange="calculateTotal()">
            </div>

            <div class="form-group">
                <label for="unit_price">Unit Price per kg (UGX)</label>
                <input type="number" name="unit_price" id="unit_price" class="form-control" required 
                       min="0.01" step="0.01" value="{{ old('unit_price') }}"
                       placeholder="Enter price per kilogram"
                       onchange="calculateTotal()">
            </div>

            <div class="form-group">
                <label for="total_amount">Total Amount (UGX)</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control" readonly 
                       step="0.01" value="{{ old('total_amount') }}"
                       style="background: rgba(111, 78, 55, 0.1);">
            </div>

            <div class="form-group">
                <label for="expected_delivery_date">Expected Delivery Date</label>
                <input type="date" name="expected_delivery_date" id="expected_delivery_date" 
                       class="form-control" required value="{{ old('expected_delivery_date') }}">
            </div>

            <div class="form-group">
                <label for="order_status">Order Status</label>
                <select name="order_status" id="order_status" class="form-control" required>
                    <option value="pending" {{ old('order_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ old('order_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ old('order_status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ old('order_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ old('order_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ old('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="3" 
                          placeholder="Additional notes about the order">{{ old('notes') }}</textarea>
            </div>

            <div class="auth-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Submit Order
                </button>
                <a href="{{ route('processor.order.farmer_order.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    function calculateTotal() {
        const quantity = parseFloat(document.getElementById('quantity_kg').value) || 0;
        const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
        const total = (quantity * unitPrice).toFixed(2);
        document.getElementById('total_amount').value = total;
    }

    // Calculate initial total if values exist
    calculateTotal();
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

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 1px solid;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger);
        border-color: rgba(220, 53, 69, 0.2);
    }
</style>
@endsection
