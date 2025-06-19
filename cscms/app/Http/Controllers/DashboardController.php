<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Farmer\FarmerHarvest; // Adjust based on your model
use App\Models\Farmer\FarmerOrder;   // Adjust based on your model
use App\Models\Farmer\Message;   // Adjust based on your model

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Redirect based on user type
        if ($user->user_type === 'farmer') {
            return redirect()->route('farmers.dashboard');
        } elseif ($user->user_type === 'processor') {
            return redirect()->route('processor.dashboard');
        } elseif ($user->user_type === 'retailer') {
            return redirect()->route('retailer.dashboard');
        } elseif ($user->user_type === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            // Default fallback - show a general dashboard or redirect to login
            return redirect()->route('login');
        }
    }
}