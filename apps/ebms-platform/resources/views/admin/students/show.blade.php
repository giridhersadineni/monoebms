@extends('layouts.admin')
@section('title', $student->name)

@section('content')
<div class="max-w-4xl">
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">{{ $student->name }}</h1>
            <p class="text-sm text-gray-500">Hall Ticket: {{ $student->hall_ticket }} | Course: {{ $student->course_name ?? $student->course }}</p>
        </div>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('admin.students.login-as', $student->hall_ticket) }}">
                @csrf
                <button type="submit"
                        class="border border-gray-300 px-3 py-1.5 rounded text-sm text-gray-700 hover:bg-gray-50">
                    Login As
                </button>
            </form>
            <a href="{{ route('admin.students.edit', $student->hall_ticket) }}"
               class="bg-slate-900 hover:bg-slate-800 text-white px-3 py-1.5 rounded text-sm font-medium transition-colors">
                Edit
            </a>
            <a href="{{ route('admin.gradesheets.show', $student) }}" class="border border-gray-300 px-3 py-1.5 rounded text-sm text-gray-700 hover:bg-gray-50">
                Grade Sheet
            </a>
        </div>
    </div>

    {{-- Photo & Signature --}}
    @if($student->photo_url || $student->signature_url)
    <div class="bg-white rounded-lg border border-gray-200 p-5 mb-6 flex items-start gap-6">
        <div class="text-center">
            @if($student->photo_url)
                <img src="{{ $student->photo_url }}" alt="Photo" class="w-24 rounded border border-gray-200 object-cover mb-1">
                <p class="text-xs text-gray-400">Photo</p>
            @else
                <div class="w-24 h-32 bg-gray-100 rounded border border-dashed border-gray-300 flex items-center justify-center mb-1">
                    <span class="text-xs text-gray-400">No photo</span>
                </div>
            @endif
        </div>
        <div class="text-center">
            @if($student->signature_url)
                <img src="{{ $student->signature_url }}" alt="Signature" class="h-12 rounded border border-gray-200 mb-1">
                <p class="text-xs text-gray-400">Signature</p>
            @else
                <div class="w-40 h-12 bg-gray-100 rounded border border-dashed border-gray-300 flex items-center justify-center mb-1">
                    <span class="text-xs text-gray-400">No signature</span>
                </div>
            @endif
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6 flex items-center gap-3">
        <span class="text-gray-400 text-sm">No photo or signature uploaded.</span>
    </div>
    @endif

    {{-- Student Details Card --}}
    <div class="bg-white rounded-lg border border-gray-200 p-5 mb-6 space-y-4 text-sm">
        @php
        $field = fn($label, $value) => $value
            ? "<div><p class='text-gray-400 text-xs'>{$label}</p><p class='truncate'>{$value}</p></div>"
            : "<div><p class='text-gray-400 text-xs'>{$label}</p><p class='text-gray-300'>—</p></div>";
        @endphp

        {{-- Personal --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Personal</p>
            <div class="grid grid-cols-4 gap-x-4 gap-y-3">
                <div><p class="text-gray-400 text-xs">Gender</p><p>{{ $student->gender ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">DOB</p><p>{{ $student->dob?->format('d M Y') ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Father</p><p>{{ $student->father_name ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Mother</p><p>{{ $student->mother_name ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Phone</p><p>{{ $student->phone ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Email</p><p class="truncate">{{ $student->email ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Caste</p><p>{{ $student->caste ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Sub-Caste</p><p>{{ $student->sub_caste ?? '—' }}</p></div>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- Academic --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Academic</p>
            <div class="grid grid-cols-4 gap-x-4 gap-y-3">
                <div><p class="text-gray-400 text-xs">Course</p><p>{{ $student->course }}</p></div>
                <div><p class="text-gray-400 text-xs">Course Name</p><p class="truncate">{{ $student->course_name ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Group Code</p><p>{{ $student->group_code ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Medium</p><p>{{ $student->medium }}</p></div>
                <div><p class="text-gray-400 text-xs">Semester</p><p>{{ $student->semester }}</p></div>
                <div><p class="text-gray-400 text-xs">Scheme</p><p>{{ $student->scheme }}</p></div>
                <div><p class="text-gray-400 text-xs">Admission Year</p><p>{{ $student->admission_year ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Status</p>
                    <p>
                        @if($student->is_active)
                            <span class="text-green-600 font-medium">Active</span>
                        @else
                            <span class="text-red-500 font-medium">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- Address --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Address</p>
            <div class="grid grid-cols-4 gap-x-4 gap-y-3">
                <div class="col-span-2"><p class="text-gray-400 text-xs">Address</p><p>{{ implode(', ', array_filter([$student->address, $student->address2])) ?: '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Mandal</p><p>{{ $student->mandal ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">City</p><p>{{ $student->city ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">State</p><p>{{ $student->state ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Pincode</p><p>{{ $student->pincode ?? '—' }}</p></div>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- IDs --}}
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">IDs & Other</p>
            <div class="grid grid-cols-4 gap-x-4 gap-y-3">
                <div><p class="text-gray-400 text-xs">DOST ID</p><p class="font-mono text-xs">{{ $student->dost_id ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">APAAR ID</p><p class="font-mono text-xs">{{ $student->apaar_id ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">SSC Hall Ticket</p><p class="font-mono text-xs">{{ $student->ssc_hall_ticket ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Inter Hall Ticket</p><p class="font-mono text-xs">{{ $student->inter_hall_ticket ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Challenged Quota</p><p>{{ $student->challenged_quota ?? '—' }}</p></div>
            </div>
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
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
