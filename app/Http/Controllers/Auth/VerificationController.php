<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Show the email verification form.
     */
    public function show()
    {
        return view('auth.verify-email');
    }

    /**
     * Handle email verification with code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        try {
            Auth::user()->verifyEmailWithCode($request->code);
            return redirect()->route('home')->with('success', 'Email verified successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['code' => $e->getMessage()]);
        }
    }

    /**
     * Resend verification code.
     */
    public function resend()
    {
        Auth::user()->sendEmailVerificationNotification();
        return back()->with('info', 'New verification code has been sent to your email!');
    }
}
