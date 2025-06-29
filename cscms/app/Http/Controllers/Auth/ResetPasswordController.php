<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use Notifiable;

    public function show()
    {
        return view('auth.reset-password');
    }

    public function routeNotificationForMail() {
        return request()->email;
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email','exists:users']
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);

    }
}
