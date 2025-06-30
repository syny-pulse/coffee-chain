<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            // Increase inventory
            $existingInventory = DB::table('retailer_inventory')
                ->where('coffee_breed', $order->coffee_breed)
                ->where('roast_grade', $order->roast_grade)
                ->first();

            if ($existingInventory) {
                DB::table('retailer_inventory')
                    ->where('id', $existingInventory->id)
                    ->increment('quantity', $order->quantity);
            } else {
                DB::table('retailer_inventory')->insert([
                    'coffee_breed' => $order->coffee_breed,
                    'roast_grade' => $order->roast_grade,
                    'quantity' => $order->quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Record transaction
            DB::table('retailer_inventory_transactions')->insert([
                'transaction_type' => 'order_delivered',
                'coffee_breed' => $order->coffee_breed,
                'roast_grade' => $order->roast_grade,
                'quantity' => $order->quantity,
                'notes' => 'Order ID ' . $id . ' marked as delivered',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('retailer.orders.index')->with('success', 'Order status updated successfully.');
    }
}
