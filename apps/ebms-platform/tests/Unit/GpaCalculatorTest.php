<?php

namespace Tests\Unit;

use App\Services\GpaCalculatorService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GpaCalculatorTest extends TestCase
{
    private GpaCalculatorService $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new GpaCalculatorService();
    }

    // Default totals are ext 100 / int 20 (maxTotal 120); percentage = (ext + int) / 120 * 100.
    // Grade scale (UGC 2025-26): O>=90, A+>=75, A>=60, B+>=55, B>=50, C>=45, P>=40, else F.

    #[Test]
    public function grade_o_for_90_percent_and_above(): void
    {
        // ext 95 (95% >= 40), int 18 (90% >= 40) → 113/120 = 94.17%
        $result = $this->calculator->gradeFromMarks(95, 18, false, false);
        $this->assertEquals('O', $result['grade']);
        $this->assertEquals('P', $result['result']);
        $this->assertEquals(10.0, $result['gp_value']);
    }

    #[Test]
    public function grade_a_plus_for_75_to_89_percent(): void
    {
        // ext 85, int 17 → 102/120 = 85%
        $result = $this->calculator->gradeFromMarks(85, 17, false, false);
        $this->assertEquals('A+', $result['grade']);
        $this->assertEquals(9.0, $result['gp_value']);
    }

    #[Test]
    public function grade_a_for_60_to_74_percent(): void
    {
        // ext 72, int 12 → 84/120 = 70%
        $result = $this->calculator->gradeFromMarks(72, 12, false, false);
        $this->assertEquals('A', $result['grade']);
        $this->assertEquals('P', $result['result']);
        $this->assertEquals(8.0, $result['gp_value']);
    }

    #[Test]
    public function grade_p_for_40_to_44_percent(): void
    {
        // ext 42 (42% >= 40), int 9 (45% >= 40) → 51/120 = 42.5%
        $result = $this->calculator->gradeFromMarks(42, 9, false, false);
        $this->assertEquals('P', $result['grade']);
        $this->assertEquals('P', $result['result']);
        $this->assertEquals(4.0, $result['gp_value']);
    }

    #[Test]
    public function fails_when_external_below_40_percent(): void
    {
        // ext 30/100 = 30% (< 40%) → F regardless of internal
        $result = $this->calculator->gradeFromMarks(30, 18, false, false);
        $this->assertEquals('F', $result['grade']);
        $this->assertEquals('F', $result['result']);
        $this->assertEquals(0, $result['gp_credits']);
    }

    #[Test]
    public function fails_when_internal_below_40_percent(): void
    {
        // ext 60 (passes), int 5/20 = 25% (< 40%) → F on internal
        $result = $this->calculator->gradeFromMarks(60, 5, false, false);
        $this->assertEquals('F', $result['grade']);
        $this->assertEquals('F', $result['result']);
    }

    #[Test]
    public function practical_paper_auto_passes_internal(): void
    {
        // No internal component (intTotal 0); ext 60/100 = 60% → passes, 60/100 = 60% overall
        $result = $this->calculator->gradeFromMarks(60, 0, false, false, 100, 0);
        $this->assertEquals('P', $result['result']);
        $this->assertEquals('A', $result['grade']);
    }

    #[Test]
    public function absent_external_returns_ab(): void
    {
        $result = $this->calculator->gradeFromMarks(null, 15, true, false);
        $this->assertEquals('AB', $result['grade']);
        $this->assertEquals('AB', $result['result']);
        $this->assertEquals(0, $result['gp_value']);
        $this->assertEquals(0, $result['gp_credits']);
    }

    #[Test]
    public function malpractice_takes_precedence_over_pass(): void
    {
        // Marks would otherwise pass, but malpractice flag forces MP
        $result = $this->calculator->gradeFromMarks(80, 16, false, false, 100, 20, 3.0, 0, 0, 0, true);
        $this->assertEquals('MP', $result['result']);
        $this->assertEquals('MP', $result['grade']);
        $this->assertEquals(0, $result['gp_value']);
    }

    #[Test]
    public function failed_paper_keeps_full_credits_in_denominator(): void
    {
        // Failing papers still carry their credits (grade point 0) so they count in SGPA denominator
        $result = $this->calculator->gradeFromMarks(30, 5, false, false, 100, 20, 4.0);
        $this->assertEquals('F', $result['result']);
        $this->assertEquals(4.0, $result['credits']);
        $this->assertEquals(0.0, $result['gp_credits']);
    }

    #[Test]
    public function floatation_marks_are_added_to_external_once(): void
    {
        // Raw ext 36 (36% → would fail); +6 floatation → 42 (42% → passes at grade P)
        $result = $this->calculator->gradeFromMarks(36, 9, false, false, 100, 20, 3.0, 6);
        $this->assertEquals('P', $result['result']);
        $this->assertEquals(51, $result['total_marks']); // 42 + 9
    }

    #[Test]
    public function division_from_cgpa_boundaries(): void
    {
        $this->assertEquals('First Class with Distinction', $this->calculator->divisionFromCgpa(7.0));
        $this->assertEquals('First Class with Distinction', $this->calculator->divisionFromCgpa(8.5));
        $this->assertEquals('First Class', $this->calculator->divisionFromCgpa(6.0));
        $this->assertEquals('First Class', $this->calculator->divisionFromCgpa(6.9));
        $this->assertEquals('Second Class', $this->calculator->divisionFromCgpa(5.0));
        $this->assertEquals('Second Class', $this->calculator->divisionFromCgpa(5.9));
        $this->assertEquals('Pass Class', $this->calculator->divisionFromCgpa(4.9));
        $this->assertEquals('Pass Class', $this->calculator->divisionFromCgpa(0.0));
    }
}
