<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DFormController extends Controller
{
    private function formData(): array
    {
        return [
            'subjects' => Subject::select('code', 'name')->orderBy('code')->distinct()->get(),
            'exams'    => Exam::orderByDesc('id')->get(),
        ];
    }

    public function index(): View
    {
        return view('admin.dform.index', $this->formData());
    }

    public function attendanceIndex(): View
    {
        return view('admin.attendance.index', $this->formData());
    }

    private function validateAndLoad(Request $request): array
    {
        $request->validate([
            'subject_code' => ['required', 'string', 'exists:subjects,code'],
            'exam_id'      => ['required', 'integer', 'exists:exams,id'],
        ]);

        return [
            Exam::findOrFail($request->integer('exam_id')),
            Subject::where('code', $request->subject_code)->firstOrFail(),
        ];
    }

    public function print(Request $request): View
    {
        [$exam, $subject] = $this->validateAndLoad($request);

        $hallTickets = ExamEnrollment::query()
            ->where('exam_id', $exam->id)
            ->whereNotNull('fee_paid_at')
            ->whereHas('enrollmentSubjects', fn ($q) =>
                $q->where('subject_code', $subject->code)
            )
            ->orderBy('hall_ticket')
            ->pluck('hall_ticket');

        return view('admin.dform.print', compact('exam', 'subject', 'hallTickets'));
    }

    public function attendancePrint(Request $request): View
    {
        [$exam, $subject] = $this->validateAndLoad($request);

        $enrollments = ExamEnrollment::query()
            ->where('exam_id', $exam->id)
            ->whereNotNull('fee_paid_at')
            ->whereHas('enrollmentSubjects', fn ($q) =>
                $q->where('subject_code', $subject->code)
            )
            ->with('student:id,hall_ticket,name')
            ->orderBy('hall_ticket')
            ->get();

        return view('admin.attendance.print', compact('exam', 'subject', 'enrollments'));
    }
}
