<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\FarmerOrder;
use App\Models\ProcessorRawMaterialInventory;
use App\Models\Pricing;
use App\Models\Company;
use App\Models\Farmer\FarmerHarvest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FarmerOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        $orders = FarmerOrder::where('processor_company_id', $companyId)->get();
        return view('processor.order.farmer_order.index', compact('orders'));
    }

    public function create()
    {
        // No need to pass farmers here, will be loaded via AJAX
        return view('processor.order.farmer_order.create');
    }

    // Add this method to fetch farmers by coffee variety
    public function getFarmersByVariety(Request $request)
    {
        $request->validate([
            'coffee_variety' => 'required|in:arabica,robusta',
        ]);

        // Find farmers who have pricing for the selected variety
        $farmers = Company::where('company_type', 'farmer')
            ->where('acceptance_status', 'accepted')
            ->whereHas('pricings', function($q) use ($request) {
                $q->where('coffee_variety', $request->coffee_variety);
            })
            ->select('company_id', 'company_name')
            ->get();

        return response()->json(['farmers' => $farmers]);
    }

    public function store(Request $request)
    {
        Log::info('Store method hit with data: ', $request->all());
        if (!Auth::check()) {
            Log::warning('Unauthenticated attempt to create farmer order');
            return redirect()->route('login')->with('error', 'Please log in as a processor to create orders.');
        }

        $request->validate([
            'farmer_company_id' => 'required|exists:companies,company_id',
            'coffee_variety' => 'required|in:arabica,robusta',
            'processing_method' => 'required|in:natural,washed,honey',
            'grade' => 'required|in:grade_1,grade_2,grade_3,grade_4,grade_5',
            'quantity_kg' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:0',
            'expected_delivery_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $order = FarmerOrder::create([
            'processor_company_id' => Auth::user()->company_id,
            'farmer_company_id' => $request->farmer_company_id,
            'coffee_variety' => $request->coffee_variety,
            'processing_method' => $request->processing_method,
            'grade' => $request->grade,
            'quantity_kg' => $request->quantity_kg,
            'unit_price' => $request->unit_price,
            'total_amount' => $request->quantity_kg * $request->unit_price,
            'expected_delivery_date' => $request->expected_delivery_date,
            'order_status' => 'pending', // Always set to pending
            'notes' => $request->notes,
        ]);

        Log::info('Order created with ID: ' . $order->order_id);
        return redirect()->route('processor.order.farmer_order.index')->with('success', 'Farmer order created successfully.');
    }

    public function show($order_id)
    {
        $user = Auth::user();
        $order = FarmerOrder::where('order_id', $order_id)
            ->where('processor_company_id', $user->company_id)
            ->firstOrFail();

        return view('processor.order.farmer_order.show', compact('order'));
    }

    public function edit($order_id)
    {
        $user = Auth::user();
        $order = FarmerOrder::where('order_id', $order_id)
            ->where('processor_company_id', $user->company_id)
            ->firstOrFail();

        return view('processor.order.farmer_order.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = FarmerOrder::findOrFail($id);
        $previous_status = $order->order_status;
        $user_role = Auth::user()->user_type;

        // Define allowed status transitions
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'], // From pending
            'confirmed' => ['shipped', 'cancelled'], // From confirmed
            'shipped' => ['delivered', 'cancelled'], // From shipped
            'delivered' => [], // No transitions from delivered
            'cancelled' => ['pending'], // From cancelled
        ];

        // Role-based status restrictions
        $roleStatusRestrictions = [
            'farmer' => ['pending', 'confirmed', 'shipped', 'cancelled'],
            'processor' => ['pending', 'delivered', 'cancelled'],
        ];

        // Validate status and role
        $request->validate([
            'order_status' => [
                'required',
                'in:' . implode(',', array_unique(array_merge([$previous_status], $allowedTransitions[$previous_status] ?? []))),
                function ($attribute, $value, $fail) use ($user_role, $roleStatusRestrictions, $previous_status) {
                    if (!in_array($value, $roleStatusRestrictions[$user_role])) {
                        $fail("Invalid status for $user_role: $value");
                    }
                    if ($value === 'confirmed' && $user_role !== 'farmer') {
                        $fail('Only farmers can confirm orders.');
                    }
                },
            ],
        ]);

        // Role-based validation for processor fields (only in pending status)
        if ($user_role === 'processor' && $order->order_status === 'pending') {
            $request->validate([
                'quantity_kg' => 'required|numeric|min:0.01',
                'unit_price' => 'required|numeric|min:0.01',
                'expected_delivery_date' => 'required|date',
                'actual_delivery_date' => 'nullable|date',
                'notes' => 'nullable|string',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $order, $previous_status, $user_role) {
                if ($request->order_status === 'confirmed' && $previous_status !== 'confirmed') {
                    // Only farmers can reach this due to validation
                    $availableHarvests = FarmerHarvest::where([
                        'company_id' => $order->farmer_company_id,
                        'coffee_variety' => $order->coffee_variety,
                        'grade' => $order->grade,
                        'availability_status' => 'available',
                    ])
                    ->whereRaw('available_quantity_kg > reserved_quantity_kg')
                    ->orderBy('harvest_date', 'asc')
                    ->get();

                    $totalUnreserved = $availableHarvests->sum(function ($harvest) {
                        return $harvest->available_quantity_kg - $harvest->reserved_quantity_kg;
                    });

                    $quantityToCheck = $user_role === 'processor' && $order->order_status === 'pending' ? $request->quantity_kg : $order->quantity_kg;
                    if ($totalUnreserved < $quantityToCheck) {
                        throw new \Exception("Insufficient unreserved harvest quantity ($totalUnreserved kg available, $quantityToCheck kg required).");
                    }

                    // Allocate harvests
                    $remainingQty = $quantityToCheck;
                    foreach ($availableHarvests as $harvest) {
                        if ($remainingQty <= 0) break;
                        $availableForThisHarvest = $harvest->available_quantity_kg - $harvest->reserved_quantity_kg;
                        $allocate = min($availableForThisHarvest, $remainingQty);
                        if ($allocate > 0) {
                            $order->harvests()->attach($harvest->harvest_id, ['allocated_quantity_kg' => $allocate]);
                            $harvest->reserved_quantity_kg += $allocate;
                            $harvest->availability_status = ($harvest->available_quantity_kg <= 0) ? 'sold_out' :
                                ($harvest->available_quantity_kg == $harvest->reserved_quantity_kg ? 'reserved' :
                                ($harvest->reserved_quantity_kg > 0 ? 'reserved' : 'available'));
                            $harvest->save();
                            $remainingQty -= $allocate;
                        }
                    }
                } elseif ($request->order_status === 'cancelled' && $previous_status === 'confirmed') {
                    // Allow both farmer and processor to cancel
                    foreach ($order->harvests as $harvest) {
                        $allocatedQty = $harvest->pivot->allocated_quantity_kg;
                        $harvest->reserved_quantity_kg -= $allocatedQty;
                        $harvest->availability_status = ($harvest->available_quantity_kg > $harvest->reserved_quantity_kg) ? 'available' :
                            ($harvest->available_quantity_kg <= 0 ? 'sold_out' : 'reserved');
                        $harvest->save();
                    }
                    $order->harvests()->detach();
                } elseif ($request->order_status === 'delivered' && $previous_status !== 'delivered') {
                    if ($user_role !== 'processor') {
                        throw new \Exception('Only processors can set the status to delivered.');
                    }

                    foreach ($order->harvests as $harvest) {
                        $allocatedQty = $harvest->pivot->allocated_quantity_kg;
                        $harvest->reserved_quantity_kg -= $allocatedQty;
                        $harvest->available_quantity_kg -= $allocatedQty;
                        $harvest->availability_status = ($harvest->available_quantity_kg <= 0) ? 'sold_out' :
                            ($harvest->available_quantity_kg == $harvest->reserved_quantity_kg ? 'reserved' :
                            ($harvest->reserved_quantity_kg > 0 ? 'reserved' : 'available'));
                        $harvest->save();
                    }

                    // Update processor raw material inventory
                    $inventory = ProcessorRawMaterialInventory::where([
                        'processor_company_id' => $order->processor_company_id,
                        'coffee_variety' => $order->coffee_variety,
                        'processing_method' => $order->processing_method,
                        'grade' => $order->grade,
                    ])->first();

                    if ($inventory) {
                        $inventory->current_stock_kg += $order->quantity_kg;
                        $inventory->available_stock_kg += $order->quantity_kg;
                        $inventory->last_updated = now();
                        $inventory->save();
                    } else {
                        ProcessorRawMaterialInventory::create([
                            'processor_company_id' => $order->processor_company_id,
                            'coffee_variety' => $order->coffee_variety,
                            'processing_method' => $order->processing_method,
                            'grade' => $order->grade,
                            'current_stock_kg' => $order->quantity_kg,
                            'reserved_stock_kg' => 0,
                            'available_stock_kg' => $order->quantity_kg,
                            'average_cost_per_kg' => $order->unit_price,
                            'last_updated' => now(),
                        ]);
                    }
                    $order->actual_delivery_date = now();
                }

                // Update order fields
                $updateData = ['order_status' => $request->order_status];
                if ($user_role === 'processor' && $order->order_status === 'pending') {
                    $updateData = array_merge($updateData, [
                        'quantity_kg' => $request->quantity_kg,
                        'unit_price' => $request->unit_price,
                        'expected_delivery_date' => $request->expected_delivery_date,
                        'actual_delivery_date' => $request->actual_delivery_date,
                        'notes' => $request->notes,
                        'total_amount' => $request->quantity_kg * $request->unit_price,
                    ]);
                }
                $order->update($updateData);
            });

            // Redirect to appropriate dashboard
            if ($user_role === 'farmer') {
                return redirect()->route('farmers.inventory.index')->with('success', 'Order updated successfully.');
            } elseif ($user_role === 'processor') {
                return redirect()->route('processor.order.farmer_order.index')->with('success', 'Order updated successfully.');
            }
        } catch (\Exception $e) {
            return back()->withErrors(['order_status' => $e->getMessage()]);
        }
    }
    //

    public function getPrice(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                Log::error('User not authenticated for getPrice request');
                return response()->json(['error' => 'Authentication required.'], 401);
            }

            Log::info('getPrice method called by user:', ['user_id' => Auth::id(), 'user_type' => Auth::user()->user_type]);

            $request->validate([
                'farmer_company_id' => 'required|exists:companies,company_id',
                'coffee_variety' => 'required|in:arabica,robusta',
                'grade' => 'required|in:grade_1,grade_2,grade_3,grade_4,grade_5',
            ]);

            $coffee_variety = strtolower($request->coffee_variety);
            $grade = strtolower($request->grade);

            // Log the search parameters for debugging
            Log::info('Searching for pricing:', [
                'company_id' => $request->farmer_company_id,
                'coffee_variety' => $coffee_variety,
                'grade' => $grade
            ]);

            $pricing = Pricing::where('company_id', $request->farmer_company_id)
                ->where('coffee_variety', $coffee_variety)
                ->where('grade', $grade)
                ->first();

            if ($pricing) {
                Log::info('Pricing found:', ['unit_price' => $pricing->unit_price]);
                return response()->json(['unit_price' => $pricing->unit_price]);
            } else {
                Log::warning('No pricing found for:', [
                    'company_id' => $request->farmer_company_id,
                    'coffee_variety' => $coffee_variety,
                    'grade' => $grade
                ]);
                return response()->json(['error' => 'No pricing found for the selected options.'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error in getPrice:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'An error occurred while fetching the price.'], 500);
        }
    }
}
