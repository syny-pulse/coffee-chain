<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Processor\ProcessorDashboardController;
use App\Http\Controllers\Processor\InventoryController;
use App\Http\Controllers\Processor\RetailerOrderController;
use App\Http\Controllers\Processor\FarmerOrderController;
use App\Http\Controllers\Processor\MessageController;
use App\Http\Controllers\Processor\AnalyticsController;
use App\Http\Controllers\Processor\EmployeeController;

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
    
    Route::resource('farmer_order', FarmerOrderController::class)->names([
        'index' => 'processor.order.farmer_order.index',
        'create' => 'processor.order.farmer_order.create',
        'store' => 'processor.order.farmer_order.store',
        'show' => 'processor.order.farmer_order.show',
        'edit' => 'processor.order.farmer_order.edit',
        'update' => 'processor.order.farmer_order.update',
        'destroy' => 'processor.order.farmer_order.destroy',
    ]);
    
    Route::resource('message', MessageController::class)->names([
        'index' => 'processor.message.index',
        'create' => 'processor.message.create',
        'store' => 'processor.message.store',
        'show' => 'processor.message.show',
        'edit' => 'processor.message.edit',
        'update' => 'processor.message.update',
        'destroy' => 'processor.message.destroy',
    ]);
    
    Route::get('/analytics.index', [AnalyticsController::class, 'index'])->name('processor.analytics.index');
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// Logout route
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// General dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 

// Role-specific routes
Route::middleware(['auth'])->group(function () {

    // Farmer routes
    Route::prefix('farmer')->name('farmer.')->group(function () {

        Route::get('/inventory', function () {
            return view('farmer.inventory');
        })->name('inventory');

        Route::get('/orders', function () {
            return view('farmer.orders');
        })->name('orders');

        Route::get('/analytics', function () {
            return view('farmer.analytics');
        })->name('analytics');
    });

    // Processor extra routes
    Route::prefix('processor')->name('processor.')->group(function () {
        Route::get('/production', function () {
            return view('processor.production');
        })->name('production');

    });

    // Retailer routes
    Route::prefix('retailer')->name('retailer.')->group(function () {
        Route::get('/orders', function () {
            return view('retailer.orders');
        })->name('orders');

        Route::get('/inventory', function () {
            return view('retailer.inventory');
        })->name('inventory');

        Route::get('/customers', function () {
            return view('retailer.customers');
        })->name('customers');
    });

    // Admin routes (commented out)
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', function () {
            return view('admin.users');
        })->name('users');

        
        Route::get('/companies', function () {

            return view('admin.companies');
        })->name('companies');

        Route::get('/analytics', function () {
            return view('admin.analytics');
        })->name('analytics');

        
        Route::get('/settings', function() {
            return view('admin.settings');
        })->name('settings');
    });
    

    // Profile route

    Route::get('/profile', function () {
        return view('profile.show');  
    })->name('profile.show');

 });

