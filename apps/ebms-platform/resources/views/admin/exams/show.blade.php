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

    {{-- Fee Rules per Course/Group --}}
    @php
        $groupsByCourse = $courses->mapWithKeys(fn($c) => [
            $c->code => $c->groups->pluck('code')->values()->all()
        ]);
    @endphp
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-4 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 font-semibold text-slate-700 text-sm">
            Fee Rules
            <span class="ml-1.5 text-xs font-normal text-slate-400">Course/group overrides → course default → exam default.</span>
        </div>

        @if($exam->feeRules->isNotEmpty())
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-4 py-2.5 text-left">Course</th>
                    <th class="px-4 py-2.5 text-left">Group</th>
                    <th class="px-4 py-2.5 text-right">Regular</th>
                    <th class="px-4 py-2.5 text-right">Supply ≤2</th>
                    <th class="px-4 py-2.5 text-right">Improvement</th>
                    <th class="px-4 py-2.5 text-right">Fine</th>
                    <th class="px-4 py-2.5"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($exam->feeRules->sortBy([['course', 'asc'], ['group_code', 'asc']]) as $rule)
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors {{ is_null($rule->course) ? 'bg-amber-50 hover:bg-amber-100' : '' }}">
                    <td class="px-4 py-2.5 font-medium text-slate-700">
                        {{ $rule->course ?? '— all courses —' }}
                    </td>
                    <td class="px-4 py-2.5 text-slate-500">
                        {{ $rule->group_code ?? '— all groups —' }}
                    </td>
                    <td class="px-4 py-2.5 text-right font-mono text-slate-700">
                        {{ $rule->fee_regular !== null ? '&#8377;'.number_format($rule->fee_regular) : '—' }}
                    </td>
                    <td class="px-4 py-2.5 text-right font-mono text-slate-700">
                        {{ $rule->fee_supply_upto2 !== null ? '&#8377;'.number_format($rule->fee_supply_upto2) : '—' }}
                    </td>
                    <td class="px-4 py-2.5 text-right font-mono text-slate-700">
                        {{ $rule->fee_improvement !== null ? '&#8377;'.number_format($rule->fee_improvement) : '—' }}
                    </td>
                    <td class="px-4 py-2.5 text-right font-mono text-slate-700">
                        {{ $rule->fee_fine ? '&#8377;'.number_format($rule->fee_fine) : '—' }}
                    </td>
                    <td class="px-4 py-2.5 text-right">
                        <form method="POST"
                              action="{{ route('admin.exams.fee-rules.destroy', [$exam, $rule]) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-red-400 hover:text-red-600 text-xs font-medium transition-colors">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="px-5 py-4 text-sm text-slate-400">No rules yet — exam-level fee defaults apply to all students.</p>
        @endif

        {{-- Add Rule Form --}}
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Add / Update Rule</p>
            <form method="POST" action="{{ route('admin.exams.fee-rules.store', $exam) }}">
                @csrf
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 mb-3">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Course</label>
                        <select name="course" id="fee-rule-course"
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">— All courses —</option>
                            @foreach($courses as $course)
                            <option value="{{ $course->code }}">{{ $course->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Group</label>
                        <select name="group_code" id="fee-rule-group"
                                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">— All groups —</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Regular fee</label>
                        <input type="number" name="fee_regular" min="0"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="e.g. 1200">
                    </div>
                    @if($exam->exam_type === 'supplementary')
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Supply ≤2 fee</label>
                        <input type="number" name="fee_supply_upto2" min="0"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="e.g. 600">
                    </div>
                    @endif
                    @if($exam->exam_type === 'improvement')
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Improvement fee / paper</label>
                        <input type="number" name="fee_improvement" min="0"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="e.g. 200">
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Fine</label>
                        <input type="number" name="fee_fine" min="0" value="0"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Save Rule
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script nonce="{{ $csp_nonce ?? '' }}">
    (function () {
        var groupsByCourse = @json($groupsByCourse);
        var courseSelect = document.getElementById('fee-rule-course');
        var groupSelect  = document.getElementById('fee-rule-group');

        function refreshGroups() {
            var course = courseSelect.value;
            var groups = groupsByCourse[course] || [];
            while (groupSelect.firstChild) { groupSelect.removeChild(groupSelect.firstChild); }
            var defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = '— All groups —';
            groupSelect.appendChild(defaultOpt);
            for (var i = 0; i < groups.length; i++) {
                var opt = document.createElement('option');
                opt.value = groups[i];
                opt.textContent = groups[i];
                groupSelect.appendChild(opt);
            }
            groupSelect.disabled = (groups.length === 0);
        }

        courseSelect.addEventListener('change', refreshGroups);
        refreshGroups();
    })();
    </script>
    @endpush

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
