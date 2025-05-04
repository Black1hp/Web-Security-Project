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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class RegisterController extends Controller
{
    // Advanced registration with verification
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function doRegister(Request $request) {
    $key = 'register|' . \Str::lower($request->input('email')) . '|' . $request->ip();
    if (\RateLimiter::tooManyAttempts($key, 5)) {
        return back()->withErrors(['email' => 'Too many registration attempts. Please try again in 1 minute.']);
    }
    \RateLimiter::hit($key, 60);
    
    try {
        $request->validate([
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->numbers()->letters()->mixedCase()->symbols()],
            'cf-turnstile-response' => ['required'],
        ], [
            'email.unique' => 'This email is already registered. Please try logging in or use a different email.',
        ]);
        
        // Verify Cloudflare Turnstile
        $turnstileResponse = $this->verifyTurnstile($request->input('cf-turnstile-response'), $request->ip());
        
        if (!$turnstileResponse['success']) {
            Log::error('Turnstile verification failed', [
                'errors' => $turnstileResponse['error-codes'] ?? 'Unknown error',
                'ip' => $request->ip()
            ]);
            return redirect()->back()->withInput($request->except('password', 'password_confirmation'))
                                   ->withErrors(['captcha' => 'CAPTCHA verification failed. Please try again.']);
        }
    } catch(\Exception $e) {
        Log::error('Registration error', ['error' => $e->getMessage()]);
        return redirect()->back()->withInput($request->except('password', 'password_confirmation'))
                               ->withErrors('An unexpected error occurred during registration.');
    }
    
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);
    
    // Automatically log the user in
    Auth::login($user);
    $user->assignRole('Student');

    // Send the email verification notification using Laravel's built-in system
    $user->sendEmailVerificationNotification();
    
    return redirect()->route('verification.notice')
                    ->with('status', 'Registration successful! Please check your email to verify your account.');
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

    /**
     * Verify Cloudflare Turnstile token
     *
     * @param string $token The token from the CAPTCHA
     * @param string $ip The user's IP address
     * @return array The response from Cloudflare
     */
    private function verifyTurnstile($token, $ip)
    {
        try {
            // Log the verification attempt for debugging
            Log::info('Attempting Turnstile verification', [
                'ip' => $ip,
                'token_length' => strlen($token),
                'secret_key' => substr(env('CF_TURNSTILE_SECRET'), 0, 5) . '...' // Log only first few chars for security
            ]);
            
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => env('CF_TURNSTILE_SECRET'),
                'response' => $token,
                'remoteip' => $ip
            ]);
            
            $result = $response->json();
            
            // Log the result
            if (!isset($result['success']) || $result['success'] !== true) {
                Log::warning('Turnstile verification failed', [
                    'response' => $result,
                    'ip' => $ip
                ]);
            } else {
                Log::info('Turnstile verification successful');
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Turnstile verification exception', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ['success' => false, 'error-codes' => ['connection_error']];
        }
    }
} 