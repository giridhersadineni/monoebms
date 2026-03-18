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
                <input type="text" name="course" value="{{ old('course', $exam?->course) }}" required
                       placeholder="e.g. CS, BCA"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                              placeholder:font-sans placeholder:text-slate-400">
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
                <select name="exam_type" required
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
                <option value="open"   {{ old('status', $exam?->status ?? 'open') === 'open'   ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ old('status', $exam?->status) === 'closed' ? 'selected' : '' }}>Closed</option>
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

        {{-- Fee JSON --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Fee Configuration (JSON)</label>
            <textarea name="fee_json" rows="6" spellcheck="false"
                      placeholder='{"CS": {"regular": 500, "above_2_sem": 800}, "default": 400}'
                      class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono
                             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                             placeholder:font-sans placeholder:text-slate-400 resize-y">{{ old('fee_json', $exam?->fee_json ? json_encode($exam->fee_json, JSON_PRETTY_PRINT) : '') }}</textarea>
            <p class="text-xs text-slate-400 mt-1">Leave blank if not applicable. Must be valid JSON.</p>
            <x-form-error field="fee_json" />
        </div>

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
