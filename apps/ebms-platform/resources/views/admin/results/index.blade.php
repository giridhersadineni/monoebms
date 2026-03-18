@extends('layouts.admin')
@section('title', 'Results — Select Exam')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-xl font-bold text-gray-800 mb-1">Results Entry</h1>
    <p class="text-sm text-gray-500 mb-6">Select an exam to enter or review results.</p>

    @if($exams->isEmpty())
        <p class="text-sm text-gray-500">No exams found.</p>
    @else
        <div class="divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white shadow-sm">
            @foreach($exams as $exam)
            <a href="{{ route('admin.results.entry', $exam) }}"
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
