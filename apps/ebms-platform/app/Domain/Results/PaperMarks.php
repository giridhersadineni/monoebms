<?php

namespace App\Domain\Results;

use App\Models\Result;

/**
 * Every input a single paper's grade is derived from.
 *
 * Grouping these keeps the calculator's signature stable — the marks and the
 * context they are judged against (totals, credits, floatation) travel together.
 */
final class PaperMarks
{
    public function __construct(
        public readonly ?int $ext,
        public readonly ?int $int,
        public readonly bool $absentExt = false,
        public readonly bool $absentInt = false,
        public readonly int $extTotal = 100,
        public readonly int $intTotal = 20,
        public readonly float $credits = 3.0,
        public readonly int $floatation = 0,
        public readonly int $acMarks = 0,
        public readonly int $floatDeduct = 0,
        public readonly bool $malpractice = false,
    ) {}

    /**
     * Build from a stored row, so a paper can be recalculated from what is
     * already persisted without anyone re-entering the marks.
     *
     * `itotal` distinguishes null from 0: the column is nullable, and a stored 0
     * means a practical paper with no internal component (which auto-passes the
     * internal check) rather than "unset".
     *
     * `credits` is taken exactly as stored, including 0. Recalculation must never
     * invent a credit weight — a paper stored with 0 credits is a data problem to
     * be corrected at the source, not silently papered over here (defaulting it
     * would rewrite the row and change the SGPA behind the admin's back).
     */
    public static function fromResult(Result $result): self
    {
        return new self(
            ext:         $result->ext_marks,
            int:         $result->int_marks,
            absentExt:   (bool) $result->is_absent_ext,
            absentInt:   (bool) $result->is_absent_int,
            extTotal:    (int) ($result->etotal ?: 100),
            intTotal:    $result->itotal === null ? 20 : (int) $result->itotal,
            credits:     (float) $result->credits,
            floatation:  (int) ($result->floatation_marks ?? 0),
            acMarks:     (int) ($result->ac_marks ?? 0),
            floatDeduct: (int) ($result->float_deduct ?? 0),
            malpractice: (bool) $result->is_malpractice,
        );
    }

    /** Same paper context, different marks — used when an admin edits marks by hand. */
    public function withMarks(?int $ext, ?int $int, bool $absentExt, bool $absentInt): self
    {
        return new self(
            ext:         $ext,
            int:         $int,
            absentExt:   $absentExt,
            absentInt:   $absentInt,
            extTotal:    $this->extTotal,
            intTotal:    $this->intTotal,
            credits:     $this->credits,
            floatation:  $this->floatation,
            acMarks:     $this->acMarks,
            floatDeduct: $this->floatDeduct,
            malpractice: $this->malpractice,
        );
    }

    /**
     * Same paper, different credit weight — used when an admin corrects a paper
     * whose stored credits are wrong (legacy migration artefacts, mostly).
     */
    public function withCredits(float $credits): self
    {
        return new self(
            ext:         $this->ext,
            int:         $this->int,
            absentExt:   $this->absentExt,
            absentInt:   $this->absentInt,
            extTotal:    $this->extTotal,
            intTotal:    $this->intTotal,
            credits:     $credits,
            floatation:  $this->floatation,
            acMarks:     $this->acMarks,
            floatDeduct: $this->floatDeduct,
            malpractice: $this->malpractice,
        );
    }

    /** True when grace marks contributed to this paper's external score. */
    public function hasFloatation(): bool
    {
        return $this->floatation !== 0 || $this->acMarks !== 0 || $this->floatDeduct !== 0;
    }
}
