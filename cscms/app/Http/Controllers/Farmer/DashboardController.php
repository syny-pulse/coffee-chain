<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\FarmerOrder;
use App\Models\Farmer\FarmerHarvest;
use App\Models\Message;
use App\Services\NotificationService;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        // Stats
        $total_harvest = FarmerHarvest::where('company_id', $company->company_id)->sum('quantity_kg');
        $available_stock = FarmerHarvest::where('company_id', $company->company_id)->sum('available_quantity_kg');
        $total_revenue = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->whereIn('order_status', ['delivered', 'confirmed'])
            ->sum('total_amount');
        $pending_orders = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->where('order_status', 'pending')
            ->count();

        $stats = [
            'total_harvest' => $total_harvest,
            'available_stock' => $available_stock,
            'total_revenue' => $total_revenue,
            'pending_orders' => $pending_orders
        ];

        // Calculate trends (compare this month to last month)
        $startOfThisMonth = now()->startOfMonth();
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        // Harvest trend
        $harvest_this = FarmerHarvest::where('company_id', $company->company_id)
            ->where('harvest_date', '>=', $startOfThisMonth)
            ->sum('quantity_kg');
        $harvest_last = FarmerHarvest::where('company_id', $company->company_id)
            ->whereBetween('harvest_date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('quantity_kg');
        $harvest_trend = $harvest_last > 0 ? round((($harvest_this - $harvest_last) / $harvest_last) * 100, 1) : ($harvest_this > 0 ? 100 : 0);

        // Stock trend
        $stock_this = FarmerHarvest::where('company_id', $company->company_id)
            ->where('created_at', '>=', $startOfThisMonth)
            ->sum('available_quantity_kg');
        $stock_last = FarmerHarvest::where('company_id', $company->company_id)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('available_quantity_kg');
        $stock_trend = $stock_last > 0 ? round((($stock_this - $stock_last) / $stock_last) * 100, 1) : ($stock_this > 0 ? 100 : 0);

        // Revenue trend
        $revenue_this = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->whereIn('order_status', ['delivered', 'confirmed'])
            ->where('created_at', '>=', $startOfThisMonth)
            ->sum('total_amount');
        $revenue_last = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->whereIn('order_status', ['delivered', 'confirmed'])
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_amount');
        $revenue_trend = $revenue_last > 0 ? round((($revenue_this - $revenue_last) / $revenue_last) * 100, 1) : ($revenue_this > 0 ? 100 : 0);

        // Pending orders trend
        $pending_this = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->where('order_status', 'pending')
            ->where('created_at', '>=', $startOfThisMonth)
            ->count();
        $pending_last = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->where('order_status', 'pending')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();
        $pending_trend = $pending_last > 0 ? ($pending_this - $pending_last) : $pending_this;

        $trends = [
            'total_harvest' => $harvest_trend,
            'available_stock' => $stock_trend,
            'total_revenue' => $revenue_trend,
            'pending_orders' => $pending_trend
        ];

        // Recent activity (last 4 events, any type)
        $activities = [];

        // Fetch latest 4 orders
        foreach (FarmerOrder::where('farmer_company_id', $company->company_id)->orderByDesc('created_at')->take(4)->get() as $order) {
            if (!$order->id) continue;
            $activities[] = [
                'type' => 'order',
                'title' => 'Order from ' . ($order->notes ?? 'a buyer'),
                'time' => $order->created_at,
                'human_time' => $order->created_at->diffForHumans(),
                'id' => $order->id,
                'link' => route('farmers.orders.show', ['order' => $order->id])
            ];
        }
        // Fetch latest 4 harvests
        foreach (FarmerHarvest::where('company_id', $company->company_id)->orderByDesc('created_at')->take(4)->get() as $harvest) {
            if (!$harvest->id) continue;
            $activities[] = [
                'type' => 'harvest',
                'title' => 'Harvest: ' . $harvest->quantity_kg . 'kg ' . ucfirst($harvest->coffee_variety),
                'time' => $harvest->created_at,
                'human_time' => $harvest->created_at->diffForHumans(),
                'id' => $harvest->id,
                'link' => route('farmers.harvests.show', ['id' => $harvest->id])
            ];
        }
        // Fetch latest 4 payments (delivered orders)
        foreach (FarmerOrder::where('farmer_company_id', $company->company_id)->where('order_status', 'delivered')->orderByDesc('updated_at')->take(4)->get() as $order) {
            if (!$order->id) continue;
            $activities[] = [
                'type' => 'payment',
                'title' => 'Payment received: UGX ' . number_format($order->total_amount) . ' from last shipment',
                'time' => $order->updated_at,
                'human_time' => $order->updated_at->diffForHumans(),
                'id' => $order->id,
                'link' => route('farmers.orders.show', ['order' => $order->id])
            ];
        }
        // Fetch latest 4 messages
        foreach (Message::where('receiver_company_id', $company->company_id)->orderByDesc('created_at')->take(4)->get() as $msg) {
            $activities[] = [
                'type' => 'message',
                'title' => 'Message from ' . ($msg->senderCompany->company_name ?? 'a partner'),
                'time' => $msg->created_at,
                'human_time' => $msg->created_at->diffForHumans(),
                'id' => $msg->id,
                'link' => route('farmers.communication.index') . '#msg-' . $msg->id
            ];
        }
        // Sort all activities by actual timestamp desc
        usort($activities, function($a, $b) {
            return $b['time']->timestamp <=> $a['time']->timestamp;
        });
        $recent_activity = array_slice($activities, 0, 4);

        // Get notifications
        $notificationService = new NotificationService();
        $notifications = $notificationService->getFarmerNotifications($company);
        $notificationCount = $notificationService->getNotificationCount($company);
        $pendingOrdersCount = $notificationService->getPendingOrdersCount($company);
        $unreadMessagesCount = $notificationService->getUnreadMessagesCount($company);

        // Only use the new dashboard view and pass all required data
        return view('farmers.dashboard', compact('user', 'stats', 'trends', 'recent_activity', 'notifications', 'notificationCount', 'pendingOrdersCount', 'unreadMessagesCount'));
    }
}