<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Check if user is pending approval
        if ($user->isPending()) {
            return view('dashboard.pending', compact('user'));
        }
        
        switch ($user->user_type) {
            case 'farmer':
                return $this->farmerDashboard();
            case 'processor':
                return $this->processorDashboard();
            case 'retailer':
                return $this->retailerDashboard();
            case 'admin':
                return $this->adminDashboard();
            default:
                return redirect()->route('login');
        }
    }

    private function farmerDashboard()
    {
        $user = Auth::user();
        // Add farmer-specific data here
        return view('dashboard.farmer', compact('user'));
    }

    private function processorDashboard()
    {
        $user = Auth::user();
        // Add processor-specific data here
        return view('dashboard.processor', compact('user'));
    }

    private function retailerDashboard()
    {
        $user = Auth::user();
        // Add retailer-specific data here
        return view('dashboard.retailer', compact('user'));
    }

    private function adminDashboard()
    {
        $user = Auth::user();
        // Add admin-specific data here
        return view('dashboard.admin', compact('user'));
    }
}