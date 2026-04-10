@extends('layouts.admin')
@section('title', 'Papers')

@section('content')
<div class="max-w-6xl">
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Papers</h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage exam papers for each course and group</p>
        </div>
        @if(auth('admin')->user()->canAccess('papers.manage'))
        <a href="{{ route('admin.papers.create') }}"
           class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            + New Paper
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex gap-2.5 mb-5 items-center flex-wrap">
        <select name="course" id="filter_course"
                class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Courses</option>
            @foreach($courses as $c)
                <option value="{{ $c->code }}" {{ request('course') === $c->code ? 'selected' : '' }}>
                    {{ $c->code }}
                </option>
            @endforeach
        </select>

        <select name="group_code" id="filter_group"
                class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Groups</option>
            @foreach($groups as $g)
                <option value="{{ $g->code }}"
                        data-course="{{ $g->course->code ?? '' }}"
                        {{ request('group_code') === $g->code ? 'selected' : '' }}>
                    {{ $g->code }}
                </option>
            @endforeach
        </select>

        <select name="semester"
                class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Semesters</option>
            @foreach(range(1, 6) as $s)
                <option value="{{ $s }}" {{ request('semester') == $s ? 'selected' : '' }}>Sem {{ $s }}</option>
            @endforeach
        </select>

        <select name="medium"
                class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Mediums</option>
            @foreach(['TM' => 'Telugu', 'EM' => 'English', 'BM' => 'Bilingual'] as $val => $label)
                <option value="{{ $val }}" {{ request('medium') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>

        <select name="paper_type"
                class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Types</option>
            <option value="compulsory" {{ request('paper_type') === 'compulsory' ? 'selected' : '' }}>Compulsory</option>
            <option value="elective"   {{ request('paper_type') === 'elective'   ? 'selected' : '' }}>Elective</option>
        </select>

        <select name="scheme"
                class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Schemes</option>
            @foreach($schemes as $sc)
                <option value="{{ $sc }}" {{ request('scheme') == $sc ? 'selected' : '' }}>{{ $sc }}</option>
            @endforeach
        </select>

        <button type="submit"
                class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['course','group_code','semester','medium','paper_type','scheme']))
            <a href="{{ route('admin.papers.index') }}"
               class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold tracking-wide uppercase border-b border-slate-100">
                    <th class="px-5 py-3 text-left">Code</th>
                    <th class="px-5 py-3 text-left">Name</th>
                    <th class="px-5 py-3 text-left">Course</th>
                    <th class="px-5 py-3 text-left">Group</th>
                    <th class="px-5 py-3 text-left">Medium</th>
                    <th class="px-5 py-3 text-left">Sem</th>
                    <th class="px-5 py-3 text-left">Type</th>
                    <th class="px-5 py-3 text-left">Elective Grp</th>
                    <th class="px-5 py-3 text-left">Scheme</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($papers as $paper)
                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors last:border-0">
                    <td class="px-5 py-3">
                        <code class="font-mono text-xs text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $paper->code }}</code>
                    </td>
                    <td class="px-5 py-3 text-slate-800 font-medium max-w-xs">
                        <span class="block truncate" title="{{ $paper->name }}">{{ $paper->name }}</span>
                    </td>
                    <td class="px-5 py-3 text-slate-500 text-xs font-mono">{{ $paper->course }}</td>
                    <td class="px-5 py-3 text-slate-500 text-xs font-mono">{{ $paper->group_code ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-500 text-xs">{{ $paper->medium }}</td>
                    <td class="px-5 py-3 text-slate-500 text-xs font-mono">{{ $paper->semester }}</td>
                    <td class="px-5 py-3">
                        @if($paper->paper_type === 'elective')
                            <span class="inline-flex items-center bg-purple-50 text-purple-800 text-xs font-medium px-2 py-0.5 rounded-md ring-1 ring-purple-200">Elective</span>
                        @else
                            <span class="inline-flex items-center bg-blue-50 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-md ring-1 ring-blue-200">Compulsory</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500 text-xs font-mono">{{ $paper->elective_group ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-500 text-xs font-mono">{{ $paper->scheme }}</td>
                    <td class="px-5 py-3">
                        @if(auth('admin')->user()->canAccess('papers.manage'))
                        <div class="flex gap-3">
                            <a href="{{ route('admin.papers.edit', $paper) }}"
                               class="text-blue-600 hover:underline text-xs font-medium">Edit</a>
                            <form method="POST"
                                  action="{{ route('admin.papers.destroy', $paper) }}"
                                  onsubmit="return confirm('Delete paper {{ $paper->code }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-5 py-10 text-center text-slate-400 text-sm">
                        No papers found.@if(auth('admin')->user()->canAccess('papers.manage')) <a href="{{ route('admin.papers.create') }}" class="text-blue-600 hover:underline">Add one</a>.@endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($papers->hasPages())
        <div class="px-5 py-3 border-t border-slate-100">
            {{ $papers->links() }}
        </div>
        @endif
    </div>

    <p class="mt-3 text-xs text-slate-400">{{ $papers->total() }} paper{{ $papers->total() !== 1 ? 's' : '' }} total</p>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
(function () {
    var courseSelect = document.getElementById('filter_course');
    var groupSelect  = document.getElementById('filter_group');
    if (!courseSelect || !groupSelect) return;

    function filterGroups() {
        var selected = courseSelect.value;
        var opts = groupSelect.querySelectorAll('option[data-course]');
        opts.forEach(function(opt) {
            opt.hidden = selected && opt.dataset.course !== selected;
        });
        // reset if current selection is hidden
        if (groupSelect.selectedOptions[0] && groupSelect.selectedOptions[0].hidden) {
            groupSelect.value = '';
        }
    }

    courseSelect.addEventListener('change', filterGroups);
    filterGroups();
})();
</script>
@endsection
