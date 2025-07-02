<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\RetailerOrder;
use App\Models\FarmerOrder;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProcessorDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->company) {
            return redirect()->route('login')->with('error', 'Please log in to access the processor dashboard.');
        }

        if ($user->user_type !== 'processor') {
            return redirect()->route('login')->with('error', 'Access denied. This dashboard is for processors only.');
        }

        $companyId = $user->company_id;

        try {
            // Default forecast value
            $forecast = ['predicted_demand' => 1200]; // Fallback value
            try {
                $mlServerUrl = config('services.ml_server.url', 'http://localhost:5000');
                $response = Http::timeout(5)->get("{$mlServerUrl}/api/predict-demand", [
                    'product_name' => 'espresso_blend',
                    'period' => 'monthly',
                ]);
                if ($response->successful()) {
                    $forecast = $response->json();
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch forecast: ' . $e->getMessage());
            }

            // Calculate key metrics for dashboard
            $total_farmer_orders = FarmerOrder::where('processor_company_id', $companyId)->count();
            $total_retailer_orders = RetailerOrder::where('processor_company_id', $companyId)->count();
            $total_inventory_items = Product::where('user_id', $user->id)->count();
            
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
            $total_processed_kg = Product::where('user_id', $user->id)
                ->whereIn('product_type', ['roasted_beans', 'ground_coffee'])
                ->sum('quantity_kg');
            $total_raw_kg = Product::where('user_id', $user->id)
                ->where('product_type', 'green_beans')
                ->sum('quantity_kg');
            
            $processing_efficiency = ($total_raw_kg + $total_processed_kg) > 0 ? 
                round(($total_processed_kg / ($total_raw_kg + $total_processed_kg)) * 100, 1) : 0;

            // Calculate average quality score
            $avg_quality_score = Product::where('user_id', $user->id)
                ->whereNotNull('quality_score')
                ->avg('quality_score');
            $quality_score_percentage = $avg_quality_score ? round(($avg_quality_score / 10) * 100, 1) : 0;

            // Calculate customer satisfaction (simplified - based on order completion rate)
            $customer_satisfaction = $order_fulfillment_rate; // Simplified metric

            // Inventory data
            $inventoryQuery = Product::where('user_id', $user->id)
                ->selectRaw('SUM(CASE WHEN product_type = "green_beans" THEN quantity_kg ELSE 0 END) as raw_material_total')
                ->selectRaw('SUM(CASE WHEN product_type IN ("roasted_beans", "ground_coffee") THEN quantity_kg ELSE 0 END) as finished_goods_total')
                ->selectRaw('AVG(price_per_kg) as avg_cost_per_kg')
                ->selectRaw('AVG(price_per_kg * 1.2) as avg_production_cost')
                ->first();

            $inventory = (object) [
                'raw_material_total' => $inventoryQuery->raw_material_total ?? 0,
                'finished_goods_total' => $inventoryQuery->finished_goods_total ?? 0,
                'avg_cost_per_kg' => $inventoryQuery->avg_cost_per_kg ?? 0,
                'avg_production_cost' => $inventoryQuery->avg_production_cost ?? 0,
            ];

            // Raw materials and finished goods
            $raw_materials = Product::where('user_id', $user->id)
                ->where('product_type', 'green_beans')
                ->get();

            $finished_goods = Product::where('user_id', $user->id)
                ->whereIn('product_type', ['roasted_beans', 'ground_coffee'])
                ->get();

            // Retailer and Farmer Orders
            $retailer_orders = RetailerOrder::where('processor_company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            $farmer_orders = FarmerOrder::where('processor_company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Messages
            $messages = Message::where(function($query) use ($companyId) {
                $query->where('receiver_company_id', $companyId)
                      ->orWhere('sender_company_id', $companyId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

            // Employees
            $employees = Employee::where('processor_company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get companies for messaging (farmers and retailers only)
            $companies = Company::whereIn('company_type', ['farmer', 'retailer'])
                ->where('acceptance_status', 'accepted')
                ->get(['company_id', 'company_name', 'company_type']);

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
                'customer_satisfaction'
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