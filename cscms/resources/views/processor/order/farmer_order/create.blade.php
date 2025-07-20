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

        <form action="{{ route('processor.order.farmer_order.store') }}" method="POST" class="form-container"
            id="farmerOrderForm">
            @csrf

            <div class="form-group">
                <label for="coffee_variety">Coffee Variety</label>
                <select name="coffee_variety" id="coffee_variety" class="form-control" required>
                    <option value="">Select Variety</option>
                    <option value="arabica" {{ old('coffee_variety') == 'arabica' ? 'selected' : '' }}>Arabica</option>
                    <option value="robusta" {{ old('coffee_variety') == 'robusta' ? 'selected' : '' }}>Robusta</option>
                </select>
            </div>

            <div class="form-group">
                <label for="farmer_company_id">Select Farmer Company</label>
                <select name="farmer_company_id" id="farmer_company_id" class="form-control" required disabled>
                    <option value="">Select a Farmer Company</option>
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
                <input type="number" name="quantity_kg" id="quantity_kg" class="form-control" required min="0.01"
                    step="0.01" value="{{ old('quantity_kg') }}" placeholder="Enter quantity in kilograms"
                    onchange="calculateTotal()">
            </div>

            <div class="form-group">
                <label for="unit_price">Unit Price per kg (UGX)</label>
                <input type="number" name="unit_price" id="unit_price" class="form-control" required min="0.01"
                    step="0.01" value="{{ old('unit_price') }}" placeholder="Unit price will be auto-filled" readonly>
                <small id="unit_price_error" class="text-danger" style="display:none;"></small>
            </div>

            <div class="form-group">
                <label for="total_amount">Total Amount (UGX)</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control" readonly step="0.01"
                    value="{{ old('total_amount') }}" style="background: rgba(111, 78, 55, 0.1);">
            </div>

            <div class="form-group">
                <label for="expected_delivery_date">Expected Delivery Date</label>
                <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control"
                    required value="{{ old('expected_delivery_date') }}">
            </div>

            <!-- Remove the order status dropdown and add a hidden input for order_status -->
            <input type="hidden" name="order_status" value="pending">

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

        // Fetch price when farmer, variety, grade, or processing method changes
        function fetchUnitPrice() {
            const farmerId = document.getElementById('farmer_company_id').value;
            const variety = document.getElementById('coffee_variety').value;
            const grade = document.getElementById('grade').value;
            const processingMethod = document.getElementById('processing_method').value;
            const unitPriceInput = document.getElementById('unit_price');
            const errorElem = document.getElementById('unit_price_error');

            console.log('Fetching price for:', {
                farmerId,
                variety,
                grade,
                processingMethod
            });

            if (farmerId && variety && grade && processingMethod) {
                const url =
                    `{{ route('processor.order.farmer_order.getPrice') }}?farmer_company_id=${farmerId}&coffee_variety=${variety}&grade=${grade}&processing_method=${processingMethod}`;
                console.log('Fetching from URL:', url);

                fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json().then(data => ({
                            status: response.status,
                            data
                        }));
                    })
                    .then(({
                        status,
                        data
                    }) => {
                        console.log('Response data:', data);
                        if (status === 200 && data.unit_price) {
                            unitPriceInput.value = data.unit_price;
                            errorElem.style.display = 'none';
                            calculateTotal();
                        } else {
                            unitPriceInput.value = '';
                            errorElem.textContent = data.error || 'No price found for this combination.';
                            errorElem.style.display = 'block';
                            calculateTotal();
                        }
                    })
                    .catch((error) => {
                        console.error('Error fetching price:', error);
                        unitPriceInput.value = '';
                        errorElem.textContent = 'Error fetching price. Please try again.';
                        errorElem.style.display = 'block';
                        calculateTotal();
                    });
            } else {
                unitPriceInput.value = '';
                errorElem.style.display = 'none';
                calculateTotal();
            }
        }

        // Fetch farmers when coffee variety changes
        document.getElementById('coffee_variety').addEventListener('change', function() {
            const variety = this.value;
            const farmerSelect = document.getElementById('farmer_company_id');
            farmerSelect.innerHTML = '<option value="">Select a Farmer Company</option>';
            farmerSelect.disabled = true;
            
            console.log('Coffee variety changed to:', variety);
            
            if (variety) {
                const url = `{{ route('processor.order.farmer_order.getFarmersByVariety') }}?coffee_variety=${variety}`;
                console.log('Fetching farmers from URL:', url);
                
                fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json().then(data => ({
                            status: response.status,
                            data
                        }));
                    })
                    .then(({status, data}) => {
                        console.log('Response data:', data);
                        if (status === 200 && data.farmers && data.farmers.length > 0) {
                            data.farmers.forEach(farmer => {
                                const option = document.createElement('option');
                                option.value = farmer.company_id;
                                option.textContent = farmer.company_name;
                                farmerSelect.appendChild(option);
                            });
                            farmerSelect.disabled = false;
                            console.log('Farmers loaded successfully:', data.farmers.length, 'farmers');
                        } else {
                            farmerSelect.innerHTML =
                                '<option value="">No farmer companies found for this variety</option>';
                            farmerSelect.disabled = true;
                            console.log('No farmers found for variety:', variety);
                        }
                        fetchUnitPrice(); // Trigger price fetch after farmers are loaded
                    })
                    .catch((error) => {
                        console.error('Error fetching farmers:', error);
                        farmerSelect.innerHTML = '<option value="">Error loading farmer companies</option>';
                        farmerSelect.disabled = true;
                        fetchUnitPrice(); // Still attempt to fetch price
                    });
            } else {
                console.log('No variety selected, clearing farmers');
                fetchUnitPrice(); // Update price if variety is cleared
            }
        });

        // Event listeners for price updates
        document.getElementById('farmer_company_id').addEventListener('change', fetchUnitPrice);
        document.getElementById('coffee_variety').addEventListener('change', fetchUnitPrice);
        document.getElementById('grade').addEventListener('change', fetchUnitPrice);
        document.getElementById('processing_method').addEventListener('change', fetchUnitPrice); // Added
        document.getElementById('quantity_kg').addEventListener('input', calculateTotal);

        // Initial fetch if values exist
        fetchUnitPrice();
        calculateTotal();

        // Prevent form submission if unit price is empty or invalid
        document.getElementById('farmerOrderForm').addEventListener('submit', function(e) {
            const unitPriceInput = document.getElementById('unit_price');
            const errorElem = document.getElementById('unit_price_error');
            if (!unitPriceInput.value || isNaN(unitPriceInput.value) || parseFloat(unitPriceInput.value) <= 0) {
                e.preventDefault();
                errorElem.textContent = 'A valid unit price must be available before submitting.';
                errorElem.style.display = 'block';
                unitPriceInput.focus();
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
