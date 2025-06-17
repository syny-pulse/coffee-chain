<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\RetailerOrder;
use App\Models\FarmerOrder;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ProcessorDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Default forecast value
        $forecast = ['error' => 'Unable to fetch forecast data'];
        try {
            $mlServerUrl = config('services.ml_server.url', 'http://localhost:5000');
            $response = Http::timeout(5)->get("{$mlServerUrl}/api/predict-demand", [
                'product_name' => 'espresso_blend',
                'period' => 'monthly',
            ]);
            $response->throw();
            $forecast = $response->json();
            $forecast['predicted_demand'] = $forecast['predicted_demand'] ?? 1200; // Fallback
        } catch (RequestException | \Exception $e) {
            \Log::error('Failed to fetch forecast: ' . $e->getMessage());
            $forecast['predicted_demand'] = 1200; // Fallback value
        }

        // Inventory data
        $userId = Auth::check() ? Auth::id() : 0; // Default to 0 for testing
        $inventoryQuery = Product::where('user_id', $userId)
            ->selectRaw('SUM(CASE WHEN product_type = "green_beans" THEN quantity_kg ELSE 0 END) as raw_material_total')
            ->selectRaw('SUM(CASE WHEN product_type IN ("roasted_beans", "ground_coffee") THEN quantity_kg ELSE 0 END) as finished_goods_total')
            ->selectRaw('AVG(price_per_kg) as avg_cost_per_kg')
            ->selectRaw('AVG(price_per_kg * 1.2) as avg_production_cost')
            ->first();

        $inventory = (object) [
            'raw_material_total' => $inventoryQuery->raw_material_total ?? 1000,
            'finished_goods_total' => $inventoryQuery->finished_goods_total ?? 0,
            'avg_cost_per_kg' => $inventoryQuery->avg_cost_per_kg ?? 0,
            'avg_production_cost' => $inventoryQuery->avg_production_cost ?? 0,
        ];

        // Raw materials and finished goods
        $raw_materials = Auth::check()
            ? Product::where('user_id', Auth::id())->where('product_type', 'green_beans')->get()
            : collect([(object) ['name' => 'Sample Arabica', 'product_type' => 'green_beans', 'quantity_kg' => 500, 'price_per_kg' => 5.0]]);

        $finished_goods = Auth::check()
            ? Product::where('user_id', Auth::id())->whereIn('product_type', ['roasted_beans', 'ground_coffee'])->get()
            : collect([(object) ['name' => 'Sample Roasted', 'product_type' => 'roasted_beans', 'quantity_kg' => 200, 'price_per_kg' => 10.0]]);

        // Retailer and Farmer Orders
        $retailer_orders = Auth::check()
            ? RetailerOrder::where('seller_id', Auth::id())->orWhere('buyer_id', Auth::id())->get()
            : collect([(object) ['order_number' => 'RET001', 'total_amount' => 1000, 'status' => 'pending']]);

        $farmer_orders = Auth::check()
            ? FarmerOrder::where('seller_id', Auth::id())->orWhere('buyer_id', Auth::id())->get()
            : collect([(object) ['order_number' => 'FARM001', 'total_amount' => 500, 'status' => 'pending']]);

        // Full customer segmentation (using retailer orders for simplicity)
        $segmentDetails = $this->segmentCustomers($retailer_orders);

        // Simplified names for view display
        $customerSegments = array_keys($segmentDetails);

        // Messages
        $messages = Auth::check()
            ? Message::where('receiver_id', Auth::id())->orWhere('sender_id', Auth::id())->latest()->get()
            : collect([(object) ['subject' => 'Sample Message', 'sender_id' => 0, 'is_read' => false]]);

        // Employees
        $employees = Auth::check()
            ? Employee::where('processor_company_id', Auth::id())->get()
            : collect([
                (object) [
                    'name' => 'John Doe',
                    'employee_id' => 'EMP001',
                    'position' => 'Grading',
                    'shift' => 'Morning',
                    'status' => 'active'
                ]
            ]);

        // Users for messages
        $users = Auth::check()
            ? User::where('id', '!=', Auth::id())->get(['id', 'name'])
            : collect([(object) ['id' => 1, 'name' => 'Sample User']]);

        return view('processor.dashboard', compact(
            'forecast', 'inventory', 'raw_materials', 'finished_goods',
            'retailer_orders', 'farmer_orders', 'messages', 'employees', 'users', 'customerSegments'
        ));
    }

    protected function segmentCustomers(Collection $orders)
    {
        $segments = [];
        if ($orders->isEmpty()) {
            return $segments;
        }

        // Simple segmentation based on total amount spent
        $totalSpent = $orders->groupBy('buyer_id')->map(function ($group) {
            return $group->sum('total_amount');
        });

        foreach ($totalSpent as $buyerId => $amount) {
            if ($amount < 500) {
                $segments["Low Spenders (ID: $buyerId)"] = [
                    'description' => 'Customers spending less than $500',
                    'recommendation' => 'Offer discounts or introductory bundles',
                ];
            } elseif ($amount >= 500 && $amount < 2000) {
                $segments["Medium Spenders (ID: $buyerId)"] = [
                    'description' => 'Customers spending $500-$2000',
                    'recommendation' => 'Suggest premium products or loyalty rewards',
                ];
            } else {
                $segments["High Spenders (ID: $buyerId)"] = [
                    'description' => 'Customers spending over $2000',
                    'recommendation' => 'Provide exclusive offers or personalized support',
                ];
            }
        }

        return $segments;
    }
}