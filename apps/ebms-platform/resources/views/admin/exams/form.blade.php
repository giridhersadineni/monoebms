@extends('layouts.admin')
@section('title', $exam ? 'Edit ' . $exam->name : 'New Exam')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.exams.index') }}"
               class="text-slate-400 hover:text-slate-600 text-sm">Exams</a>
            @if($exam)
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.exams.show', $exam) }}"
                   class="text-slate-400 hover:text-slate-600 text-sm">{{ $exam->name }}</a>
            @endif
            <span class="text-slate-300">/</span>
            <span class="text-slate-600 text-sm">{{ $exam ? 'Edit' : 'New' }}</span>
        </div>
        <h1 class="text-xl font-semibold text-slate-800">
            {{ $exam ? 'Edit Exam' : 'New Exam' }}
        </h1>
    </div>

    <form method="POST"
          action="{{ $exam ? route('admin.exams.update', $exam) : route('admin.exams.store') }}"
          class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">
        @csrf
        @if($exam) @method('PUT') @endif

        {{-- Name --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Exam Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $exam?->name) }}" required
                   class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            <x-form-error field="name" />
        </div>

        {{-- Course + Semester --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Course <span class="text-red-500">*</span></label>
                <select name="course"
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                    <option value="" {{ old('course', $exam?->course) === null ? 'selected' : '' }}>
                        All Courses (BA / BCOM / BSC)
                    </option>
                    @foreach($courses as $c)
                    <option value="{{ $c->code }}"
                            {{ old('course', $exam?->course) === $c->code ? 'selected' : '' }}>
                        {{ $c->code }} — {{ $c->name }}
                    </option>
                    @endforeach
                </select>
                <x-form-error field="course" />
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Semester <span class="text-red-500">*</span></label>
                <input type="number" name="semester" value="{{ old('semester', $exam?->semester) }}"
                       min="1" max="10" required
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <x-form-error field="semester" />
            </div>
        </div>

        {{-- Type + Scheme --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Exam Type <span class="text-red-500">*</span></label>
                <select name="exam_type" required onchange="onExamTypeChange(this.value)"
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                    @foreach(['regular', 'supplementary', 'special', 'backlog'] as $type)
                        <option value="{{ $type }}"
                                {{ old('exam_type', $exam?->exam_type) === $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
                <x-form-error field="exam_type" />
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Scheme</label>
                <input type="text" name="scheme" value="{{ old('scheme', $exam?->scheme) }}"
                       placeholder="e.g. CBCS, RUSA"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                              placeholder:text-slate-400">
                <x-form-error field="scheme" />
            </div>
        </div>

        {{-- Month + Year --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Month <span class="text-red-500">*</span></label>
                <select name="month" required
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}"
                                {{ (int) old('month', $exam?->month) === $m ? 'selected' : '' }}>
                            {{ \DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>
                <x-form-error field="month" />
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Year <span class="text-red-500">*</span></label>
                <input type="number" name="year" value="{{ old('year', $exam?->year ?? now()->year) }}"
                       min="2000" max="2100" required
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <x-form-error field="year" />
            </div>
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Status <span class="text-red-500">*</span></label>
            <select name="status" required
                    class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                @foreach(['NOTIFY' => 'Notify', 'RUNNING' => 'Running', 'REVALOPEN' => 'Reval Open', 'CLOSED' => 'Closed'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', $exam?->status ?? 'NOTIFY') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <x-form-error field="status" />
        </div>

        {{-- Revaluation --}}
        <div class="flex items-center gap-3">
            <input type="hidden" name="revaluation_open" value="0">
            <input type="checkbox" name="revaluation_open" id="revaluation_open" value="1"
                   {{ old('revaluation_open', $exam?->revaluation_open) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <label for="revaluation_open" class="text-sm text-slate-700 font-medium">Revaluation open</label>
        </div>

        {{-- Fee Configuration --}}
        <fieldset class="border border-slate-200 rounded-lg p-4 space-y-4">
            <legend class="text-xs font-semibold text-slate-600 px-1">Fee Configuration</legend>

            {{-- Regular fee (always shown; label adapts to exam type) --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">
                    <span id="fee_regular_label_text">Fee (₹)</span>
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" name="fee_regular" id="fee_regular"
                       value="{{ old('fee_regular', $exam?->fee_regular) }}"
                       min="0" placeholder="e.g. 650"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <p class="text-xs text-slate-400 mt-1" id="fee_regular_hint">Flat fee charged for all students</p>
                <x-form-error field="fee_regular" />
            </div>

            {{-- Supply ≤2 papers tier (shown only for supplementary exam type) --}}
            <div id="fee_supply_upto2_fields" class="{{ old('exam_type', $exam?->exam_type) === 'supplementary' ? '' : 'hidden' }}">
                <label class="block text-xs font-medium text-slate-600 mb-1.5">
                    Fee — up to 2 papers (₹) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="fee_supply_upto2"
                       value="{{ old('fee_supply_upto2', $exam?->fee_supply_upto2) }}"
                       min="0" placeholder="e.g. 450"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <p class="text-xs text-slate-400 mt-1">
                    Flat fee for 1–2 papers &nbsp;·&nbsp; 3+ papers use the fee above
                </p>
                <x-form-error field="fee_supply_upto2" />
            </div>

            {{-- Improvement & Fine --}}
            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">
                        Improvement Fee (₹) <span class="text-xs text-slate-400 font-normal">/ paper</span>
                    </label>
                    <input type="number" name="fee_improvement"
                           value="{{ old('fee_improvement', $exam?->fee_improvement) }}"
                           min="0" placeholder="e.g. 300"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="fee_improvement" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Late Fine (₹)</label>
                    <input type="number" name="fee_fine"
                           value="{{ old('fee_fine', $exam?->fee_fine) }}"
                           min="0" placeholder="e.g. 50"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="fee_fine" />
                </div>
            </div>
        </fieldset>

        <script nonce="{{ $csp_nonce ?? '' }}">
        function onExamTypeChange(type) {
            const supplyFields = document.getElementById('fee_supply_upto2_fields');
            const regularText  = document.getElementById('fee_regular_label_text');
            const regularHint  = document.getElementById('fee_regular_hint');
            if (type === 'supplementary') {
                supplyFields.classList.remove('hidden');
                regularText.textContent = 'Fee — 3+ papers (₹)';
                regularHint.textContent = 'Flat fee for 3 or more papers';
            } else {
                supplyFields.classList.add('hidden');
                regularText.textContent = 'Fee (₹)';
                regularHint.textContent = 'Flat fee charged for all students';
            }
        }
        </script>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
            <button type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                {{ $exam ? 'Save Changes' : 'Create Exam' }}
            </button>
            <a href="{{ $exam ? route('admin.exams.show', $exam) : route('admin.exams.index') }}"
               class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
