@extends('layouts.admin')
@section('title', 'Manage Subjects — Enrollment #' . $enrollment->id)

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center gap-2 mb-1 text-sm">
        <a href="{{ route('admin.enrollments.index') }}" class="text-slate-400 hover:text-slate-600">Enrollments</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('admin.enrollments.show', $enrollment->id) }}" class="text-slate-400 hover:text-slate-600">#{{ $enrollment->id }}</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-600">Subjects</span>
    </div>
    <div class="flex items-start justify-between mb-6 mt-1">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Manage Enrolled Subjects</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $enrollment->student?->name }} &mdash; {{ $enrollment->hall_ticket }} &mdash; {{ $enrollment->exam?->name }}
            </p>
        </div>
    </div>

    {{-- Current subjects --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <span class="text-sm font-semibold text-slate-700">Enrolled Subjects ({{ $enrollment->enrollmentSubjects->count() }})</span>
        </div>
        @if($enrollment->enrollmentSubjects->isEmpty())
        <p class="px-5 py-4 text-sm text-slate-400">No subjects enrolled.</p>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Code</th>
                    <th class="px-5 py-2.5 text-left">Subject</th>
                    <th class="px-5 py-2.5 text-left">Type</th>
                    <th class="px-5 py-2.5 text-right">Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollment->enrollmentSubjects as $es)
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50">
                    <td class="px-5 py-2.5 font-mono text-xs text-slate-600">{{ $es->subject_code }}</td>
                    <td class="px-5 py-2.5 text-slate-800">{{ $es->subject?->name ?? $es->subject_code }}</td>
                    <td class="px-5 py-2.5 text-slate-500 text-xs capitalize">{{ $es->subject_type }}</td>
                    <td class="px-5 py-2.5 text-right">
                        @if(auth('admin')->user()->canAccess('enrollments.manage'))
                        <form method="POST"
                              action="{{ route('admin.enrollments.subjects.destroy', [$enrollment->id, $es->id]) }}"
                              onsubmit="return confirm('Remove {{ addslashes($es->subject_code) }} from this enrollment?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Remove</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Add subject --}}
    @if(auth('admin')->user()->canAccess('enrollments.manage'))
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <h2 class="text-sm font-semibold text-slate-700 mb-4">Add Subject</h2>

        @if($availableSubjects->isEmpty())
        <p class="text-sm text-slate-400">No additional subjects available for this student's course and semester.</p>
        @else
        <form method="POST" action="{{ route('admin.enrollments.subjects.store', $enrollment->id) }}">
            @csrf
            <div class="flex gap-3 items-end">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Subject</label>
                    <select name="subject_id" required
                            class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                        <option value="">Select a subject…</option>
                        @foreach($availableSubjects as $subject)
                        <option value="{{ $subject->id }}">
                            {{ $subject->code }} — {{ $subject->name }}
                            ({{ strtoupper($subject->medium) }}, Sem {{ $subject->semester }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Type</label>
                    <select name="subject_type" required
                            class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                        <option value="regular">Regular</option>
                        <option value="improvement">Improvement</option>
                        <option value="elective">Elective</option>
                    </select>
                </div>
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Add
                </button>
            </div>
        </form>
        @endif
    </div>
    @endif
</div>
@endsection
