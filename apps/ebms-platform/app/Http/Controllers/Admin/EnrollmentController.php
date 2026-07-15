<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeeMarkRequest;
use App\Models\ExamEnrollment;
use App\Models\Subject;
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

        $enrollments = $query->latest('enrolled_at')->paginate(30)->withQueryString();

        return view('admin.enrollments.index', compact('enrollments'));
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

        return view('admin.enrollments.show', compact('enrollment'));
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

        return back()->with('success', 'Fee payment marked as received.');
    }
}
