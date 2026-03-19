@extends('layouts.admin')
@section('title', $paper ? 'Edit ' . $paper->code : 'New Paper')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.papers.index') }}"
               class="text-slate-400 hover:text-slate-600 text-sm">Papers</a>
            @if($paper)
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.papers.show', $paper) }}"
                   class="text-slate-400 hover:text-slate-600 text-sm">{{ $paper->code }}</a>
            @endif
            <span class="text-slate-300">/</span>
            <span class="text-slate-600 text-sm">{{ $paper ? 'Edit' : 'New' }}</span>
        </div>
        <h1 class="text-xl font-semibold text-slate-800">{{ $paper ? 'Edit Paper' : 'New Paper' }}</h1>
    </div>

    <form method="POST"
          action="{{ $paper ? route('admin.papers.update', $paper) : route('admin.papers.store') }}"
          class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf
        @if($paper) @method('PUT') @endif

        {{-- Row 1: Code | Course --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">
                    Paper Code <span class="text-red-500">*</span>
                </label>
                <input type="text" name="code"
                       value="{{ old('code', $paper?->code) }}" required
                       placeholder="e.g. BCM211"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono uppercase
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                              placeholder:font-sans placeholder:normal-case placeholder:text-slate-400">
                <x-form-error field="code" />
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">
                    Course <span class="text-red-500">*</span>
                </label>
                <select name="course" id="course_select" required
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                    <option value="">— Select Course —</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->code }}" {{ old('course', $paper?->course) === $c->code ? 'selected' : '' }}>
                            {{ $c->code }} — {{ $c->name }}
                        </option>
                    @endforeach
                </select>
                <x-form-error field="course" />
            </div>
        </div>

        {{-- Row 2: Group | Medium --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Group</label>
                <select name="group_code" id="group_select"
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                    <option value="">— None / All Groups —</option>
                    @foreach($courses as $c)
                        @foreach($c->groups as $g)
                            <option value="{{ $g->code }}"
                                    data-course="{{ $c->code }}"
                                    {{ old('group_code', $paper?->group_code) === $g->code ? 'selected' : '' }}>
                                {{ $g->code }} — {{ $g->name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
                <x-form-error field="group_code" />
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">
                    Medium <span class="text-red-500">*</span>
                </label>
                <select name="medium" required
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                    <option value="">— Select Medium —</option>
                    @foreach(['TM' => 'Telugu', 'EM' => 'English', 'BM' => 'Bilingual'] as $val => $label)
                        <option value="{{ $val }}" {{ old('medium', $paper?->medium) === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <x-form-error field="medium" />
            </div>
        </div>

        {{-- Row 3: Paper Name (full width) --}}
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">
                Paper Name <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name"
                   value="{{ old('name', $paper?->name) }}" required
                   placeholder="e.g. Financial Accounting - II"
                   class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            <x-form-error field="name" />
        </div>

        {{-- Row 4: Semester | Scheme | Part --}}
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">
                    Semester <span class="text-red-500">*</span>
                </label>
                <input type="number" name="semester" min="1" max="12" required
                       value="{{ old('semester', $paper?->semester ?? '') }}"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <x-form-error field="semester" />
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">
                    Scheme <span class="text-red-500">*</span>
                </label>
                <input type="number" name="scheme" min="2000" required
                       value="{{ old('scheme', $paper?->scheme ?? '') }}"
                       placeholder="e.g. 2025"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                              placeholder:font-sans">
                <x-form-error field="scheme" />
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Part</label>
                <input type="number" name="part" min="1" max="10"
                       value="{{ old('part', $paper?->part ?? 1) }}"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <x-form-error field="part" />
            </div>
        </div>

        {{-- Row 5: Paper Type | Elective Group --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">
                    Paper Type <span class="text-red-500">*</span>
                </label>
                <select name="paper_type" id="paper_type_select" required
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
                    <option value="compulsory" {{ old('paper_type', $paper?->paper_type ?? 'compulsory') === 'compulsory' ? 'selected' : '' }}>Compulsory</option>
                    <option value="elective"   {{ old('paper_type', $paper?->paper_type) === 'elective' ? 'selected' : '' }}>Elective</option>
                </select>
                <x-form-error field="paper_type" />
            </div>
            <div id="elective_group_wrap" style="display:none;">
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Elective Group</label>
                <input type="text" name="elective_group"
                       value="{{ old('elective_group', $paper?->elective_group) }}"
                       placeholder="e.g. E1, E2"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                              placeholder:font-sans placeholder:text-slate-400">
                <x-form-error field="elective_group" />
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
            <button type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                {{ $paper ? 'Save Changes' : 'Create Paper' }}
            </button>
            <a href="{{ $paper ? route('admin.papers.show', $paper) : route('admin.papers.index') }}"
               class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Cancel</a>
        </div>
    </form>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
(function () {
    var courseSelect = document.getElementById('course_select');
    var groupSelect  = document.getElementById('group_select');
    var typeSelect   = document.getElementById('paper_type_select');
    var electiveWrap = document.getElementById('elective_group_wrap');

    function filterGroups() {
        var selected = courseSelect ? courseSelect.value : '';
        if (!groupSelect) return;
        var opts = groupSelect.querySelectorAll('option[data-course]');
        opts.forEach(function(opt) {
            opt.hidden = selected && opt.dataset.course !== selected;
        });
        if (groupSelect.selectedOptions[0] && groupSelect.selectedOptions[0].hidden) {
            groupSelect.value = '';
        }
    }

    function toggleElective() {
        if (!typeSelect || !electiveWrap) return;
        electiveWrap.style.display = typeSelect.value === 'elective' ? '' : 'none';
    }

    if (courseSelect) courseSelect.addEventListener('change', filterGroups);
    if (typeSelect) typeSelect.addEventListener('change', toggleElective);

    filterGroups();
    toggleElective();
})();
</script>
@endsection
