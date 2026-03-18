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

    #[Test]
    public function grade_o_for_marks_85_to_100(): void
    {
        $result = $this->calculator->gradeFromMarks(72, 12, false, false); // 84/120 = 70%
        $this->assertEquals('A', $result['grade']);
        $this->assertEquals('P', $result['result']);
    }

    #[Test]
    public function grade_f_when_external_below_40_percent(): void
    {
        // External: 30/100 = 30% (below 40%)
        $result = $this->calculator->gradeFromMarks(30, 15, false, false);
        $this->assertEquals('F', $result['grade']);
        $this->assertEquals('F', $result['result']);
        $this->assertEquals(0, $result['gp_credits']);
    }

    #[Test]
    public function grade_f_when_overall_below_40_percent(): void
    {
        // External: 42/100, Internal: 5/20 → total 47/120 = 39.2% < 40%
        $result = $this->calculator->gradeFromMarks(42, 5, false, false);
        $this->assertEquals('F', $result['grade']);
        $this->assertEquals('F', $result['result']);
    }

    #[Test]
    public function absent_external_returns_ab_result(): void
    {
        $result = $this->calculator->gradeFromMarks(null, 15, true, false);
        $this->assertEquals('AB', $result['grade']);
        $this->assertEquals('AB', $result['result']);
        $this->assertEquals(0, $result['gp_value']);
        $this->assertEquals(0, $result['gp_credits']);
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

    #[Test]
    public function grade_e_for_marks_40_to_49_percent(): void
    {
        // 44/120 = 36.7% → F (below 40%)
        // 52/120 = 43.3% → E
        $result = $this->calculator->gradeFromMarks(45, 7, false, false); // 52/120 = 43.3%
        $this->assertEquals('E', $result['grade']);
        $this->assertEquals('P', $result['result']);
        $this->assertEquals(5.0, $result['gp_value']);
    }

    #[Test]
    public function grade_o_for_marks_85_percent_and_above(): void
    {
        // 102/120 = 85% → O
        $result = $this->calculator->gradeFromMarks(85, 17, false, false); // 102/120 = 85%
        $this->assertEquals('O', $result['grade']);
        $this->assertEquals(10.0, $result['gp_value']);
    }
}
