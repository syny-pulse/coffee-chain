<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\RetailerOrder;
use App\Models\RetailerOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetailerOrderController extends Controller
{
    public function index()
    {
        $orders = RetailerOrder::where('processor_company_id', Auth::id())->get();
        return view('processor.order.retailer_order.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        return view('processor.order.retailer_order.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|unique:retailer_orders,order_number',
            'shipping_address' => 'required|string',
            'expected_delivery_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_units' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity_units'] * $item['unit_price'];
        }

        $order = RetailerOrder::create([
            'order_number' => $request->order_number,
            'processor_company_id' => Auth::id(),
            'total_amount' => $totalAmount,
            'expected_delivery_date' => $request->expected_delivery_date,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            RetailerOrderItem::create([
                'order_id' => $order->order_id,
                'product_id' => $item['product_id'],
                'product_name' => $product->name,
                'product_variant' => 'Standard', // Add logic for variants if needed
                'quantity_units' => $item['quantity_units'],
                'unit_price' => $item['unit_price'],
                'line_total' => $item['quantity_units'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('processor.order.retailer_order.index')->with('success', 'Retailer order created successfully.');
    }

    public function show(RetailerOrder $order)
    {
        if ($order->processor_company_id != Auth::id()) {
            abort(403);
        }
        return view('processor.order.retailer_order.show', compact('order'));
    }

    public function update(Request $request, RetailerOrder $order)
    {
        if ($order->processor_company_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'actual_delivery_date' => 'nullable|date|required_if:order_status,delivered',
        ]);

        $order->update([
            'order_status' => $request->order_status,
            'actual_delivery_date' => $request->actual_delivery_date,
        ]);

        return redirect()->route('processor.order.retailer_order.index')->with('success', 'Retailer order updated successfully.');
    }
}