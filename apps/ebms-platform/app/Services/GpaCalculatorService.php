<?php

namespace App\Services;

use App\Domain\Results\GradeScale;
use App\Domain\Results\PaperCalculator;
use App\Domain\Results\PaperMarks;
use App\Domain\Results\SemesterAggregator;
use App\Models\ExamEnrollment;
use App\Models\Gpa;
use App\Models\Result;
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
        // Get all GPAs for the student grouped by part
        $part1Gpas = Result::where('hall_ticket', function ($q) use ($studentId) {
            $q->select('hall_ticket')->from('students')->where('id', $studentId);
        })
            ->excludeGradeEx()
            ->where('part', 1)
            ->whereNotIn('result', ['F', 'AB'])
            ->selectRaw('SUM(gp_credits) as total_gpc, SUM(credits) as total_credits')
            ->first();

        $part2Gpas = Result::where('hall_ticket', function ($q) use ($studentId) {
            $q->select('hall_ticket')->from('students')->where('id', $studentId);
        })
            ->excludeGradeEx()
            ->where('part', 2)
            ->whereNotIn('result', ['F', 'AB'])
            ->selectRaw('SUM(gp_credits) as total_gpc, SUM(credits) as total_credits')
            ->first();

        $part1Cgpa = ($part1Gpas?->total_credits > 0)
            ? round($part1Gpas->total_gpc / $part1Gpas->total_credits, 2)
            : 0.0;

        $part2Cgpa = ($part2Gpas?->total_credits > 0)
            ? round($part2Gpas->total_gpc / $part2Gpas->total_credits, 2)
            : 0.0;

        $totalCredits = ($part1Gpas?->total_credits ?? 0) + ($part2Gpas?->total_credits ?? 0);
        $totalGpc     = ($part1Gpas?->total_gpc ?? 0) + ($part2Gpas?->total_gpc ?? 0);
        $allCgpa = ($totalCredits > 0) ? round($totalGpc / $totalCredits, 2) : 0.0;

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
        $totalCredits = Result::forHallTicket($hallTicket)
            ->whereNotIn('result', ['F', 'AB'])
            ->excludeGradeEx()
            ->sum('credits');

        // Degree pass thresholds
        return in_array((int) $totalCredits, [164, 181], true) || $totalCredits >= 164;
    }
}
