<?php

namespace App\Services;

use App\Models\FarmerOrder;
use App\Models\Message;
use App\Models\Company;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Get notifications for a farmer company
     */
    public function getFarmerNotifications(Company $company)
    {
        $notifications = [];

        // Check for pending orders
        $pendingOrders = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->where('order_status', 'pending')
            ->count();

        if ($pendingOrders > 0) {
            $notifications[] = [
                'type' => 'pending_orders',
                'title' => 'Pending Orders',
                'message' => "You have {$pendingOrders} pending order(s) that require your attention.",
                'count' => $pendingOrders,
                'icon' => 'fas fa-clipboard-list',
                'color' => 'warning',
                'link' => route('farmers.orders.index'),
                'priority' => 'high'
            ];
        }

        // Check for new messages (messages received in the last 24 hours)
        $newMessages = Message::where('receiver_company_id', $company->company_id)
            ->where('is_read', false)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        if ($newMessages > 0) {
            $notifications[] = [
                'type' => 'new_messages',
                'title' => 'New Messages',
                'message' => "You have {$newMessages} new message(s) from your partners.",
                'count' => $newMessages,
                'icon' => 'fas fa-envelope',
                'color' => 'info',
                'link' => route('farmers.communication.index'),
                'priority' => 'medium'
            ];
        }

        // Check for overdue deliveries (orders that are past expected delivery date)
        $overdueOrders = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->where('order_status', 'confirmed')
            ->where('expected_delivery_date', '<', Carbon::now())
            ->count();

        if ($overdueOrders > 0) {
            $notifications[] = [
                'type' => 'overdue_deliveries',
                'title' => 'Overdue Deliveries',
                'message' => "You have {$overdueOrders} order(s) that are overdue for delivery.",
                'count' => $overdueOrders,
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'danger',
                'link' => route('farmers.orders.index'),
                'priority' => 'high'
            ];
        }

        // Check for low stock (if available stock is less than 100kg)
        $lowStock = FarmerOrder::where('farmer_company_id', $company->company_id)
            ->where('order_status', 'pending')
            ->sum('quantity_kg');

        $availableStock = \App\Models\Farmer\FarmerHarvest::where('company_id', $company->company_id)
            ->sum('available_quantity_kg');

        if ($availableStock < 100 && $lowStock > 0) {
            $notifications[] = [
                'type' => 'low_stock',
                'title' => 'Low Stock Alert',
                'message' => "Your available stock is low ({$availableStock}kg). Consider adding more harvest data.",
                'count' => 1,
                'icon' => 'fas fa-boxes-stacked',
                'color' => 'warning',
                'link' => route('farmers.harvests.create'),
                'priority' => 'medium'
            ];
        }

        // Sort notifications by priority (high first, then medium, then low)
        usort($notifications, function($a, $b) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            return $priorityOrder[$b['priority']] <=> $priorityOrder[$a['priority']];
        });

        return $notifications;
    }

    /**
     * Get notification count for a farmer company
     */
    public function getNotificationCount(Company $company)
    {
        $count = 0;

        // Count pending orders
        $count += FarmerOrder::where('farmer_company_id', $company->company_id)
            ->where('order_status', 'pending')
            ->count();

        // Count new messages
        $count += Message::where('receiver_company_id', $company->company_id)
            ->where('is_read', false)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        // Count overdue deliveries
        $count += FarmerOrder::where('farmer_company_id', $company->company_id)
            ->where('order_status', 'confirmed')
            ->where('expected_delivery_date', '<', Carbon::now())
            ->count();

        return $count;
    }

    /**
     * Get pending orders count for a farmer company
     */
    public function getPendingOrdersCount(Company $company)
    {
        return FarmerOrder::where('farmer_company_id', $company->company_id)
            ->where('order_status', 'pending')
            ->count();
    }

    /**
     * Get unread messages count for a farmer company
     */
    public function getUnreadMessagesCount(Company $company)
    {
        return Message::where('receiver_company_id', $company->company_id)
            ->where('is_read', false)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();
    }
} 