<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResultEntryRequest;
use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Models\Result;
use App\Models\Subject;
use App\Services\GpaCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function __construct(private GpaCalculatorService $gpaCalculator) {}

    public function index(): View
    {
        $exams = Exam::orderByDesc('id')->get();

        return view('admin.results.index', compact('exams'));
    }

    public function entryForm(Exam $exam): View
    {
        $enrollments = ExamEnrollment::forExam($exam->id)
            ->feePaid()
            ->with(['student', 'enrollmentSubjects.subject'])
            ->get();

        return view('admin.results.entry', compact('exam', 'enrollments'));
    }

    public function store(ResultEntryRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            foreach ($request->results as $enrollmentId => $subjectResults) {
                $enrollment = ExamEnrollment::findOrFail($enrollmentId);

                foreach ($subjectResults as $subjectId => $marks) {
                    $subject = Subject::findOrFail($subjectId);

                    $isAbsentExt = ($marks['ext'] ?? '') === 'AB';
                    $isAbsentInt = ($marks['int'] ?? '') === 'AB';
                    $extMarks    = $isAbsentExt ? null : (int) ($marks['ext'] ?? 0);
                    $intMarks    = $isAbsentInt ? null : (int) ($marks['int'] ?? 0);

                    $computed = $this->gpaCalculator->gradeFromMarks($extMarks, $intMarks, $isAbsentExt, $isAbsentInt);

                    Result::updateOrCreate(
                        ['enrollment_id' => $enrollmentId, 'subject_id' => $subjectId],
                        array_merge($computed, [
                            'hall_ticket'    => $enrollment->hall_ticket,
                            'exam_id'        => $enrollment->exam_id,
                            'ext_marks'      => $extMarks,
                            'int_marks'      => $intMarks,
                            'is_absent_ext'  => $isAbsentExt,
                            'is_absent_int'  => $isAbsentInt,
                            'part'           => $subject->part,
                            'semester'       => $enrollment->exam->semester ?? 1,
                        ])
                    );
                }
            }
        });

        return back()->with('success', 'Results saved successfully.');
    }

    public function processGpa(Exam $exam): RedirectResponse
    {
        $this->gpaCalculator->calculateBatch($exam->id);

        return back()->with('success', "GPA calculated for all enrollments in {$exam->name}.");
    }

    public function show(ExamEnrollment $enrollment): View
    {
        $enrollment->load(['student', 'exam', 'results.subject', 'gpa']);

        return view('admin.results.show', compact('enrollment'));
    }
}
