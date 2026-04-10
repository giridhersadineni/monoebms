@extends('layouts.admin')
@section('title', 'Planning — ' . $exam->name)

@section('content')
<div class="max-w-5xl">

    {{-- Header --}}
    <div class="mb-5 flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1 text-sm">
                <a href="{{ route('admin.exams.index') }}" class="text-slate-400 hover:text-slate-600">Exams</a>
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.exams.show', $exam) }}" class="text-slate-400 hover:text-slate-600">{{ $exam->name }}</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-600">Planning</span>
            </div>
            <h1 class="text-xl font-semibold text-slate-800">Exam Planning</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $exam->course ?? 'All Courses' }} &middot; Semester {{ $exam->semester }}
                &middot; {{ $exam->month_name }} {{ $exam->year }}
                &middot; <span class="capitalize">{{ $exam->exam_type }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if(auth('admin')->user()->canAccess('dform.view'))
            <a href="{{ route('admin.dform.index', ['exam_id' => $exam->id]) }}"
               class="px-3 py-2 rounded-lg text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                D-Form Generator
            </a>
            @endif
            @if(auth('admin')->user()->canAccess('attendance.view'))
            <a href="{{ route('admin.attendance.index', ['exam_id' => $exam->id]) }}"
               class="px-3 py-2 rounded-lg text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                Attendance Generator
            </a>
            @endif
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-3 gap-4 mb-5">
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-2xl font-bold text-slate-800">{{ $papers->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Papers with Enrollments</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-2xl font-bold text-emerald-600">{{ number_format($totalPaidEnrollments) }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Fee-Paid Enrollments</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-2xl font-bold text-slate-800">{{ $papers->sum('total') > 0 ? number_format(round($papers->sum('total') / max($papers->count(), 1))) : '—' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Avg Students / Paper</p>
        </div>
    </div>

    {{-- Papers Table --}}
    @if($papers->isEmpty())
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-10 text-center">
        <p class="text-slate-400 text-sm">No fee-paid enrollments found for this exam.</p>
        <p class="text-slate-300 text-xs mt-1">D-Forms can only be generated once students have paid the exam fee.</p>
    </div>
    @else
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-slate-700">Generatable D-Forms</span>
                <span class="ml-2 text-xs text-slate-400">{{ $papers->count() }} papers &middot; fee-paid students only</span>
            </div>
            @if(auth('admin')->user()->canAccess('dform.view'))
            <p class="text-xs text-slate-400">Click a paper to open its D-Form / Attendance sheet</p>
            @endif
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Paper Code</th>
                    <th class="px-5 py-2.5 text-left">Subject Name</th>
                    <th class="px-5 py-2.5 text-left">Course</th>
                    <th class="px-5 py-2.5 text-left">Scheme</th>
                    <th class="px-5 py-2.5 text-right">Students</th>
                    @if(auth('admin')->user()->canAccess('dform.view'))
                    <th class="px-5 py-2.5 text-center">D-Form</th>
                    @endif
                    @if(auth('admin')->user()->canAccess('attendance.view'))
                    <th class="px-5 py-2.5 text-center">Attendance</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($papers as $paper)
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3">
                        <code class="font-mono text-xs font-semibold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">
                            {{ $paper->subject_code }}
                        </code>
                    </td>
                    <td class="px-5 py-3 text-slate-700">
                        @if($paper->name)
                            {{ $paper->name }}
                        @else
                            <span class="text-slate-400 italic">Unknown</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-slate-500 text-xs">{{ $paper->course ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-500 text-xs">{{ $paper->scheme ?? '—' }}</td>
                    <td class="px-5 py-3 text-right">
                        <span class="font-semibold text-slate-800">{{ number_format($paper->total) }}</span>
                    </td>
                    @if(auth('admin')->user()->canAccess('dform.view'))
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('admin.dform.print', ['exam_id' => $exam->id, 'subject_code' => $paper->subject_code]) }}"
                           target="_blank"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium
                                  border border-blue-200 text-blue-700 hover:bg-blue-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print
                        </a>
                    </td>
                    @endif
                    @if(auth('admin')->user()->canAccess('attendance.view'))
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('admin.attendance.print', ['exam_id' => $exam->id, 'subject_code' => $paper->subject_code]) }}"
                           target="_blank"
                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium
                                  border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Sheet
                        </a>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-slate-50 border-t border-slate-200 text-xs font-semibold text-slate-600">
                    <td class="px-5 py-2.5" colspan="4">Total</td>
                    <td class="px-5 py-2.5 text-right">{{ number_format($papers->sum('total')) }}</td>
                    @if(auth('admin')->user()->canAccess('dform.view'))
                    <td></td>
                    @endif
                    @if(auth('admin')->user()->canAccess('attendance.view'))
                    <td></td>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

</div>
@endsection
