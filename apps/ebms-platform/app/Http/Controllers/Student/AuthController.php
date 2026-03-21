<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\LoginRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return view('student.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('hall_ticket', 'dob', 'dost_id');

        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors([
            'hall_ticket' => 'The provided credentials do not match our records.',
        ])->onlyInput('hall_ticket');
    }

    /**
     * Legacy SSO: validate HMAC-signed token from students.uasckuexams.in
     * and auto-login the student without re-entering credentials.
     */
    public function ssoLogin(Request $request): RedirectResponse
    {
        $ht  = $request->query('ht');
        $ts  = $request->query('ts');
        $sig = $request->query('sig');

        // 1. Require all three parameters
        if (!$ht || !$ts || !$sig) {
            Log::warning('SSO: missing parameters', ['ip' => $request->ip()]);

            return redirect()->route('student.login')
                ->withErrors(['hall_ticket' => 'Invalid login link.']);
        }

        // 2. Ensure shared secret is configured
        $secret = config('auth.sso_secret');
        if (!$secret) {
            Log::error('SSO: LEGACY_SSO_SECRET not configured');
            abort(500, 'SSO not configured.');
        }

        // 3. Token must be ≤ 60 seconds old
        $tokenAge = abs(time() - (int) $ts);
        if ($tokenAge > 60) {
            Log::warning('SSO: expired token', [
                'ht'          => $ht,
                'age_seconds' => $tokenAge,
                'ip'          => $request->ip(),
            ]);

            return redirect()->route('student.login')
                ->withErrors(['hall_ticket' => 'Login link has expired. Please log in again.']);
        }

        // 4. Verify HMAC signature (timing-safe comparison)
        $expected = hash_hmac('sha256', $ht . '|' . $ts, $secret);
        if (!hash_equals($expected, $sig)) {
            Log::warning('SSO: invalid signature', [
                'ht' => $ht,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('student.login')
                ->withErrors(['hall_ticket' => 'Invalid login link.']);
        }

        // 5. Look up student in the new database
        $student = Student::active()->byHallTicket($ht)->first();
        if (!$student) {
            Log::warning('SSO: student not found in new DB', [
                'ht' => $ht,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('student.login')
                ->withErrors(['hall_ticket' => 'Account not found. Please contact the exam branch.']);
        }

        // 6. Establish session
        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        Log::info('SSO: student authenticated via legacy redirect', [
            'student_id' => $student->id,
            'ht'         => $ht,
            'ip'         => $request->ip(),
        ]);

        return redirect()->route('student.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
