@extends('layouts.admin')
@section('title', 'Results — ' . $enrollment->hall_ticket)

@section('content')
<div class="max-w-4xl">
    <h1 class="text-xl font-bold text-gray-800 mb-1">Results</h1>
    <p class="text-sm text-gray-500 mb-6">{{ $enrollment->student?->name }} — {{ $enrollment->exam?->name }}</p>

    @if($enrollment->gpa)
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-blue-700">{{ $enrollment->gpa->sgpa }}</p>
            <p class="text-xs text-gray-400">SGPA</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-700">{{ $enrollment->gpa->cgpa }}</p>
            <p class="text-xs text-gray-400">CGPA</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-700">{{ $enrollment->gpa->total_marks }}</p>
            <p class="text-xs text-gray-400">Total Marks</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <x-status-badge :status="$enrollment->gpa->result" />
            <p class="text-xs text-gray-400 mt-1">Result</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Code</th>
                    <th class="px-4 py-3 text-left">Subject</th>
                    <th class="px-4 py-3 text-center">Ext</th>
                    <th class="px-4 py-3 text-center">Int</th>
                    <th class="px-4 py-3 text-center">Total</th>
                    <th class="px-4 py-3 text-center">Grade</th>
                    <th class="px-4 py-3 text-center">Credits</th>
                    <th class="px-4 py-3 text-center">Result</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($enrollment->results as $result)
                <tr class="{{ $result->result === 'F' ? 'bg-red-50' : '' }}">
                    <td class="px-4 py-2 font-mono text-xs">{{ $result->subject?->code }}</td>
                    <td class="px-4 py-2">{{ $result->subject?->name }}</td>
                    <td class="px-4 py-2 text-center">{{ $result->is_absent_ext ? 'AB' : $result->ext_marks }}</td>
                    <td class="px-4 py-2 text-center">{{ $result->is_absent_int ? 'AB' : $result->int_marks }}</td>
                    <td class="px-4 py-2 text-center font-semibold">{{ $result->total_marks }}</td>
                    <td class="px-4 py-2 text-center font-bold">{{ $result->grade }}</td>
                    <td class="px-4 py-2 text-center">{{ $result->credits }}</td>
                    <td class="px-4 py-2 text-center"><x-status-badge :status="$result->result" /></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
