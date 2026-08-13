@extends('layouts.admin')
@section('title', 'Results — ' . $exam->name)

@php
    $sortLink = fn (string $col) => route('admin.results.records', array_merge(['exam' => $exam->id], request()->except('page'), [
        'sort' => $col,
        'dir'  => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc',
    ]));
    $sortIcon = fn (string $col) => $sort === $col ? ($dir === 'asc' ? '▲' : '▼') : '';
@endphp

@section('content')
<div class="max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('admin.results.index') }}" class="text-xs text-slate-500 hover:underline">&larr; Back to Results</a>
        <h1 class="text-xl font-semibold text-slate-800 mt-1">{{ $exam->name }}</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ $results->total() }} result row(s)</p>
    </div>

    <form method="GET" class="flex gap-2.5 mb-5 items-center">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Search hall ticket, name, or subject"
               class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm w-72 bg-white
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Search
        </button>
        @if(request('q'))
        <a href="{{ route('admin.results.records', $exam) }}" class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">
            Clear
        </a>
        @endif
    </form>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold tracking-wide uppercase border-b border-slate-100">
                    <th class="px-4 py-3 text-left"><a href="{{ $sortLink('hall_ticket') }}" class="hover:text-slate-800">Hall Ticket {{ $sortIcon('hall_ticket') }}</a></th>
                    <th class="px-4 py-3 text-left">Student</th>
                    <th class="px-4 py-3 text-left"><a href="{{ $sortLink('subject_code') }}" class="hover:text-slate-800">Subject {{ $sortIcon('subject_code') }}</a></th>
                    <th class="px-4 py-3 text-center"><a href="{{ $sortLink('ext_marks') }}" class="hover:text-slate-800">Ext {{ $sortIcon('ext_marks') }}</a></th>
                    <th class="px-4 py-3 text-center"><a href="{{ $sortLink('int_marks') }}" class="hover:text-slate-800">Int {{ $sortIcon('int_marks') }}</a></th>
                    <th class="px-4 py-3 text-center"><a href="{{ $sortLink('total_marks') }}" class="hover:text-slate-800">Total {{ $sortIcon('total_marks') }}</a></th>
                    <th class="px-4 py-3 text-center"><a href="{{ $sortLink('grade') }}" class="hover:text-slate-800">Grade {{ $sortIcon('grade') }}</a></th>
                    <th class="px-4 py-3 text-center"><a href="{{ $sortLink('credits') }}" class="hover:text-slate-800">Credits {{ $sortIcon('credits') }}</a></th>
                    <th class="px-4 py-3 text-center"><a href="{{ $sortLink('result') }}" class="hover:text-slate-800">Result {{ $sortIcon('result') }}</a></th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $r)
                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors last:border-0">
                    <td class="px-4 py-3 font-mono text-xs text-slate-700">{{ $r->hall_ticket }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $r->enrollment?->student?->name }}</td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs text-slate-500">{{ $r->subject?->code }}</span>
                        <span class="text-slate-700">{{ $r->subject?->name }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">{{ $r->is_absent_ext ? 'AB' : $r->ext_marks }}</td>
                    <td class="px-4 py-3 text-center">{{ $r->is_absent_int ? 'AB' : $r->int_marks }}</td>
                    <td class="px-4 py-3 text-center font-semibold text-slate-800">{{ $r->total_marks }}</td>
                    <td class="px-4 py-3 text-center font-bold">{{ $r->grade }}</td>
                    <td class="px-4 py-3 text-center text-slate-600">{{ $r->credits }}</td>
                    <td class="px-4 py-3 text-center"><x-status-badge :status="$r->result" /></td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center text-sm text-slate-400">No results found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-500">{{ $results->links() }}</div>
    </div>
</div>
@endsection
