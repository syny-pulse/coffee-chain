<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share notification data with all farmer views
        View::composer('farmers.*', function ($view) {
            if (Auth::check() && Auth::user()->company && Auth::user()->role === 'farmer') {
                $notificationService = new NotificationService();
                $notificationCount = $notificationService->getNotificationCount(Auth::user()->company);
                $pendingOrdersCount = $notificationService->getPendingOrdersCount(Auth::user()->company);
                $unreadMessagesCount = $notificationService->getUnreadMessagesCount(Auth::user()->company);
                
                $view->with([
                    'notificationCount' => $notificationCount,
                    'pendingOrdersCount' => $pendingOrdersCount,
                    'unreadMessagesCount' => $unreadMessagesCount
                ]);
            }
        });
    }
}
