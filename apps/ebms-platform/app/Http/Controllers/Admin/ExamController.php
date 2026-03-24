<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExamRequest;
use App\Models\Course;
use App\Models\CourseGroup;
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
        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        $exams = $query->orderByDesc('year')->orderByDesc('month')->paginate(20)->withQueryString();

        return view('admin.exams.index', compact('exams'));
    }

    public function show(Exam $exam): View
    {
        $exam->loadCount('enrollments');
        $exam->load('feeRules');
        $recentEnrollments = $exam->enrollments()->with('student')->latest()->limit(10)->get();

        return view('admin.exams.show', compact('exam', 'recentEnrollments'));
    }

    public function create(): View
    {
        $courses = Course::where('is_active', true)->orderBy('code')->get();
        return view('admin.exams.form', ['exam' => null, 'courses' => $courses]);
    }

    public function store(ExamRequest $request): RedirectResponse
    {
        $exam = Exam::create($request->validated());

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exam created successfully.');
    }

    public function edit(Exam $exam): View
    {
        $courses = Course::where('is_active', true)->orderBy('code')->get();
        return view('admin.exams.form', compact('exam', 'courses'));
    }

    public function update(ExamRequest $request, Exam $exam): RedirectResponse
    {
        $exam->update($request->validated());

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Exam updated successfully.');
    }

    public function toggleStatus(Exam $exam): RedirectResponse
    {
        $next = $exam->nextStatus();
        $exam->update(['status' => $next]);

        return back()->with('success', 'Exam status updated to ' . $next . '.');
    }

    public function toggleRevaluation(Exam $exam): RedirectResponse
    {
        $exam->update(['revaluation_open' => ! $exam->revaluation_open]);

        return back()->with('success', 'Revaluation ' . ($exam->revaluation_open ? 'opened' : 'closed') . '.');
    }
}
