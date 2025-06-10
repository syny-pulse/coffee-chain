<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Logout route (only for authenticated users)
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Role-specific routes
    Route::middleware(['role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
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

    Route::middleware(['role:processor'])->prefix('processor')->name('processor.')->group(function () {
        Route::get('/production', function () {
            return view('processor.production');
        })->name('production');
        
        Route::get('/orders', function () {
            return view('processor.orders');
        })->name('orders');
        
        Route::get('/inventory', function () {
            return view('processor.inventory');
        })->name('inventory');
    });

    Route::middleware(['role:retailer'])->prefix('retailer')->name('retailer.')->group(function () {
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

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', function () {
            return view('admin.users');
        })->name('users');
        
        Route::get('/companies', function () {
            return view('admin.companies');
        })->name('companies');
        
        Route::get('/analytics', function () {
            return view('admin.analytics');
        })->name('analytics');
        
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
    });
    
    // Profile routes
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
});