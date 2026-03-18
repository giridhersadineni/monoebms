<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function search(): View
    {
        return view('admin.students.search');
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
}
