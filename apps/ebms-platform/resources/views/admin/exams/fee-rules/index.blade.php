@extends('layouts.admin')
@section('title', 'Fee Rules — ' . $exam->name)

@section('content')
<div class="max-w-4xl">

    {{-- Breadcrumb + header --}}
    <div class="mb-6 flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1 text-sm">
                <a href="{{ route('admin.exams.index') }}" class="text-slate-400 hover:text-slate-600">Exams</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.exams.show', $exam) }}" class="text-slate-400 hover:text-slate-600">{{ $exam->name }}</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-600">Fee Rules</span>
            </div>
            <h1 class="text-xl font-semibold text-slate-800">Fee Rules</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $exam->course ?? 'All Courses' }} · Semester {{ $exam->semester }} ·
                {{ $exam->month_name }} {{ $exam->year }}
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    {{-- Exam-level fee defaults summary --}}
    <div class="bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 mb-5 text-sm flex flex-wrap gap-x-6 gap-y-1">
        <span class="text-slate-500 font-medium">Exam defaults:</span>
        @if($exam->fee_regular !== null)
            <span class="text-slate-700">Regular <span class="font-mono font-semibold">Rs.{{ number_format($exam->fee_regular) }}</span></span>
        @endif
        @if($exam->fee_supply_upto2 !== null)
            <span class="text-slate-700">Supply ≤2 <span class="font-mono font-semibold">Rs.{{ number_format($exam->fee_supply_upto2) }}</span></span>
        @endif
        @if($exam->fee_improvement)
            <span class="text-slate-700">Improvement <span class="font-mono font-semibold">Rs.{{ number_format($exam->fee_improvement) }}</span>/paper</span>
        @endif
        @if($exam->fee_fine)
            <span class="text-slate-700">Fine <span class="font-mono font-semibold">Rs.{{ number_format($exam->fee_fine) }}</span></span>
        @endif
        @if($exam->fee_regular === null && !$exam->fee_improvement)
            <span class="text-slate-400 italic">No exam-level defaults set</span>
        @endif
        @if(auth('admin')->user()->canAccess('exams.manage'))
        <a href="{{ route('admin.exams.edit', $exam) }}" class="ml-auto text-blue-600 hover:underline text-xs">Edit exam defaults</a>
        @endif
    </div>

    {{-- Rules table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <div>
                <span class="font-semibold text-slate-700 text-sm">Course / Group Rules</span>
                <span class="ml-2 text-xs text-slate-400">Exact match → course default → exam default</span>
            </div>
            <span class="text-xs text-slate-400">{{ $exam->feeRules->count() }} {{ Str::plural('rule', $exam->feeRules->count()) }}</span>
        </div>

        @if($exam->feeRules->isNotEmpty())
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Course</th>
                    <th class="px-5 py-2.5 text-left">Group</th>
                    <th class="px-5 py-2.5 text-right">Regular</th>
                    <th class="px-5 py-2.5 text-right">Supply ≤2</th>
                    <th class="px-5 py-2.5 text-right">Improvement</th>
                    <th class="px-5 py-2.5 text-right">Fine</th>
                    <th class="px-5 py-2.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exam->feeRules->sortBy([['course', 'asc'], ['group_code', 'asc']]) as $rule)
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors {{ is_null($rule->course) ? 'bg-amber-50 hover:bg-amber-100' : '' }}">
                    <td class="px-5 py-3 font-medium text-slate-700">
                        @if(is_null($rule->course))
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                All Courses
                            </span>
                        @else
                            {{ $rule->course }}
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500">
                        {{ $rule->group_code ?? '— all groups —' }}
                    </td>
                    <td class="px-5 py-3 text-right font-mono text-slate-700">
                        {{ $rule->fee_regular !== null ? 'Rs.'.number_format($rule->fee_regular) : '—' }}
                    </td>
                    <td class="px-5 py-3 text-right font-mono text-slate-700">
                        {{ $rule->fee_supply_upto2 !== null ? 'Rs.'.number_format($rule->fee_supply_upto2) : '—' }}
                    </td>
                    <td class="px-5 py-3 text-right font-mono text-slate-700">
                        {{ $rule->fee_improvement !== null ? 'Rs.'.number_format($rule->fee_improvement) : '—' }}
                    </td>
                    <td class="px-5 py-3 text-right font-mono text-slate-700">
                        {{ $rule->fee_fine ? 'Rs.'.number_format($rule->fee_fine) : '—' }}
                    </td>
                    <td class="px-5 py-3 text-right">
                        @if(auth('admin')->user()->canAccess('exams.manage'))
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.exams.fee-rules.edit', [$exam, $rule]) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium transition-colors">
                                Edit
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.exams.fee-rules.destroy', [$exam, $rule]) }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-red-400 hover:text-red-600 text-xs font-medium transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="px-5 py-10 text-center">
            <p class="text-slate-400 text-sm mb-1">No fee rules yet</p>
            <p class="text-slate-400 text-xs">Exam-level defaults apply to all students. Add a rule below to override for specific courses or groups.</p>
        </div>
        @endif
    </div>

    {{-- Add Rule Form --}}
    @if(auth('admin')->user()->canAccess('exams.manage'))
    @php
        $groupsByCourse = $courses->mapWithKeys(fn($c) => [
            $c->code => $c->groups->pluck('code')->values()->all()
        ]);
    @endphp
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100">
            <span class="font-semibold text-slate-700 text-sm">Add Rule</span>
            <p class="text-xs text-slate-400 mt-0.5">Leave Course blank to create an exam-wide default rule. Leave Group blank to apply to all groups within the course.</p>
        </div>
        <form method="POST" action="{{ route('admin.exams.fee-rules.store', $exam) }}" class="px-5 py-5">
            @csrf
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6 mb-5">
                <div class="lg:col-span-1">
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Course</label>
                    <select name="course" id="add-rule-course"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                        <option value="">All Courses</option>
                        @foreach($courses as $c)
                        <option value="{{ $c->code }}" {{ old('course') === $c->code ? 'selected' : '' }}>
                            {{ $c->code }}
                        </option>
                        @endforeach
                    </select>
                    @error('course') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="lg:col-span-1">
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Group</label>
                    <select name="group_code" id="add-rule-group"
                            class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                        <option value="">All Groups</option>
                    </select>
                    @error('group_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Regular (Rs.)</label>
                    <input type="number" name="fee_regular" min="0" value="{{ old('fee_regular') }}"
                           placeholder="e.g. 1200"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    @error('fee_regular') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Supply ≤2 (Rs.)</label>
                    <input type="number" name="fee_supply_upto2" min="0" value="{{ old('fee_supply_upto2') }}"
                           placeholder="e.g. 600"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    @error('fee_supply_upto2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Improvement (Rs.)</label>
                    <input type="number" name="fee_improvement" min="0" value="{{ old('fee_improvement') }}"
                           placeholder="e.g. 300"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    @error('fee_improvement') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Fine (Rs.)</label>
                    <input type="number" name="fee_fine" min="0" value="{{ old('fee_fine', 0) }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    @error('fee_fine') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                    Add Rule
                </button>
                <p class="text-xs text-slate-400">If a rule for this course/group already exists, it will be updated.</p>
            </div>
        </form>
    </div>

</div>

    @endif

@push('scripts')
<script nonce="{{ $csp_nonce ?? '' }}">
(function () {
    var groupsByCourse = @json($groupsByCourse);

    function wireGroupSelect(courseId, groupId) {
        var courseSelect = document.getElementById(courseId);
        var groupSelect  = document.getElementById(groupId);
        if (!courseSelect || !groupSelect) return;

        function refresh() {
            var groups = groupsByCourse[courseSelect.value] || [];
            while (groupSelect.firstChild) { groupSelect.removeChild(groupSelect.firstChild); }
            var def = document.createElement('option');
            def.value = ''; def.textContent = 'All Groups';
            groupSelect.appendChild(def);
            groups.forEach(function (code) {
                var opt = document.createElement('option');
                opt.value = code; opt.textContent = code;
                groupSelect.appendChild(opt);
            });
            groupSelect.disabled = (groups.length === 0);
        }

        courseSelect.addEventListener('change', refresh);
        refresh();
    }

    wireGroupSelect('add-rule-course', 'add-rule-group');
})();
</script>
@endpush
@endsection
