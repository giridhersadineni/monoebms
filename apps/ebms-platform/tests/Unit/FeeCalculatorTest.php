<?php

namespace Tests\Unit;

use App\Models\Exam;
use App\Models\Student;
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

    #[Test]
    public function calculates_regular_fee_for_course(): void
    {
        $exam = new Exam();
        $exam->fee_json = ['BA' => ['regular' => 500, 'above_2_sem' => 700]];
        $exam->semester = 3;

        $student = new Student();
        $student->course = 'BA';
        $student->semester = 3;

        $fee = $this->calculator->calculate($exam, $student);

        $this->assertEquals(500, $fee);
    }

    #[Test]
    public function calculates_above_2_sem_fee(): void
    {
        $exam = new Exam();
        $exam->fee_json = ['BCOM' => ['regular' => 600, 'above_2_sem' => 900]];
        $exam->semester = 1; // Student is in sem 5 taking sem 1 = >2 sems behind

        $student = new Student();
        $student->course = 'BCOM';
        $student->semester = 5;

        $fee = $this->calculator->calculate($exam, $student);

        $this->assertEquals(900, $fee);
    }

    #[Test]
    public function returns_zero_when_course_not_in_fee_json(): void
    {
        $exam = new Exam();
        $exam->fee_json = ['BA' => ['regular' => 500, 'above_2_sem' => 700]];
        $exam->semester = 3;

        $student = new Student();
        $student->course = 'BSC';
        $student->semester = 3;

        $fee = $this->calculator->calculate($exam, $student);

        $this->assertEquals(0, $fee);
    }
}
