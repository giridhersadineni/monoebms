@extends('layouts.admin')
@section('title', $exam->name)

@section('content')
<div class="max-w-5xl">

    {{-- Header --}}
    <div class="mb-5 flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1 text-sm">
                <a href="{{ route('admin.exams.index') }}" class="text-slate-400 hover:text-slate-600">Exams</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-600">{{ $exam->name }}</span>
            </div>
            <h1 class="text-xl font-semibold text-slate-800">{{ $exam->name }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $exam->course ?? 'All Courses' }} &middot; Semester {{ $exam->semester }}
                &middot; {{ $exam->month_name }} {{ $exam->year }}
                &middot; <span class="capitalize">{{ $exam->exam_type }}</span>
            </p>
        </div>
        @if(auth('admin')->user()->canAccess('exams.edit'))
        <a href="{{ route('admin.exams.edit', $exam) }}"
           class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Edit
        </a>
        @endif
    </div>

    {{-- Status + toggles row --}}
    <div class="flex items-center gap-2 mb-6 flex-wrap">
        <x-status-badge :status="$exam->status" />

        @if(auth('admin')->user()->canAccess('exams.edit'))
        @php
            $nextStatus = $exam->nextStatus();
            $toggleLabel = match($nextStatus) {
                'RUNNING'   => 'Start Enrollment',
                'REVALOPEN' => 'Open Revaluation',
                'CLOSED'    => 'Close Exam',
                default     => 'Set Notify',
            };
            $toggleClass = $nextStatus === 'CLOSED'
                ? 'border-red-200 text-red-700 hover:bg-red-50'
                : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50';
        @endphp
        <form method="POST" action="{{ route('admin.exams.toggle-status', $exam) }}">
            @csrf @method('PATCH')
            <button class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors {{ $toggleClass }}">
                {{ $toggleLabel }}
            </button>
        </form>
        <form method="POST" action="{{ route('admin.exams.toggle-revaluation', $exam) }}">
            @csrf @method('PATCH')
            <button class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                {{ $exam->revaluation_open ? 'Close Revaluation' : 'Open Revaluation' }}
            </button>
        </form>
        <form method="POST" action="{{ route('admin.exams.toggle-results', $exam) }}">
            @csrf @method('PATCH')
            <button class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-colors
                {{ $exam->results_visible ? 'border-red-200 text-red-700 hover:bg-red-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                {{ $exam->results_visible ? 'Hide Results' : 'Publish Results' }}
            </button>
        </form>
        @endif

        <div class="flex-1"></div>

        {{-- Quick nav links --}}
        @if(auth('admin')->user()->canAccess('enrollments.view'))
        <a href="{{ route('admin.enrollments.index', ['exam_id' => $exam->id]) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-blue-200 text-blue-700 hover:bg-blue-50 transition-colors">
            Enrollments ({{ number_format($exam->enrollments_count) }})
        </a>
        @endif
        <a href="{{ route('admin.exams.planning', $exam) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-violet-200 text-violet-700 hover:bg-violet-50 transition-colors">
            Planning
        </a>
        @if(auth('admin')->user()->canAccess('dform.view'))
        <a href="{{ route('admin.dform.index', ['exam_id' => $exam->id]) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
            D-Form
        </a>
        @endif
        @if(auth('admin')->user()->canAccess('attendance.view'))
        <a href="{{ route('admin.attendance.index', ['exam_id' => $exam->id]) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
            Attendance
        </a>
        @endif
    </div>

    {{-- Stats row --}}
    @php
        $total    = $feeStats->total ?? 0;
        $paid     = $feeStats->paid_count ?? 0;
        $unpaid   = $feeStats->unpaid_count ?? 0;
        $pct      = $total > 0 ? round($paid / $total * 100) : 0;
    @endphp
    <div class="grid grid-cols-4 gap-4 mb-5">
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-2xl font-bold text-slate-800">{{ number_format($total) }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Total Enrollments</p>
            @if($typeBreakdown->isNotEmpty())
            <div class="flex gap-2 mt-2 flex-wrap">
                @foreach($typeBreakdown as $type => $count)
                <span class="text-[10px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded capitalize">
                    {{ $type }}: {{ $count }}
                </span>
                @endforeach
            </div>
            @endif
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-2xl font-bold text-emerald-600">{{ number_format($paid) }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Fee Paid</p>
            <p class="text-xs text-emerald-700 font-medium mt-1">₹{{ number_format($feeStats->collected ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-2xl font-bold {{ $unpaid > 0 ? 'text-amber-600' : 'text-slate-300' }}">{{ number_format($unpaid) }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Fee Unpaid</p>
            @if($unpaid > 0)
            <p class="text-xs text-amber-600 font-medium mt-1">₹{{ number_format($feeStats->outstanding ?? 0) }} outstanding</p>
            @endif
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-2xl font-bold text-slate-800">{{ $pct }}%</p>
            <p class="text-xs text-slate-500 mt-0.5">Collection Rate</p>
            <div class="mt-2 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full {{ $pct === 100 ? 'bg-emerald-500' : 'bg-blue-400' }}"
                     style="width: {{ $pct }}%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-5">

        {{-- Exam details --}}
        <div class="col-span-1 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-3">Details</p>
            <dl class="space-y-2.5 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Scheme</dt>
                    <dd class="font-medium text-slate-700">{{ $exam->scheme ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Revaluation</dt>
                    <dd><x-status-badge :status="$exam->revaluation_open ? 'open' : 'closed'" /></dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Results</dt>
                    <dd><x-status-badge :status="$exam->results_visible ? 'open' : 'closed'" /></dd>
                </div>
                @if($exam->fee_regular !== null)
                <hr class="border-slate-100">
                <div class="flex justify-between">
                    <dt class="text-slate-500">{{ $exam->exam_type === 'supplementary' ? 'Fee (3+ papers)' : 'Flat Fee' }}</dt>
                    <dd class="font-mono font-semibold text-slate-800">₹{{ number_format($exam->fee_regular) }}</dd>
                </div>
                @if($exam->fee_supply_upto2)
                <div class="flex justify-between">
                    <dt class="text-slate-500">Fee (1–2 papers)</dt>
                    <dd class="font-mono font-semibold text-slate-800">₹{{ number_format($exam->fee_supply_upto2) }}</dd>
                </div>
                @endif
                @if($exam->fee_improvement)
                <div class="flex justify-between">
                    <dt class="text-slate-500">Improvement / paper</dt>
                    <dd class="font-mono font-semibold text-slate-800">₹{{ number_format($exam->fee_improvement) }}</dd>
                </div>
                @endif
                @if($exam->fee_fine)
                <div class="flex justify-between">
                    <dt class="text-slate-500">Late Fine</dt>
                    <dd class="font-mono font-semibold text-red-600">₹{{ number_format($exam->fee_fine) }}</dd>
                </div>
                @endif
                @endif
            </dl>
        </div>

        {{-- Fee Rules --}}
        <div class="col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-sm font-semibold text-slate-700">Fee Rules</span>
                    <span class="ml-2 text-xs text-slate-400">{{ $exam->feeRules->count() }} configured</span>
                </div>
                @if(auth('admin')->user()->canAccess('exams.edit'))
                <a href="{{ route('admin.exams.fee-rules.index', $exam) }}"
                   class="text-xs text-blue-600 hover:underline font-medium">Manage →</a>
                @endif
            </div>
            @if($exam->feeRules->isNotEmpty())
            <div class="p-4 flex flex-wrap gap-2">
                @foreach($exam->feeRules->sortBy([['course', 'asc'], ['group_code', 'asc']]) as $rule)
                <a href="{{ route('admin.exams.fee-rules.edit', [$exam, $rule]) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs
                          text-slate-600 hover:border-blue-300 hover:bg-blue-50 transition-colors
                          {{ is_null($rule->course) ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-200' }}">
                    <span class="font-medium">{{ $rule->course ?? 'All' }}</span>
                    @if($rule->group_code)
                        <span class="text-slate-400">/</span><span>{{ $rule->group_code }}</span>
                    @endif
                    @if($rule->fee_regular !== null)
                        <span class="text-slate-400">·</span>
                        <span class="font-mono">₹{{ number_format($rule->fee_regular) }}</span>
                    @endif
                </a>
                @endforeach
            </div>
            @else
            <p class="px-5 py-4 text-sm text-slate-400">No rules — exam-level defaults apply to all.</p>
            @endif
        </div>
    </div>

    {{-- Course-wise Breakdown --}}
    @if($courseBreakdown->isNotEmpty())
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-5">
        <div class="px-5 py-3 border-b border-slate-100">
            <span class="text-sm font-semibold text-slate-700">Course-wise Breakdown</span>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Course</th>
                    <th class="px-5 py-2.5 text-right">Total</th>
                    <th class="px-5 py-2.5 text-right">Paid</th>
                    <th class="px-5 py-2.5 text-right">Unpaid</th>
                    <th class="px-5 py-2.5 text-right">Collected</th>
                    <th class="px-5 py-2.5 text-center w-28">Progress</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courseBreakdown as $row)
                @php $pct = $row->total > 0 ? round($row->paid / $row->total * 100) : 0; @endphp
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-2.5 font-medium text-slate-800">{{ $row->course ?? '—' }}</td>
                    <td class="px-5 py-2.5 text-right text-slate-700">{{ number_format($row->total) }}</td>
                    <td class="px-5 py-2.5 text-right text-emerald-600 font-medium">{{ number_format($row->paid) }}</td>
                    <td class="px-5 py-2.5 text-right {{ $row->unpaid > 0 ? 'text-amber-600 font-medium' : 'text-slate-400' }}">{{ number_format($row->unpaid) }}</td>
                    <td class="px-5 py-2.5 text-right font-mono text-xs text-slate-600">₹{{ number_format($row->collected) }}</td>
                    <td class="px-5 py-2.5">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $pct === 100 ? 'bg-emerald-500' : 'bg-blue-400' }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 w-8 text-right shrink-0">{{ $pct }}%</span>
                        </div>
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
            <span class="text-sm font-semibold text-slate-700">Recent Enrollments</span>
            <a href="{{ route('admin.enrollments.index', ['exam_id' => $exam->id]) }}"
               class="text-xs text-blue-600 hover:underline font-medium">View all →</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Hall Ticket</th>
                    <th class="px-5 py-2.5 text-left">Student</th>
                    <th class="px-5 py-2.5 text-left">Type</th>
                    <th class="px-5 py-2.5 text-right">Fee</th>
                    <th class="px-5 py-2.5 text-left">Status</th>
                    <th class="px-5 py-2.5 text-left">Enrolled</th>
                    <th class="px-5 py-2.5"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEnrollments as $e)
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-2.5">
                        <code class="font-mono text-xs text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $e->hall_ticket }}</code>
                    </td>
                    <td class="px-5 py-2.5 font-medium text-slate-800">{{ $e->student?->name }}</td>
                    <td class="px-5 py-2.5 text-slate-500 text-xs capitalize">{{ $e->exam_type }}</td>
                    <td class="px-5 py-2.5 text-right font-mono text-xs text-slate-600">₹{{ number_format($e->fee_amount) }}</td>
                    <td class="px-5 py-2.5"><x-status-badge :status="$e->getFeeStatus()" /></td>
                    <td class="px-5 py-2.5 text-slate-400 text-xs">{{ $e->enrolled_at?->diffForHumans() }}</td>
                    <td class="px-5 py-2.5 text-right">
                        <a href="{{ route('admin.enrollments.show', $e) }}" class="text-xs text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
