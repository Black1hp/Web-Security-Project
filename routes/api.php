<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\FinancialRecordController;
use App\Http\Controllers\API\GradeController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\StudentRequestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Email Verification Routes
Route::post('/email/verify', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
Route::post('/email/resend', 'App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');

// Course Registration Routes
Route::middleware('auth:api')->group(function () {
    // Course Routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    
    // Course Registration Routes
    Route::post('/courses/{id}/register', [CourseController::class, 'register']);
    Route::post('/courses/{id}/drop', [CourseController::class, 'drop']);
    Route::post('/courses/{id}/waitlist', [CourseController::class, 'joinWaitlist']);
    Route::delete('/courses/{id}/waitlist', [CourseController::class, 'leaveWaitlist']);
    Route::get('/schedule', [CourseController::class, 'schedule']);
    Route::post('/check-conflicts', [CourseController::class, 'checkConflicts']);
    
    // Financial Management Routes
    Route::get('/financial-records', [FinancialRecordController::class, 'index']);
    Route::post('/financial-records', [FinancialRecordController::class, 'store']);
    Route::get('/financial-records/{id}', [FinancialRecordController::class, 'show']);
    Route::put('/financial-records/{id}', [FinancialRecordController::class, 'update']);
    Route::delete('/financial-records/{id}', [FinancialRecordController::class, 'destroy']);
    Route::post('/financial-records/{id}/payment', [FinancialRecordController::class, 'processPayment']);
    Route::get('/financial-statement', [FinancialRecordController::class, 'generateStatement']);
    Route::get('/financial-holds', [FinancialRecordController::class, 'checkFinancialHolds']);
    
    // Academic Records & Grading Routes
    Route::get('/grades', [GradeController::class, 'index']);
    Route::post('/grades', [GradeController::class, 'store']);
    Route::get('/grades/{id}', [GradeController::class, 'show']);
    Route::put('/grades/{id}', [GradeController::class, 'update']);
    Route::delete('/grades/{id}', [GradeController::class, 'destroy']);
    Route::post('/grades/bulk', [GradeController::class, 'submitBulk']);
    Route::get('/transcript', [GradeController::class, 'generateTranscript']);
    Route::get('/gpa', [GradeController::class, 'getGPA']);
    
    // Attendance Tracking Routes
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::get('/attendance/{id}', [AttendanceController::class, 'show']);
    Route::put('/attendance/{id}', [AttendanceController::class, 'update']);
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy']);
    Route::put('/attendance/{id}/student-attendance', [AttendanceController::class, 'updateStudentAttendance']);
    Route::post('/student-attendance/{id}/excuse', [AttendanceController::class, 'submitExcuse']);
    Route::put('/student-attendance/{id}/verify-excuse', [AttendanceController::class, 'verifyExcuse']);
    Route::get('/attendance/course/statistics', [AttendanceController::class, 'courseStatistics']);
    Route::get('/attendance/student/statistics', [AttendanceController::class, 'studentStatistics']);
    Route::get('/attendance/at-risk-students', [AttendanceController::class, 'atRiskStudents']);
    
    // Student Requests Routes
    Route::get('/student-requests', [StudentRequestController::class, 'index']);
    Route::post('/student-requests', [StudentRequestController::class, 'store']);
    Route::get('/student-requests/{id}', [StudentRequestController::class, 'show']);
    Route::put('/student-requests/{id}', [StudentRequestController::class, 'update']);
    Route::delete('/student-requests/{id}', [StudentRequestController::class, 'destroy']);
    Route::post('/student-requests/{id}/approve', [StudentRequestController::class, 'approve']);
    Route::post('/student-requests/{id}/reject', [StudentRequestController::class, 'reject']);
    Route::get('/student-requests/{id}/workflow', [StudentRequestController::class, 'getWorkflow']);
    Route::get('/pending-approvals', [StudentRequestController::class, 'pendingApprovals']);
    Route::get('/request-history', [StudentRequestController::class, 'history']);
    Route::post('/student-requests/{id}/attachments', [StudentRequestController::class, 'uploadAttachment']);
    Route::delete('/student-requests/{requestId}/attachments/{attachmentId}', [StudentRequestController::class, 'deleteAttachment']);
});
