<?php

namespace Tests\Feature\Student;

use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function student_can_view_open_exams(): void
    {
        $student = Student::factory()->create(['course' => 'BA', 'dob' => '2000-01-01']);
        Exam::factory()->open()->create(['course' => 'BA']);

        $response = $this->actingAs($student, 'student')
            ->get('/student/enrollments/create');

        $response->assertOk();
        $response->assertViewHas('exams');
    }

    #[Test]
    public function student_cannot_enroll_twice_in_same_exam(): void
    {
        $student = Student::factory()->create(['course' => 'BA', 'dob' => '2000-01-01']);
        $exam    = Exam::factory()->open()->create(['course' => 'BA', 'semester' => 3]);
        $subject = Subject::factory()->create(['course' => 'BA', 'semester' => 3, 'paper_type' => 'compulsory']);

        ExamEnrollment::factory()->create([
            'exam_id'    => $exam->id,
            'student_id' => $student->id,
            'hall_ticket' => $student->hall_ticket,
        ]);

        // Simulate pending enrollment session
        $pendingData = [
            'exam_id'     => $exam->id,
            'subject_ids' => [$subject->id],
            'fee_amount'  => 500,
        ];

        $response = $this->actingAs($student, 'student')
            ->withSession(['pending_enrollment' => $pendingData])
            ->post('/student/enrollments');

        $response->assertRedirect('/student/enrollments');
        $response->assertSessionHas('info');
        $this->assertDatabaseCount('exam_enrollments', 1);
    }

    #[Test]
    public function results_require_fee_payment(): void
    {
        $student = Student::factory()->create(['dob' => '2000-01-01']);
        $exam    = Exam::factory()->closed()->create();
        ExamEnrollment::factory()->feePending()->create([
            'exam_id'    => $exam->id,
            'student_id' => $student->id,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get("/student/results/{$exam->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function challan_accessible_before_fee_paid(): void
    {
        $student    = Student::factory()->create(['dob' => '2000-01-01']);
        $exam       = Exam::factory()->open()->create();
        $enrollment = ExamEnrollment::factory()->feePending()->create([
            'exam_id'    => $exam->id,
            'student_id' => $student->id,
        ]);

        // The ChallanController returns a PDF response — just check 200/not-403
        $response = $this->actingAs($student, 'student')
            ->get("/student/challan/{$enrollment->id}");

        $this->assertNotEquals(403, $response->getStatusCode());
    }
}
