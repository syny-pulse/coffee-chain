<?php

namespace App\Farmers\Controllers;

use App\Farmers\Services\FarmerOrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(FarmerOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        try {
            $orders = $this->orderService->getAll();
            return view('farmers.orders.index', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Order index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading orders.');
        }
    }

    public function create()
    {
        return view('farmers.orders.create');
    }

    public function store(Request $request)
    {
        try {
            $this->orderService->create($request->all());
            return redirect()->route('farmers.orders.index')->with('success', 'Order created successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Order store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the order.')->withInput();
        }
    }

    public function view($order_id)
    {
        try {
            $order = $this->orderService->find($order_id);
            return view('farmers.orders.view', compact('order'));
        } catch (\Exception $e) {
            Log::error('Order view error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the order.');
        }
    }

    public function edit($order_id)
    {
        try {
            $order = $this->orderService->find($order_id);
            return view('farmers.orders.edit', compact('order'));
        } catch (\Exception $e) {
            Log::error('Order edit error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the order.');
        }
    }

    public function update(Request $request, $order_id)
    {
        try {
            $this->orderService->update($order_id, $request->all());
            return redirect()->route('farmers.orders.index')->with('success', 'Order updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Order update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the order.')->withInput();
        }
    }

    public function destroy($order_id)
    {
        try {
            $this->orderService->delete($order_id);
            return redirect()->route('farmers.orders.index')->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            Log::error('Order delete error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while cancelling the order.');
        }
    }
}