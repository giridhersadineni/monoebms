<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamEnrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PrintApplicationController extends Controller
{
    public function show(ExamEnrollment $enrollment): View
    {
        $student = Auth::guard('student')->user();

        if ((int) $enrollment->student_id !== (int) $student->id) {
            abort(403);
        }

        $enrollment->load(['exam', 'enrollmentSubjects.subject']);

        // Make PII fields visible for the printed form
        $student->makeVisible(['aadhaar', 'dob', 'email']);

        return view('student.enrollments.application-print', compact('enrollment', 'student'));
    }
}
