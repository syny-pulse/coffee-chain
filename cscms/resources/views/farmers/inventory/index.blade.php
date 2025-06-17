@extends('farmers.layouts.app')

@section('title', 'Inventory')

@section('content')
    <h1><i class="fas fa-warehouse"></i> Inventory</h1>
    <table>
        <thead>
            <tr>
                <th>Variety</th>
                <th>Grade</th>
                <th>Total Quantity (kg)</th>
                <th>Reserved (kg)</th>
                <th>Available (kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventory as $item)
                <tr>
                    <td>{{ $item->coffee_variety }}</td>
                    <td>{{ $item->grade }}</td>
                    <td>{{ $item->total_quantity_kg }}</td>
                    <td>{{ $item->reserved_quantity_kg }}</td>
                    <td>{{ $item->available_quantity_kg }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection