```html
@extends('layouts.processor')

@section('title', 'Inventory Management')

@section('content')
<section class="section" id="inventory">
    <div class="section-container">
          <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-warehouse"></i>
            <div>
                <h1>Inventory Management</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Manage Inventory as of {{ now()->format('h:i A T, l, F d, Y') }}
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.inventory.create') }}" class="btn btn-success">
                <i class="fas fa-warehouse"></i>
                Make a new Inventory
            </a>
        </div>
    </div>
        <div class="section-header">
            <h2>Inventory Management</h2>
            <p>Track your raw materials and finished goods</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3>Raw Materials</h3>
                @forelse($raw_materials as $material)
                    <p>{{ ucfirst($material->coffee_variety) }} ({{ $material->processing_method }}): {{ $material->current_quantity }} kg</p>
                @empty
                    <p>No raw materials available.</p>
                @endforelse
                <a href="#raw-materials" class="btn btn-outline">View Details</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-coffee"></i>
                </div>
                <h3>Finished Goods</h3>
                @forelse($finished_goods as $product)
                    <p>{{ str_replace('_', ' ', ucfirst($product->product_name)) }}: {{ $product->current_stock_units }} units</p>
                @empty
                    <p>No finished goods available.</p>
                @endforelse
                <a href="#finished-goods" class="btn btn-outline">View Details</a>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
    <style>
        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 2px solid var(--coffee-medium);
            border-radius: 25px;
            color: var(--coffee-medium);
            text-decoration: none;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--coffee-medium);
            color: white;
            transform: translateY(-2px);
        }
    </style>
@endsection