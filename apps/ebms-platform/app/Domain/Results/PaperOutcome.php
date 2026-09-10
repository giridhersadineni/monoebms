<?php

namespace App\Domain\Results;

/** What a paper's marks resolve to once the scale has been applied. */
final class PaperOutcome
{
    public function __construct(
        public readonly string $grade,
        public readonly string $result,
        public readonly int $totalMarks,
        public readonly float $credits,
        public readonly float $gpValue,
        public readonly float $gpCredits,
    ) {}

    /** The derived columns only — never the marks the outcome was derived from. */
    public function toArray(): array
    {
        return [
            'grade'       => $this->grade,
            'result'      => $this->result,
            'total_marks' => $this->totalMarks,
            'credits'     => $this->credits,
            'gp_value'    => $this->gpValue,
            'gp_credits'  => $this->gpCredits,
        ];
    }

    public function isPassed(): bool
    {
        return $this->result === 'P';
    }
}
