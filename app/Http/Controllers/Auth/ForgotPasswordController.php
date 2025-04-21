<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
class ForgotPasswordController extends Controller
{
    /**
     * Show the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
  

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $key = 'reset|' . Str::lower($request->input('email')) . '|' . $request->ip();
        // \Log::info('RateLimiter count', [
        //     'key' => $key,
        //     'attempts' => \RateLimiter::attempts($key)
        // ]);
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'Too many requests. Please try again in 1 minute.']);
        }
        RateLimiter::hit($key, 60);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }
} 