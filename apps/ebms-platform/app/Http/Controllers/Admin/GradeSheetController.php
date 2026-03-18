<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Services\GpaCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GradeSheetController extends Controller
{
    public function __construct(private GpaCalculatorService $gpaCalculator) {}

    public function show(int $studentId): View
    {
        $student = Student::with(['grade', 'enrollments.exam'])->findOrFail($studentId);

        return view('admin.gradesheets.show', compact('student'));
    }

    public function generate(int $studentId): RedirectResponse
    {
        $student = Student::findOrFail($studentId);

        $cgpaData = $this->gpaCalculator->calculateDegreeCgpa($student->id);

        Grade::updateOrCreate(
            ['student_id' => $student->id],
            array_merge($cgpaData, [
                'hall_ticket' => $student->hall_ticket,
                'modified_by' => auth('admin')->id(),
            ])
        );

        return back()->with('success', 'Grade sheet generated successfully.');
    }
}
