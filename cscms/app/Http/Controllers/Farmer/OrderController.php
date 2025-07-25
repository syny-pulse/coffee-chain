<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FarmerOrder;
use App\Models\Company;
use App\Models\Pricing;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        $orders = FarmerOrder::with('processor')->where('farmer_company_id', $company->company_id)->orderByDesc('created_at')->get();
        return view('farmers.orders.index', compact('orders'));
    }

    public function create()
    {
        $processors = Company::where('company_type', 'processor')->pluck('company_name', 'company_id');
        return view('farmers.orders.create', compact('processors'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        $request->validate([
            'processor_company_id' => 'required|exists:companies,company_id',
            'coffee_variety' => 'required|string',
            'grade' => 'required|string',
            'quantity_kg' => 'required|numeric|min:0',
            'expected_delivery_date' => 'required|date',
            'order_status' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        // Fetch the latest price from the Pricing table
        $pricing = Pricing::where('company_id', $company->company_id)
            ->where('coffee_variety', strtolower($request->coffee_variety))
            ->where('grade', strtolower(str_replace(' ', '_', $request->grade)))
            ->first();
        $unit_price = $pricing ? $pricing->unit_price : 0;
        $total_amount = $unit_price * $request->quantity_kg;
        FarmerOrder::create([
            'farmer_company_id' => $company->company_id,
            'processor_company_id' => $request->processor_company_id,
            'coffee_variety' => $request->coffee_variety,
            'grade' => $request->grade,
            'quantity_kg' => $request->quantity_kg,
            'unit_price' => $unit_price,
            'total_amount' => $total_amount,
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
        $processors = Company::where('company_type', 'processor')->pluck('company_name', 'company_id');
        return view('farmers.orders.edit', compact('order', 'processors'));
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
