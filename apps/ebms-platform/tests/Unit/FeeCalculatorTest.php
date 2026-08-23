<?php

namespace Tests\Unit;

use App\Models\Exam;
use App\Services\FeeCalculatorService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FeeCalculatorTest extends TestCase
{
    private FeeCalculatorService $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new FeeCalculatorService();
    }

    private function regularExam(int $regular): Exam
    {
        $exam              = new Exam();
        $exam->exam_type   = 'regular';
        $exam->fee_regular = $regular;

        return $exam;
    }

    private function supplyExam(int $upto2, int $regular): Exam
    {
        $exam                  = new Exam();
        $exam->exam_type       = 'supplementary';
        $exam->fee_regular     = $regular;
        $exam->fee_supply_upto2 = $upto2;

        return $exam;
    }

    // ── Regular exams ─────────────────────────────────────────────────────────

    #[Test]
    public function regular_exam_always_charges_flat_fee(): void
    {
        $exam = $this->regularExam(650);

        $this->assertEquals(650, $this->calculator->calculate($exam, 1));
        $this->assertEquals(650, $this->calculator->calculate($exam, 2));
        $this->assertEquals(650, $this->calculator->calculate($exam, 5));
    }

    // ── Supplementary exams ──────────────────────────────────────────────────

    #[Test]
    public function supply_exam_charges_per_subject_for_one_subject(): void
    {
        $exam = $this->supplyExam(upto2: 450, regular: 550);

        $this->assertEquals(450, $this->calculator->calculate($exam, 1));
    }

    #[Test]
    public function supply_exam_charges_flat_fee_for_two_subjects(): void
    {
        $exam = $this->supplyExam(upto2: 450, regular: 550);

        $this->assertEquals(450, $this->calculator->calculate($exam, 2));
    }

    #[Test]
    public function supply_exam_charges_flat_regular_for_three_or_more_subjects(): void
    {
        $exam = $this->supplyExam(upto2: 450, regular: 550);

        $this->assertEquals(550, $this->calculator->calculate($exam, 3));
        $this->assertEquals(550, $this->calculator->calculate($exam, 6));
    }

    // ── Improvement exams ────────────────────────────────────────────────────

    #[Test]
    public function improvement_exam_charges_per_subject_with_no_threshold(): void
    {
        $exam                  = new Exam();
        $exam->exam_type       = 'improvement';
        $exam->fee_regular     = 650;
        $exam->fee_improvement = 300;

        $this->assertEquals(300,  $this->calculator->calculate($exam, 1));
        $this->assertEquals(600,  $this->calculator->calculate($exam, 2));
        $this->assertEquals(900,  $this->calculator->calculate($exam, 3));
        $this->assertEquals(1200, $this->calculator->calculate($exam, 4));
    }

    // ── Fine ──────────────────────────────────────────────────────────────────

    #[Test]
    public function late_fine_is_added_on_top_of_any_fee(): void
    {
        $exam           = $this->supplyExam(upto2: 450, regular: 550);
        $exam->fee_fine = 50;

        $this->assertEquals(500, $this->calculator->calculate($exam, 1)); // 450 + 50
        $this->assertEquals(600, $this->calculator->calculate($exam, 3)); // 550 + 50
    }

    // ── Edge cases ───────────────────────────────────────────────────────────

    #[Test]
    public function returns_zero_when_no_fee_configured(): void
    {
        $exam = new Exam();

        $this->assertEquals(0, $this->calculator->calculate($exam, 2));
    }
}
