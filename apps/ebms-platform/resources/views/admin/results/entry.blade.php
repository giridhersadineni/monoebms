@extends('layouts.admin')
@section('title', 'Result Entry')

@section('content')
<div class="max-w-6xl">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Result Entry</h1>
            <p class="text-sm text-gray-500">{{ $exam->name }}</p>
        </div>
        <form method="POST" action="{{ route('admin.results.process', $exam) }}">
            @csrf
            <button class="bg-blue-700 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                Calculate GPA for All
            </button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.results.store') }}">
        @csrf

        @foreach($enrollments as $enrollment)
        <div class="bg-white rounded-lg border border-gray-200 mb-4">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <span class="font-semibold text-gray-700">{{ $enrollment->student?->name }}</span>
                <span class="text-xs text-gray-400 font-mono">{{ $enrollment->hall_ticket }}</span>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-2 text-left">Code</th>
                        <th class="px-4 py-2 text-left">Subject</th>
                        <th class="px-4 py-2 text-center w-24">Ext Marks</th>
                        <th class="px-4 py-2 text-center w-24">Int Marks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($enrollment->enrollmentSubjects as $es)
                    <tr>
                        <td class="px-4 py-2 font-mono text-xs">{{ $es->subject_code }}</td>
                        <td class="px-4 py-2">{{ $es->subject?->name }}</td>
                        <td class="px-4 py-2">
                            <input type="text" name="results[{{ $enrollment->id }}][{{ $es->subject_id }}][ext]"
                                   placeholder="0–100 or AB"
                                   class="w-full border border-gray-300 rounded px-2 py-1 text-center text-sm focus:outline-none focus:ring-1 focus:ring-blue-400"
                                   value="{{ old("results.{$enrollment->id}.{$es->subject_id}.ext") }}">
                        </td>
                        <td class="px-4 py-2">
                            <input type="text" name="results[{{ $enrollment->id }}][{{ $es->subject_id }}][int]"
                                   placeholder="0–20 or AB"
                                   class="w-full border border-gray-300 rounded px-2 py-1 text-center text-sm focus:outline-none focus:ring-1 focus:ring-blue-400"
                                   value="{{ old("results.{$enrollment->id}.{$es->subject_id}.int") }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach

        <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded text-sm hover:bg-gray-800">
            Save All Results
        </button>
    </form>
</div>
@endsection
