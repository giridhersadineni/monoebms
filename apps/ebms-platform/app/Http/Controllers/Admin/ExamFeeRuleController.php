<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamFeeRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExamFeeRuleController extends Controller
{
    public function store(Request $request, Exam $exam): RedirectResponse
    {
        $data = $request->validate([
            'course'            => ['nullable', 'string', 'max:10'],
            'group_code'        => ['nullable', 'string', 'max:20'],
            'fee_regular'       => ['nullable', 'integer', 'min:0'],
            'fee_supply_upto2'  => ['nullable', 'integer', 'min:0'],
            'fee_improvement'   => ['nullable', 'integer', 'min:0'],
            'fee_fine'          => ['nullable', 'integer', 'min:0'],
        ]);

        // Normalize empty strings to null for the unique key
        $data['course']     = $data['course']     ?: null;
        $data['group_code'] = $data['group_code'] ?: null;
        $data['fee_fine']   = $data['fee_fine']   ?? 0;

        ExamFeeRule::updateOrCreate(
            ['exam_id' => $exam->id, 'course' => $data['course'], 'group_code' => $data['group_code']],
            $data
        );

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Fee rule saved.');
    }

    public function destroy(Exam $exam, ExamFeeRule $rule): RedirectResponse
    {
        $rule->delete();

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Fee rule deleted.');
    }
}
