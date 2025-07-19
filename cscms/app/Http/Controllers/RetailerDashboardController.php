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
        $notificationService = new NotificationService();
        $notifications = $notificationService->getNotifications($company);
        return view('retailers.dashboard', compact('notifications'));
    }
} 