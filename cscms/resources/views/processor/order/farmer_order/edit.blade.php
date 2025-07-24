@extends('layouts.processor')

@section('title', 'Edit Farmer Order')

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-edit"></i>
            <div>
                <h1>Edit Farmer Order</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Update order details and status
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

        <form action="{{ route('processor.order.farmer_order.update', $order->order_id) }}" method="POST"
            class="form-container">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="farmer_company_id">Farmer</label>
                <input type="text" class="form-control" value="{{ $order->farmer->company_name ?? 'Unknown Farmer' }}"
                    readonly style="background: rgba(111, 78, 55, 0.1);">
                <input type="hidden" name="farmer_company_id" value="{{ $order->farmer_company_id }}">
            </div>

            <div class="form-group">
                <label for="coffee_variety">Coffee Variety</label>
                <input type="text" class="form-control" value="{{ ucfirst($order->coffee_variety) }}" readonly
                    style="background: rgba(111, 78, 55, 0.1);">
                <input type="hidden" name="coffee_variety" value="{{ $order->coffee_variety }}">
            </div>

            <div class="form-group">
                <label for="processing_method">Processing Method</label>
                <input type="text" class="form-control" value="{{ ucfirst($order->processing_method) }}" readonly
                    style="background: rgba(111, 78, 55, 0.1);">
                <input type="hidden" name="processing_method" value="{{ $order->processing_method }}">
            </div>

            <div class="form-group">
                <label for="grade">Grade</label>
                <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $order->grade)) }}"
                    readonly style="background: rgba(111, 78, 55, 0.1);">
                <input type="hidden" name="grade" value="{{ $order->grade }}">
            </div>

            <div class="form-group">
                <label for="quantity_kg">Quantity (kg)</label>
                <input type="number" name="quantity_kg" id="quantity_kg" class="form-control" required min="0.01"
                    step="0.01" value="{{ $order->quantity_kg }}" placeholder="Enter quantity in kilograms"
                    onchange="calculateTotal()">
            </div>

            <div class="form-group">
                <label for="unit_price">Unit Price per kg (UGX)</label>
                <input type="number" name="unit_price" id="unit_price" class="form-control" required min="0.01"
                    step="0.01" value="{{ $order->unit_price }}" placeholder="Enter price per kilogram"
                    onchange="calculateTotal()">
            </div>

            <div class="form-group">
                <label for="total_amount">Total Amount (UGX)</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control" readonly step="0.01"
                    value="{{ $order->total_amount }}" style="background: rgba(111, 78, 55, 0.1);">
            </div>

            <div class="form-group">
                <label for="expected_delivery_date">Expected Delivery Date</label>
                <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control"
                    required
                    value="{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('Y-m-d') : '' }}">
            </div>

            <div class="form-group">
                <label for="order_status">Order Status</label>
                <select name="order_status" id="order_status" class="form-control" required>
                    {{-- <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                     <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option> --}}
                    <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered
                    </option>
                    <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="actual_delivery_date">Actual Delivery Date</label>
                <input type="date" name="actual_delivery_date" id="actual_delivery_date" class="form-control"
                    value="{{ $order->actual_delivery_date ? $order->actual_delivery_date->format('Y-m-d') : '' }}">
                <small class="form-text text-muted">Fill this when order status is set to "delivered"</small>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="3"
                    placeholder="Additional notes about the order">{{ $order->notes }}</textarea>
            </div>

            <div class="auth-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Order
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

        // Auto-fill actual delivery date when status is set to delivered
        document.getElementById('order_status').addEventListener('change', function() {
            const actualDeliveryField = document.getElementById('actual_delivery_date');
            if (this.value === 'delivered' && !actualDeliveryField.value) {
                actualDeliveryField.value = new Date().toISOString().split('T')[0];
            }
        });

        // Calculate initial total
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

        .form-control[readonly] {
            background: rgba(111, 78, 55, 0.1);
            cursor: not-allowed;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-text {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-top: 0.25rem;
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

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
    </style>
@endsection
