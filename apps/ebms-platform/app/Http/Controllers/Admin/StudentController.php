<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::query()->orderBy('hall_ticket');

        if ($q = $request->get('q')) {
            $query->where(function ($sq) use ($q) {
                $sq->where('hall_ticket', 'like', $q . '%')
                   ->orWhere('name', 'like', '%' . $q . '%');
            });
        }
        if ($request->filled('course')) {
            $query->where('course', $request->get('course'));
        }

        $students = $query->paginate(50)->withQueryString();
        $courses  = Student::distinct()->orderBy('course')->pluck('course');

        return view('admin.students.index', compact('students', 'courses'));
    }

    public function show(string $hallTicket): View
    {
        $student = Student::byHallTicket($hallTicket)
            ->with(['enrollments.exam', 'grade'])
            ->firstOrFail();

        return view('admin.students.show', compact('student'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:60'],
            'father_name' => ['nullable', 'string', 'max:60'],
            'mother_name' => ['nullable', 'string', 'max:60'],
            'email'      => ['nullable', 'email', 'max:50'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'address'    => ['nullable', 'string', 'max:60'],
            'city'       => ['nullable', 'string', 'max:30'],
            'state'      => ['nullable', 'string', 'max:20'],
            'pincode'    => ['nullable', 'string', 'max:10'],
        ]);

        $student->update($validated);

        return back()->with('success', 'Student record updated.');
    }

    public function loginAs(string $hallTicket): RedirectResponse
    {
        $student = Student::byHallTicket($hallTicket)->firstOrFail();

        // Scheme-2025+ students are on the new platform.
        // Older students live on the legacy portal — redirect there via signed SSO.
        $newSchemes = ['2025', '2026'];
        if (! in_array($student->scheme, $newSchemes, true)) {
            $secret = config('services.legacy_sso.secret');
            $ts     = time();
            $sig    = hash_hmac('sha256', $hallTicket . '|' . $ts, $secret);
            $url    = 'https://students.uasckuexams.in/admin_sso.php?' . http_build_query([
                'ht'  => $hallTicket,
                'ts'  => $ts,
                'sig' => $sig,
            ]);
            return redirect($url);
        }

        Auth::guard('student')->login($student);

        return redirect('/student/dashboard');
    }
}
