<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class RetailerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->company) {
            return redirect()->route('login')->with('error', 'Please log in to access the retailer dashboard.');
        }
        if ($user->user_type !== 'retailer') {
            return redirect()->route('login')->with('error', 'Access denied. This dashboard is for retailers only.');
        }
        $company = $user->company;
        $notificationService = new \App\Services\NotificationService();
        $notifications = $notificationService->getNotifications($company);

        // --- Real stats ---
        $today = date('Y-m-d');
        $cupsSoldToday = \DB::table('retailer_sales')->where('date', $today)->sum('quantity');
        $stockLevel = \DB::table('retailer_inventory')->sum('quantity');
        $dailyRevenue = \DB::table('retailer_sales as rs')
            ->join('retailer_products as rp', 'rs.product_id', '=', 'rp.product_id')
            ->where('rs.date', $today)
            ->sum(\DB::raw('rs.quantity * rp.price_per_kg'));
        $pendingOrders = \DB::table('retailer_orders')->where('order_status', 'pending')->count();
        $stats = [
            'cups_sold_today' => $cupsSoldToday,
            'stock_level' => $stockLevel,
            'daily_revenue' => $dailyRevenue,
            'pending_orders' => $pendingOrders,
        ];
        return view('retailers.dashboard', compact('notifications', 'stats'));
    }
} 