@extends('farmers.layouts.app')

@section('title', 'Harvests')
@section('page-title', 'Coffee Harvests')
@section('page-subtitle', 'Manage and track your coffee harvests')

@section('page-actions')
    <a href="{{ route('farmers.harvests.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Add New Harvest
    </a>
@endsection

@section('content')
    @if (isset($harvests) && count($harvests) > 0)
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-wheat-awn"></i>
                    All Harvests
                </h2>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Variety</th>
                            <th>Grade</th>
                            <th>Quantity (kg)</th>
                            <th>Available (kg)</th>
                            <th>Reserved (kg)</th>
                            <th>Harvest Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($harvests as $harvest)
                            @include('farmers.partials.table-row', [
                                'item' => $harvest,
                                'columns' => [
                                    ['field' => 'harvest_id'],
                                    ['field' => 'coffee_variety'],
                                    ['field' => 'grade'],
                                    ['field' => 'quantity_kg', 'type' => 'number'],
                                    ['field' => 'available_quantity_kg', 'type' => 'number'],
                                    ['field' => 'reserved_quantity_kg', 'type' => 'number'],
                                    ['field' => 'harvest_date', 'type' => 'date'],
                                    ['field' => 'availability_status', 'type' => 'status'],
                                ],
                                'actions' => [
                                    [
                                        'type' => 'link',
                                        'url' => route('farmers.harvests.edit', $harvest['harvest_id']),
                                        'icon' => 'edit',
                                        'style' => 'outline',
                                        'title' => 'Edit',
                                    ],
                                    [
                                        'type' => 'delete',
                                        'url' => route('farmers.harvests.destroy', $harvest['harvest_id']),
                                    ],
                                ],
                            ])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <i class="fas fa-wheat-awn"></i>
                <h3>No Harvests Recorded</h3>
                <p>Start tracking your coffee harvests to monitor your production and manage your inventory.</p>
                <a href="{{ route('farmers.harvests.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Record Your First Harvest
                </a>
            </div>
        </div>
    @endif
@endsection
