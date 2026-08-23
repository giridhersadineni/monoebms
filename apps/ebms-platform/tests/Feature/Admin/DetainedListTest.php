<?php

namespace Tests\Feature\Admin;

use App\Models\AdminUser;
use App\Models\ExamEnrollment;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DetainedListTest extends TestCase
{
    use RefreshDatabase;

    private function paper(ExamEnrollment $enrollment, Subject $subject, string $result, float $credits, array $overrides = []): Result
    {
        return Result::create(array_merge([
            'enrollment_id' => $enrollment->id,
            'subject_id'    => $subject->id,
            'hall_ticket'   => $enrollment->hall_ticket,
            'exam_id'       => $enrollment->exam_id,
            'ext_marks'     => $result === 'P' ? 60 : 20,
            'int_marks'     => 10,
            'credits'       => $credits,
            'grade'         => $result === 'P' ? 'B' : 'F',
            'result'        => $result,
        ], $overrides));
    }

    #[Test]
    public function it_lists_a_student_below_the_cutoff_and_omits_one_above_it(): void
    {
        $admin = AdminUser::factory()->admin()->create();

        // Detained: 4 of 12 credits earned = 33.33%
        $weak       = Student::factory()->create(['name' => 'Weak Student']);
        $weakEnroll = ExamEnrollment::factory()->create(['student_id' => $weak->id]);
        $this->paper($weakEnroll, Subject::factory()->create(), 'P', 4.0);
        $this->paper($weakEnroll, Subject::factory()->create(), 'F', 4.0);
        $this->paper($weakEnroll, Subject::factory()->create(), 'F', 4.0);

        // Not detained: 8 of 12 credits earned = 66.67%
        $ok       = Student::factory()->create(['name' => 'Passing Student']);
        $okEnroll = ExamEnrollment::factory()->create(['student_id' => $ok->id]);
        $this->paper($okEnroll, Subject::factory()->create(), 'P', 4.0);
        $this->paper($okEnroll, Subject::factory()->create(), 'P', 4.0);
        $this->paper($okEnroll, Subject::factory()->create(), 'F', 4.0);

        $response = $this->actingAs($admin, 'admin')->get('/admin/detained');

        $response->assertOk();
        $response->assertSee('Weak Student');
        $response->assertDontSee('Passing Student');
        $response->assertSee('33.33%');
    }

    #[Test]
    public function a_paper_passed_on_a_later_attempt_earns_its_credits_once(): void
    {
        $admin   = AdminUser::factory()->admin()->create();
        $student = Student::factory()->create(['name' => 'Retry Student']);
        $subject = Subject::factory()->create();

        // Same paper twice across two exams: failed, then passed.
        $first  = ExamEnrollment::factory()->create(['student_id' => $student->id]);
        $second = ExamEnrollment::factory()->create(['student_id' => $student->id]);
        $this->paper($first, $subject, 'F', 4.0);
        $this->paper($second, $subject, 'P', 4.0);

        // One more paper, failed, so the student is still on the list.
        $this->paper($first, Subject::factory()->create(), 'F', 4.0);

        $response = $this->actingAs($admin, 'admin')->get('/admin/detained');

        $response->assertOk();
        $response->assertSee('Retry Student');
        // 4 of 8 credits, not 4 of 12 — the retaken paper counts its credits
        // once. Counting each attempt separately would show 33.33%.
        $response->assertDontSee('33.33%');
    }

    #[Test]
    public function the_same_paper_code_under_two_subject_rows_counts_its_credits_once(): void
    {
        $admin   = AdminUser::factory()->admin()->create();
        $student = Student::factory()->create(['name' => 'Supply Student']);

        // One paper, two subjects rows — same code, different medium. The
        // regular sitting resolved to one row and the supplementary to the
        // other, which is how production data ended up double-counted.
        $regularPaper = Subject::factory()->create(['code' => 'ENG101', 'medium' => 'EM']);
        $supplyPaper  = Subject::factory()->create(['code' => 'ENG101', 'medium' => 'TM']);

        $regular = ExamEnrollment::factory()->create(['student_id' => $student->id]);
        $supply  = ExamEnrollment::factory()->create(['student_id' => $student->id]);
        $this->paper($regular, $regularPaper, 'F', 4.0);
        $this->paper($supply, $supplyPaper, 'F', 4.0);

        // A passed paper, so the student sits at 4 of 8 credits = 50% and stays
        // off the list. Counting ENG101 twice would give 4 of 12 = 33.33%.
        $this->paper($regular, Subject::factory()->create(), 'P', 4.0);

        $response = $this->actingAs($admin, 'admin')->get('/admin/detained?cutoff=50');

        $response->assertOk();
        $response->assertDontSee('Supply Student');
    }

    #[Test]
    public function papers_graded_ex_are_left_out_of_the_credit_totals(): void
    {
        $admin   = AdminUser::factory()->admin()->create();
        $student = Student::factory()->create(['name' => 'Excluded Paper Student']);
        $enroll  = ExamEnrollment::factory()->create(['student_id' => $student->id]);

        $this->paper($enroll, Subject::factory()->create(), 'P', 4.0);
        $this->paper($enroll, Subject::factory()->create(), 'F', 4.0);
        // Counting this EX paper would drop the student to 33.33% and detain them.
        $this->paper($enroll, Subject::factory()->create(), 'F', 4.0, ['grade' => 'EX']);

        $response = $this->actingAs($admin, 'admin')->get('/admin/detained?cutoff=50');

        // 4 of 8 = 50%, which is not below the 50% cut-off.
        $response->assertOk();
        $response->assertDontSee('Excluded Paper Student');
    }

    #[Test]
    public function the_course_filter_narrows_the_list(): void
    {
        $admin = AdminUser::factory()->admin()->create();

        $arts   = Student::factory()->create(['name' => 'Arts Student', 'course' => 'BA']);
        $sci    = Student::factory()->create(['name' => 'Science Student', 'course' => 'BSC']);

        foreach ([$arts, $sci] as $student) {
            $enroll = ExamEnrollment::factory()->create(['student_id' => $student->id]);
            $this->paper($enroll, Subject::factory()->create(), 'F', 4.0);
        }

        $response = $this->actingAs($admin, 'admin')->get('/admin/detained?course=BSC');

        $response->assertOk();
        $response->assertSee('Science Student');
        $response->assertDontSee('Arts Student');
    }

    #[Test]
    public function an_out_of_range_cutoff_falls_back_to_the_default(): void
    {
        $admin   = AdminUser::factory()->admin()->create();
        $student = Student::factory()->create(['name' => 'Weak Student']);
        $enroll  = ExamEnrollment::factory()->create(['student_id' => $student->id]);
        $this->paper($enroll, Subject::factory()->create(), 'F', 4.0);

        // 0 and 900 are both rejected, so the 50% default applies and the
        // student with 0% earned credits still shows up.
        foreach (['0', '900', 'abc'] as $bad) {
            $response = $this->actingAs($admin, 'admin')->get('/admin/detained?cutoff=' . $bad);
            $response->assertOk();
            $response->assertSee('Weak Student');
        }
    }

    #[Test]
    public function it_requires_an_authenticated_admin(): void
    {
        $this->get('/admin/detained')->assertRedirect();
    }
}
