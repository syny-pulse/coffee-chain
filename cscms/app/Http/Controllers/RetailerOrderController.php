<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\RetailerInventory;

class RetailerOrderController extends Controller
{
    public function index()
    {
        $orders = DB::table('retailer_order')->orderBy('created_at', 'desc')->get();

        return view('retailers.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'coffee_breed' => 'required|in:arabica,robusta',
            'roast_grade' => 'required|integer|between:1,5',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::table('retailer_order')->insert([
            'coffee_breed' => $data['coffee_breed'],
            'roast_grade' => $data['roast_grade'],
            'quantity' => $data['quantity'],
            'order_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('retailer.orders.index')->with('success', 'Order created successfully.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,delivered,cancelled',
        ]);

        $order = DB::table('retailer_order')->where('id', $id)->first();

        DB::table('retailer_order')->where('id', $id)->update([
            'order_status' => $data['status'],
            'updated_at' => now(),
        ]);

        // If status changed to delivered, increase inventory and record transaction
        if ($order && $order->order_status !== 'delivered' && $data['status'] === 'delivered') {
            $productType = $order->product_type ?? 'drinking_coffee';
            // Increase inventory
            $existingInventory = RetailerInventory::where('product_type', $productType)
                ->where('coffee_breed', $order->coffee_breed)
                ->where('roast_grade', $order->roast_grade)
                ->first();

            if ($existingInventory) {
                $existingInventory->increment('quantity', $order->quantity);
            } else {
                RetailerInventory::create([
                    'product_type' => $productType,
                    'coffee_breed' => $order->coffee_breed,
                    'roast_grade' => $order->roast_grade,
                    'quantity' => $order->quantity,
                ]);
            }
            // Record transaction (optional, if you have a transaction model/table)
            // DB::table('retailer_inventory_transactions')->insert([...]);
        }

        return redirect()->route('retailer.orders.index')->with('success', 'Order status updated successfully.');
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
