<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->isActive()) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated.',
            ]);
        }

        // Check if user has required role
        if (!in_array($user->user_type, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        // Redirect to appropriate dashboard based on user type
        if ($request->is('dashboard')) {
            switch ($user->user_type) {
                case 'farmer':
                    return redirect()->route('farmers.dashboard');
                case 'processor':
                    return redirect()->route('processor.dashboard');
                case 'retailer':
                    return redirect()->route('retailer.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}