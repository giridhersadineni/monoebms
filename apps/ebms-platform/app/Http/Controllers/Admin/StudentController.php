<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentUpdateRequest;
use App\Models\Course;
use App\Models\CourseGroup;
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

    public function edit(string $hallTicket): View
    {
        $student = Student::byHallTicket($hallTicket)->firstOrFail();
        $courses = Course::orderBy('code')->get();
        $groups  = CourseGroup::with('course')->orderBy('code')->get();

        return view('admin.students.edit', compact('student', 'courses', 'groups'));
    }

    public function update(StudentUpdateRequest $request, int $id): RedirectResponse
    {
        $student = Student::findOrFail($id);
        $student->update($request->validated());

        return redirect()->route('admin.students.show', $student->hall_ticket)
            ->with('success', 'Student record updated.');
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
