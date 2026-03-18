@extends('layouts.admin')
@section('title', $student->name)

@section('content')
<div class="max-w-4xl">
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">{{ $student->name }}</h1>
            <p class="text-sm text-gray-500">Hall Ticket: {{ $student->hall_ticket }} | Course: {{ $student->course_name ?? $student->course }}</p>
        </div>
        <a href="{{ route('admin.gradesheets.show', $student) }}" class="border border-gray-300 px-3 py-1.5 rounded text-sm text-gray-700 hover:bg-gray-50">
            Grade Sheet
        </a>
    </div>

    {{-- Student Details Card --}}
    <div class="bg-white rounded-lg border border-gray-200 p-5 mb-6">
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div><p class="text-gray-400 text-xs">Father</p><p>{{ $student->father_name }}</p></div>
            <div><p class="text-gray-400 text-xs">Mother</p><p>{{ $student->mother_name }}</p></div>
            <div><p class="text-gray-400 text-xs">Phone</p><p>{{ $student->phone }}</p></div>
            <div><p class="text-gray-400 text-xs">Email</p><p>{{ $student->email }}</p></div>
            <div><p class="text-gray-400 text-xs">Caste</p><p>{{ $student->caste }}</p></div>
            <div><p class="text-gray-400 text-xs">Scheme</p><p>{{ $student->scheme }}</p></div>
        </div>
    </div>

    {{-- Enrollments --}}
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-100 font-semibold text-gray-700">Enrollments</div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-2 text-left">Exam</th>
                    <th class="px-4 py-2 text-left">Fee</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($student->enrollments as $enrollment)
                <tr>
                    <td class="px-4 py-2">{{ $enrollment->exam?->name }}</td>
                    <td class="px-4 py-2">₹{{ number_format($enrollment->fee_amount) }}</td>
                    <td class="px-4 py-2"><x-status-badge :status="$enrollment->getFeeStatus()" /></td>
                    <td class="px-4 py-2 flex gap-2">
                        <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="text-blue-600 hover:underline text-xs">Details</a>
                        <a href="{{ route('admin.results.show', $enrollment) }}" class="text-green-600 hover:underline text-xs">Results</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
