<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamEnrollment;
use App\Services\ChallanPdfService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChallanController extends Controller
{
    public function __construct(private ChallanPdfService $challanService) {}

    public function show(ExamEnrollment $enrollment): Response
    {
        $student = Auth::guard('student')->user();

        if ($enrollment->student_id !== $student->id) {
            abort(403);
        }

        $enrollment->load(['exam', 'enrollmentSubjects.subject']);

        return $this->challanService->generate($enrollment);
    }
}
