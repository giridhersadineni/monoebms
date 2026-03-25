<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\EnrollRequest;
use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Models\ExamEnrollmentSubject;
use App\Models\Result;
use App\Models\Subject;
use App\Services\FeeCalculatorService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function __construct(private FeeCalculatorService $feeCalculator) {}

    public function index(): View
    {
        $student = Auth::guard('student')->user();
        $enrollments = $student->enrollments()
            ->with('exam')
            ->latest('enrolled_at')
            ->paginate(15);

        return view('student.enrollments.index', compact('student', 'enrollments'));
    }

    public function selectExam(): View
    {
        $student = Auth::guard('student')->user();
        $exams = Exam::open()
            ->where(function ($q) use ($student) {
                $q->whereNull('course')
                  ->orWhere('course', 'ALL')
                  ->orWhere('course', $student->course);
            })
            ->with('feeRules')
            ->orderByDesc('year')
            ->get();

        $resolvedFees = $exams->mapWithKeys(
            fn($e) => [$e->id => $e->resolvedFeeComponents($student->course, $student->group_code)]
        );

        return view('student.enrollments.create', compact('student', 'exams', 'resolvedFees'));
    }

    public function selectSubjects(Request $request): View
    {
        $request->validate(['exam_id' => ['required', 'integer', 'exists:exams,id']]);

        $student = Auth::guard('student')->user();
        $exam = Exam::with('feeRules')->findOrFail($request->exam_id);

        $subjectQuery = Subject::forCourse($student->course)
            ->forSemester($exam->semester)
            ->where('medium', $student->medium)
            ->where('scheme', $student->scheme);

        if ($student->group_code) {
            $subjectQuery->where('group_code', $student->group_code);
        }

        $allSubjects = (clone $subjectQuery)->get();

        // For supplementary exams, only show subjects the student has failed.
        // Fetch directly by ID to avoid the group_code filter excluding subjects
        // that belong to a different group code than the student record (e.g. BZC vs BTZC).
        if ($exam->exam_type === 'supplementary') {
            $failedSubjectIds = Result::where('hall_ticket', $student->hall_ticket)
                ->whereIn('exam_id', Exam::where('exam_type', 'regular')
                    ->where('semester', $exam->semester)
                    ->pluck('id'))
                ->where('result', '!=', 'P')
                ->pluck('subject_id')
                ->unique();

            if ($failedSubjectIds->isNotEmpty()) {
                $allSubjects = Subject::whereIn('id', $failedSubjectIds->all())->get();
            }
        }

        $compulsorySubjects = $allSubjects->filter(fn($s) => is_null($s->elective_group))->values();

        $electiveSubjects = $allSubjects->filter(fn($s) => !is_null($s->elective_group))->groupBy('elective_group');

        $resolvedFee = $exam->resolvedFeeComponents($student->course, $student->group_code);

        return view('student.enrollments.subjects', compact('student', 'exam', 'compulsorySubjects', 'electiveSubjects', 'resolvedFee'));
    }

    public function confirm(EnrollRequest $request): View
    {
        $student = Auth::guard('student')->user();
        $exam = Exam::findOrFail($request->exam_id);

        $subjectIds = array_merge(
            $request->input('compulsory_subjects', []),
            $request->input('elective_subjects', [])
        );

        $subjects = Subject::whereIn('id', $subjectIds)->get();
        $exam->load('feeRules');
        $fee = $this->feeCalculator->calculate($exam, count($subjectIds), $student->course, $student->group_code);

        $request->session()->put('pending_enrollment', [
            'exam_id'    => $exam->id,
            'subject_ids' => $subjectIds,
            'fee_amount' => $fee,
        ]);

        return view('student.enrollments.confirm', compact('student', 'exam', 'subjects', 'fee'));
    }

    public function store(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_enrollment');

        if (! $pending) {
            return redirect()->route('student.enrollments.create')
                ->withErrors(['error' => 'Session expired. Please start again.']);
        }

        $student = Auth::guard('student')->user();

        $enrollment = null;

        try {
            DB::transaction(function () use ($student, $pending, &$enrollment) {
                $enrollment = ExamEnrollment::create([
                    'exam_id'     => $pending['exam_id'],
                    'student_id'  => $student->id,
                    'hall_ticket' => $student->hall_ticket,
                    'fee_amount'  => $pending['fee_amount'],
                    'enrolled_at' => now(),
                ]);

                $subjects = Subject::whereIn('id', $pending['subject_ids'])->get();

                foreach ($subjects as $subject) {
                    ExamEnrollmentSubject::create([
                        'enrollment_id' => $enrollment->id,
                        'subject_id'    => $subject->id,
                        'subject_code'  => $subject->code,
                        'subject_type'  => !is_null($subject->elective_group) ? 'elective' : 'regular',
                    ]);
                }
            });
        } catch (UniqueConstraintViolationException) {
            return redirect()->route('student.enrollments.index')
                ->with('info', 'You are already enrolled in this exam.');
        }

        $request->session()->forget('pending_enrollment');

        return redirect()->route('student.enrollments.success', $enrollment);
    }

    public function success(ExamEnrollment $enrollment): View
    {
        $student = Auth::guard('student')->user();

        if ((int) $enrollment->student_id !== (int) $student->id) {
            abort(403);
        }

        $enrollment->load(['exam', 'enrollmentSubjects.subject']);

        return view('student.enrollments.success', compact('enrollment'));
    }
}
