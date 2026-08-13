@extends('layouts.admin')
@section('title', 'Mark Revaluation Fee Payment')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Mark Revaluation Fee Payment</h1>
        <p class="text-sm text-slate-500 mt-0.5">Look up a revaluation application by ID or hall ticket and record challan receipt.</p>
    </div>

    {{-- Search form --}}
    <form method="GET" action="{{ route('admin.revaluations.mark-payment') }}"
          class="flex gap-2.5 mb-6">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Application ID or Hall Ticket"
               autofocus
               class="border border-slate-300 rounded-lg px-3.5 py-2 text-sm w-72 bg-white font-mono
                      placeholder:font-sans placeholder:text-slate-400
                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
        <button type="submit"
                class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Look Up
        </button>
    </form>

    @if($revaluation)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">

        {{-- Student & exam details --}}
        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Student</p>
                <p class="font-semibold text-slate-800">{{ $revaluation->student?->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Hall Ticket</p>
                <code class="font-mono text-sm text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $revaluation->hall_ticket }}</code>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Course</p>
                <p class="text-slate-700">{{ $revaluation->student?->course }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Exam</p>
                <p class="text-slate-700">{{ $revaluation->exam?->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Reference</p>
                <p class="font-mono text-slate-700">REV-{{ $revaluation->id }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Fee Amount</p>
                <p class="font-bold text-slate-800 text-base">&#8377;{{ number_format($revaluation->fee_amount) }}</p>
            </div>
        </div>

        {{-- Revaluation subjects --}}
        @if($revaluation->subjects->isNotEmpty())
        <div class="border border-slate-100 rounded-lg overflow-hidden mb-5">
            <div class="bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-100">
                Papers ({{ $revaluation->subjects->count() }})
            </div>
            <table class="w-full text-sm">
                <tbody>
                    @foreach($revaluation->subjects as $rs)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="px-4 py-2 font-mono text-xs text-slate-500 w-24">{{ $rs->subject_code }}</td>
                        <td class="px-4 py-2 text-slate-700">{{ $rs->subject?->name ?? $rs->subject_code }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($revaluation->isFeePaid())
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3 text-sm text-emerald-800">
            <span class="font-semibold">Fee paid</span> on {{ $revaluation->fee_paid_at?->format('d M Y') }}.
            Challan: <strong>{{ $revaluation->challan_number }}</strong>.
            Received by: {{ $revaluation->challan_received_by }}.
        </div>
        @else
        <form method="POST" action="{{ route('admin.revaluations.fee', $revaluation->id) }}"
              class="border-t border-slate-100 pt-5">
            @csrf
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Challan Details</h2>
            <div class="grid grid-cols-3 gap-4 mb-5">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">
                        Challan Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="challan_number" required autocomplete="off"
                           value="{{ old('challan_number', $revaluation->id) }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="challan_number" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">
                        Submitted On <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="challan_submitted_on" required
                           value="{{ old('challan_submitted_on', now()->toDateString()) }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="challan_submitted_on" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">
                        Received By <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="challan_received_by" required autocomplete="off"
                           value="{{ old('challan_received_by', auth('admin')->user()?->name) }}"
                           class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="challan_received_by" />
                </div>
            </div>
            <button type="submit"
                    class="bg-emerald-700 hover:bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors">
                Mark as Fee Paid
            </button>
        </form>
        @endif
    </div>

    @elseif(request()->has('q'))
    <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 text-sm text-amber-800">
        No revaluation application found for <strong>{{ request('q') }}</strong>. Try the application ID (number) or the student's hall ticket.
    </div>
    @endif
</div>
@endsection
