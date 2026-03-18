<?php

namespace Tests\Browser\Admin;

use App\Models\AdminUser;
use App\Models\Exam;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

class ExamsTest extends DuskTestCase
{
    use DatabaseMigrations;

    private AdminUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = AdminUser::factory()->admin()->create([
            'login_id' => 'examadmin',
            'password' => Hash::make('password123'),
        ]);
    }

    #[Test]
    public function exams_index_lists_exams(): void
    {
        Exam::factory()->create([
            'name'      => 'B.Sc CS Semester 5',
            'course'    => 'CS',
            'semester'  => 5,
            'exam_type' => 'regular',
            'month'     => 11,
            'year'      => 2025,
            'status'    => 'open',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin')
                    ->visit('/admin/exams')
                    ->assertPathIs('/admin/exams')
                    ->assertSee('B.Sc CS Semester 5')
                    ->assertSee('CS')
                    ->assertSeeIn('table', 'OPEN');
        });
    }

    #[Test]
    public function exams_index_has_new_exam_button(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin')
                    ->visit('/admin/exams')
                    ->assertSeeLink('+ New Exam')
                    ->clickLink('+ New Exam')
                    ->assertPathIs('/admin/exams/create');
        });
    }

    #[Test]
    public function exams_index_can_filter_by_status(): void
    {
        Exam::factory()->create(['name' => 'Open Exam',   'status' => 'open',   'month' => 1, 'year' => 2025]);
        Exam::factory()->create(['name' => 'Closed Exam', 'status' => 'closed', 'month' => 2, 'year' => 2025]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin')
                    ->visit('/admin/exams')
                    ->select('status', 'open')
                    ->press('Filter')
                    ->assertSee('Open Exam')
                    ->assertDontSee('Closed Exam');
        });
    }

    #[Test]
    public function admin_can_create_exam(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin')
                    ->visit('/admin/exams/create')
                    ->assertSee('New Exam')
                    ->type('name', 'B.Com Semester 3 Nov 2025')
                    ->type('course', 'BCOM')
                    ->type('semester', '3')
                    ->select('exam_type', 'regular')
                    ->select('month', '11')
                    ->type('year', '2025')
                    ->select('status', 'open')
                    ->press('Create Exam')
                    ->assertPathBeginsWith('/admin/exams/')
                    ->assertSee('Exam created successfully')
                    ->assertSee('B.Com Semester 3 Nov 2025');
        });
    }

    #[Test]
    public function admin_can_edit_exam(): void
    {
        $exam = Exam::factory()->create([
            'name'      => 'Original Name',
            'course'    => 'CS',
            'semester'  => 1,
            'exam_type' => 'regular',
            'month'     => 6,
            'year'      => 2025,
            'status'    => 'open',
        ]);

        $this->browse(function (Browser $browser) use ($exam) {
            $browser->loginAs($this->admin, 'admin')
                    ->visit("/admin/exams/{$exam->id}/edit")
                    ->assertInputValue('name', 'Original Name')
                    ->clear('name')
                    ->type('name', 'Updated Name')
                    ->press('Save Changes')
                    ->assertSee('Exam updated successfully')
                    ->assertSee('Updated Name');
        });
    }

    #[Test]
    public function admin_can_toggle_exam_status(): void
    {
        $exam = Exam::factory()->create([
            'name'      => 'Toggle Test Exam',
            'course'    => 'CS',
            'semester'  => 2,
            'exam_type' => 'regular',
            'month'     => 3,
            'year'      => 2025,
            'status'    => 'open',
        ]);

        $this->browse(function (Browser $browser) use ($exam) {
            $browser->loginAs($this->admin, 'admin')
                    ->visit("/admin/exams/{$exam->id}")
                    ->assertSee('Close Exam')
                    ->press('Close Exam')
                    ->assertSee('Exam status updated')
                    ->assertSee('Open Exam');
        });
    }

    #[Test]
    public function exam_show_displays_enrollment_count(): void
    {
        $exam = Exam::factory()->create([
            'name'      => 'Show Test Exam',
            'course'    => 'CS',
            'semester'  => 4,
            'exam_type' => 'regular',
            'month'     => 7,
            'year'      => 2025,
            'status'    => 'closed',
        ]);

        $this->browse(function (Browser $browser) use ($exam) {
            $browser->loginAs($this->admin, 'admin')
                    ->visit("/admin/exams/{$exam->id}")
                    ->assertSee('Show Test Exam')
                    ->assertSee('Enrollments')
                    ->assertSee('0');
        });
    }

    #[Test]
    public function exams_link_is_active_in_sidebar(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin, 'admin')
                    ->visit('/admin/exams')
                    ->assertPresent('a.bg-blue-50');
        });
    }
}
