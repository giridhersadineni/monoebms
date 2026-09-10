<?php

namespace App\Domain\Results;

use Illuminate\Support\Collection;

/**
 * Rolls a semester's papers up into SGPA, total marks and an overall result.
 *
 * Overall result rollup (per UGC 2025-26 postexam rules):
 *   any malpractice paper   → MP
 *   any failed/absent paper → R (backlog / result withheld)
 *   otherwise               → P
 */
final class SemesterAggregator
{
    /**
     * @param  iterable  $papers  Rows exposing credits, gp_credits, total_marks and result.
     * @return array{sgpa: float, total_marks: int, result: string}
     */
    public function aggregate(iterable $papers): array
    {
        $papers = Collection::make($papers);

        $totalCredits = (float) $papers->sum('credits');
        $totalGpc     = (float) $papers->sum('gp_credits');

        return [
            'sgpa'        => $totalCredits > 0 ? round($totalGpc / $totalCredits, 2) : 0.0,
            'total_marks' => (int) $papers->sum('total_marks'),
            'result'      => $this->overallResult($papers),
        ];
    }

    private function overallResult(Collection $papers): string
    {
        return match (true) {
            $papers->contains(fn ($p) => $p->result === 'MP')                    => 'MP',
            $papers->contains(fn ($p) => in_array($p->result, ['F', 'AB'], true)) => 'R',
            default                                                               => 'P',
        };
    }
}
