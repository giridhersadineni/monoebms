<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Models\RevaluationEnrollment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'open_exams'         => Exam::open()->count(),
            'enrollments_today'  => ExamEnrollment::whereDate('enrolled_at', today())->count(),
            'fee_unpaid'         => ExamEnrollment::feeUnpaid()->count(),
            'pending_revaluations' => RevaluationEnrollment::where('status', 'pending')->count(),
        ];

        $recentEnrollments = ExamEnrollment::with(['student', 'exam'])
            ->latest('enrolled_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentEnrollments'));
    }
}
