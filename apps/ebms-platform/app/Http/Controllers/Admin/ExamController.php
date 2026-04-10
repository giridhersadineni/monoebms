<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExamRequest;
use App\Models\Course;
use App\Models\CourseGroup;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $query = Exam::withCount('enrollments');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('course')) {
            $query->forCourse($request->course);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->integer('year'));
        }
        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        $exams = $query->orderByDesc('year')->orderByDesc('month')->paginate(20)->withQueryString();

        return view('admin.exams.index', compact('exams'));
    }

    public function show(Exam $exam): View
    {
        $exam->loadCount('enrollments');
        $exam->load('feeRules');

        $feeStats = $exam->enrollments()
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN fee_paid_at IS NOT NULL THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN fee_paid_at IS NULL     THEN 1 ELSE 0 END) as unpaid_count,
                SUM(CASE WHEN fee_paid_at IS NOT NULL THEN fee_amount ELSE 0 END) as collected,
                SUM(CASE WHEN fee_paid_at IS NULL     THEN fee_amount ELSE 0 END) as outstanding,
                SUM(fee_amount) as total_amount
            ')
            ->first();

        $typeBreakdown = $exam->enrollments()
            ->selectRaw('exam_type, COUNT(*) as count')
            ->groupBy('exam_type')
            ->pluck('count', 'exam_type');

        $courseBreakdown = $exam->enrollments()
            ->join('students', 'students.id', '=', 'exam_enrollments.student_id')
            ->selectRaw('
                students.course,
                COUNT(*) as total,
                SUM(CASE WHEN fee_paid_at IS NOT NULL THEN 1 ELSE 0 END) as paid,
                SUM(CASE WHEN fee_paid_at IS NULL THEN 1 ELSE 0 END) as unpaid,
                SUM(CASE WHEN fee_paid_at IS NOT NULL THEN fee_amount ELSE 0 END) as collected
            ')
            ->groupBy('students.course')
            ->orderBy('students.course')
            ->get();

        $recentEnrollments = $exam->enrollments()
            ->with('student:id,hall_ticket,name')
            ->latest('enrolled_at')
            ->limit(10)
            ->get();

        return view('admin.exams.show', compact('exam', 'recentEnrollments', 'feeStats', 'typeBreakdown', 'courseBreakdown'));
    }

    public function planning(Exam $exam): View
    {
        // All subject codes that have at least one fee-paid enrollment in this exam
        $papers = DB::table('exam_enrollment_subjects')
            ->join('exam_enrollments', 'exam_enrollments.id', '=', 'exam_enrollment_subjects.enrollment_id')
            ->leftJoin('subjects', 'subjects.code', '=', 'exam_enrollment_subjects.subject_code')
            ->where('exam_enrollments.exam_id', $exam->id)
            ->whereNotNull('exam_enrollments.fee_paid_at')
            ->groupBy('exam_enrollment_subjects.subject_code', 'subjects.name', 'subjects.course', 'subjects.semester', 'subjects.scheme')
            ->select([
                'exam_enrollment_subjects.subject_code',
                'subjects.name',
                'subjects.course',
                'subjects.semester',
                'subjects.scheme',
                DB::raw('COUNT(*) as total'),
            ])
            ->orderBy('exam_enrollment_subjects.subject_code')
            ->get();

        $totalPaidEnrollments = $exam->enrollments()->whereNotNull('fee_paid_at')->count();

        return view('admin.exams.planning', compact('exam', 'papers', 'totalPaidEnrollments'));
    }

    public function create(): View
    {
        $courses = Course::where('is_active', true)->orderBy('code')->get();
        return view('admin.exams.form', ['exam' => null, 'courses' => $courses]);
    }

    public function store(ExamRequest $request): RedirectResponse
    {
        $exam = Exam::create($request->validated());

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exam created successfully.');
    }

    public function edit(Exam $exam): View
    {
        $courses = Course::where('is_active', true)->orderBy('code')->get();
        return view('admin.exams.form', compact('exam', 'courses'));
    }

    public function update(ExamRequest $request, Exam $exam): RedirectResponse
    {
        $exam->update($request->validated());

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exam updated successfully.');
    }

    public function toggleStatus(Exam $exam): RedirectResponse
    {
        $next = $exam->nextStatus();
        $exam->update(['status' => $next]);

        return back()->with('success', 'Exam status updated to ' . $next . '.');
    }

    public function toggleRevaluation(Exam $exam): RedirectResponse
    {
        $exam->update(['revaluation_open' => ! $exam->revaluation_open]);

        return back()->with('success', 'Revaluation ' . ($exam->revaluation_open ? 'opened' : 'closed') . '.');
    }

    public function toggleResults(Exam $exam): RedirectResponse
    {
        $exam->update(['results_visible' => ! $exam->results_visible]);

        return back()->with('success', 'Results ' . ($exam->results_visible ? 'published' : 'hidden') . '.');
    }
}
