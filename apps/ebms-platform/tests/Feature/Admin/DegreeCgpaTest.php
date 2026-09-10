<?php

namespace Tests\Feature\Admin;

use App\Models\ExamEnrollment;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Services\GpaCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DegreeCgpaTest extends TestCase
{
    use RefreshDatabase;

    private GpaCalculatorService $calculator;

    private Student $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new GpaCalculatorService();
        $this->student    = Student::factory()->create(['hall_ticket' => '001251009']);
    }

    private function sitting(): ExamEnrollment
    {
        return ExamEnrollment::factory()->create([
            'student_id'  => $this->student->id,
            'hall_ticket' => $this->student->hall_ticket,
        ]);
    }

    private function paper(
        ExamEnrollment $enrollment,
        Subject $subject,
        string $result,
        float $credits,
        float $gpValue
    ): Result {
        return Result::create([
            'enrollment_id' => $enrollment->id,
            'subject_id'    => $subject->id,
            'hall_ticket'   => $enrollment->hall_ticket,
            'exam_id'       => $enrollment->exam_id,
            'grade'         => $result === 'P' ? 'A' : $result,
            'result'        => $result,
            'credits'       => $credits,
            'gp_value'      => $gpValue,
            'gp_credits'    => $credits * $gpValue,
            'part'          => 1,
        ]);
    }

    #[Test]
    public function a_paper_cleared_twice_counts_once_and_keeps_the_better_attempt(): void
    {
        // One paper under two subjects rows — same code, different medium, which
        // is how a regular and an improvement sitting end up on different
        // subject_ids. Cleared at grade point 4, then again at 10.
        $regularRow     = Subject::factory()->create(['code' => 'ENG101', 'medium' => 'EM']);
        $improvementRow = Subject::factory()->create(['code' => 'ENG101', 'medium' => 'TM']);

        $this->paper($this->sitting(), $regularRow, 'P', 4.0, 4.0);
        $this->paper($this->sitting(), $improvementRow, 'P', 4.0, 10.0);

        // A second paper at grade point 6.
        $this->paper($this->sitting(), Subject::factory()->create(), 'P', 4.0, 6.0);

        $cgpa = $this->calculator->calculateDegreeCgpa($this->student->id);

        // 8 credits, 64 grade points = 8.0. Counting ENG101 twice would give
        // 12 credits and 80 grade points = 6.67.
        $this->assertEquals(8.0, $cgpa['part1_cgpa']);
        $this->assertEquals(8.0, $cgpa['all_cgpa']);
    }

    #[Test]
    public function promoted_and_malpractice_papers_carry_no_credits(): void
    {
        $this->paper($this->sitting(), Subject::factory()->create(), 'P', 4.0, 10.0);
        $this->paper($this->sitting(), Subject::factory()->create(), 'P', 4.0, 6.0);

        $sitting = $this->sitting();
        $this->paper($sitting, Subject::factory()->create(), 'R', 4.0, 0.0);
        $this->paper($sitting, Subject::factory()->create(), 'M', 4.0, 0.0);

        $cgpa = $this->calculator->calculateDegreeCgpa($this->student->id);

        // 8 credits, 64 grade points = 8.0. Letting R and M into the
        // denominator would give 16 credits and 64 points = 4.0.
        $this->assertEquals(8.0, $cgpa['all_cgpa']);
    }

    #[Test]
    public function failed_and_absent_attempts_do_not_displace_the_pass(): void
    {
        // The ordinary path: failed in the regular exam, passed in the supply.
        $paper   = Subject::factory()->create(['code' => 'HIS101']);
        $retaken = Subject::factory()->create(['code' => 'HIS101', 'medium' => 'TM']);

        $this->paper($this->sitting(), $paper, 'F', 4.0, 0.0);
        $this->paper($this->sitting(), $retaken, 'P', 4.0, 7.0);

        $cgpa = $this->calculator->calculateDegreeCgpa($this->student->id);

        $this->assertEquals(7.0, $cgpa['all_cgpa']);
    }

    #[Test]
    public function a_student_with_no_countable_papers_scores_zero(): void
    {
        $this->paper($this->sitting(), Subject::factory()->create(), 'F', 4.0, 0.0);

        $cgpa = $this->calculator->calculateDegreeCgpa($this->student->id);

        $this->assertEquals(0.0, $cgpa['all_cgpa']);
        $this->assertEquals('Pass Class', $cgpa['final_division']);
    }
}
