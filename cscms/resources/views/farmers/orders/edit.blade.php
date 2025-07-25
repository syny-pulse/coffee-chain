@extends('farmers.layouts.app')

@section('title', 'Update Order Status')
@section('page-title', 'Update Order Status')
@section('page-subtitle', 'Change the status of the order')

@section('page-actions')
    <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i>
        Back to Orders
    </a>
@endsection

@section('content')
    <div class="form-container">
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

        <form action="{{ route('processor.order.farmer_order.update', $order->order_id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('farmers.partials.form-field', [
                'name' => 'order_status',
                'label' => 'Order Status',
                'type' => 'select',
                'value' => old('order_status', $order->order_status),
                'required' => true,
                'options' => array_filter(
                    [
                        '' => 'Select Status',
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'shipped' => 'Shipped',
                        'cancelled' => 'Cancelled',
                    ],
                    function ($key) use ($order) {
                        return in_array(
                            $key,
                            array_merge(
                                [$order->order_status],
                                [
                                    'pending' => ['confirmed', 'cancelled'],
                                    'confirmed' => ['shipped', 'cancelled'],
                                    'shipped' => ['cancelled'],
                                    'delivered' => [],
                                    'cancelled' => ['pending'],
                                ][$order->order_status] ?? []))
                            ? true
                            : false;
                    },
                    ARRAY_FILTER_USE_KEY),
            ])

            @if ($order->order_status === 'pending')
                <div class="alert alert-info">
                    Available unreserved quantity for {{ ucfirst($order->coffee_variety) }},
                    {{ ucfirst(str_replace('_', ' ', $order->grade)) }},
                    {{ ucfirst($order->processing_method) }}:
                    {{ \App\Models\Farmer\FarmerHarvest::where('company_id', $order->farmer_company_id)->where('coffee_variety', $order->coffee_variety)->where('processing_method', $order->processing_method)->where('grade', $order->grade)->where('availability_status', 'available')->whereRaw('available_quantity_kg > reserved_quantity_kg')->sum(DB::raw('available_quantity_kg - reserved_quantity_kg')) }}
                    kg
                </div>
            @endif

            @if ($order->order_status === 'confirmed' && $order->harvests->isNotEmpty())
                <div class="form-group">
                    <label>Allocated Harvests</label>
                    <ul>
                        @foreach ($order->harvests as $harvest)
                            <li>Harvest #{{ $harvest->harvest_id }} ({{ ucfirst($harvest->coffee_variety) }},
                                {{ ucfirst(str_replace('_', ' ', $harvest->grade)) }},
                                {{ ucfirst($harvest->processing_method) }}):
                                {{ $harvest->pivot->allocated_quantity_kg }} kg (Reserved:
                                {{ $harvest->reserved_quantity_kg }} kg,
                                Available: {{ $harvest->available_quantity_kg }} kg, Status:
                                {{ ucfirst($harvest->availability_status) }})</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Status
                </button>
                <a href="{{ route('farmers.orders.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
