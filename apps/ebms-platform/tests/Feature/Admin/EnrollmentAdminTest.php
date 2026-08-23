<?php

namespace Tests\Feature\Admin;

use App\Models\AdminUser;
use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Models\ExamEnrollmentSubject;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EnrollmentAdminTest extends TestCase
{
    use RefreshDatabase;

    // ── Delete enrollment ─────────────────────────────────────────────────────

    #[Test]
    public function admin_can_delete_enrollment(): void
    {
        $admin      = AdminUser::factory()->admin()->create();
        $enrollment = ExamEnrollment::factory()->feePending()->create();

        $response = $this->actingAs($admin, 'admin')
            ->delete("/admin/enrollments/{$enrollment->id}");

        $response->assertRedirect('/admin/enrollments');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('exam_enrollments', ['id' => $enrollment->id]);
    }

    #[Test]
    public function deleting_enrollment_also_removes_subjects(): void
    {
        $admin      = AdminUser::factory()->admin()->create();
        $enrollment = ExamEnrollment::factory()->feePending()->create();
        $subject    = Subject::factory()->create();
        ExamEnrollmentSubject::create([
            'enrollment_id' => $enrollment->id,
            'subject_id'    => $subject->id,
            'subject_code'  => $subject->code,
            'subject_type'  => 'regular',
        ]);

        $this->actingAs($admin, 'admin')
            ->delete("/admin/enrollments/{$enrollment->id}");

        $this->assertDatabaseMissing('exam_enrollment_subjects', ['enrollment_id' => $enrollment->id]);
    }

    #[Test]
    public function staff_cannot_delete_enrollment(): void
    {
        $staff      = AdminUser::factory()->create(['role' => 'staff']);
        $enrollment = ExamEnrollment::factory()->feePending()->create();

        $response = $this->actingAs($staff, 'admin')
            ->delete("/admin/enrollments/{$enrollment->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('exam_enrollments', ['id' => $enrollment->id]);
    }

    // ── Year filter ───────────────────────────────────────────────────────────

    #[Test]
    public function year_filter_returns_only_matching_enrollments(): void
    {
        $admin = AdminUser::factory()->admin()->create();

        $exam2024 = Exam::factory()->create(['year' => 2024]);
        $exam2025 = Exam::factory()->create(['year' => 2025]);

        $e2024 = ExamEnrollment::factory()->feePending()->create(['exam_id' => $exam2024->id]);
        $e2025 = ExamEnrollment::factory()->feePending()->create(['exam_id' => $exam2025->id]);

        $response = $this->actingAs($admin, 'admin')
            ->get('/admin/enrollments?year=2025');

        $response->assertOk();
        $response->assertSee($e2025->hall_ticket);
        $response->assertDontSee($e2024->hall_ticket);
    }
}
