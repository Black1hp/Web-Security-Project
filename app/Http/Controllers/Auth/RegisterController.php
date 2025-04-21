<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Mail\VerificationEmail;
use App\Mail\ForgetPassEmail;
class RegisterController extends Controller
{
    // Advanced registration with verification
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function doRegister(Request $request) {
    $key = 'register|' . \Str::lower($request->input('email')) . '|' . $request->ip();
    // \Log::info('RateLimiter count', [
    //     'key' => $key,
    //     'attempts' => \RateLimiter::attempts($key)
    // ]);
    if (\RateLimiter::tooManyAttempts($key, 5)) {
        return back()->withErrors(['email' => 'Too many registration attempts. Please try again in 1 minute.']);
    }
    \RateLimiter::hit($key, 60);
        try {
            $request->validate([
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', PasswordRule::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        } catch(\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); 
        $user->save();

        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("verify", ['token' => $token]);
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));
        return redirect('/');
    }

    public function verify(Request $request) {
        try {
            $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        } catch (\Exception $e) {
            return response('Invalid or expired verification link.', 400);
        }
    
        $user = User::where('id', $decryptedData['id'])
                    ->where('email', $decryptedData['email'])
                    ->first();
    
        if (!$user) {
            return response('User not found or already verified.', 404);
        }
    
        if ($user->email_verified_at) {
            return view('users.verified', compact('user'))->with('status', 'Your email is already verified.');
        }
    
        $user->email_verified_at = Carbon::now();
        $user->save();
    
        return view('users.verified', compact('user'))->with('status', 'Your email has been successfully verified.');
    }

    public function showForgotForm() {
        return view('auth.forgot-password');
    }

    
    public function sendResetLink(Request $request) {
    $key = \Str::lower($request->input('email')) . '|' . $request->ip();
    if (\RateLimiter::tooManyAttempts($key, 5)) {
        return back()->withErrors(['email' => 'Too many reset attempts. Please try again in 1 minute.']);
    }
    \RateLimiter::hit($key, 60);
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'This email is not registered in our system.']);
        }
        if ($user->email_verified_at == null) {
            return back()->withErrors(['email' => 'Your email is not verified.']);
        }
        $title = "Reset Password Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("ShowRestForm", ['token' => $token]);
        Mail::to($user->email)->send(new ForgetPassEmail($link, $user->name));
        return redirect()->route('login')->with('status', 'Password reset link sent to your email.');
    }

    public function showResetLink(Request $request) {
        $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        $user = User::find($decryptedData['id']);
        if(!$user) abort(401);
        return view('auth.reset-password', compact('user'));
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'password' => ['required', 'confirmed', PasswordRule::min(8)->numbers()->letters()->mixedCase()->symbols()],
            'token' => ['required'], 
        ]);
        $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        $user = User::find($decryptedData['id']);
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid or expired token.']);
        }
        $user->password = bcrypt($request->password); 
        $user->save();
        return redirect()->route('login')->with('status', 'Password reset successfully.');
    }
} 