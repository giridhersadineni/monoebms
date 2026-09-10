<?php

namespace Tests\Unit\Domain;

use App\Domain\Results\PaperCalculator;
use App\Domain\Results\PaperMarks;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PaperCalculatorTest extends TestCase
{
    private PaperCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new PaperCalculator();
    }

    private function calculate(array $overrides = []): \App\Domain\Results\PaperOutcome
    {
        return $this->calculator->calculate(new PaperMarks(...$overrides));
    }

    // Default totals are ext 100 / int 20 (maxTotal 120); percentage = (ext + int) / 120 * 100.
    // Grade scale (UGC 2025-26): O>=90, A+>=75, A>=60, B+>=55, B>=50, C>=45, P>=40, else F.

    #[Test]
    public function applies_the_grade_scale_bands(): void
    {
        // [ext, int, expected grade, expected grade point]
        $cases = [
            [95, 18, 'O',  10.0],  // 113/120 = 94.17%
            [85, 17, 'A+', 9.0],   // 102/120 = 85%
            [72, 12, 'A',  8.0],   // 84/120  = 70%
            [56, 12, 'B+', 7.0],   // 68/120  = 56.67%
            [50, 11, 'B',  6.0],   // 61/120  = 50.83%
            [45, 10, 'C',  5.0],   // 55/120  = 45.83%
            [40, 9,  'P',  4.0],   // 49/120  = 40.83%
        ];

        foreach ($cases as [$ext, $int, $grade, $point]) {
            $outcome = $this->calculate(['ext' => $ext, 'int' => $int]);

            $this->assertSame($grade, $outcome->grade, "ext {$ext} / int {$int}");
            $this->assertSame($point, $outcome->gpValue, "ext {$ext} / int {$int}");
            $this->assertSame('P', $outcome->result);
        }
    }

    #[Test]
    public function fails_the_paper_when_external_is_below_forty_percent(): void
    {
        // 39/100 external is under the 40% bar even though the total percentage clears it.
        $outcome = $this->calculate(['ext' => 39, 'int' => 20]);

        $this->assertSame('F', $outcome->result);
        $this->assertSame('F', $outcome->grade);
        $this->assertSame(0.0, $outcome->gpValue);
    }

    #[Test]
    public function fails_the_paper_when_internal_is_below_forty_percent(): void
    {
        $outcome = $this->calculate(['ext' => 90, 'int' => 7]); // 7/20 = 35%

        $this->assertSame('F', $outcome->result);
    }

    #[Test]
    public function practical_papers_auto_pass_the_internal_component(): void
    {
        // ITOTAL 0 means there is no internal component to fail.
        $outcome = $this->calculate(['ext' => 60, 'int' => 0, 'extTotal' => 100, 'intTotal' => 0]);

        $this->assertSame('P', $outcome->result);
    }

    #[Test]
    public function absent_external_marks_the_paper_absent_and_zeroes_the_total(): void
    {
        $outcome = $this->calculate(['ext' => null, 'int' => 18, 'absentExt' => true]);

        $this->assertSame('AB', $outcome->result);
        $this->assertSame('AB', $outcome->grade);
        $this->assertSame(0, $outcome->totalMarks);
        $this->assertSame(0.0, $outcome->gpValue);
    }

    #[Test]
    public function malpractice_takes_precedence_over_a_pass(): void
    {
        $outcome = $this->calculate(['ext' => 95, 'int' => 18, 'malpractice' => true]);

        $this->assertSame('MP', $outcome->result);
        $this->assertSame('MP', $outcome->grade);
        $this->assertSame(0.0, $outcome->gpValue);
    }

    #[Test]
    public function failed_papers_still_carry_their_credits_into_the_sgpa_denominator(): void
    {
        $outcome = $this->calculate(['ext' => 10, 'int' => 2, 'credits' => 4.0]);

        $this->assertSame('F', $outcome->result);
        $this->assertSame(4.0, $outcome->credits);
        $this->assertSame(0.0, $outcome->gpCredits);
    }

    #[Test]
    public function floatation_and_ac_marks_lift_the_external_score(): void
    {
        // 38 external fails on its own; +2 floatation reaches the 40% bar.
        $this->assertSame('F', $this->calculate(['ext' => 38, 'int' => 10])->result);

        $lifted = $this->calculate(['ext' => 38, 'int' => 10, 'floatation' => 2]);
        $this->assertSame('P', $lifted->result);
        $this->assertSame(50, $lifted->totalMarks); // 38 + 2 grace + 10 internal
    }

    #[Test]
    public function float_deduct_is_subtracted_and_the_score_never_goes_negative(): void
    {
        $outcome = $this->calculate(['ext' => 3, 'int' => 10, 'floatDeduct' => 20]);

        $this->assertSame(10, $outcome->totalMarks); // external floored at 0, internal intact
    }

    #[Test]
    public function recalculating_the_same_marks_is_idempotent(): void
    {
        // The property that makes the Recalculate button safe to press repeatedly:
        // grace marks are folded in once, not compounded on each run.
        $marks = new PaperMarks(ext: 38, int: 10, floatation: 3, acMarks: 1, floatDeduct: 1);

        $first  = $this->calculator->calculate($marks);
        $second = $this->calculator->calculate($marks);

        $this->assertSame($first->toArray(), $second->toArray());
    }
}
