<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeeMarkRequest;
use App\Models\Exam;
use App\Models\RevaluationEnrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RevaluationController extends Controller
{
    public function index(Request $request): View
    {
        $query = RevaluationEnrollment::with([
            'exam', 'student', 'subjects.subject', 'originalEnrollment.results',
        ]);

        if ($examId = $request->get('exam_id')) {
            $query->where('exam_id', $examId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $revaluations = $query->latest('created_at')->get();

        $rows = collect();

        foreach ($revaluations as $revaluation) {
            $resultsBySubject = $revaluation->originalEnrollment?->results->keyBy('subject_id') ?? collect();

            foreach ($revaluation->subjects as $revalSubject) {
                $result = $resultsBySubject->get($revalSubject->subject_id);

                $rows->push((object) [
                    'revaluation'      => $revaluation,
                    'subject_code'     => $revalSubject->subject_code,
                    'subject_name'     => $revalSubject->subject?->name,
                    'ext_marks'        => $result?->ext_marks,
                    'etotal'           => $result?->etotal,
                    'int_marks'        => $result?->int_marks,
                    'itotal'           => $result?->itotal,
                    'result'           => $result?->result,
                    'grade'            => $result?->grade,
                    'scriptcode'       => $result?->fl_scriptcode,
                    'moderation_marks' => $result?->moderation_marks,
                    'ac_marks'         => $result?->ac_marks,
                    'is_moderated'     => $result?->is_moderated,
                ]);
            }
        }

        $exams = Exam::whereIn('id', RevaluationEnrollment::select('exam_id')->distinct())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.revaluations.index', compact('rows', 'exams'));
    }

    public function markFeePaid(FeeMarkRequest $request, int $id): RedirectResponse
    {
        $revaluation = RevaluationEnrollment::findOrFail($id);

        $revaluation->update([
            'fee_paid_at'          => now(),
            'challan_number'       => $request->challan_number,
            'challan_submitted_on' => $request->challan_submitted_on,
            'challan_received_by'  => $request->challan_received_by,
            'status'               => 'confirmed',
        ]);

        return back()->with('success', 'Revaluation fee payment marked as received.');
    }
}
