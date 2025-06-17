@extends('farmers.layouts.app')

@section('title', 'Harvests')

@section('content')
    <h1><i class="fas fa-seedling"></i> Harvests</h1>
    <a href="{{ route('farmers.harvests.create') }}" class="btn"><i class="fas fa-plus"></i> Add New Harvest</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Variety</th>
                <th>Grade</th>
                <th>Quantity (kg)</th>
                <th>Available (kg)</th>
                <th>Harvest Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($harvests as $harvest)
                <tr>
                    <td>{{ $harvest->harvest_id }}</td>
                    <td>{{ $harvest->coffee_variety }}</td>
                    <td>{{ $harvest->grade }}</td>
                    <td>{{ $harvest->quantity_kg }}</td>
                    <td>{{ $harvest->available_quantity_kg }}</td>
                    <td>{{ $harvest->harvest_date }}</td>
                    <td>{{ $harvest->availability_status }}</td>
                    <td>
                        <a href="{{ route('farmers.harvests.edit', $harvest->harvest_id) }}" class="btn btn-outline"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('farmers.harvests.destroy', $harvest->harvest_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection