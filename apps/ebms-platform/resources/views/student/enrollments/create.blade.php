@extends('layouts.student')
@section('title', 'Register for Exam')

@section('content')

<div class="animate-in" style="margin-bottom:24px;">
    <h1 class="font-display" style="font-size:24px;font-weight:600;color:var(--navy);margin:0 0 4px;">Select Exam</h1>
    <p style="font-size:14px;color:var(--muted);margin:0;">Choose an open exam for your course</p>
</div>

@if($exams->count())
<div style="display:flex;flex-direction:column;gap:12px;" class="animate-in delay-1">
    @foreach($exams as $i => $exam)
    <a href="{{ route('student.enrollments.subjects', ['exam_id' => $exam->id]) }}"
       class="card" style="padding:20px 22px;text-decoration:none;display:flex;align-items:center;justify-content:space-between;gap:16px;transition:transform .15s,box-shadow .15s;animation-delay:{{ $i * .05 }}s;"
       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(22,43,62,.1)'"
       onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 4px rgba(22,43,62,.06)'">
        <div>
            <p style="font-size:16px;font-weight:700;color:var(--navy);margin:0 0 4px;">{{ $exam->name }}</p>
            <p style="font-size:13px;color:var(--muted);margin:0;">Semester {{ $exam->semester }}@if($exam->month ?? $exam->year) · {{ $exam->month ?? '' }} {{ $exam->year ?? '' }}@endif</p>
        </div>
        @php
            $rf = $resolvedFees[$exam->id] ?? [];
            $rfRegular = ($rf['fee_regular'] ?? 0) + ($rf['fee_fine'] ?? 0);
            $rfSupply  = ($rf['fee_supply_upto2'] ?? 0) + ($rf['fee_fine'] ?? 0);
            $rfImprove = $rf['fee_improvement'] ?? 0;
        @endphp
        <div style="text-align:right;flex-shrink:0;">
            @if($exam->exam_type === 'improvement' && $rfImprove)
                <p style="font-size:16px;font-weight:700;color:var(--amber);margin:0 0 4px;">₹{{ number_format($rfImprove) }}<span style="font-size:11px;font-weight:500;color:var(--muted);">/paper</span></p>
            @elseif($exam->exam_type === 'supplementary' && $rfSupply)
                <p style="font-size:16px;font-weight:700;color:var(--amber);margin:0 0 4px;">₹{{ number_format($rfSupply) }}<span style="font-size:11px;font-weight:500;color:var(--muted);">–{{ number_format($rfRegular) }}</span></p>
            @elseif($rfRegular)
                <p style="font-size:16px;font-weight:700;color:var(--amber);margin:0 0 4px;">₹{{ number_format($rfRegular) }}</p>
            @endif
            <span class="badge badge-open">Open</span>
        </div>
    </a>
    @endforeach
</div>
@else
<div class="card animate-in delay-1" style="padding:56px 24px;text-align:center;">
    <div style="width:56px;height:56px;background:#EEF0F3;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <svg width="24" height="24" fill="none" stroke="#8A9AB0" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </div>
    <p style="font-size:15px;font-weight:600;color:var(--navy);margin:0 0 6px;">No open exams</p>
    <p style="font-size:13px;color:var(--muted);margin:0;">No exams are currently open for your course. Check back later.</p>
</div>
@endif

@endsection
