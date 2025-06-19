<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $orders = auth()->check() ? Order::where('seller_id', auth()->id())->orWhere('buyer_id', auth()->id())->get() : collect();
        $customerSegments = $this->segmentCustomers($orders);

        // Dummy analytics data (replace with actual calculations if data exists)
        $analytics = (object) [
            'profit_margin' => 15.5, // Example value in percentage
            'inventory_turnover' => 4.2, // Example value in times/year
            'production_efficiency' => 87.3, // Example value in percentage
        ];

        // Debugging: Log the data being passed to the view
        Log::info('Analytics Data', [
            'orders' => $orders->count(),
            'customerSegments' => $customerSegments,
            'analytics' => $analytics
        ]);

        return view('processor.analytics.index', compact('customerSegments', 'analytics'));
    }

    protected function segmentCustomers($orders)
    {
        $segments = [];
        if ($orders->isEmpty()) {
            return $segments;
        }

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