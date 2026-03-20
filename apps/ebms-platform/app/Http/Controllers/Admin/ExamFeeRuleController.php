<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamFeeRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamFeeRuleController extends Controller
{
    public function index(Exam $exam): View
    {
        $exam->load('feeRules');
        $courses = Course::with('groups')->where('is_active', true)->orderBy('code')->get();

        return view('admin.exams.fee-rules.index', compact('exam', 'courses'));
    }

    public function store(Request $request, Exam $exam): RedirectResponse
    {
        $data = $this->validated($request);

        ExamFeeRule::updateOrCreate(
            ['exam_id' => $exam->id, 'course' => $data['course'], 'group_code' => $data['group_code']],
            $data
        );

        return redirect()->route('admin.exams.fee-rules.index', $exam)
            ->with('success', 'Fee rule saved.');
    }

    public function edit(Exam $exam, ExamFeeRule $rule): View
    {
        $courses = Course::with('groups')->where('is_active', true)->orderBy('code')->get();

        return view('admin.exams.fee-rules.edit', compact('exam', 'rule', 'courses'));
    }

    public function update(Request $request, Exam $exam, ExamFeeRule $rule): RedirectResponse
    {
        $data = $this->validated($request);
        $rule->update($data);

        return redirect()->route('admin.exams.fee-rules.index', $exam)
            ->with('success', 'Fee rule updated.');
    }

    public function destroy(Exam $exam, ExamFeeRule $rule): RedirectResponse
    {
        $rule->delete();

        return redirect()->route('admin.exams.fee-rules.index', $exam)
            ->with('success', 'Fee rule deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'course'            => ['nullable', 'string', 'max:10'],
            'group_code'        => ['nullable', 'string', 'max:20'],
            'fee_regular'       => ['nullable', 'integer', 'min:0'],
            'fee_supply_upto2'  => ['nullable', 'integer', 'min:0'],
            'fee_improvement'   => ['nullable', 'integer', 'min:0'],
            'fee_fine'          => ['nullable', 'integer', 'min:0'],
        ]);

        $data['course']     = $data['course']     ?: null;
        $data['group_code'] = $data['group_code'] ?: null;
        $data['fee_fine']   = $data['fee_fine']   ?? 0;

        return $data;
    }
}
