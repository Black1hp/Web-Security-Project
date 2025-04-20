<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'doRegister'])->name('register.post');
Route::get('/verify', [App\Http\Controllers\Auth\RegisterController::class, 'verify'])->name('verify');
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Password reset routes
Route::get('/password/forgot', [App\Http\Controllers\Auth\RegisterController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/password/email', [App\Http\Controllers\Auth\RegisterController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset', [App\Http\Controllers\Auth\RegisterController::class, 'showResetLink'])->name('ShowRestForm');
Route::post('/password/reset', [App\Http\Controllers\Auth\RegisterController::class, 'resetPassword'])->name('password.update');

// Protected routes that require authentication
Route::middleware(['auth'])->group(function () {
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
