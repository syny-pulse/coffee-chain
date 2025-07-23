<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\RetailerInventory;

class RetailerOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\RetailerOrder::query();
        if ($request->filled('processor_company_id')) {
            $query->where('processor_company_id', $request->processor_company_id);
        }
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $orders = $query->orderByDesc('created_at')->paginate(15)->appends($request->except('page'));
        $processors = \App\Models\Company::where('company_type', 'processor')->where('acceptance_status', 'accepted')->get();
        $products = \App\Models\RetailerProduct::all();
        return view('retailers.orders.index', [
            'orders' => $orders,
            'products' => $products,
            'processors' => $processors,
            'filters' => $request->only(['processor_company_id','product','status','date_from','date_to'])
        ]);
    }

    public function create()
    {
        $processors = \App\Models\Company::where('company_type', 'processor')->where('acceptance_status', 'accepted')->get();
        return view('retailers.orders.create', compact('processors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'processor_company_id' => 'required|exists:companies,company_id',
            'coffee_breed' => 'required|in:arabica,robusta',
            'roast_grade' => 'required|integer|between:1,5',
            'quantity' => 'required|integer|min:1',
            'expected_delivery_date' => 'required|date',
            'shipping_address' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Fetch price per unit from pricings table
        $pricing = DB::table('pricings')
            ->where('company_id', $data['processor_company_id'])
            ->where('coffee_variety', $data['coffee_breed'])
            ->where('grade', 'grade_' . $data['roast_grade'])
            ->first();
        $unitPrice = $pricing ? $pricing->unit_price : 0;
        $totalAmount = $unitPrice * $data['quantity'];
        $orderNumber = 'RO-' . strtoupper(uniqid());
        // Insert order and get ID
        $order = \App\Models\RetailerOrder::create([
            'order_number' => $orderNumber,
            'processor_company_id' => $data['processor_company_id'],
            'retailer_company_id' => \Illuminate\Support\Facades\Auth::user()->company_id,
            'expected_delivery_date' => $data['expected_delivery_date'],
            'shipping_address' => $data['shipping_address'],
            'notes' => $data['notes'] ?? null,
            'order_status' => 'pending',
            'total_amount' => $totalAmount,
        ]);
        // Insert corresponding item
        \App\Models\RetailerOrderItem::create([
            'order_id' => $order->order_id,
            'recipe_id' => 1, // Use 1 as a default valid recipe_id
            'product_name' => $pricing && isset($pricing->product_type) ? $pricing->product_type : 'drinking_coffee',
            'product_variant' => 'Standard',
            'quantity_units' => $data['quantity'],
            'unit_price' => $unitPrice,
            'line_total' => $totalAmount,
        ]);

        return redirect()->route('retailer.orders.index')->with('success', 'Order created successfully.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,delivered,cancelled',
        ]);

        $order = \App\Models\RetailerOrder::findOrFail($id);
        $order->update([
            'order_status' => $data['status'],
        ]);

        // If status changed to delivered, update actual delivery date
        if ($order->order_status !== 'delivered' && $data['status'] === 'delivered') {
            $order->update([
                'actual_delivery_date' => now(),
            ]);
        }

        return redirect()->route('retailer.orders.index')->with('success', 'Order status updated successfully.');
    }

    public function show($id)
    {
        $order = \App\Models\RetailerOrder::findOrFail($id);
        $processors = \App\Models\Company::where('company_type', 'processor')->where('acceptance_status', 'accepted')->get();
        return view('retailers.orders.show', compact('order', 'processors'));
    }

    public function destroy($id)
    {
        $order = \App\Models\RetailerOrder::findOrFail($id);
        $order->delete();
        return redirect()->route('retailer.orders.index')->with('success', 'Order deleted.');
    }

    public function getPrediction(Request $request)
    {
        $product = $request->input('product');
        $month = $request->input('month');
        $year = $request->input('year');
        // Example ML API call
        $mlServerUrl = config('services.ml_server.url', 'http://localhost:5000');
        $response = Http::get("{$mlServerUrl}/api/predict-demand", [
            'product_name' => $product,
            'month' => $month,
            'year' => $year,
        ]);
        if ($response->successful()) {
            return response()->json(['prediction' => $response->json('predicted_demand')]);
        }
        return response()->json(['prediction' => null, 'error' => 'ML server unavailable'], 500);
    }
}
