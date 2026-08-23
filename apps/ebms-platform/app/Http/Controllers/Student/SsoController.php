<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SsoController extends Controller
{
    private const MAX_AGE_SECONDS = 300; // 5 minutes

    public function handle(Request $request): RedirectResponse
    {
        $ht  = $request->query('ht', '');
        $ts  = (int) $request->query('ts', 0);
        $sig = $request->query('sig', '');

        $secret = config('services.legacy_sso.secret');

        // Validate inputs exist
        if (! $ht || ! $ts || ! $sig || ! $secret) {
            return redirect()->route('student.login')
                ->withErrors(['error' => 'Invalid login link. Please log in manually.']);
        }

        // Verify timestamp freshness (prevent replay attacks)
        if (abs(time() - $ts) > self::MAX_AGE_SECONDS) {
            return redirect()->route('student.login')
                ->withErrors(['error' => 'Login link has expired. Please try again from the student portal.']);
        }

        // Verify HMAC signature
        $expected = hash_hmac('sha256', $ht . '|' . $ts, $secret);
        if (! hash_equals($expected, $sig)) {
            return redirect()->route('student.login')
                ->withErrors(['error' => 'Invalid login link. Please log in manually.']);
        }

        // Find student by hall ticket
        $student = Student::where('hall_ticket', $ht)->first();
        if (! $student) {
            return redirect()->route('student.login')
                ->withErrors(['error' => 'Student record not found. Please log in manually.']);
        }

        // Log the student in
        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard');
    }
}
