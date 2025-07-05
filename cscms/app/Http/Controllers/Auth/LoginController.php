<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Attempt to log the user in
        if (Auth::attempt($this->credentials($request), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user account is active
            if (!$user->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is not active. Please contact support.',
                ]);
            }

            // Check company acceptance status for non-processor users
            if ($user->user_type !== 'processor' && $user->company && !$user->company->isAccepted()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your company registration is still pending approval.',
                ]);
            }

            // Redirect based on user type
            switch ($user->user_type) {
                case 'farmer':
                    return redirect()->intended('/farmers/dashboard');
                case 'processor':
                    return redirect()->intended('/processor/dashboard');
                case 'retailer':
                    return redirect()->intended('/retailer/dashboard');
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                default:
                    return redirect()->intended('/dashboard');
            }
        }

        // If login fails, increment login attempts
        $this->incrementLoginAttempts($request);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    }

    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    protected function incrementLoginAttempts(Request $request)
    {
        // You can implement rate limiting here if needed
        // For now, we'll keep it simple
    }
}
