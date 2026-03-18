<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = Auth::guard('student')->user();
        $enrollments = $student->enrollments()
            ->with('exam')
            ->latest('enrolled_at')
            ->get();

        $openEnrollments = $enrollments->where('fee_paid_at', null)->count();
        $paidEnrollments = $enrollments->whereNotNull('fee_paid_at')->count();

        return view('student.dashboard', compact('student', 'enrollments', 'openEnrollments', 'paidEnrollments'));
    }
}
