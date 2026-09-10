@extends('layouts.admin')
@section('title', 'Detained List')

@section('content')
{{-- Force light form controls: DataTables search/length inputs render with a
     dark UA background (invisible text) under a dark color-scheme otherwise. --}}
<style>
    #detained-table_wrapper { color-scheme: light; }
    #detained-table_wrapper .dt-search input,
    #detained-table_wrapper .dt-length select,
    #detained-table_wrapper .dataTables_filter input,
    #detained-table_wrapper .dataTables_length select {
        background-color: #ffffff;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        padding: 0.35rem 0.6rem;
    }
    #detained-table_wrapper .dt-search input::placeholder { color: #94a3b8; }
</style>

<div class="w-full">

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-red-700">Detained List</h1>
        <p class="text-sm text-slate-500 mt-0.5">
            Students holding less than {{ rtrim(rtrim(number_format($cutoff, 2, '.', ''), '0'), '.') }}%
            of the credits they have appeared for
        </p>
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-2.5 mb-5 items-end">
        <div>
            <label for="cutoff" class="block text-xs font-medium text-slate-600 mb-1.5">Credits secured below (%)</label>
            <input type="number" name="cutoff" id="cutoff" min="1" max="100" step="0.01"
                   value="{{ rtrim(rtrim(number_format($cutoff, 2, '.', ''), '0'), '.') }}" autocomplete="off"
                   class="w-44 border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
        </div>

        <div>
            <label for="course" class="block text-xs font-medium text-slate-600 mb-1.5">Course</label>
            <select name="course" id="course"
                    class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <option value="">All Courses</option>
                @foreach($courseOpts as $c)
                <option value="{{ $c }}" @selected($course === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="scheme" class="block text-xs font-medium text-slate-600 mb-1.5">Scheme</label>
            <select name="scheme" id="scheme"
                    class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <option value="">All</option>
                @foreach($schemeOpts as $sc)
                <option value="{{ $sc }}" @selected($scheme === $sc)>{{ $sc }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-red-700 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Refresh List
        </button>

        @if($course !== '' || $scheme !== '' || (float) $cutoff !== 50.0)
        <a href="{{ route('admin.detained.index') }}" class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">
            Clear
        </a>
        @endif
    </form>

    {{-- Summary --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-5">
        <p class="text-sm text-slate-700">
            Detained: <strong class="text-red-700">{{ $rows->count() }}</strong>
            @if($evaluated !== null)
                <span class="text-slate-400 px-1">of</span>
                <strong>{{ $evaluated }}</strong> students with results
            @endif
            @if($course !== '' || $scheme !== '')
                <span class="text-slate-300 px-1.5">|</span>
                Filter: <strong>{{ $course !== '' ? $course : 'All courses' }}</strong>
                @if($scheme !== '')
                    <span class="text-slate-400">/ Scheme</span> <strong>{{ $scheme }}</strong>
                @endif
            @endif
            <span class="text-xs text-slate-400 ml-1">({{ $queryMs }} ms)</span>
        </p>
        <p class="text-xs text-slate-500 mt-1.5">
            Credits are counted over <strong>every paper the student has appeared in</strong> across all
            exams. Each paper counts its credits once regardless of the number of attempts, and a paper
            passed on any attempt earns its credits. Papers graded <strong>EX</strong> are left out.
        </p>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden overflow-x-auto p-4">
        <table id="detained-table" class="js-datatable display nowrap w-full text-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Hall Ticket</th>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Group</th>
                    <th>Medium</th>
                    <th>Scheme</th>
                    <th>Papers Appeared</th>
                    <th>Attempts</th>
                    <th>Credits Appeared</th>
                    <th>Credits Secured</th>
                    <th>% Credits</th>
                    <th>Papers Pending</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $r)
                <tr>
                    <td class="text-slate-400">{{ $i + 1 }}</td>
                    <td class="font-mono text-xs text-slate-700">
                        <a href="{{ route('admin.students.show', $r->hall_ticket) }}"
                           class="text-blue-600 hover:underline">{{ $r->hall_ticket }}</a>
                    </td>
                    <td class="font-medium text-slate-800">{{ $r->name }}</td>
                    <td class="text-slate-700">{{ $r->course }}</td>
                    <td class="text-slate-700">{{ $r->group_code }}</td>
                    <td class="text-slate-700">{{ $r->medium }}</td>
                    <td class="text-slate-700">{{ $r->scheme }}</td>
                    <td class="text-center">{{ (int) $r->papers_appeared }}</td>
                    <td class="text-center">{{ (int) $r->attempts }}</td>
                    <td class="text-center">{{ rtrim(rtrim(number_format((float) $r->total_credits, 1, '.', ''), '0'), '.') }}</td>
                    <td class="text-center">{{ rtrim(rtrim(number_format((float) $r->earned_credits, 1, '.', ''), '0'), '.') }}</td>
                    <td class="text-center {{ (float) $r->credit_pct == 0.0 ? 'text-red-800 font-semibold' : 'text-slate-700' }}"
                        data-order="{{ $r->credit_pct }}">{{ $r->credit_pct }}%</td>
                    <td class="text-center">{{ (int) $r->papers_pending }}</td>
                    <td class="text-slate-600">{{ $r->phone }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($rows->isEmpty())
        <p class="text-sm text-emerald-700 mt-4">
            No detained students at the
            {{ rtrim(rtrim(number_format($cutoff, 2, '.', ''), '0'), '.') }}% cut-off for this filter.
        </p>
        @endif
    </div>

    <p class="text-xs text-slate-400 mt-3">
        Search filters the list as you type. Copy/CSV/Excel/PDF/Print export every matching row,
        not just the page on screen — clear the search first to export the whole list.
    </p>

</div>
@endsection

@push('scripts')
{{-- Loads DataTables: the search box, pagination and the export buttons.
     The js-datatable class alone does nothing without this bundle. --}}
@vite(['resources/js/admin-datatable.js'])
@endpush
