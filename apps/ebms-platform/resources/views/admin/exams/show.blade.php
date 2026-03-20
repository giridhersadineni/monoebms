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
                {{ $exam->course ?? 'All Courses' }} · Semester {{ $exam->semester }} ·
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
            @php
                $nextStatus = $exam->nextStatus();
                $btnClass = $nextStatus === 'CLOSED'
                    ? 'border-red-200 text-red-700 hover:bg-red-50'
                    : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50';
                $btnLabel = match($nextStatus) {
                    'RUNNING'   => 'Start Enrollment',
                    'REVALOPEN' => 'Open Revaluation',
                    'CLOSED'    => 'Close Exam',
                    default     => 'Set Notify',
                };
            @endphp
            <button type="submit"
                    class="px-4 py-2 rounded-lg text-sm font-medium border transition-colors {{ $btnClass }}">
                {{ $btnLabel }}
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

    {{-- Fee Configuration (exam-level defaults) --}}
    @if($exam->fee_regular !== null)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-4 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 font-semibold text-slate-700 text-sm">
            Fee Defaults (exam-level)
        </div>
        <div class="px-5 py-4 grid grid-cols-3 gap-5 text-sm">
            <div>
                <p class="text-xs text-slate-400 mb-0.5">
                    {{ $exam->exam_type === 'supplementary' ? 'Fee (3+ papers)' : 'Fee' }}
                </p>
                <p class="font-mono font-semibold text-slate-800">&#8377;{{ number_format($exam->fee_regular) }}</p>
            </div>
            @if($exam->exam_type === 'supplementary' && $exam->fee_supply_upto2 !== null)
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Fee (1–2 papers)</p>
                <p class="font-mono font-semibold text-slate-800">&#8377;{{ number_format($exam->fee_supply_upto2) }}</p>
            </div>
            @endif
            @if($exam->fee_improvement)
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Improvement Fee</p>
                <p class="font-mono font-semibold text-slate-800">&#8377;{{ number_format($exam->fee_improvement) }} <span class="text-slate-400 text-xs font-normal">/ paper</span></p>
            </div>
            @endif
            @if($exam->fee_fine)
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Late Fine</p>
                <p class="font-mono font-semibold text-slate-800">&#8377;{{ number_format($exam->fee_fine) }}</p>
            </div>
            @endif
        </div>
        @if($exam->exam_type === 'supplementary' && $exam->fee_supply_upto2 !== null)
        <div class="px-5 pb-4 text-xs text-slate-400">
            1–2 papers → &#8377;{{ number_format($exam->fee_supply_upto2) }} (flat) &nbsp;·&nbsp;
            3+ papers → &#8377;{{ number_format($exam->fee_regular) }} (flat)
        </div>
        @endif
    </div>
    @endif

    {{-- Fee Rules summary card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-4 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <div>
                <span class="font-semibold text-slate-700 text-sm">Fee Rules</span>
                <span class="ml-2 text-xs text-slate-400">{{ $exam->feeRules->count() }} {{ Str::plural('rule', $exam->feeRules->count()) }} configured</span>
            </div>
            <a href="{{ route('admin.exams.fee-rules.index', $exam) }}"
               class="text-blue-600 hover:underline text-xs font-medium">
                Manage Fee Rules →
            </a>
        </div>
        @if($exam->feeRules->isNotEmpty())
        <div class="px-5 py-3 flex flex-wrap gap-2">
            @foreach($exam->feeRules->sortBy([['course', 'asc'], ['group_code', 'asc']]) as $rule)
            <a href="{{ route('admin.exams.fee-rules.edit', [$exam, $rule]) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200
                      text-xs text-slate-600 hover:border-blue-300 hover:bg-blue-50 transition-colors
                      {{ is_null($rule->course) ? 'bg-amber-50 border-amber-200' : 'bg-slate-50' }}">
                <span class="font-medium">{{ $rule->course ?? 'All' }}</span>
                @if($rule->group_code)
                    <span class="text-slate-400">/</span>
                    <span>{{ $rule->group_code }}</span>
                @endif
                @if($rule->fee_regular !== null)
                    <span class="text-slate-400">·</span>
                    <span class="font-mono">Rs.{{ number_format($rule->fee_regular) }}</span>
                @endif
            </a>
            @endforeach
        </div>
        @else
        <p class="px-5 py-3 text-sm text-slate-400">No rules — exam-level fee defaults apply to all students.</p>
        @endif
    </div>

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
