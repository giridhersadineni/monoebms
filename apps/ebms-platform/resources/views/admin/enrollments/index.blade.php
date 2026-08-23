@extends('layouts.admin')
@section('title', 'Enrollments')

@section('content')
<div class="max-w-6xl">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Enrollments</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage and verify student enrollments</p>
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex gap-2.5 mb-5 items-center">
        <input type="number" name="id" value="{{ request('id') }}"
               placeholder="ID"
               class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm w-24 bg-white
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                      font-mono placeholder:font-sans placeholder:text-slate-400">
        <input type="text" name="hall_ticket" value="{{ request('hall_ticket') }}"
               placeholder="Hall Ticket"
               class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm w-44 bg-white
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                      font-mono placeholder:font-sans placeholder:text-slate-400">
        <select name="year"
                class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Years</option>
            @foreach($years as $y)
            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <select name="exam_id"
                class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Exams</option>
            @foreach($exams as $examId => $examName)
            <option value="{{ $examId }}" {{ request('exam_id') == $examId ? 'selected' : '' }}>{{ $examName }}</option>
            @endforeach
        </select>
        <select name="fee_status"
                class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-slate-700">
            <option value="">All Fee Status</option>
            <option value="paid" {{ request('fee_status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="unpaid" {{ request('fee_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
        </select>
        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Filter
        </button>
        @if(request('id') || request('hall_ticket') || request('fee_status') || request('exam_id') || request('year'))
        <a href="{{ route('admin.enrollments.index') }}" class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">
            Clear
        </a>
        @endif
    </form>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold tracking-wide uppercase border-b border-slate-100">
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Hall Ticket</th>
                    <th class="px-5 py-3 text-left">Student</th>
                    <th class="px-5 py-3 text-left">Exam ID</th>
                    <th class="px-5 py-3 text-left">Exam</th>
                    <th class="px-5 py-3 text-left">Fee</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Enrolled</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollments as $e)
                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors last:border-0">
                    <td class="px-5 py-3 text-slate-400 text-xs font-mono">{{ $e->id }}</td>
                    <td class="px-5 py-3">
                        <code class="font-mono text-xs text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $e->hall_ticket }}</code>
                    </td>
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $e->student?->name }}</td>
                    <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ $e->exam_id }}</td>
                    <td class="px-5 py-3 text-slate-600 text-xs">{{ $e->exam?->name }}</td>
                    <td class="px-5 py-3 text-slate-800">&#8377;{{ number_format($e->fee_amount) }}</td>
                    <td class="px-5 py-3"><x-status-badge :status="$e->getFeeStatus()" /></td>
                    <td class="px-5 py-3 text-slate-400 text-xs">{{ $e->enrolled_at?->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex gap-3 items-center">
                            <a href="{{ route('admin.enrollments.show', $e) }}" class="text-blue-600 hover:underline text-xs font-medium">View</a>
                            @if(! $e->isFeePaid())
                                <button type="button"
                                        onclick="openFeeModal({{ $e->id }}, '{{ addslashes($e->student?->name) }}', '{{ $e->hall_ticket }}', {{ $e->fee_amount }})"
                                        class="text-emerald-600 hover:underline text-xs font-medium">Mark Paid</button>
                            @else
                                <span class="text-emerald-600 text-xs">&#10003; Paid</span>
                                <form method="POST" action="{{ route('admin.enrollments.fee.clear', $e->id) }}"
                                      onsubmit="return confirm('Clear payment for enrollment #{{ $e->id }}?');"
                                      style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Clear</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.enrollments.destroy', $e->id) }}"
                                  onsubmit="return confirm('Delete enrollment #{{ $e->id }}?');"
                                  style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-slate-100 text-xs text-slate-500">{{ $enrollments->links() }}</div>
    </div>
</div>
{{-- Fee Payment Modal --}}
<div id="fee-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40" onclick="closeFeeModal()"></div>
    <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md mx-4 p-6">
        <div class="mb-5">
            <h2 class="text-base font-semibold text-slate-800">Mark Fee Payment</h2>
            <p id="fee-modal-desc" class="text-xs text-slate-500 mt-0.5"></p>
        </div>
        <form id="fee-modal-form" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Challan Number <span class="text-red-500">*</span></label>
                    <input type="text" name="challan_number" id="fee-challan-number" required autocomplete="off"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Submitted On <span class="text-red-500">*</span></label>
                        <input type="date" name="challan_submitted_on" id="fee-submitted-on" required
                               class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Received By <span class="text-red-500">*</span></label>
                        <input type="text" name="challan_received_by" id="fee-received-by" required autocomplete="off"
                               class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6">
                <button type="submit"
                        class="bg-emerald-700 hover:bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                    Confirm Payment
                </button>
                <button type="button" onclick="closeFeeModal()"
                        class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script nonce="{{ $csp_nonce ?? '' }}">
const feeRoute = '{{ rtrim(route("admin.enrollments.fee", ["id" => "__ID__"]), "/") }}';

function openFeeModal(id, name, hallTicket, fee) {
    document.getElementById('fee-modal-desc').textContent =
        '#' + id + ' — ' + name + ' (' + hallTicket + ') — ₹' + fee.toLocaleString('en-IN');
    document.getElementById('fee-modal-form').action = feeRoute.replace('__ID__', id);
    document.getElementById('fee-submitted-on').value = new Date().toISOString().slice(0, 10);
    document.getElementById('fee-challan-number').value = '';
    document.getElementById('fee-received-by').value = '';

    const modal = document.getElementById('fee-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => document.getElementById('fee-challan-number').focus(), 50);
}

function closeFeeModal() {
    const modal = document.getElementById('fee-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeFeeModal(); });
</script>
@endsection
