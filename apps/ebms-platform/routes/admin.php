<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DFormController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamFeeRuleController;
use App\Http\Controllers\Admin\FeatureFlagController;
use App\Http\Controllers\Admin\GradeSheetController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');
});

Route::middleware('auth:admin')->group(function () {
    Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:dashboard.view');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::middleware('permission:students.view')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
        Route::get('/students/{hallTicket}', [StudentController::class, 'show'])->name('students.show');
    });
    Route::middleware('permission:students.edit')->group(function () {
        Route::get('/students/{hallTicket}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
        Route::post('/students/{hallTicket}/login-as', [StudentController::class, 'loginAs'])->name('students.login-as');
    });

    Route::middleware('permission:enrollments.view')->group(function () {
        Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    });
    Route::middleware('permission:enrollments.edit')->group(function () {
        // Specific routes before wildcard
        Route::get('/enrollments/mark-payment', [EnrollmentController::class, 'markPaymentPage'])->name('enrollments.mark-payment');
        Route::post('/enrollments/{id}/fee', [EnrollmentController::class, 'markFeePaid'])->name('enrollments.fee');
        Route::delete('/enrollments/{id}/fee', [EnrollmentController::class, 'clearPayment'])->name('enrollments.fee.clear');
        Route::get('/enrollments/{id}/subjects', [EnrollmentController::class, 'manageSubjects'])->name('enrollments.subjects');
        Route::post('/enrollments/{id}/subjects', [EnrollmentController::class, 'addSubject'])->name('enrollments.subjects.store');
        Route::delete('/enrollments/{id}/subjects/{subjectId}', [EnrollmentController::class, 'removeSubject'])->name('enrollments.subjects.destroy');
    });
    // Wildcard after all specific routes
    Route::middleware('permission:enrollments.view')->group(function () {
        Route::get('/enrollments/{id}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    });
    Route::middleware('permission:enrollments.delete')->group(function () {
        Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
    });

    Route::middleware('permission:exams.view')->group(function () {
        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
        Route::get('/exams/{exam}/planning', [ExamController::class, 'planning'])->name('exams.planning');
        Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
        Route::get('/exams/{exam}/fee-rules', [ExamFeeRuleController::class, 'index'])->name('exams.fee-rules.index');
    });
    Route::middleware('permission:exams.edit')->group(function () {
        Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
        Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
        Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
        Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
        Route::patch('/exams/{exam}/status', [ExamController::class, 'toggleStatus'])->name('exams.toggle-status');
        Route::patch('/exams/{exam}/revaluation', [ExamController::class, 'toggleRevaluation'])->name('exams.toggle-revaluation');
        Route::patch('/exams/{exam}/results', [ExamController::class, 'toggleResults'])->name('exams.toggle-results');
        Route::post('/exams/{exam}/fee-rules', [ExamFeeRuleController::class, 'store'])->name('exams.fee-rules.store');
        Route::get('/exams/{exam}/fee-rules/{rule}/edit', [ExamFeeRuleController::class, 'edit'])->name('exams.fee-rules.edit');
        Route::put('/exams/{exam}/fee-rules/{rule}', [ExamFeeRuleController::class, 'update'])->name('exams.fee-rules.update');
    });
    Route::middleware('permission:exams.delete')->group(function () {
        Route::delete('/exams/{exam}/fee-rules/{rule}', [ExamFeeRuleController::class, 'destroy'])->name('exams.fee-rules.destroy');
    });

    Route::middleware('permission:courses.view')->group(function () {
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    });
    Route::middleware('permission:courses.edit')->group(function () {
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::post('/courses/{course}/groups', [CourseController::class, 'storeGroup'])->name('courses.groups.store');
        Route::get('/courses/{course}/groups/{group}/edit', [CourseController::class, 'editGroup'])->name('courses.groups.edit');
        Route::put('/courses/{course}/groups/{group}', [CourseController::class, 'updateGroup'])->name('courses.groups.update');
    });
    Route::middleware('permission:courses.delete')->group(function () {
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::delete('/courses/{course}/groups/{group}', [CourseController::class, 'destroyGroup'])->name('courses.groups.destroy');
    });

    Route::middleware('permission:papers.view')->group(function () {
        Route::get('/papers', [SubjectController::class, 'index'])->name('papers.index');
        Route::get('/papers/{paper}', [SubjectController::class, 'show'])->name('papers.show');
    });
    Route::middleware('permission:papers.edit')->group(function () {
        Route::get('/papers/create', [SubjectController::class, 'create'])->name('papers.create');
        Route::post('/papers', [SubjectController::class, 'store'])->name('papers.store');
        Route::get('/papers/{paper}/edit', [SubjectController::class, 'edit'])->name('papers.edit');
        Route::put('/papers/{paper}', [SubjectController::class, 'update'])->name('papers.update');
    });
    Route::middleware('permission:papers.delete')->group(function () {
        Route::delete('/papers/{paper}', [SubjectController::class, 'destroy'])->name('papers.destroy');
    });

    Route::middleware('permission:dform.view')->group(function () {
        Route::get('/dform', [DFormController::class, 'index'])->name('dform.index');
        Route::get('/dform/print', [DFormController::class, 'print'])->name('dform.print');
    });

    Route::middleware('permission:attendance.view')->group(function () {
        Route::get('/attendance', [DFormController::class, 'attendanceIndex'])->name('attendance.index');
        Route::get('/attendance/print', [DFormController::class, 'attendancePrint'])->name('attendance.print');
    });

    Route::middleware('role:superadmin')->group(function () {
        Route::get('/admin-users', [AdminUserController::class, 'index'])->name('admin-users.index');
        Route::get('/admin-users/create', [AdminUserController::class, 'create'])->name('admin-users.create');
        Route::post('/admin-users', [AdminUserController::class, 'store'])->name('admin-users.store');
        Route::patch('/admin-users/{adminUser}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('admin-users.toggle-active');
        Route::post('/admin-users/{adminUser}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin-users.reset-password');
        Route::get('/admin-users/{adminUser}/permissions', [AdminUserController::class, 'permissions'])->name('admin-users.permissions');
        Route::post('/admin-users/{adminUser}/permissions', [AdminUserController::class, 'savePermissions'])->name('admin-users.permissions.save');
    });

    Route::middleware('role:superadmin')->group(function () {
        Route::get('/feature-flags', [FeatureFlagController::class, 'index'])->name('feature-flags.index');
        Route::patch('/feature-flags/{featureFlag}/toggle', [FeatureFlagController::class, 'toggle'])->name('feature-flags.toggle');
        Route::patch('/feature-flags/{featureFlag}/message', [FeatureFlagController::class, 'updateMessage'])->name('feature-flags.message');
    });

    Route::get('/gradesheets/{student}', [GradeSheetController::class, 'show'])
        ->middleware('permission:gradesheets.view')
        ->name('gradesheets.show');
    Route::post('/gradesheets/{student}/generate', [GradeSheetController::class, 'generate'])
        ->middleware('permission:gradesheets.generate')
        ->name('gradesheets.generate');
});
