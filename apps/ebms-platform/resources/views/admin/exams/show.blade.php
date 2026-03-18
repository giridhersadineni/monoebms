@extends('layouts.admin')
@section('title', $exam->name)

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.exams.index') }}"
                   class="text-slate-400 hover:text-slate-600 text-sm">Exams</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-600 text-sm">{{ $exam->name }}</span>
            </div>
            <h1 class="text-xl font-semibold text-slate-800">{{ $exam->name }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $exam->course }} · Semester {{ $exam->semester }} ·
                {{ $exam->month_name }} {{ $exam->year }}
            </p>
        </div>
        <a href="{{ route('admin.exams.edit', $exam) }}"
           class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Edit
        </a>
    </div>

    {{-- Metadata --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-4">
        <div class="grid grid-cols-4 gap-5 text-sm">
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Status</p>
                <x-status-badge :status="$exam->status" />
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Type</p>
                <p class="font-medium text-slate-700">{{ ucfirst($exam->exam_type) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Scheme</p>
                <p class="font-medium text-slate-700">{{ $exam->scheme ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Enrollments</p>
                <p class="font-mono font-semibold text-slate-800">{{ number_format($exam->enrollments_count) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Revaluation</p>
                <x-status-badge :status="$exam->revaluation_open ? 'open' : 'closed'" />
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="flex gap-2.5 mb-4">
        <form method="POST" action="{{ route('admin.exams.toggle-status', $exam) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="px-4 py-2 rounded-lg text-sm font-medium border transition-colors
                           {{ $exam->status === 'open'
                               ? 'border-red-200 text-red-700 hover:bg-red-50'
                               : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                {{ $exam->status === 'open' ? 'Close Exam' : 'Open Exam' }}
            </button>
        </form>
        <form method="POST" action="{{ route('admin.exams.toggle-revaluation', $exam) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="px-4 py-2 rounded-lg text-sm font-medium border border-slate-200
                           text-slate-600 hover:bg-slate-50 transition-colors">
                {{ $exam->revaluation_open ? 'Close Revaluation' : 'Open Revaluation' }}
            </button>
        </form>
    </div>

    {{-- Fee Configuration --}}
    @if($exam->fee_json)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-4 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 font-semibold text-slate-700 text-sm">Fee Configuration</div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2 text-left">Course</th>
                    <th class="px-5 py-2 text-left">Regular</th>
                    <th class="px-5 py-2 text-left">Above 2 Sem</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exam->fee_json as $course => $fee)
                <tr class="border-b border-slate-50 last:border-0">
                    <td class="px-5 py-2">
                        <code class="font-mono text-xs bg-slate-100 px-1.5 py-0.5 rounded text-slate-700">{{ $course }}</code>
                    </td>
                    <td class="px-5 py-2 font-mono text-slate-700">
                        @if(is_array($fee))
                            &#8377;{{ number_format($fee['regular'] ?? 0) }}
                        @else
                            &#8377;{{ number_format($fee) }}
                        @endif
                    </td>
                    <td class="px-5 py-2 font-mono text-slate-500">
                        @if(is_array($fee) && isset($fee['above_2_sem']))
                            &#8377;{{ number_format($fee['above_2_sem']) }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Recent Enrollments --}}
    @if($recentEnrollments->isNotEmpty())
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <span class="font-semibold text-slate-700 text-sm">Recent Enrollments</span>
            <a href="{{ route('admin.enrollments.index', ['exam_id' => $exam->id]) }}"
               class="text-blue-600 hover:underline text-xs font-medium">View all</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Hall Ticket</th>
                    <th class="px-5 py-2.5 text-left">Student</th>
                    <th class="px-5 py-2.5 text-left">Fee</th>
                    <th class="px-5 py-2.5 text-left">Enrolled</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEnrollments as $e)
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-2.5">
                        <code class="font-mono text-xs text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $e->hall_ticket }}</code>
                    </td>
                    <td class="px-5 py-2.5 text-slate-700">{{ $e->student?->name }}</td>
                    <td class="px-5 py-2.5"><x-status-badge :status="$e->getFeeStatus()" /></td>
                    <td class="px-5 py-2.5 text-slate-400 text-xs">{{ $e->enrolled_at?->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
