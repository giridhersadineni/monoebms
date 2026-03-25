<?php

namespace Tests\Feature\Student;

use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Models\Result;
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

    // ── Supplementary exam subject filtering ──────────────────────────────────

    #[Test]
    public function supplementary_exam_shows_only_failed_subjects(): void
    {
        $student = Student::factory()->create([
            'course'     => 'BSC',
            'group_code' => 'MPC',
            'medium'     => 'EM',
            'scheme'     => 'CBCS',
            'semester'   => 3,
        ]);

        $regularExam = Exam::factory()->closed()->create([
            'course'     => 'BSC',
            'semester'   => 3,
            'exam_type'  => 'regular',
        ]);

        $failedSubject = Subject::factory()->create([
            'course'     => 'BSC',
            'group_code' => 'MPC',
            'medium'     => 'EM',
            'semester'   => 3,
            'scheme'     => 'CBCS',
        ]);
        $passedSubject = Subject::factory()->create([
            'course'     => 'BSC',
            'group_code' => 'MPC',
            'medium'     => 'EM',
            'semester'   => 3,
            'scheme'     => 'CBCS',
        ]);

        Result::create([
            'hall_ticket' => $student->hall_ticket,
            'exam_id'     => $regularExam->id,
            'subject_id'  => $failedSubject->id,
            'result'      => 'F',
            'semester'    => 3,
            'total_marks' => 100,
        ]);
        Result::create([
            'hall_ticket' => $student->hall_ticket,
            'exam_id'     => $regularExam->id,
            'subject_id'  => $passedSubject->id,
            'result'      => 'P',
            'semester'    => 3,
            'total_marks' => 100,
        ]);

        $supplyExam = Exam::factory()->open()->create([
            'course'           => 'BSC',
            'semester'         => 3,
            'exam_type'        => 'supplementary',
            'fee_regular'      => 750,
            'fee_supply_upto2' => 550,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get("/student/enrollments/subjects?exam_id={$supplyExam->id}");

        $response->assertOk();
        $response->assertSee($failedSubject->name);
        $response->assertDontSee($passedSubject->name);
    }

    #[Test]
    public function supplementary_exam_shows_no_subjects_when_student_passed_all(): void
    {
        $student = Student::factory()->create([
            'course'     => 'BSC',
            'group_code' => 'MPC',
            'medium'     => 'EM',
            'scheme'     => 'CBCS',
            'semester'   => 3,
        ]);

        $regularExam = Exam::factory()->closed()->create([
            'course'    => 'BSC',
            'semester'  => 3,
            'exam_type' => 'regular',
        ]);

        $subject = Subject::factory()->create([
            'course'     => 'BSC',
            'group_code' => 'MPC',
            'medium'     => 'EM',
            'semester'   => 3,
            'scheme'     => 'CBCS',
        ]);

        Result::create([
            'hall_ticket' => $student->hall_ticket,
            'exam_id'     => $regularExam->id,
            'subject_id'  => $subject->id,
            'result'      => 'P',
            'semester'    => 3,
            'total_marks' => 100,
        ]);

        $supplyExam = Exam::factory()->open()->create([
            'course'    => 'BSC',
            'semester'  => 3,
            'exam_type' => 'supplementary',
        ]);

        $response = $this->actingAs($student, 'student')
            ->get("/student/enrollments/subjects?exam_id={$supplyExam->id}");

        $response->assertOk();
        $response->assertDontSee($subject->name);
    }

    // ── Enrollment success page ───────────────────────────────────────────────

    #[Test]
    public function student_can_view_own_enrollment_success_page(): void
    {
        $student    = Student::factory()->create();
        $enrollment = ExamEnrollment::factory()->feePending()->create([
            'student_id' => $student->id,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get("/student/enrollments/{$enrollment->id}/success");

        $response->assertOk();
        $response->assertViewIs('student.enrollments.success');
    }

    #[Test]
    public function student_cannot_view_another_students_success_page(): void
    {
        $student    = Student::factory()->create();
        $other      = Student::factory()->create();
        $enrollment = ExamEnrollment::factory()->feePending()->create([
            'student_id' => $other->id,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get("/student/enrollments/{$enrollment->id}/success");

        $response->assertForbidden();
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
