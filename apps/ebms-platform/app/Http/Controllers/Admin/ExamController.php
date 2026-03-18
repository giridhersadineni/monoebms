<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExamRequest;
use App\Models\Exam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $query = Exam::withCount('enrollments');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('course')) {
            $query->forCourse($request->course);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->integer('year'));
        }

        $exams = $query->orderByDesc('year')->orderByDesc('month')->paginate(20)->withQueryString();

        return view('admin.exams.index', compact('exams'));
    }

    public function show(Exam $exam): View
    {
        $exam->loadCount('enrollments');
        $recentEnrollments = $exam->enrollments()->with('student')->latest()->limit(10)->get();

        return view('admin.exams.show', compact('exam', 'recentEnrollments'));
    }

    public function create(): View
    {
        return view('admin.exams.form', ['exam' => null]);
    }

    public function store(ExamRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (isset($data['fee_json'])) {
            $data['fee_json'] = json_decode($data['fee_json'], true);
        }

        $exam = Exam::create($data);

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exam created successfully.');
    }

    public function edit(Exam $exam): View
    {
        return view('admin.exams.form', compact('exam'));
    }

    public function update(ExamRequest $request, Exam $exam): RedirectResponse
    {
        $data = $request->validated();
        if (isset($data['fee_json'])) {
            $data['fee_json'] = json_decode($data['fee_json'], true);
        }

        $exam->update($data);

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exam updated successfully.');
    }

    public function toggleStatus(Exam $exam): RedirectResponse
    {
        $exam->update(['status' => $exam->status === 'open' ? 'closed' : 'open']);

        return back()->with('success', 'Exam status updated to ' . ($exam->status === 'open' ? 'closed' : 'open') . '.');
    }

    public function toggleRevaluation(Exam $exam): RedirectResponse
    {
        $exam->update(['revaluation_open' => ! $exam->revaluation_open]);

        return back()->with('success', 'Revaluation ' . ($exam->revaluation_open ? 'opened' : 'closed') . '.');
    }
}
