<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Processor\ProcessorDashboardController;
use App\Http\Controllers\Processor\InventoryController;
use App\Http\Controllers\Processor\RetailerOrderController;
use App\Http\Controllers\Processor\FarmerOrderController;
use App\Http\Controllers\Processor\ReportController;
use App\Http\Controllers\Processor\CompanyController;
use App\Http\Controllers\Processor\WorkDistributionController;
use App\Http\Controllers\Processor\AnalyticsController;
use App\Http\Controllers\Processor\EmployeeController;
use App\Http\Controllers\Farmer\DashboardController as FarmerDashboardController;
use App\Http\Controllers\Farmer\HarvestController;
use App\Http\Controllers\Farmer\InventoryController as FarmerInventoryController;
use App\Http\Controllers\Farmer\OrderController;
use App\Http\Controllers\Farmer\FinancialController;
use App\Http\Controllers\Farmer\AnalyticsController as FarmerAnalyticsController;
use App\Http\Controllers\MessageController;








Route::get('/', function () {
    return view('welcome');
})->name('welcome');



// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    //Reset password
    Route::get('/forgot-password', [ResetPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [ResetPasswordController::class, 'send'])->name('password.send');

    // change password
    Route::get('/change-password/{token}', [ChangePasswordController::class, 'show'])->name('password.reset');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.update');
});

// Logout route
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// General dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Role-specific routes
Route::middleware(['auth'])->group(function () {

    // Processor routes
    Route::prefix('processor')->group(function () {
        Route::get('/dashboard', [ProcessorDashboardController::class, 'index'])->name('processor.dashboard');

        Route::resource('employee', EmployeeController::class)->names([
            'index' => 'processor.employee.index',
            'create' => 'processor.employee.create',
            'store' => 'processor.employee.store',
            'show' => 'processor.employee.show',
            'edit' => 'processor.employee.edit',
            'update' => 'processor.employee.update',
            'destroy' => 'processor.employee.destroy',
        ]);

        Route::resource('inventory', InventoryController::class)->names([
            'index' => 'processor.inventory.index',
            'create' => 'processor.inventory.create',
            'store' => 'processor.inventory.store',
            'show' => 'processor.inventory.show',
            'edit' => 'processor.inventory.edit',
            'update' => 'processor.inventory.update',
            'destroy' => 'processor.inventory.destroy',
        ]);

        Route::resource('retailer_order', RetailerOrderController::class)->names([
            'index' => 'processor.order.retailer_order.index',
            'create' => 'processor.order.retailer_order.create',
            'store' => 'processor.order.retailer_order.store',
            'show' => 'processor.order.retailer_order.show',
            'edit' => 'processor.order.retailer_order.edit',
            'update' => 'processor.order.retailer_order.update',
            'destroy' => 'processor.order.retailer_order.destroy',
        ]);

        // Custom routes must come BEFORE the resource route to avoid conflicts
        // AJAX: Get price for selected farmer, variety, and grade
        Route::get('farmer_order/get-price', [FarmerOrderController::class, 'getPrice'])->name('processor.order.farmer_order.getPrice');

        // AJAX: Get farmers by coffee variety
        Route::get('farmer_order/get-farmers-by-variety', [FarmerOrderController::class, 'getFarmersByVariety'])->name('processor.order.farmer_order.getFarmersByVariety');

        Route::resource('farmer_order', FarmerOrderController::class)->names([
            'index' => 'processor.order.farmer_order.index',
            'create' => 'processor.order.farmer_order.create',
            'store' => 'processor.order.farmer_order.store',
            'show' => 'processor.order.farmer_order.show',
            'edit' => 'processor.order.farmer_order.edit',
            'update' => 'processor.order.farmer_order.update',
            'destroy' => 'processor.order.farmer_order.destroy',
        ]);

        Route::get('/analytics.index', [AnalyticsController::class, 'index'])->name('processor.analytics.index');
        Route::get('/company', [CompanyController::class, 'index'])->name('processor.company.index');
        Route::post('/company/{companyId}/acceptance-status', [CompanyController::class, 'updateAcceptanceStatus'])->name('processor.company.updateAcceptanceStatus');
        Route::post('/company/user/{userId}/account-status', [CompanyController::class, 'updateAccountStatus'])->name('processor.company.updateAccountStatus');
        Route::get('/work_distribution', [WorkDistributionController::class, 'index'])->name('processor.work.index');

        // Report routes
        Route::get('/reports/application', [ReportController::class, 'application'])->name('processor.reports.application');
    });

    // Farmer routes
    Route::prefix('farmers')->group(function () {
        Route::get('/dashboard', [FarmerDashboardController::class, 'index'])->name('farmers.dashboard');
        Route::resource('harvests', HarvestController::class)->names('farmers.harvests')->except(['show']);
        Route::get('/harvests/{id}', [HarvestController::class, 'show'])->name('farmers.harvests.show');
        Route::get('/inventory', [FarmerInventoryController::class, 'index'])->name('farmers.inventory.index');
        Route::get('/inventory/{id}', [FarmerInventoryController::class, 'show'])->name('farmers.inventory.show');
        Route::get('/inventory/{id}/edit', [FarmerInventoryController::class, 'edit'])->name('farmers.inventory.edit');
        Route::delete('/inventory/{id}', [FarmerInventoryController::class, 'destroy'])->name('farmers.inventory.destroy');
        Route::resource('orders', OrderController::class)->names('farmers.orders');
        Route::get('/financials', [FinancialController::class, 'index'])->name('farmers.financials.index');
        Route::get('/financials/pricing', [FinancialController::class, 'pricing'])->name('farmers.financials.pricing');
        Route::post('/financials/pricing', [FinancialController::class, 'updatePricing'])->name('farmers.financials.pricing.update');
        Route::get('/financials/reports', [FinancialController::class, 'reports'])->name('farmers.financials.reports');
        Route::get('/financials/expenses', [FinancialController::class, 'expenses'])->name('farmers.financials.expenses');
        Route::get('/financials/cashflow', [FinancialController::class, 'cashflow'])->name('farmers.financials.cashflow');
        Route::get('/financials/forecasting', [FinancialController::class, 'forecasting'])->name('farmers.financials.forecasting');
        Route::get('/analytics/reports', [FarmerAnalyticsController::class, 'reports'])->name('farmers.analytics.reports');

        // Notification routes
        Route::post('/notifications/mark-read', function () {
            $user = Auth::user();
            if ($user && $user->role === 'farmer' && $user->company) {
                // Mark recent messages as read
                \App\Models\Message::where('receiver_company_id', $user->company->company_id)
                    ->where('is_read', false)
                    ->where('created_at', '>=', \Carbon\Carbon::now()->subDay())
                    ->update(['is_read' => true]);

                // Get updated counts
                $notificationService = new \App\Services\NotificationService();
                $pendingOrdersCount = $notificationService->getPendingOrdersCount($user->company);
                $unreadMessagesCount = $notificationService->getUnreadMessagesCount($user->company);

                return response()->json([
                    'success' => true,
                    'pendingOrdersCount' => $pendingOrdersCount,
                    'unreadMessagesCount' => $unreadMessagesCount
                ]);
            }
            return response()->json(['success' => false], 403);
        })->name('farmers.notifications.mark-read');
    });

    // Retailer routes
    Route::prefix('retailer')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\RetailerDashboardController::class, 'index'])->name('retailer.dashboard');

        Route::get('/orders', function () {
            return view('retailer.orders');
        })->name('retailer.orders');

        Route::get('/orders/create', [\App\Http\Controllers\RetailerOrderController::class, 'create'])->name('retailer.orders.create');

        Route::get('/inventory', function () {
            return view('retailer.inventory');
        })->name('retailer.inventory');

        Route::get('/customers', function () {
            return view('retailer.customers');
        })->name('retailer.customers');

        // Product recipes routes
        Route::get('/product-recipes', [\App\Http\Controllers\RetailerProductRecipeController::class, 'index'])->name('retailer.product_recipes.index');
        Route::get('/product-recipes/create', [\App\Http\Controllers\RetailerProductRecipeController::class, 'create'])->name('retailer.product_recipes.create');
        Route::post('/product-recipes', [\App\Http\Controllers\RetailerProductRecipeController::class, 'store'])->name('retailer.product_recipes.store');
        Route::get('/product-recipes/{id}', [\App\Http\Controllers\RetailerProductRecipeController::class, 'show'])->name('retailer.product_recipes.show');
        Route::get('/product-recipes/{id}/edit', [\App\Http\Controllers\RetailerProductRecipeController::class, 'edit'])->name('retailer.product_recipes.edit');
        Route::put('/product-recipes/{id}', [\App\Http\Controllers\RetailerProductRecipeController::class, 'update'])->name('retailer.product_recipes.update');
        Route::delete('/product-recipes/{id}', [\App\Http\Controllers\RetailerProductRecipeController::class, 'destroy'])->name('retailer.product_recipes.destroy');
        Route::post('/product-recipes/create-retailer-product', [\App\Http\Controllers\RetailerProductRecipeController::class, 'createRetailerProduct'])->name('retailer.product_recipes.createRetailerProduct');

        // Retailer sales routes
        Route::get('/sales', [\App\Http\Controllers\RetailerSalesController::class, 'index'])->name('retailer.sales.index');
        Route::post('/sales', [\App\Http\Controllers\RetailerSalesController::class, 'store'])->name('retailer.sales.store');
        Route::get('/sales/create', [\App\Http\Controllers\RetailerSalesController::class, 'create'])->name('retailer.sales.create');

        // Retailer orders routes
        Route::get('/orders', [\App\Http\Controllers\RetailerOrderController::class, 'index'])->name('retailer.orders.index');
        Route::post('/orders', [\App\Http\Controllers\RetailerOrderController::class, 'store'])->name('retailer.orders.store');
        Route::put('/orders/{id}', [\App\Http\Controllers\RetailerOrderController::class, 'update'])->name('retailer.orders.update');
        Route::get('/orders/{id}', [\App\Http\Controllers\RetailerOrderController::class, 'show'])->name('retailer.orders.show');
        Route::delete('/orders/{id}', [\App\Http\Controllers\RetailerOrderController::class, 'destroy'])->name('retailer.orders.destroy');

        // Retailer inventory routes
        Route::get('/inventory', [\App\Http\Controllers\RetailerInventoryController::class, 'index'])->name('retailer.inventory.index');
        // Retailer inventory adjustment route
        Route::post('/inventory/adjust', [\App\Http\Controllers\RetailerInventoryController::class, 'storeAdjustment'])->name('retailer.inventory.adjust');

        // Retailer communication routes
        Route::get('/communication', function () {
            return view('retailers.communication.index');
        })->name('retailer.communication.index');

        // Retailer analytics routes
        Route::get('/analytics', [\App\Http\Controllers\RetailerAnalyticsController::class, 'index'])->name('retailer.analytics.index');
        Route::get('/analytics/export/{type}', [\App\Http\Controllers\RetailerAnalyticsController::class, 'export'])->name('retailer.analytics.export');

        // Retailer order ML prediction route
        Route::get('/orders/predict', [\App\Http\Controllers\RetailerOrderController::class, 'getPrediction'])->name('retailer.orders.predict');
        // Retailer demand planning route
        Route::get('/demand-planning', [\App\Http\Controllers\RetailerAnalyticsController::class, 'demandPlanning'])->name('retailer.demand_planning.index');

        // Retailer product info management
        Route::get('/product-info', [\App\Http\Controllers\RetailerProductInfoController::class, 'index'])->name('retailer.product_info.index');
        Route::get('/product-info/create', [\App\Http\Controllers\RetailerProductInfoController::class, 'create'])->name('retailer.product_info.create');
        Route::post('/product-info', [\App\Http\Controllers\RetailerProductInfoController::class, 'store'])->name('retailer.product_info.store');
        Route::get('/product-info/{id}/edit', [\App\Http\Controllers\RetailerProductInfoController::class, 'edit'])->name('retailer.product_info.edit');
        Route::put('/product-info/{id}', [\App\Http\Controllers\RetailerProductInfoController::class, 'update'])->name('retailer.product_info.update');
        Route::delete('/product-info/{id}', [\App\Http\Controllers\RetailerProductInfoController::class, 'destroy'])->name('retailer.product_info.destroy');
        Route::get('/product-info/{id}/analytics', [\App\Http\Controllers\RetailerProductInfoController::class, 'productAnalytics'])->name('retailer.product_info.analytics');

        // Retailer financials
        Route::get('/financials', [\App\Http\Controllers\RetailerFinancialsController::class, 'index'])->name('retailer.financials.index');
        Route::get('/financials/invoices', [\App\Http\Controllers\RetailerFinancialsController::class, 'invoices'])->name('retailer.financials.invoices');
        Route::get('/financials/payments', [\App\Http\Controllers\RetailerFinancialsController::class, 'payments'])->name('retailer.financials.payments');
        Route::post('/financials/invoices/store', [\App\Http\Controllers\RetailerFinancialsController::class, 'storeInvoice'])->name('retailer.financials.invoices.store');
        Route::post('/financials/payments/store', [\App\Http\Controllers\RetailerFinancialsController::class, 'storePayment'])->name('retailer.financials.payments.store');
        Route::get('/financials/profit-loss', [\App\Http\Controllers\RetailerFinancialsController::class, 'profitLoss'])->name('retailer.financials.profit_loss');
        Route::post('/financials/invoices/{id}/mark-paid', [\App\Http\Controllers\RetailerFinancialsController::class, 'markInvoicePaid'])->name('retailer.financials.invoices.mark_paid');
        Route::get('/financials/invoices/{id}', [\App\Http\Controllers\RetailerFinancialsController::class, 'showInvoice'])->name('retailer.financials.invoices.show');
        Route::get('/financials/invoices/export', [\App\Http\Controllers\RetailerFinancialsController::class, 'exportInvoices'])->name('retailer.financials.invoices.export');
        Route::get('/financials/invoices/{id}/download', [\App\Http\Controllers\RetailerFinancialsController::class, 'downloadInvoicePdf'])->name('retailer.financials.invoices.download');
    });

    // Unified messaging routes for all roles
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/messages/{id}/reply', [MessageController::class, 'replyForm'])->name('messages.reply');
    Route::post('/messages/mark-all-read', [App\Http\Controllers\MessageController::class, 'markAllRead'])->name('messages.mark-all-read')->middleware('auth');
    Route::get('/messages/thread/{companyId}', [App\Http\Controllers\MessageController::class, 'thread'])->name('messages.thread');


    // Profile route
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
 });
