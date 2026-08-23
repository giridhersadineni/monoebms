<?php

namespace Tests\Feature\Admin;

use App\Models\AdminUser;
use App\Models\ExamEnrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FeeMarkingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_mark_challan_received(): void
    {
        $admin      = AdminUser::factory()->admin()->create();
        $enrollment = ExamEnrollment::factory()->feePending()->create();

        $this->assertNull($enrollment->fee_paid_at);

        $response = $this->actingAs($admin, 'admin')
            ->post("/admin/enrollments/{$enrollment->id}/fee", [
                'challan_number'       => 'CHLN12345',
                'challan_submitted_on' => '2025-03-01',
                'challan_received_by'  => 'Ravi Kumar',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $enrollment->refresh();
        $this->assertNotNull($enrollment->fee_paid_at);
        $this->assertEquals('CHLN12345', $enrollment->challan_number);
        $this->assertEquals('Ravi Kumar', $enrollment->challan_received_by);
    }

    #[Test]
    public function staff_cannot_mark_fee_without_the_enrollments_edit_permission(): void
    {
        // The fee route sits behind permission:enrollments.edit, and that feature
        // defaults to the admin role only (AdminFeature::defaultRoles).
        $staff      = AdminUser::factory()->create(['role' => 'staff']);
        $enrollment = ExamEnrollment::factory()->feePending()->create();

        $response = $this->actingAs($staff, 'admin')
            ->post("/admin/enrollments/{$enrollment->id}/fee", [
                'challan_number'       => 'CHLN12345',
                'challan_submitted_on' => '2025-03-01',
                'challan_received_by'  => 'Ravi Kumar',
            ]);

        $response->assertForbidden();
        $enrollment->refresh();
        $this->assertNull($enrollment->fee_paid_at);
    }

    #[Test]
    public function staff_granted_enrollments_edit_can_mark_fee(): void
    {
        // An explicit grant overrides the role default, so the same staff user
        // succeeds once the permission is present.
        $staff = AdminUser::factory()->create([
            'role'        => 'staff',
            'permissions' => ['enrollments.edit'],
        ]);
        $enrollment = ExamEnrollment::factory()->feePending()->create();

        $response = $this->actingAs($staff, 'admin')
            ->post("/admin/enrollments/{$enrollment->id}/fee", [
                'challan_number'       => 'CHLN12345',
                'challan_submitted_on' => '2025-03-01',
                'challan_received_by'  => 'Ravi Kumar',
            ]);

        $response->assertRedirect();
        $enrollment->refresh();
        $this->assertNotNull($enrollment->fee_paid_at);
    }
}
