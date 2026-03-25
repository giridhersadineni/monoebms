<?php

use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\SsoController;
use App\Http\Controllers\Student\ChallanController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\PrintApplicationController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\ResultController;
use App\Http\Controllers\Student\RevaluationController;
use Illuminate\Support\Facades\Route;

// SSO from legacy portal (no auth required, no CSRF)
Route::get('/sso', [SsoController::class, 'handle'])->name('sso');

// Guest only
Route::middleware('guest:student')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');
});

// Authenticated students
Route::middleware('auth:student')->group(function () {
    Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',            [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/photo',     [ProfileController::class, 'uploadPhoto'])->name('profile.photo');
    Route::post('/profile/signature', [ProfileController::class, 'uploadSignature'])->name('profile.signature');

    // Enrollments
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/create', [EnrollmentController::class, 'selectExam'])->name('enrollments.create');
    Route::get('/enrollments/subjects', [EnrollmentController::class, 'selectSubjects'])->name('enrollments.subjects');
    Route::post('/enrollments/confirm', [EnrollmentController::class, 'confirm'])->name('enrollments.confirm');
    Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('/enrollments/{enrollment}/success', [EnrollmentController::class, 'success'])->name('enrollments.success');

    // Results
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results/{exam}', [ResultController::class, 'show'])->name('results.show');

    // Challan
    Route::get('/challan/{enrollment}', [ChallanController::class, 'show'])->name('challan.show');

    // Print Application
    Route::get('/enrollments/{enrollment}/application', [PrintApplicationController::class, 'show'])->name('enrollments.application');

    // Revaluation
    Route::get('/revaluation', [RevaluationController::class, 'index'])->name('revaluation.index');
    Route::get('/revaluation/{enrollment}', [RevaluationController::class, 'show'])->name('revaluation.show');
    Route::post('/revaluation', [RevaluationController::class, 'store'])->name('revaluation.store');
});
