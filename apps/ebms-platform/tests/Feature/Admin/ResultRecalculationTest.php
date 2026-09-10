<?php

namespace Tests\Feature\Admin;

use App\Models\AdminUser;
use App\Models\ExamEnrollment;
use App\Models\Result;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResultRecalculationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A paper whose stored grade is deliberately stale, so a recalculation has
     * something to correct.
     */
    private function staleResult(ExamEnrollment $enrollment, array $overrides = []): Result
    {
        return Result::create(array_merge([
            'enrollment_id' => $enrollment->id,
            'subject_id'    => Subject::factory()->create()->id,
            'hall_ticket'   => $enrollment->hall_ticket,
            'exam_id'       => $enrollment->exam_id,
            'ext_marks'     => 72,
            'int_marks'     => 12,
            'is_absent_ext' => false,
            'is_absent_int' => false,
            'etotal'        => 100,
            'itotal'        => 20,
            'credits'       => 4.0,
            // Stale derived values — 84/120 = 70% should actually be an 'A'.
            'grade'         => 'F',
            'result'        => 'F',
            'total_marks'   => 0,
            'gp_value'      => 0,
            'gp_credits'    => 0,
        ], $overrides));
    }

    #[Test]
    public function recalculating_a_paper_rederives_the_grade_without_touching_the_marks(): void
    {
        $admin      = AdminUser::factory()->admin()->create();
        $enrollment = ExamEnrollment::factory()->feePaid()->create();
        $result     = $this->staleResult($enrollment);

        $response = $this->actingAs($admin, 'admin')
            ->post("/admin/results/enrollment/{$enrollment->id}/subject/{$result->id}/recalculate");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $result->refresh();
        $this->assertSame('A', $result->grade);
        $this->assertSame('P', $result->result);
        $this->assertEquals(84, $result->total_marks);
        $this->assertEquals(8.0, $result->gp_value);
        $this->assertEquals(32.0, $result->gp_credits);

        // The marks themselves must be untouched.
        $this->assertEquals(72, $result->ext_marks);
        $this->assertEquals(12, $result->int_marks);
        $this->assertFalse((bool) $result->is_absent_ext);
    }

    #[Test]
    public function recalculating_a_paper_twice_is_idempotent(): void
    {
        $admin      = AdminUser::factory()->admin()->create();
        $enrollment = ExamEnrollment::factory()->feePaid()->create();
        $result     = $this->staleResult($enrollment, ['floatation_marks' => 3, 'ac_marks' => 1]);

        $url = "/admin/results/enrollment/{$enrollment->id}/subject/{$result->id}/recalculate";

        $this->actingAs($admin, 'admin')->post($url);
        $afterFirst = $result->fresh()->only(['grade', 'result', 'total_marks', 'gp_value', 'gp_credits']);

        $this->actingAs($admin, 'admin')->post($url);
        $afterSecond = $result->fresh()->only(['grade', 'result', 'total_marks', 'gp_value', 'gp_credits']);

        $this->assertSame($afterFirst, $afterSecond, 'Grace marks must not compound across runs.');
    }

    #[Test]
    public function recalculating_gpa_writes_the_enrollment_sgpa(): void
    {
        $admin      = AdminUser::factory()->admin()->create();
        $enrollment = ExamEnrollment::factory()->feePaid()->create();
        $this->staleResult($enrollment);                                  // 4 credits, 8.0 → 32
        $this->staleResult($enrollment, ['ext_marks' => 50, 'int_marks' => 11, 'credits' => 3.0]); // 61/120 = 50.83% → B, 6.0 → 18

        $response = $this->actingAs($admin, 'admin')
            ->post("/admin/results/enrollment/{$enrollment->id}/recalculate-gpa");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $gpa = $enrollment->fresh()->gpa;
        $this->assertNotNull($gpa);
        $this->assertEquals(7.14, $gpa->sgpa);     // 50 grade-point-credits / 7 credits
        $this->assertEquals(145, $gpa->total_marks); // 84 + 61
        $this->assertSame('P', $gpa->result);
        $this->assertNotNull($gpa->processed_at);
    }

    #[Test]
    public function recalculating_gpa_marks_the_semester_r_when_a_paper_fails(): void
    {
        $admin      = AdminUser::factory()->admin()->create();
        $enrollment = ExamEnrollment::factory()->feePaid()->create();
        $this->staleResult($enrollment);
        $this->staleResult($enrollment, ['ext_marks' => 10, 'int_marks' => 2]);

        $this->actingAs($admin, 'admin')
            ->post("/admin/results/enrollment/{$enrollment->id}/recalculate-gpa");

        $this->assertSame('R', $enrollment->fresh()->gpa->result);
    }

    #[Test]
    public function it_404s_when_the_paper_belongs_to_another_enrollment(): void
    {
        $admin      = AdminUser::factory()->admin()->create();
        $enrollment = ExamEnrollment::factory()->feePaid()->create();
        $other      = ExamEnrollment::factory()->feePaid()->create();
        $result     = $this->staleResult($other);

        $this->actingAs($admin, 'admin')
            ->post("/admin/results/enrollment/{$enrollment->id}/subject/{$result->id}/recalculate")
            ->assertNotFound();
    }

    #[Test]
    public function staff_without_results_edit_cannot_recalculate(): void
    {
        $staff      = AdminUser::factory()->create(['role' => 'staff', 'permissions' => null]);
        $enrollment = ExamEnrollment::factory()->feePaid()->create();
        $result     = $this->staleResult($enrollment);

        $this->actingAs($staff, 'admin')
            ->post("/admin/results/enrollment/{$enrollment->id}/subject/{$result->id}/recalculate")
            ->assertForbidden();

        $this->actingAs($staff, 'admin')
            ->post("/admin/results/enrollment/{$enrollment->id}/recalculate-gpa")
            ->assertForbidden();

        // The stale grade must survive the rejected request.
        $this->assertSame('F', $result->fresh()->grade);
    }
}
