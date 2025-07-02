@extends('farmers.layouts.app')

@section('title', 'Inventory Management')
@section('page-title', 'Inventory Management')
@section('page-subtitle', 'Track your coffee stock levels, reserved quantities, and available inventory')

@section('page-actions')
<a href="{{ route('farmers.harvests.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Harvest
            </a>
            <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline">
                <i class="fas fa-clipboard-list"></i> View Orders
            </a>
@endsection

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <div class="stat-trend {{ $trends['totalStock'] >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $trends['totalStock'] >= 0 ? 'up' : 'down' }}"></i>
                    {{ ($trends['totalStock'] >= 0 ? '+' : '') . $trends['totalStock'] . '%' }}
                </div>
            </div>
            <div class="stat-value">{{ number_format($totalStock, 1) }}</div>
            <div class="stat-label">Total Stock (kg)</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-trend {{ $trends['totalAvailable'] >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $trends['totalAvailable'] >= 0 ? 'up' : 'down' }}"></i>
                    {{ ($trends['totalAvailable'] >= 0 ? '+' : '') . $trends['totalAvailable'] . '%' }}
                </div>
            </div>
            <div class="stat-value">{{ number_format($totalAvailable, 1) }}</div>
            <div class="stat-label">Available Stock (kg)</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-trend {{ $trends['totalReserved'] >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $trends['totalReserved'] >= 0 ? 'up' : 'down' }}"></i>
                    {{ ($trends['totalReserved'] >= 0 ? '+' : '') . $trends['totalReserved'] . '%' }}
                </div>
            </div>
            <div class="stat-value">{{ number_format($totalReserved, 1) }}</div>
            <div class="stat-label">Reserved Stock (kg)</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-trend {{ $trends['inventoryCount'] >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $trends['inventoryCount'] >= 0 ? 'up' : 'down' }}"></i>
                    {{ ($trends['inventoryCount'] >= 0 ? '+' : '') . $trends['inventoryCount'] }}
                </div>
            </div>
            <div class="stat-value">{{ count($inventory) }}</div>
            <div class="stat-label">Inventory Items</div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Current Inventory</h2>
            <div class="card-actions">
                <button class="btn btn-sm btn-outline" onclick="exportInventory()">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
        
        <div class="table-container" style="overflow-x: auto; overflow-y: auto; max-height: 400px; scrollbar-width: none; -ms-overflow-style: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Coffee Variety</th>
                        <th style="min-width: 120px;">Grade</th>
                        <th>Processing Method</th>
                        <th>Total Quantity (kg)</th>
                        <th>Reserved (kg)</th>
                        <th>Available (kg)</th>
                        <th>Harvest Date</th>
                        <th>Storage Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventory as $item)
                        <tr>
                            <td>
                                <strong>{{ $item['coffee_variety'] }}</strong>
                            </td>
                            <td>
                                <span class="status-badge {{ $item['grade'] === 'grade_1' ? 'completed' : 'processing' }}" style="min-width: 100px; display: inline-block; text-align: center;">
                                    {{ ucfirst(str_replace('_', ' ', $item['grade'])) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($item['processing_method']) }}</td>
                            <td>{{ number_format($item['total_quantity_kg'], 1) }}</td>
                            <td>{{ number_format($item['reserved_quantity_kg'], 1) }}</td>
                            <td>
                                <strong>{{ number_format($item['available_quantity_kg'], 1) }}</strong>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item['harvest_date'])->format('M d, Y') }}</td>
                            <td>{{ $item['storage_location'] }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('farmers.inventory.show', $item['harvest_id']) }}" class="btn btn-sm btn-outline" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('farmers.inventory.edit', $item['harvest_id']) }}" class="btn btn-sm btn-outline" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('farmers.inventory.destroy', $item['harvest_id']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline" title="Delete" onclick="return confirm('Are you sure you want to delete this inventory item?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <h3>No Inventory Found</h3>
                                    <p>Start by adding your first harvest to track inventory.</p>
                                    <a href="{{ route('farmers.harvests.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Harvest
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inventory Summary -->
    <div class="details-grid">
        <div class="detail-section">
            <h3><i class="fas fa-chart-pie"></i> Stock Distribution</h3>
            <div class="detail-item">
                <span class="detail-label">Arabica Beans</span>
                <span class="detail-value">{{ number_format($arabicaTotal, 1) }} kg</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Robusta Beans</span>
                <span class="detail-value">{{ number_format($robustaTotal, 1) }} kg</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Utilization Rate</span>
                <span class="detail-value">{{ $totalStock > 0 ? number_format(($totalReserved / $totalStock) * 100, 1) : 0 }}%</span>
            </div>
        </div>
        
        <div class="detail-section">
            <h3><i class="fas fa-warehouse"></i> Storage Information</h3>
            <div class="detail-item">
                <span class="detail-label">Warehouse A</span>
                <span class="detail-value">{{ number_format($warehouseATotal, 1) }} kg</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Warehouse B</span>
                <span class="detail-value">{{ number_format($warehouseBTotal, 1) }} kg</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Processing Methods</span>
                <span class="detail-value">{{ $processingMethodsCount }}</span>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function exportInventory() {
    const table = document.querySelector('.table');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    for (let row of rows) {
        let cols = row.querySelectorAll('th, td');
        let rowData = [];
        for (let col of cols) {
            rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
        }
        csv.push(rowData.join(','));
    }
    const csvString = csv.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', 'inventory.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Add inventory-specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Highlight inventory nav item
    const inventoryLink = document.querySelector('a[href*="inventory"]');
    if (inventoryLink) {
        inventoryLink.classList.add('active');
    }
    
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endpush