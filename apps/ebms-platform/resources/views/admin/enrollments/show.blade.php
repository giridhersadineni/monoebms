@extends('layouts.admin')
@section('title', 'Enrollment #' . $enrollment->id)

@section('content')
<div class="max-w-3xl">
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800 mb-1">Enrollment #{{ $enrollment->id }}</h1>
            <p class="text-sm text-gray-500">{{ $enrollment->exam?->name }} — {{ $enrollment->hall_ticket }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.enrollments.subjects', $enrollment->id) }}"
               class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                Manage Subjects
            </a>
            <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment->id) }}"
                  onsubmit="return confirm('Delete enrollment #{{ $enrollment->id }}? This cannot be undone.');">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-5 mb-4">
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div><p class="text-gray-400 text-xs">Student</p><p>{{ $enrollment->student?->name }}</p></div>
            <div><p class="text-gray-400 text-xs">Course</p><p>{{ $enrollment->student?->course }}</p></div>
            <div><p class="text-gray-400 text-xs">Exam ID</p><p class="font-mono">{{ $enrollment->exam_id }}</p></div>
            <div><p class="text-gray-400 text-xs">Fee Amount</p><p class="font-semibold">₹{{ number_format($enrollment->fee_amount) }}</p></div>
            <div><p class="text-gray-400 text-xs">Enrolled At</p><p>{{ $enrollment->enrolled_at?->format('d M Y H:i') }}</p></div>
            <div><p class="text-gray-400 text-xs">Fee Paid At</p><p>{{ $enrollment->fee_paid_at?->format('d M Y') ?? '—' }}</p></div>
            <div><p class="text-gray-400 text-xs">Challan No</p><p>{{ $enrollment->challan_number ?? '—' }}</p></div>
        </div>
    </div>

    {{-- Subjects --}}
    <div class="bg-white rounded-lg border border-gray-200 mb-6">
        <div class="px-4 py-3 border-b border-gray-100 font-semibold text-gray-700">Enrolled Subjects</div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-2 text-left">Code</th>
                    <th class="px-4 py-2 text-left">Subject</th>
                    <th class="px-4 py-2 text-left">Type</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($enrollment->enrollmentSubjects as $es)
                <tr>
                    <td class="px-4 py-2 font-mono text-xs">{{ $es->subject_code }}</td>
                    <td class="px-4 py-2">{{ $es->subject?->name ?? $subjectNames[$es->subject_code] ?? $es->subject_code }}</td>
                    <td class="px-4 py-2 text-gray-500">{{ ucfirst($es->subject_type) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Fee Marking Form --}}
    @if(! $enrollment->isFeePaid())
    <div id="fee" class="bg-white rounded-lg border border-gray-200 p-5">
        <h2 class="font-semibold text-gray-700 mb-4">Mark Fee Payment Received</h2>
        <form method="POST" action="{{ route('admin.enrollments.fee', $enrollment) }}">
            @csrf
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Challan Number</label>
                    <input type="text" name="challan_number" required
                           value="{{ old('challan_number', $enrollment->id) }}"
                           class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                    <x-form-error field="challan_number" />
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Submitted On</label>
                    <input type="date" name="challan_submitted_on" required
                           class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none">
                    <x-form-error field="challan_submitted_on" />
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Received By</label>
                    <input type="text" name="challan_received_by" required
                           value="{{ old('challan_received_by', auth('admin')->user()?->name) }}"
                           class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none">
                    <x-form-error field="challan_received_by" />
                </div>
            </div>
            <button class="bg-green-700 text-white px-5 py-2 rounded text-sm hover:bg-green-600">
                Mark as Fee Paid
            </button>
        </form>
    </div>
    @else
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-800 flex items-center justify-between gap-4">
        <span>
            Fee received on {{ $enrollment->fee_paid_at?->format('d M Y') }} by {{ $enrollment->challan_received_by }}.
            Challan: {{ $enrollment->challan_number }}.
        </span>
        <form method="POST" action="{{ route('admin.enrollments.fee.clear', $enrollment->id) }}"
              onsubmit="return confirm('Clear payment record for enrollment #{{ $enrollment->id }}?');">
            @csrf @method('DELETE')
            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800 whitespace-nowrap">
                Clear Payment
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
