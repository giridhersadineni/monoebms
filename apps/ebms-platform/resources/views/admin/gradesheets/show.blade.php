@extends('layouts.admin')
@section('title', 'Grade Sheet — ' . $student->hall_ticket)

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Grade Sheet</h1>
            <p class="text-sm text-gray-500">{{ $student->name }} — {{ $student->hall_ticket }}</p>
        </div>
        <div class="flex gap-2">
            @can('generate-gradesheet')
            <form method="POST" action="{{ route('admin.gradesheets.generate', $student) }}">
                @csrf
                <button class="bg-blue-700 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                    Regenerate CGPA
                </button>
            </form>
            @endcan
            <button onclick="window.print()" class="border border-gray-300 px-4 py-2 rounded text-sm hover:bg-gray-50">
                Print
            </button>
        </div>
    </div>

    @if($student->grade)
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">Part I</h3>
                <table class="w-full text-sm">
                    <tr><td class="text-gray-500 py-1">CGPA</td><td class="font-bold">{{ $student->grade->part1_cgpa }}</td></tr>
                    <tr><td class="text-gray-500 py-1">Division</td><td>{{ $student->grade->part1_division }}</td></tr>
                    <tr><td class="text-gray-500 py-1">Subjects</td><td class="text-xs">{{ $student->grade->part1_subjects }}</td></tr>
                </table>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700 mb-3">Part II</h3>
                <table class="w-full text-sm">
                    <tr><td class="text-gray-500 py-1">CGPA</td><td class="font-bold">{{ $student->grade->part2_cgpa }}</td></tr>
                    <tr><td class="text-gray-500 py-1">Division</td><td>{{ $student->grade->part2_division }}</td></tr>
                    <tr><td class="text-gray-500 py-1">Subjects</td><td class="text-xs">{{ $student->grade->part2_subjects }}</td></tr>
                </table>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Overall CGPA</p>
                    <p class="text-3xl font-bold text-blue-700">{{ $student->grade->all_cgpa }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-sm">Final Division</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $student->grade->final_division }}</p>
                </div>
            </div>
        </div>

        @if($student->grade->remarks)
        <div class="mt-4 bg-gray-50 rounded p-3 text-sm text-gray-600">
            <strong>Remarks:</strong> {{ $student->grade->remarks }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-lg border border-gray-200 px-6 py-10 text-center text-gray-400">
        No grade sheet generated yet.
        <form method="POST" action="{{ route('admin.gradesheets.generate', $student) }}" class="mt-4">
            @csrf
            <button class="bg-blue-700 text-white px-5 py-2 rounded text-sm hover:bg-blue-600">
                Generate Grade Sheet
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
