<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamEnrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HallTicketController extends Controller
{
    public function show(ExamEnrollment $enrollment): View
    {
        $student = Auth::guard('student')->user();

        if ((int) $enrollment->student_id !== (int) $student->id) {
            abort(403);
        }

        $enrollment->load(['exam', 'enrollmentSubjects.subject']);

        if (! $enrollment->isFeePaid()) {
            abort(403, 'Hall ticket is available only after fee payment is confirmed.');
        }

        if (! $enrollment->exam || ! $enrollment->exam->canDownloadHallTicket()) {
            abort(403, 'Hall ticket is not available for this exam yet.');
        }

        return view('student.enrollments.hall-ticket', compact('enrollment', 'student'));
    }
}
