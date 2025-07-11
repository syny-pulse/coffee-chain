<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\FarmerOrder;
use App\Models\ProcessorRawMaterialInventory;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        try {
            DB::beginTransaction();

            // Create the farmer order
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
                'order_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Query eligible employees (primary_station: grading or roasting, status: active, availability_status: not on_leave)
            $eligibleEmployees = Employee::where('processor_company_id', Auth::user()->company_id)
                ->where('status', 'active')
                ->whereNotIn('availability_status', ['on_leave'])
                ->whereIn('primary_station', ['grading', 'roasting'])
                ->get();

            if ($eligibleEmployees->isEmpty()) {
                DB::commit();
                Log::warning('No eligible employees found for farmer order ID: ' . $order->order_id);
                return redirect()->route('processor.order.farmer_order.index')
                    ->with('warning', 'Farmer order created successfully, but no eligible employees available for assignment.');
            }

            // Priority 1: Employees with no farmer orders
            $noOrdersEmployees = $eligibleEmployees->filter(function ($employee) {
                return $employee->farmerOrders()->count() === 0;
            });

            // Priority 2: Employees with only delivered farmer orders
            $deliveredOrdersEmployees = $eligibleEmployees->filter(function ($employee) {
                $activeOrders = $employee->farmerOrders()
                    ->whereNotIn('order_status', ['delivered', 'cancelled'])
                    ->count();
                return $activeOrders === 0 && $employee->farmerOrders()->count() > 0;
            });

            // Priority 3: Any eligible employee (including those with active orders)
            $selectedEmployee = null;

            if (!$noOrdersEmployees->isEmpty()) {
                // Randomly select from employees with no orders
                $selectedEmployee = $noOrdersEmployees->shuffle()->first();
            } elseif (!$deliveredOrdersEmployees->isEmpty()) {
                // Randomly select from employees with only delivered orders
                $selectedEmployee = $deliveredOrdersEmployees->shuffle()->first();
            } else {
                // Randomly select from any eligible employee
                $selectedEmployee = $eligibleEmployees->shuffle()->first();
            }

            // Assign the employee to the order
            $order->employee_id = $selectedEmployee->employee_id;
            $order->save();

            // // Update employee availability
            // $selectedEmployee->availability_status = 'busy';
            // $selectedEmployee->save();

            DB::commit();
            Log::info('Farmer order ID: ' . $order->order_id . ' assigned to employee ID: ' . $selectedEmployee->employee_id);
            return redirect()->route('processor.order.farmer_order.index')
                ->with('success', 'Farmer order created and assigned to ' . $selectedEmployee->employee_name . ' successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create or assign farmer order: ' . $e->getMessage());
            return redirect()->route('processor.order.farmer_order.index')
                ->with('error', 'Failed to create or assign farmer order.');
        }
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

    public function update(Request $request, FarmerOrder $order)
    {
        $user = Auth::user();
        if ($order->processor_company_id != $user->company_id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $request->validate([
            'quantity_kg' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0.01',
            'expected_delivery_date' => 'required|date',
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'actual_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $previous_status = $order->order_status;
        $order->update([
            'quantity_kg' => $request->quantity_kg,
            'unit_price' => $request->unit_price,
            'total_amount' => $request->quantity_kg * $request->unit_price,
            'expected_delivery_date' => $request->expected_delivery_date,
            'order_status' => $request->order_status,
            'actual_delivery_date' => $request->actual_delivery_date,
            'notes' => $request->notes,
        ]);

        // If status changed to confirmed, update processor raw material inventory
        if ($previous_status !== 'confirmed' && $request->order_status === 'confirmed') {
            $inventory = ProcessorRawMaterialInventory::where([
                'processor_company_id' => $order->processor_company_id,
                'coffee_variety' => $order->coffee_variety,
                'processing_method' => $order->processing_method,
                'grade' => $order->grade,
            ])->first();
            if ($inventory) {
                $inventory->current_stock_kg += $order->quantity_kg;
                $inventory->reserved_stock_kg += $order->quantity_kg;
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
                    'reserved_stock_kg' => $order->quantity_kg,
                    'available_stock_kg' => $order->quantity_kg,
                    'average_cost_per_kg' => $order->unit_price,
                    'last_updated' => now(),
                ]);
            }
        }

        return redirect()->route('processor.order.farmer_order.index')->with('success', 'Farmer order updated successfully.');
    }

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
