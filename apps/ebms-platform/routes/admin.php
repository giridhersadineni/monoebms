<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DFormController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\GradeSheetController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

// Guest only
Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');
});

// Authenticated admin
Route::middleware('auth:admin')->group(function () {
    Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Students
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
    Route::get('/students/{hallTicket}', [StudentController::class, 'show'])->name('students.show');
    Route::put('/students/{id}', [StudentController::class, 'update'])
        ->middleware('role:admin,superadmin')
        ->name('students.update');

    // Enrollments
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{id}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::post('/enrollments/{id}/fee', [EnrollmentController::class, 'markFeePaid'])->name('enrollments.fee');

    // Exams
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
    Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
    Route::patch('/exams/{exam}/status', [ExamController::class, 'toggleStatus'])->name('exams.toggle-status');
    Route::patch('/exams/{exam}/revaluation', [ExamController::class, 'toggleRevaluation'])->name('exams.toggle-revaluation');

    // D-Form
    Route::get('/dform', [DFormController::class, 'index'])->name('dform.index');
    Route::get('/dform/print', [DFormController::class, 'print'])->name('dform.print');

    // Attendance Sheet
    Route::get('/attendance', [DFormController::class, 'attendanceIndex'])->name('attendance.index');
    Route::get('/attendance/print', [DFormController::class, 'attendancePrint'])->name('attendance.print');

    // Grade Sheets
    Route::get('/gradesheets/{student}', [GradeSheetController::class, 'show'])->name('gradesheets.show');
    Route::post('/gradesheets/{student}/generate', [GradeSheetController::class, 'generate'])
        ->middleware('role:superadmin')
        ->name('gradesheets.generate');
});
