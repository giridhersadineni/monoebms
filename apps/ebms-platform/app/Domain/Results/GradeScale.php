<?php

namespace App\Domain\Results;

/**
 * The UGC 2025-26 grade bands and degree division scale.
 *
 * Grade letter and grade point share one table so the two can never drift
 * apart — they are read off the same band.
 */
final class GradeScale
{
    /** Non-numeric paper outcomes carry their own letter and score zero. */
    private const NON_GRADED = ['AB', 'MP', 'F'];

    /** [minimum percentage, grade letter, grade point], highest band first. */
    private const BANDS = [
        [90.0, 'O',  10.0],
        [75.0, 'A+', 9.0],
        [60.0, 'A',  8.0],
        [55.0, 'B+', 7.0],
        [50.0, 'B',  6.0],
        [45.0, 'C',  5.0],
        [40.0, 'P',  4.0],
    ];

    private const DIVISION_SCALE = [
        ['min' => 7.0, 'label' => 'First Class with Distinction'],
        ['min' => 6.0, 'label' => 'First Class'],
        ['min' => 5.0, 'label' => 'Second Class'],
        ['min' => 0.0, 'label' => 'Pass Class'],
    ];

    public function grade(string $result, float $percentage): string
    {
        if (in_array($result, self::NON_GRADED, true)) {
            return $result;
        }

        return $this->band($percentage)[1] ?? 'F';
    }

    public function gradePoint(string $result, float $percentage): float
    {
        if (in_array($result, self::NON_GRADED, true)) {
            return 0.0;
        }

        return $this->band($percentage)[2] ?? 0.0;
    }

    public function division(float $cgpa): string
    {
        foreach (self::DIVISION_SCALE as $scale) {
            if ($cgpa >= $scale['min']) {
                return $scale['label'];
            }
        }

        return 'Pass Class';
    }

    /** @return array{0: float, 1: string, 2: float}|null */
    private function band(float $percentage): ?array
    {
        foreach (self::BANDS as $band) {
            if ($percentage >= $band[0]) {
                return $band;
            }
        }

        return null;
    }
}
