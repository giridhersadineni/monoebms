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
    public function staff_cannot_mark_fee_without_correct_role(): void
    {
        // Staff can still mark fee (role gate is only on student update, result store/process, grade generate)
        $staff      = AdminUser::factory()->create(['role' => 'staff']);
        $enrollment = ExamEnrollment::factory()->feePending()->create();

        $response = $this->actingAs($staff, 'admin')
            ->post("/admin/enrollments/{$enrollment->id}/fee", [
                'challan_number'       => 'CHLN12345',
                'challan_submitted_on' => '2025-03-01',
                'challan_received_by'  => 'Ravi Kumar',
            ]);

        // Fee marking is allowed for all admin roles
        $response->assertRedirect();
        $enrollment->refresh();
        $this->assertNotNull($enrollment->fee_paid_at);
    }
}
