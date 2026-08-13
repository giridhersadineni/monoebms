<?php

namespace Tests\Feature\Student;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function student_can_login_with_hall_ticket_and_dob(): void
    {
        $student = Student::factory()->create([
            'hall_ticket' => '1234567890',
            'dob'         => '2000-06-15',
        ]);

        $response = $this->post('/student/login', [
            'hall_ticket' => '1234567890',
            'dob'         => '2000-06-15',
        ]);

        $response->assertRedirect('/student/dashboard');
        $this->assertAuthenticatedAs($student, 'student');
    }

    #[Test]
    public function student_can_login_with_dost_id(): void
    {
        $student = Student::factory()->withDostId()->create([
            'hall_ticket' => '9876543210',
        ]);

        $response = $this->post('/student/login', [
            'hall_ticket' => '9876543210',
            'dob'         => '2000-01-01', // Wrong DOB
            'dost_id'     => $student->dost_id,
        ]);

        $response->assertRedirect('/student/dashboard');
        $this->assertAuthenticatedAs($student, 'student');
    }

    #[Test]
    public function login_fails_with_wrong_dob(): void
    {
        Student::factory()->create([
            'hall_ticket' => '1234567890',
            'dob'         => '2000-06-15',
        ]);

        $response = $this->post('/student/login', [
            'hall_ticket' => '1234567890',
            'dob'         => '1999-01-01',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('hall_ticket');
        $this->assertGuest('student');
    }

    #[Test]
    public function rate_limit_returns_429_after_5_attempts(): void
    {
        Student::factory()->create(['hall_ticket' => 'RATELIMIT01', 'dob' => '2000-01-01']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/student/login', ['hall_ticket' => 'RATELIMIT01', 'dob' => '1999-01-01']);
        }

        $response = $this->post('/student/login', ['hall_ticket' => 'RATELIMIT01', 'dob' => '1999-01-01']);
        $response->assertStatus(429);
    }

    #[Test]
    public function logout_clears_session(): void
    {
        $student = Student::factory()->create(['dob' => '2000-01-01']);
        $this->actingAs($student, 'student');

        $this->post('/student/logout')->assertRedirect('/student/login');
        $this->assertGuest('student');
    }

    // ── Legacy SSO Tests ────────────────────────────────────────────

    private function generateSsoUrl(string $ht, ?int $ts = null, ?string $secret = null): string
    {
        $ts     = $ts ?? time();
        $secret = $secret ?? config('auth.sso_secret');
        $sig    = hash_hmac('sha256', $ht . '|' . $ts, $secret);

        return '/student/sso?' . http_build_query([
            'ht'  => $ht,
            'ts'  => $ts,
            'sig' => $sig,
        ]);
    }

    #[Test]
    public function sso_login_succeeds_with_valid_token(): void
    {
        $student = Student::factory()->create([
            'hall_ticket' => 'SSO1234567',
            'dob'         => '2004-03-15',
            'scheme'      => '2026',
        ]);

        $url      = $this->generateSsoUrl('SSO1234567');
        $response = $this->get($url);

        $response->assertRedirect('/student/dashboard');
        $this->assertAuthenticatedAs($student, 'student');
    }

    #[Test]
    public function sso_login_rejects_expired_token(): void
    {
        Student::factory()->create([
            'hall_ticket' => 'SSO1234567',
            'scheme'      => '2026',
        ]);

        $url      = $this->generateSsoUrl('SSO1234567', time() - 120);
        $response = $this->get($url);

        $response->assertRedirect('/student/login');
        $response->assertSessionHasErrors('hall_ticket');
        $this->assertGuest('student');
    }

    #[Test]
    public function sso_login_rejects_tampered_signature(): void
    {
        Student::factory()->create([
            'hall_ticket' => 'SSO1234567',
            'scheme'      => '2026',
        ]);

        $ts  = time();
        $url = '/student/sso?' . http_build_query([
            'ht'  => 'SSO1234567',
            'ts'  => $ts,
            'sig' => 'tampered_signature_value',
        ]);

        $response = $this->get($url);

        $response->assertRedirect('/student/login');
        $response->assertSessionHasErrors('hall_ticket');
        $this->assertGuest('student');
    }

    #[Test]
    public function sso_login_rejects_missing_parameters(): void
    {
        $response = $this->get('/student/sso');

        $response->assertRedirect('/student/login');
        $response->assertSessionHasErrors('hall_ticket');
        $this->assertGuest('student');
    }

    #[Test]
    public function sso_login_fails_for_nonexistent_student(): void
    {
        $url      = $this->generateSsoUrl('DOESNOTEXIST');
        $response = $this->get($url);

        $response->assertRedirect('/student/login');
        $response->assertSessionHasErrors('hall_ticket');
        $this->assertGuest('student');
    }
}
