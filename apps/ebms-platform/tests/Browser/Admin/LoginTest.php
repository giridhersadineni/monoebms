<?php

namespace Tests\Browser\Admin;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    #[Test]
    public function admin_login_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->assertTitle('Admin Login — UASC Exams')
                    ->assertVisible('input[name="login_id"]')
                    ->assertVisible('input[name="password"]')
                    ->assertSeeIn('button[type="submit"]', 'Sign in');
        });
    }

    #[Test]
    public function admin_can_login_and_see_dashboard(): void
    {
        $admin = AdminUser::factory()->admin()->create([
            'login_id' => 'testadmin',
            'password' => Hash::make('password123'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('login_id', 'testadmin')
                    ->type('password', 'password123')
                    ->press('Sign in')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard');
        });
    }

    #[Test]
    public function wrong_credentials_show_error(): void
    {
        AdminUser::factory()->admin()->create([
            'login_id' => 'testadmin',
            'password' => Hash::make('correct'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('login_id', 'testadmin')
                    ->type('password', 'wrongpassword')
                    ->press('Sign in')
                    ->assertPathIs('/admin/login')
                    ->assertSee('credentials');
        });
    }

    #[Test]
    public function unauthenticated_admin_is_redirected_to_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/dashboard')
                    ->assertPathIs('/admin/login');
        });
    }

    #[Test]
    public function authenticated_admin_is_redirected_away_from_login(): void
    {
        $admin = AdminUser::factory()->admin()->create([
            'login_id' => 'testadmin2',
            'password' => Hash::make('password123'),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'admin')
                    ->visit('/admin/login')
                    ->assertPathIs('/admin/dashboard');
        });
    }

    #[Test]
    public function admin_can_logout(): void
    {
        $admin = AdminUser::factory()->admin()->create([
            'login_id' => 'testadmin3',
            'password' => Hash::make('password123'),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'admin')
                    ->visit('/admin/dashboard')
                    ->press('Sign out')
                    ->assertPathIs('/admin/login');
        });
    }
}
