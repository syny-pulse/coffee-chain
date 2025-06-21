@extends('layouts.processor')

@section('title', 'Inventory Management')

@section('content')
    <!-- Dashboard Header -->
          <div class="dashboard-header fade-in">
        <div class="dashboard-title">
            <i class="fas fa-warehouse"></i>
            <div>
                <h1>Inventory Management</h1>
                <p style="color: var(--text-light); margin: 0; font-size: 0.9rem;">
                    Process raw materials into finished goods for retailers
                </p>
            </div>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('processor.inventory.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add Inventory Item
            </a>
        </div>
    </div>

    <!-- Inventory Overview -->
    <div class="stats-grid fade-in">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Raw Materials</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-seedling"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $raw_materials->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-down"></i>
                <span class="change-positive">From farmers</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Finished Goods</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-coffee"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $finished_goods->count() }}</div>
            <div class="stat-card-change">
                <i class="fas fa-arrow-up"></i>
                <span class="change-positive">For retailers</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Processing Capacity</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-industry"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($total_processing_capacity ?? 1000) }} kg</div>
            <div class="stat-card-change">
                <i class="fas fa-chart-line"></i>
                <span class="change-positive">Monthly capacity</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Ready for Sale</span>
                <div class="stat-card-icon" style="background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $ready_for_sale_count ?? 0 }}</div>
            <div class="stat-card-change">
                <i class="fas fa-shopping-cart"></i>
                <span class="change-positive">Available for retailers</span>
            </div>
        </div>
    </div>

    <!-- Coffee Chain Workflow -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-route"></i>
                <span>Coffee Chain Workflow</span>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: rgba(255, 255, 255, 0.8); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(111, 78, 55, 0.1); text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3 style="color: var(--coffee-dark); margin-bottom: 0.5rem;">1. Order from Farmers</h3>
                <p style="color: var(--text-light); font-size: 0.9rem;">Place orders for raw coffee beans from farmers</p>
                <a href="{{ route('processor.order.farmer_order.create') }}" class="btn btn-outline" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i>
                    New Farmer Order
                </a>
            </div>

            <div style="background: rgba(255, 255, 255, 0.8); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(111, 78, 55, 0.1); text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-industry"></i>
                </div>
                <h3 style="color: var(--coffee-dark); margin-bottom: 0.5rem;">2. Process Raw Materials</h3>
                <p style="color: var(--text-light); font-size: 0.9rem;">Convert raw coffee into finished products</p>
                <a href="{{ route('processor.inventory.create') }}" class="btn btn-outline" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i>
                    Add Finished Good
                </a>
            </div>

            <div style="background: rgba(255, 255, 255, 0.8); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(111, 78, 55, 0.1); text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 style="color: var(--coffee-dark); margin-bottom: 0.5rem;">3. Wait for Retailers</h3>
                <p style="color: var(--text-light); font-size: 0.9rem;">Retailers place orders for finished goods</p>
                <a href="{{ route('processor.order.retailer_order.index') }}" class="btn btn-outline" style="margin-top: 1rem;">
                    <i class="fas fa-eye"></i>
                    View Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Raw Materials Section -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-seedling"></i>
                <span>Raw Materials (From Farmers)</span>
            </div>
            <a href="{{ route('processor.order.farmer_order.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Order from Farmer
            </a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Coffee Variety</th>
                        <th>Processing Method</th>
                        <th>Grade</th>
                        <th>Current Stock (kg)</th>
                        <th>Available Stock (kg)</th>
                        <th>Cost per kg</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($raw_materials as $material)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-seedling" style="color: var(--coffee-medium);"></i>
                                <strong>{{ ucfirst($material->name) }}</strong>
                            </div>
                        </td>
                        <td>{{ ucfirst($material->processing_method) }}</td>
                        <td>{{ ucfirst($material->roast_level ?? 'N/A') }}</td>
                        <td>{{ number_format($material->quantity_kg, 2) }}</td>
                        <td>{{ number_format($material->quantity_kg, 2) }}</td>
                        <td>UGX {{ number_format($material->price_per_kg, 2) }}</td>
                        <td>
                            @if($material->quantity_kg < 100)
                                <span class="status-badge status-low">Low Stock</span>
                            @elseif($material->quantity_kg < 500)
                                <span class="status-badge status-medium">Medium</span>
                            @else
                                <span class="status-badge status-high">Good</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--coffee-medium); color: white;">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No raw materials found. <a href="{{ route('processor.order.farmer_order.create') }}" style="color: var(--coffee-medium);">Order from farmers</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Finished Goods Section -->
    <div class="content-section fade-in">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-coffee"></i>
                <span>Finished Goods (For Retailers)</span>
            </div>
            <a href="{{ route('processor.inventory.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add Finished Good
            </a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Variant</th>
                        <th>Current Stock (units)</th>
                        <th>Available Stock (units)</th>
                        <th>Production Cost</th>
                        <th>Selling Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($finished_goods as $product)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-coffee" style="color: var(--coffee-medium);"></i>
                                <strong>{{ str_replace('_', ' ', ucfirst($product->name)) }}</strong>
                            </div>
                        </td>
                        <td>{{ ucfirst($product->product_type) }}</td>
                        <td>{{ number_format($product->quantity_kg, 2) }}</td>
                        <td>{{ number_format($product->quantity_kg, 2) }}</td>
                        <td>UGX {{ number_format($product->price_per_kg, 2) }}</td>
                        <td>UGX {{ number_format($product->price_per_kg * 1.2, 2) }}</td>
                        <td>
                            @if($product->quantity_kg < 50)
                                <span class="status-badge status-low">Low Stock</span>
                            @elseif($product->quantity_kg < 200)
                                <span class="status-badge status-medium">Medium</span>
                            @else
                                <span class="status-badge status-high">Ready for Sale</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.8rem; background: var(--coffee-medium); color: white;">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No finished goods found. <a href="{{ route('processor.inventory.create') }}" style="color: var(--coffee-medium);">Add finished goods</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection