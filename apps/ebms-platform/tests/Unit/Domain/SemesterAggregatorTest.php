<?php

namespace Tests\Unit\Domain;

use App\Domain\Results\SemesterAggregator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SemesterAggregatorTest extends TestCase
{
    private SemesterAggregator $aggregator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->aggregator = new SemesterAggregator();
    }

    /** Stand-in for a persisted result row — the aggregator only reads these four fields. */
    private function paper(string $result, float $credits, float $gpCredits, int $totalMarks): object
    {
        return (object) [
            'result'      => $result,
            'credits'     => $credits,
            'gp_credits'  => $gpCredits,
            'total_marks' => $totalMarks,
        ];
    }

    #[Test]
    public function sgpa_is_grade_points_weighted_by_credits(): void
    {
        $totals = $this->aggregator->aggregate([
            $this->paper('P', 4.0, 32.0, 84),  // 8.0 grade point
            $this->paper('P', 3.0, 18.0, 60),  // 6.0 grade point
        ]);

        // 50 grade-point-credits over 7 credits.
        $this->assertSame(7.14, $totals['sgpa']);
        $this->assertSame(144, $totals['total_marks']);
        $this->assertSame('P', $totals['result']);
    }

    #[Test]
    public function any_malpractice_paper_makes_the_semester_mp(): void
    {
        $totals = $this->aggregator->aggregate([
            $this->paper('P', 4.0, 32.0, 84),
            $this->paper('MP', 3.0, 0.0, 0),
            $this->paper('F', 3.0, 0.0, 20),
        ]);

        $this->assertSame('MP', $totals['result']);
    }

    #[Test]
    public function a_failed_or_absent_paper_makes_the_semester_r(): void
    {
        $failed = $this->aggregator->aggregate([
            $this->paper('P', 4.0, 32.0, 84),
            $this->paper('F', 3.0, 0.0, 20),
        ]);
        $this->assertSame('R', $failed['result']);

        $absent = $this->aggregator->aggregate([
            $this->paper('P', 4.0, 32.0, 84),
            $this->paper('AB', 3.0, 0.0, 0),
        ]);
        $this->assertSame('R', $absent['result']);
    }

    #[Test]
    public function zero_credits_does_not_divide_by_zero(): void
    {
        $totals = $this->aggregator->aggregate([
            $this->paper('AB', 0.0, 0.0, 0),
        ]);

        $this->assertSame(0.0, $totals['sgpa']);
    }
}
