<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DFormController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamFeeRuleController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\GradeSheetController;
use App\Http\Controllers\Admin\FeatureFlagController;
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
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
    Route::get('/students/{hallTicket}/edit', [StudentController::class, 'edit'])
        ->middleware('role:admin,superadmin')
        ->name('students.edit');
    Route::get('/students/{hallTicket}', [StudentController::class, 'show'])->name('students.show');
    Route::put('/students/{id}', [StudentController::class, 'update'])
        ->middleware('role:admin,superadmin')
        ->name('students.update');
    Route::post('/students/{hallTicket}/login-as', [StudentController::class, 'loginAs'])
        ->middleware('role:admin,superadmin')
        ->name('students.login-as');

    // Enrollments
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/mark-payment', [EnrollmentController::class, 'markPaymentPage'])->name('enrollments.mark-payment');
    Route::get('/enrollments/{id}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::post('/enrollments/{id}/fee', [EnrollmentController::class, 'markFeePaid'])->name('enrollments.fee');
    Route::get('/enrollments/{id}/subjects', [EnrollmentController::class, 'manageSubjects'])->name('enrollments.subjects');
    Route::post('/enrollments/{id}/subjects', [EnrollmentController::class, 'addSubject'])
        ->middleware('role:admin,superadmin')
        ->name('enrollments.subjects.store');
    Route::delete('/enrollments/{id}/subjects/{subjectId}', [EnrollmentController::class, 'removeSubject'])
        ->middleware('role:admin,superadmin')
        ->name('enrollments.subjects.destroy');
    Route::delete('/enrollments/{id}/fee', [EnrollmentController::class, 'clearPayment'])
        ->middleware('role:admin,superadmin')
        ->name('enrollments.fee.clear');
    Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy'])
        ->middleware('role:admin,superadmin')
        ->name('enrollments.destroy');

    // Exams
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
    Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
    Route::patch('/exams/{exam}/status', [ExamController::class, 'toggleStatus'])->name('exams.toggle-status');
    Route::patch('/exams/{exam}/revaluation', [ExamController::class, 'toggleRevaluation'])->name('exams.toggle-revaluation');
    Route::patch('/exams/{exam}/results', [ExamController::class, 'toggleResults'])->name('exams.toggle-results');
    Route::get('/exams/{exam}/fee-rules', [ExamFeeRuleController::class, 'index'])->name('exams.fee-rules.index');
    Route::post('/exams/{exam}/fee-rules', [ExamFeeRuleController::class, 'store'])->name('exams.fee-rules.store');
    Route::get('/exams/{exam}/fee-rules/{rule}/edit', [ExamFeeRuleController::class, 'edit'])->name('exams.fee-rules.edit');
    Route::put('/exams/{exam}/fee-rules/{rule}', [ExamFeeRuleController::class, 'update'])->name('exams.fee-rules.update');
    Route::delete('/exams/{exam}/fee-rules/{rule}', [ExamFeeRuleController::class, 'destroy'])->name('exams.fee-rules.destroy');

    // Courses & Groups
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course}/groups', [CourseController::class, 'storeGroup'])->name('courses.groups.store');
    Route::get('/courses/{course}/groups/{group}/edit', [CourseController::class, 'editGroup'])->name('courses.groups.edit');
    Route::put('/courses/{course}/groups/{group}', [CourseController::class, 'updateGroup'])->name('courses.groups.update');
    Route::delete('/courses/{course}/groups/{group}', [CourseController::class, 'destroyGroup'])->name('courses.groups.destroy');

    // Papers (Subjects)
    Route::resource('papers', SubjectController::class);

    // D-Form
    Route::get('/dform', [DFormController::class, 'index'])->name('dform.index');
    Route::get('/dform/print', [DFormController::class, 'print'])->name('dform.print');

    // Attendance Sheet
    Route::get('/attendance', [DFormController::class, 'attendanceIndex'])->name('attendance.index');
    Route::get('/attendance/print', [DFormController::class, 'attendancePrint'])->name('attendance.print');

    // Admin Users (superadmin only)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/admin-users', [AdminUserController::class, 'index'])->name('admin-users.index');
        Route::get('/admin-users/create', [AdminUserController::class, 'create'])->name('admin-users.create');
        Route::post('/admin-users', [AdminUserController::class, 'store'])->name('admin-users.store');
        Route::patch('/admin-users/{adminUser}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('admin-users.toggle-active');
        Route::post('/admin-users/{adminUser}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin-users.reset-password');
    });

    // Feature Flags (superadmin only)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/feature-flags', [FeatureFlagController::class, 'index'])->name('feature-flags.index');
        Route::patch('/feature-flags/{featureFlag}/toggle', [FeatureFlagController::class, 'toggle'])->name('feature-flags.toggle');
        Route::patch('/feature-flags/{featureFlag}/message', [FeatureFlagController::class, 'updateMessage'])->name('feature-flags.message');
    });

    // Grade Sheets
    Route::get('/gradesheets/{student}', [GradeSheetController::class, 'show'])->name('gradesheets.show');
    Route::post('/gradesheets/{student}/generate', [GradeSheetController::class, 'generate'])
        ->middleware('role:superadmin')
        ->name('gradesheets.generate');
});
