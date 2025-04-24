<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
   

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $key = Str::lower($request->input('email')) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'Too many login attempts. Please try again in 1 minute.']);
        }
        RateLimiter::hit($key, 60);

        // Check if user is an admin
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // Check if user is a regular user
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Redirect the user to Google's OAuth consent screen.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google after OAuth consent.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            // Retrieve Google user data with state validation
            $googleUser = Socialite::driver('google')->user();

            // Find user by google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Check if email is already registered
                $existingUser = User::where('email', $googleUser->getEmail())->first();
                if ($existingUser) {
                    Log::warning('Google login attempted with registered email', [
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                    ]);
                    return redirect()->route('login')->withErrors([
                        'email' => 'This email is already registered. Please link your Google account in settings or use your existing login method.',
                    ]);
                }

                // Validate avatar URL
                $avatar = $googleUser->getAvatar();
                if ($avatar && !Str::startsWith($avatar, 'https://lh3.googleusercontent.com/')) {
                    $avatar = null;
                }

                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $avatar,
                    'email_verified_at' => now(),
                    'password' => null,
                ]);

                Log::info('New user created via Google login', [
                    'email' => $user->email,
                    'google_id' => $user->google_id,
                ]);
            } elseif ($user->email !== $googleUser->getEmail()) {
                // Handle email mismatch (e.g., Google account email changed)
                Log::warning('Google login email mismatch', [
                    'user_id' => $user->id,
                    'google_email' => $googleUser->getEmail(),
                    'stored_email' => $user->email,
                ]);
                return redirect()->route('login')->withErrors([
                    'email' => 'The email associated with this Google account has changed. Please update your account settings.',
                ]);
            }

            // Log in the user and regenerate session
            Auth::login($user, true);
            session()->regenerate();

            Log::info('User logged in via Google', [
                'email' => $user->email,
                'google_id' => $user->google_id,
            ]);

            return redirect()->route('dashboard');
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::warning('Invalid OAuth state during Google login', [
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('login')->withErrors([
                'email' => 'Invalid login attempt. Please try again.',
            ]);
        } catch (\Exception $e) {
            Log::error('Google login failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to login with Google. Please try again later.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
} 