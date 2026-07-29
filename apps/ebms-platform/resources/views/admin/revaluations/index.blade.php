@extends('layouts.admin')
@section('title', 'Revaluations')

@section('content')
{{-- Force light form controls: DataTables search/length inputs render with a
     dark UA background (invisible text) under a dark color-scheme otherwise. --}}
<style>
    #revaluations-table_wrapper { color-scheme: light; }
    #revaluations-table_wrapper .dt-search input,
    #revaluations-table_wrapper .dt-length select,
    #revaluations-table_wrapper .dataTables_filter input,
    #revaluations-table_wrapper .dataTables_length select {
        background-color: #ffffff;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        padding: 0.35rem 0.6rem;
    }
    #revaluations-table_wrapper .dt-search input::placeholder { color: #94a3b8; }
</style>
<div class="max-w-6xl">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Revaluations</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ $rows->count() }} paper(s)</p>
    </div>

    <form method="GET" class="flex flex-wrap gap-2.5 mb-5 items-center">
        <select name="exam_id" class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            <option value="">All Exams</option>
            @foreach($exams as $exam)
            <option value="{{ $exam->id }}" @selected(request('exam_id') == $exam->id)>{{ $exam->name }}</option>
            @endforeach
        </select>

        <select name="status" class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm bg-white
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            <option value="">All Statuses</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
            <option value="processed" @selected(request('status') === 'processed')>Processed</option>
        </select>

        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Filter
        </button>
        @if(request('exam_id') || request('status'))
        <a href="{{ route('admin.revaluations.index') }}" class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">
            Clear
        </a>
        @endif
    </form>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden overflow-x-auto p-4">
        <table id="revaluations-table" class="js-datatable display nowrap w-full text-sm">
            <thead>
                <tr>
                    <th class="no-export">Actions</th>
                    <th>App ID</th>
                    <th>Reference</th>
                    <th>Bank Challan</th>
                    <th>Hall Ticket</th>
                    <th>Student</th>
                    <th>Exam</th>
                    <th>Paper Code</th>
                    <th>Paper Name</th>
                    <th>Ext</th>
                    <th>EToral</th>
                    <th>Int</th>
                    <th>IToral</th>
                    <th>Result</th>
                    <th>Grade</th>
                    <th>Script Code</th>
                    <th>Moderation</th>
                    <th>AC Marks</th>
                    <th>Reval Status</th>
                    <th>Applied On</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                @php $r = $row->revaluation; @endphp
                <tr>
                    <td>
                        @if(! $r->isFeePaid())
                        <button type="button"
                                class="js-mark-paid text-emerald-600 hover:underline text-xs font-medium"
                                data-id="{{ $r->id }}"
                                data-name="{{ $r->student?->name }}"
                                data-hall-ticket="{{ $r->hall_ticket }}"
                                data-fee="{{ $r->fee_amount }}">Mark Paid</button>
                        @else
                        <span class="text-emerald-600 text-xs">&#10003; Paid</span>
                        @endif
                    </td>
                    <td class="font-mono text-xs text-slate-400">{{ $r->id }}</td>
                    <td class="font-mono text-xs text-slate-600">REV-{{ $r->id }}</td>
                    <td class="font-mono text-xs text-slate-700">{{ $r->challan_number ?? '—' }}</td>
                    <td class="font-mono text-xs text-slate-700">{{ $r->hall_ticket }}</td>
                    <td class="font-medium text-slate-800">{{ $r->student?->name }}</td>
                    <td class="text-slate-700">{{ $r->exam?->name }}</td>
                    <td class="font-mono text-xs text-slate-500">{{ $row->subject_code }}</td>
                    <td class="text-slate-700">{{ $row->subject_name }}</td>
                    <td class="text-center">{{ $row->ext_marks ?? '—' }}</td>
                    <td class="text-center">{{ $row->etotal ?? '—' }}</td>
                    <td class="text-center">{{ $row->int_marks ?? '—' }}</td>
                    <td class="text-center">{{ $row->itotal ?? '—' }}</td>
                    <td class="text-center">{{ $row->result ?? '—' }}</td>
                    <td class="text-center font-bold">{{ $row->grade ?? '—' }}</td>
                    <td class="font-mono text-xs text-slate-500">{{ $row->scriptcode ?? '—' }}</td>
                    <td class="text-center">{{ $row->moderation_marks ?? '—' }}</td>
                    <td class="text-center">{{ $row->ac_marks ?? '—' }}</td>
                    <td><x-status-badge :status="$r->status" /></td>
                    <td class="text-slate-600" data-order="{{ $r->created_at->timestamp }}">{{ $r->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Fee Payment Modal --}}
<div id="fee-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div id="fee-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="relative bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md mx-4 p-6">
        <div class="mb-5">
            <h2 class="text-base font-semibold text-slate-800">Mark Revaluation Fee Payment</h2>
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
                <button type="button" id="fee-modal-cancel"
                        class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
@vite(['resources/js/admin-datatable.js'])
<script nonce="{{ $csp_nonce ?? '' }}">
const revalFeeRoute = '{{ rtrim(route("admin.revaluations.fee", ["id" => "__ID__"]), "/") }}';

document.getElementById('revaluations-table').addEventListener('click', function (e) {
    const btn = e.target.closest('.js-mark-paid');
    if (!btn) return;
    openFeeModal(btn.dataset.id, btn.dataset.name, btn.dataset.hallTicket, Number(btn.dataset.fee));
});

function openFeeModal(id, name, hallTicket, fee) {
    document.getElementById('fee-modal-desc').textContent =
        '#' + id + ' — ' + name + ' (' + hallTicket + ') — ₹' + fee.toLocaleString('en-IN');
    document.getElementById('fee-modal-form').action = revalFeeRoute.replace('__ID__', id);
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

// Bind close handlers here (not via inline onclick) — the CSP blocks inline
// event-handler attributes, so onclick="closeFeeModal()" never fires.
document.getElementById('fee-modal-backdrop').addEventListener('click', closeFeeModal);
document.getElementById('fee-modal-cancel').addEventListener('click', closeFeeModal);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeFeeModal(); });
</script>
@endpush
@endsection
