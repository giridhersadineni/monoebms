<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function index(): View
    {
        $student = Auth::guard('student')->user();

        $enrollments = $student->enrollments()
            ->with(['exam', 'gpa'])
            ->feePaid()
            ->latest('enrolled_at')
            ->get();

        return view('student.results.index', compact('student', 'enrollments'));
    }

    public function show(Exam $exam): View|RedirectResponse
    {
        $student = Auth::guard('student')->user();

        $enrollment = ExamEnrollment::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->with(['results.subject', 'gpa'])
            ->firstOrFail();

        if (! $enrollment->isFeePaid()) {
            abort(403, 'Results are available only after fee payment is confirmed.');
        }

        return view('student.results.show', compact('student', 'exam', 'enrollment'));
    }
}
