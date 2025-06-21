<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FarmerOrder;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        $orders = FarmerOrder::where('farmer_company_id', $company->company_id)->orderByDesc('created_at')->get();
        return view('farmers.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('farmers.orders.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        $request->validate([
            'coffee_variety' => 'required|string',
            'grade' => 'required|string',
            'quantity_kg' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'expected_delivery_date' => 'required|date',
            'order_status' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        FarmerOrder::create([
            'farmer_company_id' => $company->company_id,
            'coffee_variety' => $request->coffee_variety,
            'grade' => $request->grade,
            'quantity_kg' => $request->quantity_kg,
            'unit_price' => $request->unit_price,
            'total_amount' => $request->total_amount,
            'expected_delivery_date' => $request->expected_delivery_date,
            'order_status' => $request->order_status,
            'notes' => $request->notes,
        ]);
        return redirect()->route('farmers.orders.index')->with('success', 'Order created successfully.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $order = FarmerOrder::where('farmer_company_id', $company->company_id)->findOrFail($id);
        return view('farmers.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $order = FarmerOrder::where('farmer_company_id', $company->company_id)->findOrFail($id);
        return view('farmers.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $company = $user->company;
        $request->validate([
            'coffee_variety' => 'required|string',
            'grade' => 'required|string',
            'quantity_kg' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'expected_delivery_date' => 'required|date',
            'actual_delivery_date' => 'nullable|date',
            'order_status' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $order = FarmerOrder::where('farmer_company_id', $company->company_id)->findOrFail($id);
        $order->update([
            'coffee_variety' => $request->coffee_variety,
            'grade' => $request->grade,
            'quantity_kg' => $request->quantity_kg,
            'unit_price' => $request->unit_price,
            'total_amount' => $request->total_amount,
            'expected_delivery_date' => $request->expected_delivery_date,
            'actual_delivery_date' => $request->actual_delivery_date,
            'order_status' => $request->order_status,
            'notes' => $request->notes,
        ]);
        return redirect()->route('farmers.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $company = $user->company;
        $order = FarmerOrder::where('farmer_company_id', $company->company_id)->findOrFail($id);
        $order->delete();
        return redirect()->route('farmers.orders.index')->with('success', 'Order deleted successfully.');
    }
}