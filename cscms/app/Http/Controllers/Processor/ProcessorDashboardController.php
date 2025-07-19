<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\RetailerOrder;
use App\Models\FarmerOrder;
use App\Models\Message;
use App\Models\ProcessorRawMaterialInventory;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;

class ProcessorDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access the processor dashboard.');
        }

        if ($user->user_type !== 'processor') {
            return redirect()->route('dashboard')->with('error', 'Access denied. This dashboard is for processors only.');
        }

        if (!$user->company) {
            return redirect()->route('login')->with('error', 'Company information not found. Please contact administrator.');
        }

        $companyId = $user->company_id;
        $company = $user->company;
        $notificationService = new NotificationService();
        $notifications = $notificationService->getNotifications($company);

        try {
            // Default forecast value
            $forecast = ['predicted_demand' => 1200]; // Fallback value

            // Calculate key metrics for dashboard (with error handling)
            $total_farmer_orders = 0;
            $total_retailer_orders = 0;
            $total_inventory_items = 0;
            $total_revenue = 0;
            $order_fulfillment_rate = 0;
            $processing_efficiency = 0;
            $quality_score_percentage = 85.0;
            $customer_satisfaction = 0;

            try {
                $total_farmer_orders = FarmerOrder::where('processor_company_id', $companyId)->count();
                $total_retailer_orders = RetailerOrder::where('processor_company_id', $companyId)->count();
                $total_inventory_items = ProcessorRawMaterialInventory::where('processor_company_id', $companyId)->count();
                
                // Calculate total revenue from delivered retailer orders
                $total_revenue = RetailerOrder::where('processor_company_id', $companyId)
                    ->where('order_status', 'delivered')
                    ->sum('total_amount');

                // Calculate performance indicators
                $delivered_retailer_orders = RetailerOrder::where('processor_company_id', $companyId)
                    ->where('order_status', 'delivered')
                    ->count();
                $total_retailer_orders_count = RetailerOrder::where('processor_company_id', $companyId)->count();
                
                $order_fulfillment_rate = $total_retailer_orders_count > 0 ? 
                    round(($delivered_retailer_orders / $total_retailer_orders_count) * 100, 1) : 0;

                // Calculate processing efficiency based on inventory turnover
                $total_processed_kg = ProcessorRawMaterialInventory::where('processor_company_id', $companyId)
                    ->whereIn('coffee_variety', ['arabica', 'robusta'])
                    ->sum('quantity_kg');
                $total_raw_kg = ProcessorRawMaterialInventory::where('processor_company_id', $companyId)
                    ->sum('quantity_kg');
                
                $processing_efficiency = ($total_raw_kg + $total_processed_kg) > 0 ? 
                    round(($total_processed_kg / ($total_raw_kg + $total_processed_kg)) * 100, 1) : 0;

                // Calculate customer satisfaction (simplified - based on order completion rate)
                $customer_satisfaction = $order_fulfillment_rate; // Simplified metric
            } catch (\Exception $e) {
                Log::error('Error calculating dashboard metrics: ' . $e->getMessage());
            }

            // Inventory data
            $inventory = (object) [
                'raw_material_total' => 0,
                'finished_goods_total' => 0,
                'avg_cost_per_kg' => 0,
                'avg_production_cost' => 0,
            ];

            try {
                $inventoryQuery = ProcessorRawMaterialInventory::where('processor_company_id', $companyId)
                    ->selectRaw('SUM(quantity_kg) as raw_material_total')
                    ->selectRaw('AVG(price_per_kg) as avg_cost_per_kg')
                    ->first();

                $inventory->raw_material_total = $inventoryQuery->raw_material_total ?? 0;
                $inventory->avg_cost_per_kg = $inventoryQuery->avg_cost_per_kg ?? 0;
                $inventory->avg_production_cost = ($inventoryQuery->avg_cost_per_kg ?? 0) * 1.2;
            } catch (\Exception $e) {
                Log::error('Error calculating inventory data: ' . $e->getMessage());
            }

            // Raw materials
            $raw_materials = collect([]);
            $finished_goods = collect([]);

            try {
                $raw_materials = ProcessorRawMaterialInventory::where('processor_company_id', $companyId)->get();
            } catch (\Exception $e) {
                Log::error('Error fetching raw materials: ' . $e->getMessage());
            }

            // Retailer and Farmer Orders
            $retailer_orders = collect([]);
            $farmer_orders = collect([]);

            try {
                $retailer_orders = RetailerOrder::where('processor_company_id', $companyId)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $farmer_orders = FarmerOrder::where('processor_company_id', $companyId)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } catch (\Exception $e) {
                Log::error('Error fetching orders: ' . $e->getMessage());
            }

            // Messages
            $messages = collect([]);

            try {
                $messages = Message::where(function($query) use ($companyId) {
                    $query->where('receiver_company_id', $companyId)
                          ->orWhere('sender_company_id', $companyId);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            } catch (\Exception $e) {
                Log::error('Error fetching messages: ' . $e->getMessage());
            }

            // Employees
            $employees = collect([]);

            try {
                $employees = Employee::where('processor_company_id', $companyId)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } catch (\Exception $e) {
                Log::error('Error fetching employees: ' . $e->getMessage());
            }

            // Get companies for messaging (farmers and retailers only)
            $companies = collect([]);

            try {
                $companies = Company::whereIn('company_type', ['farmer', 'retailer'])
                    ->where('acceptance_status', 'accepted')
                    ->get(['company_id', 'company_name', 'company_type']);
            } catch (\Exception $e) {
                Log::error('Error fetching companies: ' . $e->getMessage());
            }

            // Customer segmentation based on retailer orders
            $customerSegments = $this->segmentCustomers($retailer_orders);

            return view('processor.dashboard', compact(
                'forecast',
                'inventory',
                'raw_materials',
                'finished_goods',
                'retailer_orders',
                'farmer_orders',
                'messages',
                'employees',
                'companies',
                'customerSegments',
                'total_farmer_orders',
                'total_retailer_orders',
                'total_inventory_items',
                'total_revenue',
                'order_fulfillment_rate',
                'processing_efficiency',
                'quality_score_percentage',
                'customer_satisfaction',
                'notifications'
            ));

        } catch (\Exception $e) {
            Log::error('ProcessorDashboard Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the dashboard. Please try again.');
        }
    }

    protected function segmentCustomers(Collection $orders)
    {
        if ($orders->isEmpty()) {
            return [
                'New' => [
                    'description' => 'No orders yet',
                    'recommendation' => 'Start building relationships with retailers'
                ]
            ];
        }

        $segments = [];
        
        // Group by company and calculate total amount
        $ordersByCompany = $orders->groupBy('processor_company_id')
            ->map(function ($companyOrders) {
                return $companyOrders->sum('total_amount');
            });

        foreach ($ordersByCompany as $companyId => $totalAmount) {
            if ($totalAmount < 500) {
                $segments["Small Volume"] = [
                    'description' => 'Orders under $500',
                    'recommendation' => 'Offer volume discounts and introductory packages'
                ];
            } elseif ($totalAmount < 2000) {
                $segments["Medium Volume"] = [
                    'description' => 'Orders between $500-$2000',
                    'recommendation' => 'Provide loyalty rewards and premium products'
                ];
            } else {
                $segments["High Volume"] = [
                    'description' => 'Orders over $2000',
                    'recommendation' => 'Offer VIP services and priority processing'
                ];
            }
        }

        return $segments;
    }
}