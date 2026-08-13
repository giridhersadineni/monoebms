@extends('layouts.admin')
@section('title', 'Results')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-bold text-gray-800 mb-1">Results</h1>
    <p class="text-sm text-gray-500 mb-6">Look up results by exam or hall ticket number.</p>

    <form method="GET" action="{{ route('admin.results.lookup') }}"
          class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6 space-y-4">
        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Exam</label>
            <select name="exam_id" class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <option value="">Select an exam&hellip;</option>
                @foreach($exams as $exam)
                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-3 text-xs text-slate-400">
            <div class="flex-1 border-t border-slate-200"></div>
            OR
            <div class="flex-1 border-t border-slate-200"></div>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Hall Ticket Number</label>
            <input type="text" name="hall_ticket" value="{{ old('hall_ticket', $hallTicket) }}"
                   placeholder="e.g. 001201060"
                   class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            <p class="text-xs text-slate-400 mt-1">If both are filled, results are narrowed to that exam.</p>
        </div>

        @if($errors->any())
        <p class="text-xs text-red-600">{{ $errors->first() }}</p>
        @endif

        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Load Results
        </button>
    </form>

    @if($matches->isNotEmpty())
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-3 border-b border-slate-100 text-sm font-medium text-slate-700">
            Multiple enrollments found for {{ $hallTicket }} — choose one
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($matches as $enrollment)
            <a href="{{ route('admin.results.show', $enrollment) }}"
               class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition-colors">
                <span class="text-sm text-slate-700">{{ $enrollment->exam?->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($exams->isEmpty())
        <p class="text-sm text-gray-500">No exams found.</p>
    @else
        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Browse by Exam</p>
        <div class="divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white shadow-sm">
            @foreach($exams as $exam)
            <a href="{{ route('admin.results.records', $exam) }}"
               class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $exam->name }}</p>
                    @if($exam->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $exam->description }}</p>
                    @endif
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
