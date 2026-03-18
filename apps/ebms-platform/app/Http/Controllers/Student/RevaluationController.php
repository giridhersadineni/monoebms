<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\RevaluationRequest;
use App\Models\ExamEnrollment;
use App\Models\RevaluationEnrollment;
use App\Models\RevaluationSubject;
use App\Models\Subject;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RevaluationController extends Controller
{
    private const FEE_PER_PAPER = 275;

    public function index(): View
    {
        $student = Auth::guard('student')->user();

        $enrollments = $student->enrollments()
            ->with('exam')
            ->whereHas('exam', fn ($q) => $q->where('revaluation_open', true))
            ->feePaid()
            ->get();

        return view('student.revaluation.index', compact('student', 'enrollments'));
    }

    public function show(ExamEnrollment $enrollment): View
    {
        $student = Auth::guard('student')->user();

        if ($enrollment->student_id !== $student->id) {
            abort(403);
        }

        if (! $enrollment->exam->revaluation_open) {
            abort(404, 'Revaluation is not open for this exam.');
        }

        $failedResults = $enrollment->results()
            ->with('subject')
            ->whereIn('result', ['F', 'R'])
            ->get();

        return view('student.revaluation.apply', compact('student', 'enrollment', 'failedResults'));
    }

    public function store(RevaluationRequest $request): RedirectResponse
    {
        $student = Auth::guard('student')->user();
        $enrollment = ExamEnrollment::findOrFail($request->enrollment_id);

        if ($enrollment->student_id !== $student->id) {
            abort(403);
        }

        $subjectIds = $request->subject_ids;
        $fee = count($subjectIds) * self::FEE_PER_PAPER;

        try {
            DB::transaction(function () use ($student, $enrollment, $subjectIds, $fee) {
                $revaluation = RevaluationEnrollment::create([
                    'original_enrollment_id' => $enrollment->id,
                    'exam_id'    => $enrollment->exam_id,
                    'student_id' => $student->id,
                    'hall_ticket' => $student->hall_ticket,
                    'fee_amount' => $fee,
                    'status'     => 'pending',
                ]);

                $subjects = Subject::whereIn('id', $subjectIds)->get();

                foreach ($subjects as $subject) {
                    RevaluationSubject::create([
                        'revaluation_enrollment_id' => $revaluation->id,
                        'subject_id'   => $subject->id,
                        'subject_code' => $subject->code,
                    ]);
                }
            });
        } catch (UniqueConstraintViolationException) {
            return back()->withErrors(['error' => 'You have already applied for revaluation in this exam.']);
        }

        return redirect()->route('student.revaluation.index')
            ->with('success', "Revaluation application submitted. Fee: ₹{$fee}. Please pay via challan.");
    }
}
