@extends('layouts.admin')
@section('title', 'Students')

@section('content')
<div class="max-w-6xl">
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-semibold text-slate-800">Students</h1>
        <span class="text-sm text-slate-400">{{ number_format($students->total()) }} records</span>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.students.index') }}" class="flex gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Search by hall ticket or name…"
               class="flex-1 border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
        <select name="course"
                class="border border-slate-300 rounded-lg px-3 py-2 text-sm
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            <option value="">All Courses</option>
            @foreach($courses as $c)
                <option value="{{ $c }}" @selected(request('course') === $c)>{{ $c }}</option>
            @endforeach
        </select>
        <button type="submit"
                class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Search
        </button>
        @if(request('q') || request('course'))
            <a href="{{ route('admin.students.index') }}"
               class="border border-slate-300 text-slate-600 hover:bg-slate-50 px-4 py-2 rounded-lg text-sm transition-colors">
                Clear
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Hall Ticket</th>
                    <th class="px-5 py-2.5 text-left">Name</th>
                    <th class="px-5 py-2.5 text-left">Course</th>
                    <th class="px-5 py-2.5 text-left">Group</th>
                    <th class="px-5 py-2.5 text-left">Semester</th>
                    <th class="px-5 py-2.5 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-2.5">
                        <a href="{{ route('admin.students.show', $student->hall_ticket) }}"
                           class="font-mono text-xs text-blue-600 hover:underline bg-slate-100 px-1.5 py-0.5 rounded">
                            {{ $student->hall_ticket }}
                        </a>
                    </td>
                    <td class="px-5 py-2.5 text-slate-700 font-medium">{{ $student->name }}</td>
                    <td class="px-5 py-2.5 text-slate-500">{{ $student->course }}</td>
                    <td class="px-5 py-2.5 text-slate-500">{{ $student->group_code ?? '—' }}</td>
                    <td class="px-5 py-2.5 text-slate-500">{{ $student->semester ?? '—' }}</td>
                    <td class="px-5 py-2.5">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.students.show', $student->hall_ticket) }}"
                               class="text-blue-600 hover:underline text-xs font-medium">View</a>
                            <span class="text-slate-200">|</span>
                            <form method="POST" action="{{ route('admin.students.login-as', $student->hall_ticket) }}">
                                @csrf
                                <button type="submit"
                                        class="text-amber-600 hover:text-amber-800 text-xs font-medium transition-colors"
                                        onclick="return confirm('Login as {{ addslashes($student->name) }} in the student portal?')">
                                    Login as
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">No students found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($students->hasPages())
    <div class="mt-4">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection
