<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeeMarkRequest;
use App\Models\Exam;
use App\Models\ExamEnrollment;
use App\Models\ExamEnrollmentSubject;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = ExamEnrollment::with(['student', 'exam']);

        if ($examId = $request->integer('exam_id')) {
            $query->forExam($examId);
        }

        if ($request->filled('fee_status')) {
            match ($request->fee_status) {
                'paid'   => $query->feePaid(),
                'unpaid' => $query->feeUnpaid(),
                default  => null,
            };
        }

        if ($request->filled('id')) {
            $query->where('id', $request->integer('id'));
        }

        if ($request->filled('hall_ticket')) {
            $query->where('hall_ticket', $request->hall_ticket);
        }

        if ($request->filled('year')) {
            $query->whereHas('exam', fn($q) => $q->where('year', $request->integer('year')));
        }

        $enrollments = $query->latest('enrolled_at')->paginate(30)->withQueryString();

        $exams = Exam::orderByDesc('year')->orderByDesc('id')->pluck('name', 'id');
        $years = Exam::distinct()->orderByDesc('year')->pluck('year');

        return view('admin.enrollments.index', compact('enrollments', 'exams', 'years'));
    }

    public function markPaymentPage(Request $request): View
    {
        $enrollment = null;
        $subjectNames = [];

        if ($q = $request->input('q')) {
            $enrollment = ExamEnrollment::with(['student', 'exam', 'enrollmentSubjects.subject'])
                ->where(is_numeric($q) ? 'id' : 'hall_ticket', $q)
                ->first();

            if ($enrollment) {
                $codes = $enrollment->enrollmentSubjects->pluck('subject_code')->unique()->all();
                $subjectNames = Subject::whereIn('code', $codes)
                    ->pluck('name', 'code')
                    ->all();
            }
        }

        return view('admin.enrollments.mark-payment', compact('enrollment', 'subjectNames'));
    }

    public function show(int $id): View
    {
        $enrollment = ExamEnrollment::with(['student', 'exam', 'enrollmentSubjects.subject', 'gpa'])
            ->findOrFail($id);

        $codes = $enrollment->enrollmentSubjects->pluck('subject_code')->unique()->all();
        $subjectNames = Subject::whereIn('code', $codes)->pluck('name', 'code')->all();

        return view('admin.enrollments.show', compact('enrollment', 'subjectNames'));
    }

    public function markFeePaid(FeeMarkRequest $request, int $id): RedirectResponse
    {
        $enrollment = ExamEnrollment::findOrFail($id);

        $enrollment->update([
            'fee_paid_at'          => now(),
            'challan_number'       => $request->challan_number,
            'challan_submitted_on' => $request->challan_submitted_on,
            'challan_received_by'  => $request->challan_received_by,
        ]);

        if ($redirect = $request->input('_redirect')) {
            return redirect($redirect)->with('success', 'Fee payment marked as received.');
        }

        return back()->with('success', 'Fee payment marked as received.');
    }

    public function clearPayment(Request $request, int $id): RedirectResponse
    {
        $enrollment = ExamEnrollment::findOrFail($id);

        $enrollment->update([
            'fee_paid_at'          => null,
            'challan_number'       => null,
            'challan_submitted_on' => null,
            'challan_received_by'  => null,
        ]);

        if ($redirect = $request->input('_redirect')) {
            return redirect($redirect)->with('success', 'Payment cleared for enrollment #' . $id . '.');
        }

        return back()->with('success', 'Payment cleared for enrollment #' . $id . '.');
    }

    public function manageSubjects(int $id): View
    {
        $enrollment = ExamEnrollment::with(['student', 'exam', 'enrollmentSubjects.subject'])
            ->findOrFail($id);

        $enrolledSubjectIds = $enrollment->enrollmentSubjects->pluck('subject_id')->filter()->all();

        $availableSubjects = Subject::forCourse($enrollment->student?->course ?? '')
            ->forSemester($enrollment->exam?->semester ?? 0)
            ->whereNotIn('id', $enrolledSubjectIds)
            ->orderBy('code')
            ->get();

        return view('admin.enrollments.subjects', compact('enrollment', 'availableSubjects'));
    }

    public function addSubject(Request $request, int $id): RedirectResponse
    {
        $enrollment = ExamEnrollment::findOrFail($id);

        $validated = $request->validate([
            'subject_id'   => ['required', 'integer', 'exists:subjects,id'],
            'subject_type' => ['required', 'in:regular,improvement,elective'],
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);

        // Skip if already enrolled
        if ($enrollment->enrollmentSubjects()->where('subject_id', $subject->id)->exists()) {
            return back()->with('success', $subject->code . ' is already in this enrollment.');
        }

        ExamEnrollmentSubject::create([
            'enrollment_id' => $enrollment->id,
            'subject_id'    => $subject->id,
            'subject_code'  => $subject->code,
            'subject_type'  => $validated['subject_type'],
        ]);

        return back()->with('success', $subject->code . ' added to enrollment.');
    }

    public function removeSubject(int $enrollmentId, int $subjectId): RedirectResponse
    {
        $es = ExamEnrollmentSubject::where('enrollment_id', $enrollmentId)
            ->where('id', $subjectId)
            ->firstOrFail();

        $code = $es->subject_code;
        $es->delete();

        return back()->with('success', $code . ' removed from enrollment.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $enrollment = ExamEnrollment::findOrFail($id);

        DB::transaction(function () use ($enrollment) {
            $enrollment->enrollmentSubjects()->delete();
            $enrollment->delete();
        });

        return redirect()->route('admin.enrollments.index')
            ->with('success', "Enrollment #{$id} deleted.");
    }
}
