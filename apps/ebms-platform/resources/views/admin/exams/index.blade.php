@extends('layouts.admin')
@section('title', 'Exams')

@section('content')
<div class="max-w-6xl">
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Exams</h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage exams, status and fee configuration</p>
        </div>
        <a href="{{ route('admin.exams.create') }}"
           class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            + New Exam
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex gap-2.5 mb-5 items-center flex-wrap">
        <select name="status"
                class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Status</option>
            @foreach(['NOTIFY' => 'Notify', 'RUNNING' => 'Running', 'REVALOPEN' => 'Reval Open', 'CLOSED' => 'Closed'] as $val => $label)
            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <input type="text" name="course" value="{{ request('course') }}"
               placeholder="Course (e.g. CS)"
               class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm w-40 bg-white
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                      font-mono placeholder:font-sans placeholder:text-slate-400">
        <select name="exam_type"
                class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Types</option>
            <option value="regular"       {{ request('exam_type') === 'regular'       ? 'selected' : '' }}>Regular</option>
            <option value="supplementary" {{ request('exam_type') === 'supplementary' ? 'selected' : '' }}>Supply</option>
            <option value="improvement"   {{ request('exam_type') === 'improvement'   ? 'selected' : '' }}>Instant</option>
        </select>
        <input type="number" name="year" value="{{ request('year') }}"
               placeholder="Year"
               class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm w-28 bg-white
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                      font-mono placeholder:font-sans placeholder:text-slate-400">
        <button type="submit"
                class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Filter
        </button>
        @if(request('status') || request('course') || request('year') || request('exam_type'))
            <a href="{{ route('admin.exams.index') }}"
               class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Clear</a>
        @endif
    </form>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold tracking-wide uppercase border-b border-slate-100">
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Name</th>
                    <th class="px-5 py-3 text-left">Course</th>
                    <th class="px-5 py-3 text-left">Sem</th>
                    <th class="px-5 py-3 text-left">Type</th>
                    <th class="px-5 py-3 text-left">Month / Year</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Enrollments</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors last:border-0">
                    <td class="px-5 py-3 text-slate-400 text-xs font-mono">{{ $exam->id }}</td>
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $exam->name }}</td>
                    <td class="px-5 py-3">
                        <code class="font-mono text-xs text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $exam->course }}</code>
                    </td>
                    <td class="px-5 py-3 text-slate-600">{{ $exam->semester }}</td>
                    <td class="px-5 py-3 text-slate-500 text-xs">{{ ucfirst($exam->exam_type) }}</td>
                    <td class="px-5 py-3 text-slate-500 text-xs">
                        {{ $exam->month_name }} {{ $exam->year }}
                    </td>
                    <td class="px-5 py-3"><x-status-badge :status="$exam->status" /></td>
                    <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ number_format($exam->enrollments_count) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex gap-3">
                            <a href="{{ route('admin.exams.show', $exam) }}"
                               class="text-blue-600 hover:underline text-xs font-medium">View</a>
                            <a href="{{ route('admin.exams.edit', $exam) }}"
                               class="text-slate-500 hover:underline text-xs font-medium">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-5 py-10 text-center text-slate-400 text-sm">No exams found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($exams->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-500">
                {{ $exams->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
