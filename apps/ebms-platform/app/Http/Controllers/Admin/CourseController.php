<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseGroupRequest;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\Course;
use App\Models\CourseGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    // ── Courses ──────────────────────────────────────────────────────────────

    public function index(): View
    {
        $courses = Course::withCount('groups')->orderBy('code')->get();

        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('admin.courses.form', ['course' => null]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        $course = Course::create($request->validated());

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Course created.');
    }

    public function show(Course $course): View
    {
        $course->load('groups');

        return view('admin.courses.show', ['course' => $course, 'editingGroup' => null]);
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.form', compact('course'));
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Course updated.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted.');
    }

    // ── Groups ────────────────────────────────────────────────────────────────

    public function storeGroup(CourseGroupRequest $request, Course $course): RedirectResponse
    {
        $course->groups()->create($request->validated());

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Group added.');
    }

    public function editGroup(Course $course, CourseGroup $group): View
    {
        $course->load('groups');

        return view('admin.courses.show', ['course' => $course, 'editingGroup' => $group]);
    }

    public function updateGroup(CourseGroupRequest $request, Course $course, CourseGroup $group): RedirectResponse
    {
        $group->update($request->validated());

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Group updated.');
    }

    public function destroyGroup(Course $course, CourseGroup $group): RedirectResponse
    {
        $group->delete();

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Group deleted.');
    }
}
