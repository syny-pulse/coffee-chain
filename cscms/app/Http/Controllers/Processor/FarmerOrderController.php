<?php

  namespace App\Http\Controllers\Processor;

  use App\Http\Controllers\Controller;
  use App\Models\FarmerOrder;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;

  class FarmerOrderController extends Controller
  {
      public function index()
      {
          $orders = FarmerOrder::where('farmer_company_id', Auth::id())->get();
          return view('processor.order.farmer_order.index', compact('orders'));
      }

      public function create()
      {
          return view('processor.order.farmer_order.create');
      }

      public function store(Request $request)
      {
          \Log::info('Store method hit with data: ', $request->all());
          if (!Auth::check()) {
              \Log::warning('Unauthenticated attempt to create farmer order');
              return redirect()->route('login')->with('error', 'Please log in as a processor to create orders.');
          }

          $request->validate([
              'coffee_variety' => 'required|in:arabica,robusta',
              'processing_method' => 'required|in:natural,washed,honey',
              'grade' => 'required|in:grade_1,grade_2,grade_3,grade_4,grade_5',
              'quantity_kg' => 'required|numeric|min:1',
              'unit_price' => 'required|numeric|min:0',
              'expected_delivery_date' => 'required|date',
              'notes' => 'nullable|string',
          ]);

          $order = FarmerOrder::create([
              'farmer_company_id' => Auth::id(),
              'coffee_variety' => $request->coffee_variety,
              'processing_method' => $request->processing_method,
              'grade' => $request->grade,
              'quantity_kg' => $request->quantity_kg,
              'unit_price' => $request->unit_price,
              'total_amount' => $request->quantity_kg * $request->unit_price,
              'expected_delivery_date' => $request->expected_delivery_date,
              'notes' => $request->notes,
          ]);

          \Log::info('Order created with ID: ' . $order->order_id);
          return redirect()->route('processor.order.farmer_order.index')->with('success', 'Farmer order created successfully.');
      }

      public function show(FarmerOrder $order)
      {
          if ($order->farmer_company_id != Auth::id()) {
              abort(403);
          }
          return view('processor.order.farmer_order.show', compact('order'));
      }

      public function update(Request $request, FarmerOrder $order)
      {
          if ($order->farmer_company_id != Auth::id()) {
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

          return redirect()->route('processor.order.farmer_order.index')->with('success', 'Farmer order updated successfully.');
      }
  }