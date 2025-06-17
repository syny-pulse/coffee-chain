@extends('farmers.layouts.app')

@section('title', 'Reports')

@section('content')
    <h1><i class="fas fa-chart-line"></i> Reports</h1>
    <h2><i class="fas fa-seedling"></i> Harvest Performance</h2>
    <table>
        <thead>
            <tr>
                <th>Variety</th>
                <th>Total Quantity (kg)</th>
                <th>Period</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($harvestReports as $report)
                <tr>
                    <td>{{ $report['coffee_variety'] }}</td>
                    <td>{{ $report['total_quantity_kg'] }}</td>
                    <td>{{ $report['period'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2><i class="fas fa-money-bill"></i> Sales Forecast</h2>
    <div class="stat-item">
        <span class="stat-number">${{ $projectedSales }}</span>
        <span class="stat-label">Projected Sales</span>
    </div>
@endsection