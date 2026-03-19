<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaperRequest;
use App\Models\Course;
use App\Models\CourseGroup;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $papers = Subject::query()
            ->when($request->course,     fn($q) => $q->where('course', $request->course))
            ->when($request->group_code, fn($q) => $q->where('group_code', $request->group_code))
            ->when($request->semester,   fn($q) => $q->where('semester', $request->semester))
            ->when($request->medium,     fn($q) => $q->where('medium', $request->medium))
            ->when($request->paper_type, fn($q) => $q->where('paper_type', $request->paper_type))
            ->when($request->scheme,     fn($q) => $q->where('scheme', $request->scheme))
            ->orderBy('course')
            ->orderBy('semester')
            ->orderBy('code')
            ->paginate(30)
            ->withQueryString();

        $courses = Course::orderBy('code')->get();
        $groups  = CourseGroup::with('course')->orderBy('code')->get();
        $schemes = Subject::distinct()->orderByDesc('scheme')->pluck('scheme');

        return view('admin.papers.index', compact('papers', 'courses', 'groups', 'schemes'));
    }

    public function create(): View
    {
        $courses = Course::with('groups')->orderBy('code')->get();
        return view('admin.papers.form', ['paper' => null, 'courses' => $courses]);
    }

    public function store(PaperRequest $request): RedirectResponse
    {
        $paper = Subject::create($request->validated());
        return redirect()->route('admin.papers.show', $paper)->with('success', 'Paper created.');
    }

    public function show(Subject $paper): View
    {
        return view('admin.papers.show', compact('paper'));
    }

    public function edit(Subject $paper): View
    {
        $courses = Course::with('groups')->orderBy('code')->get();
        return view('admin.papers.form', compact('paper', 'courses'));
    }

    public function update(PaperRequest $request, Subject $paper): RedirectResponse
    {
        $paper->update($request->validated());
        return redirect()->route('admin.papers.show', $paper)->with('success', 'Paper updated.');
    }

    public function destroy(Subject $paper): RedirectResponse
    {
        $paper->delete();
        return redirect()->route('admin.papers.index')->with('success', 'Paper deleted.');
    }
}
