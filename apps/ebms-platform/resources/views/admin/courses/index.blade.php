@extends('layouts.admin')
@section('title', 'Courses')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Courses</h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage course codes, names, and groups</p>
        </div>
        @if(auth('admin')->user()->canAccess('courses.manage'))
        <a href="{{ route('admin.courses.create') }}"
           class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            + New Course
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-4 px-4 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold tracking-wide uppercase border-b border-slate-100">
                    <th class="px-5 py-3 text-left">Code</th>
                    <th class="px-5 py-3 text-left">Name</th>
                    <th class="px-5 py-3 text-left">Semesters</th>
                    <th class="px-5 py-3 text-left">Groups</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors last:border-0">
                    <td class="px-5 py-3">
                        <code class="font-mono text-xs text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $course->code }}</code>
                    </td>
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $course->name }}</td>
                    <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $course->total_semesters }}</td>
                    <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $course->groups_count }}</td>
                    <td class="px-5 py-3">
                        <x-status-badge :status="$course->is_active ? 'open' : 'closed'" />
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex gap-3">
                            <a href="{{ route('admin.courses.show', $course) }}"
                               class="text-blue-600 hover:underline text-xs font-medium">Manage</a>
                            @if(auth('admin')->user()->canAccess('courses.manage'))
                            <a href="{{ route('admin.courses.edit', $course) }}"
                               class="text-slate-500 hover:underline text-xs font-medium">Edit</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-slate-400 text-sm">
                        No courses yet.@if(auth('admin')->user()->canAccess('courses.manage')) <a href="{{ route('admin.courses.create') }}" class="text-blue-600 hover:underline">Create one</a>.@endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
