<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResultEntryRequest;
use App\Domain\Results\PaperCalculator;
use App\Domain\Results\PaperMarks;
use App\Enums\AdminFeature;
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
    public function __construct(
        private GpaCalculatorService $gpaCalculator,
        private PaperCalculator $paperCalculator,
    ) {}

    public function index(): View
    {
        $exams = Exam::orderByDesc('id')->get();

        return view('admin.results.index', [
            'exams' => $exams,
            'matches' => collect(),
            'hallTicket' => null,
        ]);
    }

    public function lookup(Request $request): View|RedirectResponse
    {
        $examId = $request->get('exam_id');
        $hallTicket = trim((string) $request->get('hall_ticket'));

        if ($hallTicket !== '') {
            $enrollments = ExamEnrollment::where('hall_ticket', $hallTicket)
                ->when($examId, fn ($q) => $q->where('exam_id', $examId))
                ->with('exam')
                ->latest('enrolled_at')
                ->get();

            if ($enrollments->count() === 1) {
                return redirect()->route('admin.results.show', $enrollments->first());
            }

            if ($enrollments->count() > 1) {
                $exams = Exam::orderByDesc('id')->get();

                return view('admin.results.index', [
                    'exams' => $exams,
                    'matches' => $enrollments,
                    'hallTicket' => $hallTicket,
                ]);
            }

            return back()->withErrors(['hall_ticket' => "No enrollment found for hall ticket {$hallTicket}."]);
        }

        if ($examId) {
            $exam = Exam::findOrFail($examId);

            return redirect()->route('admin.results.records', $exam);
        }

        return back()->withErrors(['lookup' => 'Select an exam or enter a hall ticket.']);
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
        abort_unless(auth('admin')->user()?->canAccess(AdminFeature::ResultsEdit), 403);

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
        abort_unless(auth('admin')->user()?->canAccess(AdminFeature::ResultsEdit), 403);

        $this->gpaCalculator->calculateBatch($exam->id);

        return back()->with('success', "GPA calculated for all enrollments in {$exam->name}.");
    }

    public function show(ExamEnrollment $enrollment): View
    {
        $enrollment->load(['student', 'exam', 'results.subject', 'gpa']);

        return view('admin.results.show', compact('enrollment'));
    }

    public function updateResult(Request $request, ExamEnrollment $enrollment, Result $result): RedirectResponse
    {
        abort_unless(auth('admin')->user()?->canAccess(AdminFeature::ResultsEdit), 403);
        abort_unless($result->enrollment_id === $enrollment->id, 404);

        $validated = $request->validate([
            'ext'     => ['required', 'string', 'regex:/^(AB|\d{1,3})$/i'],
            'int'     => ['required', 'string', 'regex:/^(AB|\d{1,3})$/i'],
            'credits' => ['required', 'numeric', 'min:0', 'max:20'],
        ]);

        $isAbsentExt = strtoupper($validated['ext']) === 'AB';
        $isAbsentInt = strtoupper($validated['int']) === 'AB';
        $extMarks    = $isAbsentExt ? null : (int) $validated['ext'];
        $intMarks    = $isAbsentInt ? null : (int) $validated['int'];
        $credits     = (float) $validated['credits'];

        if (($extMarks ?? 0) > 100 || ($intMarks ?? 0) > 20) {
            return back()->with('error', 'Ext marks must be ≤ 100 and Int marks ≤ 20.');
        }

        // Preserve the paper's per-subject totals and floatation context so the
        // recomputed grade matches the migrated result rules.
        $marks = PaperMarks::fromResult($result)
            ->withMarks($extMarks, $intMarks, $isAbsentExt, $isAbsentInt)
            ->withCredits($credits);

        $computed = $this->paperCalculator->calculate($marks)->toArray();

        $result->update(array_merge($computed, [
            'ext_marks'     => $extMarks,
            'int_marks'     => $intMarks,
            'is_absent_ext' => $isAbsentExt,
            'is_absent_int' => $isAbsentInt,
        ]));

        return back()->with('success', "Marks updated for {$result->subject?->code}. Recalculate GPA to refresh SGPA.");
    }

    /**
     * Re-derive one paper's grade from its stored marks and floatation context,
     * without asking the admin to re-enter anything.
     */
    public function recalculateResult(ExamEnrollment $enrollment, Result $result): RedirectResponse
    {
        abort_unless(auth('admin')->user()?->canAccess(AdminFeature::ResultsEdit), 403);
        abort_unless($result->enrollment_id === $enrollment->id, 404);

        $before = $result->grade;
        $this->gpaCalculator->recalculatePaper($result);

        $code    = $result->subject?->code ?? 'paper';
        $message = $before === $result->grade
            ? "{$code} recalculated — grade unchanged ({$result->grade})."
            : "{$code} recalculated — grade {$before} → {$result->grade}.";

        return back()->with('success', $message);
    }

    /** Recalculate every paper on this enrollment, then its SGPA and overall result. */
    public function recalculateGpa(ExamEnrollment $enrollment): RedirectResponse
    {
        abort_unless(auth('admin')->user()?->canAccess(AdminFeature::ResultsEdit), 403);

        $gpa = DB::transaction(function () use ($enrollment) {
            $papers = $enrollment->results()->excludeGradeEx()->get();

            foreach ($papers as $paper) {
                $this->gpaCalculator->recalculatePaper($paper);
            }

            return $this->gpaCalculator->calculateForEnrollment($enrollment, $papers);
        });

        if (! $gpa) {
            return back()->with('error', 'No papers to calculate for this enrollment.');
        }

        return back()->with('success', "GPA recalculated — SGPA {$gpa->sgpa}, result {$gpa->result}.");
    }

    public function records(Request $request, Exam $exam): View
    {
        $sortable = ['hall_ticket', 'subject_code', 'ext_marks', 'int_marks', 'total_marks', 'grade', 'result', 'credits'];
        $sort = in_array($request->get('sort'), $sortable, true) ? $request->get('sort') : 'hall_ticket';
        $dir  = $request->get('dir') === 'desc' ? 'desc' : 'asc';

        $query = Result::where('exam_id', $exam->id)->with(['enrollment.student', 'subject']);

        if ($q = $request->get('q')) {
            $query->where(function ($w) use ($q) {
                $w->where('hall_ticket', 'like', "%{$q}%")
                    ->orWhereHas('enrollment.student', fn ($s) => $s->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('subject', fn ($s) => $s->where('code', 'like', "%{$q}%")->orWhere('name', 'like', "%{$q}%"));
            });
        }

        if ($sort === 'subject_code') {
            $query->join('subjects', 'subjects.id', '=', 'results.subject_id')
                ->orderBy('subjects.code', $dir)
                ->select('results.*');
        } else {
            $query->orderBy($sort, $dir);
        }

        $results = $query->paginate(50)->withQueryString();

        return view('admin.results.records', compact('exam', 'results', 'sort', 'dir'));
    }
}
