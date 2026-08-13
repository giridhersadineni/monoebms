<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Models\RevaluationEnrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('admin')->user();
        $canViewEnrollments = $user?->canAccess('enrollments.view');
        $canViewExams       = $user?->canAccess('exams.view');

        $stats = [
            'open_exams'            => Exam::where('status', Exam::STATUS_NOTIFY)->count(),
            'running_exams'         => Exam::where('status', Exam::STATUS_RUNNING)->count(),
            'enrollments_today'     => ExamEnrollment::whereDate('enrolled_at', today())->count(),
            'enrollments_this_month'=> ExamEnrollment::whereMonth('enrolled_at', now()->month)
                                            ->whereYear('enrolled_at', now()->year)->count(),
            'fee_unpaid_count'      => ExamEnrollment::feeUnpaid()->count(),
            'fee_unpaid_amount'     => ExamEnrollment::feeUnpaid()->sum('fee_amount'),
            'fee_paid_amount'       => ExamEnrollment::feePaid()->sum('fee_amount'),
            'pending_revaluations'  => RevaluationEnrollment::where('status', 'pending')->count(),
        ];

        // Exam-wise fee statistics — all exams that have at least one enrollment
        $feeStats = $canViewEnrollments
            ? DB::table('exam_enrollments')
                ->join('exams', 'exams.id', '=', 'exam_enrollments.exam_id')
                ->groupBy('exams.id', 'exams.name', 'exams.status', 'exams.exam_type', 'exams.semester', 'exams.year')
                ->select([
                    'exams.id',
                    'exams.name',
                    'exams.status',
                    'exams.exam_type',
                    'exams.semester',
                    'exams.year',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN fee_paid_at IS NOT NULL THEN 1 ELSE 0 END) as paid_count'),
                    DB::raw('SUM(CASE WHEN fee_paid_at IS NULL     THEN 1 ELSE 0 END) as unpaid_count'),
                    DB::raw('SUM(CASE WHEN fee_paid_at IS NOT NULL THEN fee_amount ELSE 0 END) as collected'),
                    DB::raw('SUM(CASE WHEN fee_paid_at IS NULL     THEN fee_amount ELSE 0 END) as outstanding'),
                    DB::raw('SUM(fee_amount) as total_amount'),
                ])
                ->orderByDesc('exams.id')
                ->get()
            : collect();

        $activeExams = $canViewExams
            ? Exam::whereIn('status', [Exam::STATUS_NOTIFY, Exam::STATUS_RUNNING])
                ->orderByDesc('id')
                ->get()
            : collect();

        $recentEnrollments = $canViewEnrollments
            ? ExamEnrollment::with(['student:id,hall_ticket,name', 'exam:id,name'])
                ->latest('enrolled_at')
                ->take(8)
                ->get()
            : collect();

        return view('admin.dashboard', compact(
            'stats', 'recentEnrollments', 'canViewEnrollments',
            'activeExams', 'canViewExams', 'feeStats'
        ));
    }
}
