<?php

namespace Tests\Feature\Admin;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_login_with_correct_credentials(): void
    {
        $admin = AdminUser::factory()->admin()->create([
            'login_id' => 'admin01',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post('/admin/login', [
            'login_id' => 'admin01',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    #[Test]
    public function admin_login_fails_with_wrong_password(): void
    {
        AdminUser::factory()->create([
            'login_id' => 'admin01',
            'password' => Hash::make('correct'),
        ]);

        $response = $this->post('/admin/login', [
            'login_id' => 'admin01',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors('login_id');
        $this->assertGuest('admin');
    }
}
