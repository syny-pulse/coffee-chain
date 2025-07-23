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
                'link' => route('messages.index'),
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
     * Get notifications for a processor company
     */
    public function getProcessorNotifications(Company $company)
    {
        $notifications = [];

        // Pending farmer orders
        $pendingFarmerOrders = \App\Models\FarmerOrder::where('processor_company_id', $company->company_id)
            ->where('order_status', 'pending')
            ->count();
        if ($pendingFarmerOrders > 0) {
            $notifications[] = [
                'type' => 'pending_farmer_orders',
                'title' => 'Pending Farmer Orders',
                'message' => "You have {$pendingFarmerOrders} pending farmer order(s) to process.",
                'count' => $pendingFarmerOrders,
                'icon' => 'fas fa-clipboard-list',
                'color' => 'warning',
                'link' => route('processor.order.farmer_order.index'),
                'priority' => 'high'
            ];
        }

        // New messages (last 24h)
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
                'link' => route('messages.index'),
                'priority' => 'medium'
            ];
        }

        // Low raw material stock (any inventory < 50kg)
        $lowStock = \App\Models\ProcessorRawMaterialInventory::where('processor_company_id', $company->company_id)
            ->where('available_stock_kg', '<', 50)
            ->count();
        if ($lowStock > 0) {
            $notifications[] = [
                'type' => 'low_stock',
                'title' => 'Critical: Raw Material Stock Low',
                'message' => "One or more raw material inventories are critically low (< 50kg). Please order more from farmers immediately!",
                'count' => $lowStock,
                'icon' => 'fas fa-boxes-stacked',
                'color' => 'danger',
                'link' => route('processor.order.farmer_order.create'),
                'priority' => 'high'
            ];
        }

        // Overdue retailer orders (orders past expected delivery date)
        $overdueRetailerOrders = \App\Models\RetailerOrder::where('processor_company_id', $company->company_id)
            ->where('order_status', 'confirmed')
            ->where('expected_delivery_date', '<', Carbon::now())
            ->count();
        if ($overdueRetailerOrders > 0) {
            $notifications[] = [
                'type' => 'overdue_retailer_orders',
                'title' => 'Overdue Retailer Orders',
                'message' => "You have {$overdueRetailerOrders} retailer order(s) overdue for delivery.",
                'count' => $overdueRetailerOrders,
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'danger',
                'link' => route('processor.order.retailer_order.index'),
                'priority' => 'high'
            ];
        }

        usort($notifications, function($a, $b) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            return $priorityOrder[$b['priority']] <=> $priorityOrder[$a['priority']];
        });
        return $notifications;
    }

    /**
     * Get notifications for a retailer company
     */
    public function getRetailerNotifications(Company $company)
    {
        $notifications = [];

        // Pending retailer orders
        $pendingOrders = \App\Models\RetailerOrder::where('processor_company_id', $company->company_id)
            ->where('order_status', 'pending')
            ->count();
        if ($pendingOrders > 0) {
            $notifications[] = [
                'type' => 'pending_orders',
                'title' => 'Pending Orders',
                'message' => "You have {$pendingOrders} pending order(s) to process.",
                'count' => $pendingOrders,
                'icon' => 'fas fa-clipboard-list',
                'color' => 'warning',
                'link' => route('retailer.orders.index'),
                'priority' => 'high'
            ];
        }

        // New messages (last 24h)
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
                'link' => route('messages.index'),
                'priority' => 'medium'
            ];
        }

        // Low product stock (any product < 20kg)
        $lowStock = \App\Models\RetailerProduct::where('user_id', $company->company_id)
            ->where('quantity_kg', '<', 20)
            ->count();
        if ($lowStock > 0) {
            $notifications[] = [
                'type' => 'low_product_stock',
                'title' => 'Low Product Stock',
                'message' => "One or more products are low in stock (< 20kg).",
                'count' => $lowStock,
                'icon' => 'fas fa-boxes-stacked',
                'color' => 'warning',
                'link' => route('retailer.inventory.index'),
                'priority' => 'medium'
            ];
        }

        // Expiring products (expiry_date within 7 days)
        $expiringProducts = \App\Models\RetailerProduct::where('user_id', $company->company_id)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', Carbon::now())
            ->where('expiry_date', '<=', Carbon::now()->addDays(7))
            ->count();
        if ($expiringProducts > 0) {
            $notifications[] = [
                'type' => 'expiring_products',
                'title' => 'Expiring Products',
                'message' => "You have {$expiringProducts} product(s) expiring within 7 days.",
                'count' => $expiringProducts,
                'icon' => 'fas fa-hourglass-end',
                'color' => 'danger',
                'link' => route('retailer.inventory.index'),
                'priority' => 'medium'
            ];
        }

        usort($notifications, function($a, $b) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            return $priorityOrder[$b['priority']] <=> $priorityOrder[$a['priority']];
        });
        return $notifications;
    }

    /**
     * Get notifications for any company (dispatch by company_type)
     */
    public function getNotifications(Company $company)
    {
        if ($company->company_type === 'farmer') {
            return $this->getFarmerNotifications($company);
        } elseif ($company->company_type === 'processor') {
            return $this->getProcessorNotifications($company);
        } elseif ($company->company_type === 'retailer') {
            return $this->getRetailerNotifications($company);
        }
        return [];
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