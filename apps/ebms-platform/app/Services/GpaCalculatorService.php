<?php

namespace App\Services;

use App\Domain\Results\GradeScale;
use App\Domain\Results\PaperCalculator;
use App\Domain\Results\PaperMarks;
use App\Domain\Results\SemesterAggregator;
use App\Models\ExamEnrollment;
use App\Models\Gpa;
use App\Models\Result;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Persistence-facing wrapper around the pure calculators in App\Domain\Results.
 *
 * The formulas themselves live in PaperCalculator/SemesterAggregator/GradeScale;
 * this class only decides what to read and what to write.
 */
class GpaCalculatorService
{
    public function __construct(
        private readonly PaperCalculator $paperCalculator = new PaperCalculator(),
        private readonly SemesterAggregator $aggregator = new SemesterAggregator(),
        private readonly GradeScale $gradeScale = new GradeScale(),
    ) {}

    /**
     * Compute a single paper's result, grade and grade points.
     *
     * @return array{grade: string, result: string, total_marks: int, credits: float, gp_value: float, gp_credits: float}
     */
    public function gradeFromMarks(
        ?int $ext,
        ?int $int,
        bool $absentExt = false,
        bool $absentInt = false,
        int $extTotal = 100,
        int $intTotal = 20,
        float $credits = 3.0,
        int $floatation = 0,
        int $acMarks = 0,
        int $floatDeduct = 0,
        bool $malpractice = false
    ): array {
        return $this->paperCalculator->calculate(new PaperMarks(
            ext: $ext,
            int: $int,
            absentExt: $absentExt,
            absentInt: $absentInt,
            extTotal: $extTotal,
            intTotal: $intTotal,
            credits: $credits,
            floatation: $floatation,
            acMarks: $acMarks,
            floatDeduct: $floatDeduct,
            malpractice: $malpractice,
        ))->toArray();
    }

    /**
     * Re-derive one paper's grade from what is already stored.
     *
     * Only the derived columns are written — the marks, absent flags and
     * floatation context are read, never modified, so this is idempotent.
     */
    public function recalculatePaper(Result $result): Result
    {
        $outcome = $this->paperCalculator->calculate(PaperMarks::fromResult($result));

        $result->update($outcome->toArray());

        return $result;
    }

    /**
     * Recompute SGPA and the overall result for one enrollment.
     *
     * @param  Collection|null  $papers  Pre-filtered papers, to avoid a query per enrollment in batch runs.
     */
    public function calculateForEnrollment(ExamEnrollment $enrollment, ?Collection $papers = null): ?Gpa
    {
        $papers ??= $enrollment->results()->excludeGradeEx()->get();

        if ($papers->isEmpty()) {
            return null;
        }

        $totals = $this->aggregator->aggregate($papers);

        return $enrollment->gpa()->updateOrCreate(
            ['enrollment_id' => $enrollment->id],
            [
                'hall_ticket'  => $enrollment->hall_ticket,
                'exam_id'      => $enrollment->exam_id,
                'sgpa'         => $totals['sgpa'],
                'cgpa'         => $totals['sgpa'], // CGPA is recalculated when the degree grade is generated
                'total_marks'  => $totals['total_marks'],
                'result'       => $totals['result'],
                'processed_at' => now(),
            ]
        );
    }

    public function calculateBatch(int $examId): void
    {
        $enrollments = ExamEnrollment::forExam($examId)
            ->feePaid()
            ->with(['results' => fn ($q) => $q->excludeGradeEx()])
            ->get();

        foreach ($enrollments as $enrollment) {
            $this->calculateForEnrollment($enrollment, $enrollment->results);
        }
    }

    public function calculateDegreeCgpa(int $studentId): array
    {
        $papers = $this->bestAttemptPapers(
            Result::where('results.hall_ticket', function ($q) use ($studentId) {
                $q->select('hall_ticket')->from('students')->where('id', $studentId);
            })
        );

        $part1 = $papers->where('part', 1);
        $part2 = $papers->where('part', 2);

        $part1Cgpa = $this->cgpaFor($part1);
        $part2Cgpa = $this->cgpaFor($part2);
        // Filter the source collection rather than merging the two halves:
        // Eloquent\Collection::merge() de-duplicates by primary key, and these
        // models carry no id (bestAttemptPapers selects four columns), so every
        // key is null and the merge would collapse to a single paper.
        $allCgpa   = $this->cgpaFor($papers->whereIn('part', [1, 2]));

        return [
            'part1_cgpa'     => $part1Cgpa,
            'part2_cgpa'     => $part2Cgpa,
            'all_cgpa'       => $allCgpa,
            'part1_division' => $this->divisionFromCgpa($part1Cgpa),
            'part2_division' => $this->divisionFromCgpa($part2Cgpa),
            'final_division' => $this->divisionFromCgpa($allCgpa),
        ];
    }

    public function divisionFromCgpa(float $cgpa): string
    {
        return $this->gradeScale->division($cgpa);
    }

    public function hasCoursePassedThreshold(string $hallTicket): bool
    {
        return $this->bestAttemptPapers(Result::forHallTicket($hallTicket))->sum('credits') >= 164;
    }

    /**
     * The papers that count toward a degree, one row per paper — the attempt
     * that earned the most grade points.
     *
     * Two things this guards against, both of which inflated the totals:
     *   - A paper cleared more than once (a re-sit for improvement) used to
     *     add its credits once per attempt.
     *   - Papers are matched on the subject *code*, not subject_id. One paper
     *     has several subjects rows (the unique key is code + group + medium +
     *     semester + scheme) and separate sittings can point at different
     *     ones, so subject_id does not identify a paper.
     *
     * Only earned passes count: F and AB are not passes, and R (promoted) and
     * M (malpractice) carry no earned credit either.
     *
     * @param  Builder<Result>  $results  Base query, already scoped to one student.
     * @return Collection<int, Result>
     */
    private function bestAttemptPapers(Builder $results): Collection
    {
        return $results
            // results.grade is nullable, and the excludeGradeEx scope's
            // `grade != 'EX'` evaluates to NULL — and so filters out — every
            // ungraded row. A passed paper with no letter grade recorded still
            // earns its credits, so match the null-safe form used by the
            // Detained List report.
            ->where(fn ($q) => $q->where('results.grade', '<>', 'EX')->orWhereNull('results.grade'))
            ->whereNotIn('results.result', ['F', 'AB', 'R', 'M'])
            ->join('subjects', 'subjects.id', '=', 'results.subject_id')
            // part lives on both tables, so it has to be qualified.
            ->select('results.part', 'results.credits', 'results.gp_credits', 'subjects.code')
            ->get()
            ->groupBy('code')
            ->map(fn (Collection $attempts) => $attempts->sortByDesc('gp_credits')->first())
            ->values();
    }

    private function cgpaFor(Collection $papers): float
    {
        $credits = (float) $papers->sum('credits');

        return $credits > 0 ? round((float) $papers->sum('gp_credits') / $credits, 2) : 0.0;
    }
}
