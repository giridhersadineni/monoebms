@extends('layouts.admin')
@section('title', 'Results — ' . $enrollment->hall_ticket)

@php
    $canEdit = (bool) auth('admin')->user()?->canAccess('results.edit');
    $gpa     = $enrollment->gpa;
    $graded  = $enrollment->results->where('grade', '!=', 'EX');
    // Code, Subject, ETotal, ITotal, Ext, Int, Total, Credits, GPV, GPC, Grade, Result (+ Actions)
    $columns = $canEdit ? 13 : 12;
    // A graded paper stored with 0 credits contributes nothing to the SGPA numerator.
    $zeroCredit = $graded->filter(fn ($r) => (float) $r->credits === 0.0);

    // Column totals, over graded papers only (EX papers sit outside the GPA).
    // Absent papers score 0 rather than null so the columns add up to what is shown.
    $tEtotal  = $graded->sum(fn ($r) => $r->etotal ?? 100);
    $tItotal  = $graded->sum(fn ($r) => $r->itotal ?? 20);
    $tExt     = $graded->sum(fn ($r) => $r->is_absent_ext ? 0 : (int) $r->ext_marks);
    $tInt     = $graded->sum(fn ($r) => $r->is_absent_int ? 0 : (int) $r->int_marks);
    $tTotal   = $graded->sum('total_marks');
    $tCredits = (float) $graded->sum('credits');
    $tGpv     = (float) $graded->sum('gp_value');
    $tGpc     = (float) $graded->sum('gp_credits');
    $tMax     = $tEtotal + $tItotal;
@endphp

@section('content')
<div class="w-full">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div class="min-w-0">
            @if($enrollment->student)
                <a href="{{ route('admin.students.show', $enrollment->student->hall_ticket) }}"
                   class="text-xs text-gray-500 hover:text-gray-700 hover:underline">&larr; Back to student</a>
            @endif
            <h1 class="text-xl font-bold text-gray-800 mt-1">{{ $enrollment->student?->name }}</h1>
            <p class="text-sm text-gray-500">
                <span class="font-mono">{{ $enrollment->hall_ticket }}</span>
                <span class="text-gray-300 px-1">|</span>
                {{ $enrollment->exam?->name }}
            </p>
        </div>

        @if($canEdit)
        <form method="POST" action="{{ route('admin.results.recalculate-gpa', $enrollment->id) }}">
            @csrf
            <button class="bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-600">
                Recalculate GPA
            </button>
        </form>
        @endif
    </div>

    {{-- Summary --}}
    @if($gpa)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-2">
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-blue-700">{{ $gpa->sgpa }}</p>
            <p class="text-xs text-gray-400">SGPA</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-700">{{ $gpa->cgpa }}</p>
            <p class="text-xs text-gray-400">CGPA</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-700">{{ $gpa->total_marks }}</p>
            <p class="text-xs text-gray-400">Total Marks</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-2xl font-bold text-gray-700">{{ rtrim(rtrim(number_format($graded->sum('credits'), 1), '0'), '.') }}</p>
            <p class="text-xs text-gray-400">Credits</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
            <x-status-badge :status="$gpa->result" />
            <p class="text-xs text-gray-400 mt-1">Result</p>
        </div>
    </div>
    <p class="text-xs text-gray-400 mb-6">
        {{ $gpa->processed_at ? 'Last processed ' . $gpa->processed_at->diffForHumans() : 'Never processed.' }}
    </p>
    @else
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
        <p class="text-sm font-medium text-amber-900">GPA not processed yet</p>
        <p class="text-xs text-amber-800 mt-1">
            Paper grades are shown below, but no SGPA has been calculated for this enrollment.
            @if($canEdit) Use <strong>Recalculate GPA</strong> above to process it. @endif
        </p>
    </div>
    @endif

    {{-- Paper-wise results --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-3 py-3 text-left">Code</th>
                    <th class="px-3 py-3 text-left">Subject</th>
                    <th class="px-2 py-3 text-center" title="Maximum external marks (ETOTAL)">ETotal</th>
                    <th class="px-2 py-3 text-center" title="Maximum internal marks (ITOTAL)">ITotal</th>
                    <th class="px-2 py-3 text-center">Ext</th>
                    <th class="px-2 py-3 text-center">Int</th>
                    <th class="px-2 py-3 text-center">Total</th>
                    <th class="px-2 py-3 text-center">Credits</th>
                    <th class="px-2 py-3 text-center" title="Grade point value">GPV</th>
                    <th class="px-2 py-3 text-center" title="Grade points &times; credits">GPC</th>
                    <th class="px-2 py-3 text-center">Grade</th>
                    <th class="px-3 py-3 text-center">Result</th>
                    @if($canEdit)
                    <th class="px-3 py-3 text-center">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($enrollment->results as $result)
                @php
                    $rowTint = match($result->result) {
                        'F'  => 'bg-red-50',
                        'AB' => 'bg-orange-50',
                        'MP' => 'bg-red-100',
                        default => '',
                    };
                    $grace = (int) ($result->floatation_marks ?? 0)
                           + (int) ($result->ac_marks ?? 0)
                           - (int) ($result->float_deduct ?? 0);
                @endphp
                <tr id="view-{{ $result->id }}" class="{{ $rowTint }}">
                    <td class="px-3 py-2 font-mono text-xs whitespace-nowrap">{{ $result->subject?->code }}</td>
                    <td class="px-3 py-2">
                        {{ $result->subject?->name }}
                        @if($result->grade === 'EX')
                            <span class="ml-1 text-xs text-gray-400">(excluded)</span>
                        @endif
                    </td>
                    <td class="px-2 py-2 text-center text-gray-500">{{ $result->etotal ?? 100 }}</td>
                    <td class="px-2 py-2 text-center text-gray-500">{{ $result->itotal ?? 20 }}</td>
                    <td class="px-2 py-2 text-center whitespace-nowrap">
                        {{ $result->is_absent_ext ? 'AB' : $result->ext_marks }}
                        @if($grace !== 0)
                            <span class="ml-1 text-xs font-medium text-emerald-700"
                                  title="Floatation/AC marks applied to the external score">
                                {{ $grace > 0 ? '+' . $grace : $grace }}
                            </span>
                        @endif
                    </td>
                    <td class="px-2 py-2 text-center">{{ $result->is_absent_int ? 'AB' : $result->int_marks }}</td>
                    <td class="px-2 py-2 text-center font-semibold">{{ $result->total_marks }}</td>
                    @php $noCredits = (float) $result->credits === 0.0 && $result->grade !== 'EX'; @endphp
                    <td class="px-2 py-2 text-center {{ $noCredits ? 'bg-amber-100 text-amber-900 font-semibold' : '' }}"
                        @if($noCredits) title="This paper has 0 credits, so it adds nothing to the SGPA numerator while still being listed. Check the subject's credit setup." @endif>
                        {{ $result->credits }}
                    </td>
                    <td class="px-2 py-2 text-center">{{ $result->gp_value }}</td>
                    <td class="px-2 py-2 text-center">{{ $result->gp_credits }}</td>
                    <td class="px-2 py-2 text-center font-bold">{{ $result->grade }}</td>
                    <td class="px-3 py-2 text-center"><x-status-badge :status="$result->result" /></td>
                    @if($canEdit)
                    <td class="px-3 py-2">
                        <div class="flex items-center justify-center gap-3">
                            <button type="button" data-edit="{{ $result->id }}"
                                    class="text-blue-600 text-xs underline bg-transparent border-0 p-0 cursor-pointer">Edit</button>
                            <form method="POST"
                                  action="{{ route('admin.results.recalculate-result', [$enrollment->id, $result->id]) }}">
                                @csrf
                                <button class="text-gray-600 text-xs underline bg-transparent border-0 p-0 cursor-pointer"
                                        title="Re-derive this paper's grade from its stored marks">Recalculate</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @if($canEdit)
                <tr id="edit-{{ $result->id }}" class="hidden bg-blue-50">
                    <td colspan="{{ $columns }}" class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.results.update-result', [$enrollment->id, $result->id]) }}"
                              class="flex flex-wrap items-center gap-3">
                            @csrf
                            <span class="font-mono text-xs text-gray-600">{{ $result->subject?->code }}</span>
                            <label class="text-xs text-gray-600">Ext
                                <input name="ext" value="{{ $result->is_absent_ext ? 'AB' : $result->ext_marks }}"
                                       autocomplete="off"
                                       class="w-20 border border-gray-300 rounded px-2 py-1 text-sm ml-1">
                            </label>
                            <label class="text-xs text-gray-600">Int
                                <input name="int" value="{{ $result->is_absent_int ? 'AB' : $result->int_marks }}"
                                       autocomplete="off"
                                       class="w-20 border border-gray-300 rounded px-2 py-1 text-sm ml-1">
                            </label>
                            <label class="text-xs text-gray-600">Credits
                                <input name="credits" type="number" step="0.5" min="0" max="20"
                                       value="{{ $result->credits }}" autocomplete="off"
                                       class="w-20 border {{ $noCredits ? 'border-amber-400 bg-amber-50' : 'border-gray-300' }} rounded px-2 py-1 text-sm ml-1">
                            </label>
                            <button class="bg-blue-700 text-white text-xs font-semibold rounded px-4 py-1.5 hover:bg-blue-600">Save</button>
                            <button type="button" data-cancel="{{ $result->id }}"
                                    class="text-gray-500 text-xs bg-transparent border-0 cursor-pointer">Cancel</button>
                            <span class="text-xs text-gray-400">Marks or "AB" for absent · Ext &le; 100, Int &le; 20 · credits drive SGPA</span>
                        </form>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="{{ $columns }}" class="px-4 py-8 text-center text-sm text-gray-400">
                        No papers recorded for this enrollment.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($graded->isNotEmpty())
            <tfoot class="bg-gray-50 border-t-2 border-gray-300 text-gray-700 font-semibold">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-right text-xs uppercase tracking-wide text-gray-500">
                        Totals &middot; {{ $graded->count() }} {{ Str::plural('paper', $graded->count()) }} (excl. EX)
                    </td>
                    <td class="px-2 py-2 text-center">{{ $tEtotal }}</td>
                    <td class="px-2 py-2 text-center">{{ $tItotal }}</td>
                    <td class="px-2 py-2 text-center">{{ $tExt }}</td>
                    <td class="px-2 py-2 text-center">{{ $tInt }}</td>
                    <td class="px-2 py-2 text-center">{{ $tTotal }}</td>
                    <td class="px-2 py-2 text-center {{ $zeroCredit->isNotEmpty() ? 'bg-amber-100 text-amber-900' : '' }}">
                        {{ rtrim(rtrim(number_format($tCredits, 1, '.', ''), '0'), '.') ?: '0' }}
                    </td>
                    <td class="px-2 py-2 text-center">{{ number_format($tGpv, 2, '.', '') }}</td>
                    <td class="px-2 py-2 text-center">{{ number_format($tGpc, 2, '.', '') }}</td>
                    <td colspan="{{ $columns - 10 }}" class="px-3 py-2"></td>
                </tr>
                <tr class="border-t border-gray-200 font-normal">
                    <td colspan="{{ $columns }}" class="px-3 py-2 text-xs text-gray-500">
                        @if($tCredits > 0)
                            <span class="mr-4">
                                <strong>SGPA</strong> = {{ number_format($tGpc, 2, '.', '') }} &divide;
                                {{ rtrim(rtrim(number_format($tCredits, 1, '.', ''), '0'), '.') }}
                                = <strong>{{ number_format($tGpc / $tCredits, 2, '.', '') }}</strong>
                            </span>
                        @else
                            <span class="mr-4 text-amber-800">
                                <strong>SGPA</strong> cannot be derived &mdash; total credits are 0.
                            </span>
                        @endif
                        @if($tMax > 0)
                            <span><strong>Marks</strong> = {{ $tTotal }} / {{ $tMax }}
                                ({{ number_format($tTotal / $tMax * 100, 2, '.', '') }}%)</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    @if($zeroCredit->isNotEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mt-4">
        <p class="text-sm font-medium text-amber-900">
            {{ $zeroCredit->count() }} {{ Str::plural('paper', $zeroCredit->count()) }} stored with 0 credits
        </p>
        <p class="text-xs text-amber-800 mt-1">
            {{ $zeroCredit->map(fn ($r) => $r->subject?->code)->filter()->join(', ') }} —
            these count toward neither side of the SGPA, so the SGPA above is calculated over
            {{ $graded->sum('credits') }} credits rather than the full paper set.
            @if($canEdit)
                Use <strong>Edit</strong> on the row to set the correct credits, then
                <strong>Recalculate GPA</strong>. Note that <strong>Recalculate</strong> alone will not
                fix it — credits are read as stored, never invented.
            @endif
        </p>
    </div>
    @endif

    @if($canEdit)
    <p class="text-xs text-gray-400 mt-3">
        <strong>Edit</strong> changes the marks and regrades the paper.
        <strong>Recalculate</strong> re-derives the grade from the stored marks without changing them —
        use it after floatation, moderation or credit changes. Then <strong>Recalculate GPA</strong> to refresh SGPA.
    </p>
    <script nonce="{{ $csp_nonce ?? '' }}">
    (function () {
        function toggle(id, showEdit) {
            document.getElementById('view-' + id).classList.toggle('hidden', showEdit);
            document.getElementById('edit-' + id).classList.toggle('hidden', !showEdit);
        }
        document.querySelectorAll('[data-edit]').forEach(function (b) {
            b.addEventListener('click', function () { toggle(b.getAttribute('data-edit'), true); });
        });
        document.querySelectorAll('[data-cancel]').forEach(function (b) {
            b.addEventListener('click', function () { toggle(b.getAttribute('data-cancel'), false); });
        });
    })();
    </script>
    @endif
</div>
@endsection
