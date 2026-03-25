<?php

namespace Tests\Feature\Student;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SsoLoginTest extends TestCase
{
    use RefreshDatabase;

    private string $secret = 'test-sso-secret';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.legacy_sso.secret' => $this->secret]);
    }

    private function validUrl(string $hallTicket, int $ts): string
    {
        $sig = hash_hmac('sha256', $hallTicket . '|' . $ts, $this->secret);
        return "/student/sso?ht={$hallTicket}&ts={$ts}&sig={$sig}";
    }

    #[Test]
    public function valid_sso_token_logs_student_in(): void
    {
        $student = Student::factory()->create(['hall_ticket' => 'SSO0000001']);

        $response = $this->get($this->validUrl('SSO0000001', time()));

        $response->assertRedirect('/student/dashboard');
        $this->assertAuthenticatedAs($student, 'student');
    }

    #[Test]
    public function expired_token_redirects_to_login_with_error(): void
    {
        Student::factory()->create(['hall_ticket' => 'SSO0000002']);

        $staleTs = time() - 400; // older than 5-minute window
        $response = $this->get($this->validUrl('SSO0000002', $staleTs));

        $response->assertRedirect('/student/login');
        $response->assertSessionHasErrors('error');
        $this->assertGuest('student');
    }

    #[Test]
    public function tampered_signature_is_rejected(): void
    {
        Student::factory()->create(['hall_ticket' => 'SSO0000003']);

        $ts  = time();
        $sig = hash_hmac('sha256', 'SSO0000003|' . $ts, $this->secret);
        $response = $this->get("/student/sso?ht=SSO0000003&ts={$ts}&sig=bad{$sig}");

        $response->assertRedirect('/student/login');
        $response->assertSessionHasErrors('error');
        $this->assertGuest('student');
    }

    #[Test]
    public function unknown_hall_ticket_redirects_to_login(): void
    {
        $response = $this->get($this->validUrl('DOESNOTEXIST', time()));

        $response->assertRedirect('/student/login');
        $response->assertSessionHasErrors('error');
        $this->assertGuest('student');
    }

    #[Test]
    public function missing_parameters_redirect_to_login(): void
    {
        $response = $this->get('/student/sso');

        $response->assertRedirect('/student/login');
        $response->assertSessionHasErrors('error');
    }
}
