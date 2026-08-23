<?php

namespace App\Domain\Results;

/**
 * Computes a single paper's result, grade and grade points.
 *
 * Mirrors the legacy postexam floatation/GPA scripts (UGC 2025-26 scale),
 * ported without their bugs: floatation marks are folded into the external
 * score once (raw marks are stored separately, so recomputing is idempotent),
 * the external pass check is measured against the external total (ETOTAL),
 * and malpractice takes precedence over a pass.
 *
 * Pass rule: external >= 40% of ETOTAL AND internal >= 40% of ITOTAL.
 * Practical papers (ITOTAL = 0) auto-pass the internal component.
 *
 * Failed/absent/malpractice papers still carry their full credits so they
 * count in the SGPA denominator (with a grade point of 0).
 */
final class PaperCalculator
{
    private const PASS_RATIO = 0.40;

    public function __construct(private readonly GradeScale $scale = new GradeScale()) {}

    public function calculate(PaperMarks $marks): PaperOutcome
    {
        // Floatation/AC marks are applied to the external score once; the raw
        // ext marks are persisted elsewhere so this stays idempotent on re-run.
        $rawExt       = $marks->absentExt ? 0 : (int) ($marks->ext ?? 0);
        $effectiveExt = max(0, $rawExt + $marks->floatation + $marks->acMarks - $marks->floatDeduct);
        $intMarks     = $marks->absentInt ? 0 : (int) ($marks->int ?? 0);

        $totalMarks = $marks->absentExt ? 0 : ($effectiveExt + $intMarks);
        $maxTotal   = $marks->extTotal + $marks->intTotal;
        $percentage = ($maxTotal > 0) ? round(($totalMarks / $maxTotal) * 100, 2) : 0.0;

        $result = $this->resultFor($marks, $effectiveExt, $intMarks);
        $gpv    = $this->scale->gradePoint($result, $percentage);

        return new PaperOutcome(
            grade:      $this->scale->grade($result, $percentage),
            result:     $result,
            totalMarks: $totalMarks,
            credits:    $marks->credits,
            gpValue:    $gpv,
            gpCredits:  round($marks->credits * $gpv, 2),
        );
    }

    private function resultFor(PaperMarks $marks, int $effectiveExt, int $intMarks): string
    {
        if ($marks->absentExt) {
            return 'AB';
        }

        if ($marks->malpractice) {
            return 'MP';
        }

        $externalPass = $marks->extTotal > 0
            ? ($effectiveExt / $marks->extTotal) >= self::PASS_RATIO
            : $effectiveExt > 0;

        // Practical papers have no internal component and auto-pass it.
        if ($marks->intTotal === 0) {
            $internalPass = true;
        } elseif ($marks->absentInt) {
            $internalPass = false;
        } else {
            $internalPass = ($intMarks / $marks->intTotal) >= self::PASS_RATIO;
        }

        return ($externalPass && $internalPass) ? 'P' : 'F';
    }
}
