<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentsController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});


// Student routes
Route::resource('students', StudentsController::class);


// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Google OAuth routes
Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login')->middleware('throttle:60,1');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->middleware('throttle:60,1');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'doRegister'])->name('register.post');
Route::get('/verify', [RegisterController::class, 'verify'])->name('verify');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Password reset routes
Route::get('/password/forgot', [RegisterController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:5,1')->name('password.email');
Route::get('/password/reset', [RegisterController::class, 'showResetLink'])->name('ShowRestForm');
Route::get('/password/reset/{token}', [RegisterController::class, 'showResetLink'])->name('password.reset');
Route::post('/password/reset', [RegisterController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard')->with('status', 'Your email has been verified!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Protected routes that require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Other protected routes...
    Route::get('/personal-info', function () {
        return view('personal-info');
    });

    // Sidebar navigation routes
    Route::get('/mess-info', function () {
        return redirect('/dashboard')->with('info', 'Mess Info page is under development');
    });

    Route::get('/academics', function () {
        return redirect('/dashboard')->with('info', 'Academics page is under development');
    });

    Route::get('/clubs', function () {
        return redirect('/dashboard')->with('info', 'Clubs page is under development');
    });

    Route::get('/achievements', function () {
        return redirect('/dashboard')->with('info', 'Achievements page is under development');
    });

    Route::get('/research', function () {
        return redirect('/dashboard')->with('info', 'Research work page is under development');
    });

    Route::get('/internships', function () {
        return redirect('/dashboard')->with('info', 'Internships page is under development');
    });

    Route::get('/skills', function () {
        return redirect('/dashboard')->with('info', 'Skills page is under development');
    });

    Route::get('/projects', function () {
        return redirect('/dashboard')->with('info', 'Projects page is under development');
    });

    // Course instructors routes
    Route::get('/instructors', function () {
        return redirect('/dashboard')->with('info', 'Course Instructors page is under development');
    });

    // Notices routes
    Route::get('/notices', function () {
        return redirect('/dashboard')->with('info', 'Notices page is under development');
    });

    Route::get('/notices/payment', function () {
        return redirect('/dashboard')->with('info', 'Payment details page is under development');
    });

    Route::get('/notices/exams', function () {
        return redirect('/dashboard')->with('info', 'Exam schedule page is under development');
    });

    // Courses routes
    Route::get('/courses', function () {
        return redirect('/dashboard')->with('info', 'Courses page is under development');
    });

    Route::get('/courses/oop', function () {
        return redirect('/dashboard')->with('info', 'Object Oriented Programming course page is under development');
    });

    Route::get('/courses/database', function () {
        return redirect('/dashboard')->with('info', 'Database Systems course page is under development');
    });

    // Search route
    Route::get('/search', function () {
        $query = request('query');

        // If empty query, redirect back to dashboard
        if (empty($query)) {
            return redirect()->route('dashboard');
        }

        // Placeholder search results - in a real app, this would query a database
        $results = [
            [
                'type' => 'course',
                'title' => 'Object Oriented Programming',
                'description' => 'A comprehensive course on OOP principles and patterns',
                'url' => '/courses/oop'
            ],
            [
                'type' => 'course',
                'title' => 'Database Systems',
                'description' => 'Introduction to database design and SQL',
                'url' => '/courses/database'
            ],
            [
                'type' => 'notice',
                'title' => 'Exam Schedule',
                'description' => 'View the upcoming exams schedule for this semester',
                'url' => '/notices/exams'
            ],
            [
                'type' => 'notice',
                'title' => 'Fee Payment',
                'description' => 'Details about semester fee payment deadlines',
                'url' => '/notices/payment'
            ],
        ];

        // Filter results based on search query
        $filteredResults = array_filter($results, function($item) use ($query) {
            return stripos($item['title'], $query) !== false ||
                   stripos($item['description'], $query) !== false;
        });

        return view('search', [
            'query' => $query,
            'results' => $filteredResults
        ]);
    })->name('search');
});

// Admin routes
Route::get('/admin/login', [App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login.post');

Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('logout');

    // User management routes
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'showUsers'])->name('users');
    Route::get('/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');

    // New members page route
    Route::get('/members', function () {
        return view('admin.members');
    })->name('members');
});
