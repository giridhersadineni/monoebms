@extends('layouts.student')
@section('title', 'My Results')

@section('content')

<div class="animate-in" style="margin-bottom:24px;">
    <h1 class="font-display" style="font-size:24px;font-weight:600;color:var(--navy);margin:0 0 4px;">My Results</h1>
    <p style="font-size:14px;color:var(--muted);margin:0;">View semester results and GPA</p>
</div>

@if($enrollments->count())
<div style="display:flex;flex-direction:column;gap:12px;" class="animate-in delay-1">
    @foreach($enrollments as $i => $enrollment)
    <div class="card" style="padding:20px 22px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;animation-delay:{{ $i * .05 }}s;">
        <div style="flex:1;min-width:0;">
            <p style="font-size:15px;font-weight:700;color:var(--navy);margin:0 0 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $enrollment->exam?->name }}</p>
            <p style="font-size:12px;color:var(--muted);margin:0;">Semester {{ $enrollment->exam?->semester }}</p>
        </div>
        <div style="display:flex;align-items:center;gap:16px;flex-shrink:0;">
            @php
                $resultsEligible = $enrollment->exam?->results_visible && $enrollment->exam?->isResultsEligibleStatus();
            @endphp
            @if($resultsEligible)
            @if($enrollment->gpa)
            @if($enrollment->gpa->result !== 'R')
            <div style="text-align:right;">
                <p class="font-display" style="font-size:22px;font-weight:700;color:var(--teal);margin:0;line-height:1;">{{ $enrollment->gpa->sgpa }}</p>
                <p style="font-size:10px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:2px 0 0;">SGPA</p>
            </div>
            @endif
            @php
                $res = strtoupper($enrollment->gpa->result ?? '');
                $resBadge = match(true) {
                    str_contains($res, 'PASS') || $res === 'PROMOTED' => 'badge-paid',
                    str_contains($res, 'FAIL') || str_contains($res, 'MALP') || str_contains($res, 'WITH') => 'badge-unpaid',
                    default => ''
                };
            @endphp
            @if($enrollment->gpa->result)
            <span class="badge {{ $resBadge }}" style="font-size:11px;">{{ $enrollment->gpa->result }}</span>
            @endif
            @endif
            @php
                $revalEligible = $enrollment->exam?->revaluation_open
                    && $enrollment->results->contains(fn ($r) => in_array($r->result, ['F', 'R'], true));
            @endphp
            @if($revalEligible && ! $enrollment->revaluation)
            <a href="{{ route('student.revaluation.show', $enrollment) }}" class="btn-sm" style="display:inline-flex;align-items:center;padding:8px 14px;border:1px solid #FCD34D;background:#FFFBEB;color:#92400E;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;white-space:nowrap;">Apply for Revaluation</a>
            @elseif($revalEligible && $enrollment->revaluation)
            <span class="badge" style="font-size:11px;">Reval: {{ ucfirst($enrollment->revaluation->status) }}</span>
            <a href="{{ route('student.revaluation.challan', $enrollment->revaluation) }}" class="btn-sm" style="display:inline-flex;align-items:center;padding:8px 14px;border:1px solid var(--border);background:#fff;color:var(--navy);border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;white-space:nowrap;">Print Challan</a>
            @endif
            <a href="{{ route('student.results.show', $enrollment->exam) }}" class="btn-primary btn-sm">View →</a>
            @else
            <span style="font-size:12px;color:var(--muted);">Not yet published</span>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card animate-in delay-1" style="padding:56px 24px;text-align:center;">
    <div style="width:56px;height:56px;background:#EEF0F3;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <svg width="24" height="24" fill="none" stroke="#8A9AB0" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </div>
    <p style="font-size:15px;font-weight:600;color:var(--navy);margin:0 0 6px;">No results available</p>
    <p style="font-size:13px;color:var(--muted);margin:0;">Results are visible after fee payment is confirmed.</p>
</div>
@endif

@endsection
