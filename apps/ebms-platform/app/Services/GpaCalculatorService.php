<?php

namespace App\Services;

use App\Models\ExamEnrollment;
use App\Models\Grade;
use App\Models\Result;
use Illuminate\Support\Facades\DB;

class GpaCalculatorService
{
    private const GRADE_SCALE = [
        ['min' => 85, 'max' => 100, 'grade' => 'O',  'gpv' => 10.0],
        ['min' => 70, 'max' => 84,  'grade' => 'A',  'gpv' => 9.0],
        ['min' => 60, 'max' => 69,  'grade' => 'B',  'gpv' => 8.0],
        ['min' => 55, 'max' => 59,  'grade' => 'C',  'gpv' => 7.0],
        ['min' => 50, 'max' => 54,  'grade' => 'D',  'gpv' => 6.0],
        ['min' => 40, 'max' => 49,  'grade' => 'E',  'gpv' => 5.0],
    ];

    private const DIVISION_SCALE = [
        ['min' => 7.0, 'label' => 'First Class with Distinction'],
        ['min' => 6.0, 'label' => 'First Class'],
        ['min' => 5.0, 'label' => 'Second Class'],
        ['min' => 0.0, 'label' => 'Pass Class'],
    ];

    public function gradeFromMarks(?int $ext, ?int $int, bool $absentExt = false, bool $absentInt = false): array
    {
        if ($absentExt) {
            return [
                'grade'      => 'AB',
                'result'     => 'AB',
                'total_marks' => 0,
                'credits'    => 0,
                'gp_value'   => 0,
                'gp_credits' => 0,
            ];
        }

        $extTotal = 100;
        $intTotal = 20;

        $extMarks = $ext ?? 0;
        $intMarks = $absentInt ? 0 : ($int ?? 0);
        $total    = $extMarks + $intMarks;
        $maxTotal = $extTotal + $intTotal;

        $percentage = ($maxTotal > 0) ? round(($total / $maxTotal) * 100, 2) : 0;

        // Fail if external marks < 40% of external total
        if ($extMarks < ($extTotal * 0.4)) {
            return [
                'grade'      => 'F',
                'result'     => 'F',
                'total_marks' => $total,
                'credits'    => 0,
                'gp_value'   => 0,
                'gp_credits' => 0,
            ];
        }

        foreach (self::GRADE_SCALE as $scale) {
            if ($percentage >= $scale['min'] && $percentage <= $scale['max']) {
                return [
                    'grade'      => $scale['grade'],
                    'result'     => 'P',
                    'total_marks' => $total,
                    'credits'    => 3.0, // Default credits; overridden by subject.credits if available
                    'gp_value'   => $scale['gpv'],
                    'gp_credits' => 3.0 * $scale['gpv'],
                ];
            }
        }

        // Below 40%
        return [
            'grade'      => 'F',
            'result'     => 'F',
            'total_marks' => $total,
            'credits'    => 0,
            'gp_value'   => 0,
            'gp_credits' => 0,
        ];
    }

    public function calculateBatch(int $examId): void
    {
        $enrollments = ExamEnrollment::forExam($examId)
            ->feePaid()
            ->with(['results' => fn ($q) => $q->excludeGradeEx()])
            ->get();

        foreach ($enrollments as $enrollment) {
            $results = $enrollment->results;

            if ($results->isEmpty()) {
                continue;
            }

            $totalCredits  = $results->sum('credits');
            $totalGpc      = $results->sum('gp_credits');
            $totalMarks    = $results->sum('total_marks');
            $hasFailed     = $results->contains(fn ($r) => in_array($r->result, ['F', 'AB']));

            $sgpa = ($totalCredits > 0) ? round($totalGpc / $totalCredits, 2) : 0.0;

            // Apply floatation: exactly one failing paper → promote
            $failedCount = $results->where('result', 'F')->count();
            if ($failedCount === 1) {
                $this->applyFloatation($enrollment->id);
                $hasFailed = false;
            }

            $overallResult = $hasFailed ? 'F' : 'P';

            $enrollment->gpa()->updateOrCreate(
                ['enrollment_id' => $enrollment->id],
                [
                    'hall_ticket' => $enrollment->hall_ticket,
                    'exam_id'     => $examId,
                    'sgpa'        => $sgpa,
                    'cgpa'        => $sgpa, // CGPA will be recalculated when degree grade is generated
                    'total_marks' => $totalMarks,
                    'result'      => $overallResult,
                    'processed_at' => now(),
                ]
            );
        }
    }

    public function applyFloatation(int $enrollmentId): void
    {
        Result::where('enrollment_id', $enrollmentId)
            ->where('result', 'F')
            ->update(['passed_by_floatation' => true, 'result' => 'P']);
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
        foreach (self::DIVISION_SCALE as $scale) {
            if ($cgpa >= $scale['min']) {
                return $scale['label'];
            }
        }

        return 'Pass Class';
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
