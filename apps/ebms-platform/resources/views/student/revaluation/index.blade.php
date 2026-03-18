@extends('layouts.student')
@section('title', 'Revaluation')

@section('content')

<div class="animate-in" style="margin-bottom:24px;">
    <h1 class="font-display" style="font-size:24px;font-weight:600;color:var(--navy);margin:0 0 4px;">Revaluation</h1>
    <p style="font-size:14px;color:var(--muted);margin:0;">Apply for paper revaluation</p>
</div>

@if($enrollments->count())
<div style="display:flex;flex-direction:column;gap:12px;" class="animate-in delay-1">
    @foreach($enrollments as $i => $enrollment)
    <div class="card" style="padding:20px 22px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;animation-delay:{{ $i * .05 }}s;">
        <div style="flex:1;min-width:0;">
            <p style="font-size:15px;font-weight:700;color:var(--navy);margin:0 0 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $enrollment->exam?->name }}</p>
            <p style="font-size:12px;color:var(--muted);margin:0;">Semester {{ $enrollment->exam?->semester }}</p>
        </div>
        <a href="{{ route('student.revaluation.show', $enrollment) }}" class="btn-primary btn-sm">Apply →</a>
    </div>
    @endforeach
</div>
@else
<div class="card animate-in delay-1" style="padding:56px 24px;text-align:center;">
    <div style="width:56px;height:56px;background:#EEF0F3;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <svg width="24" height="24" fill="none" stroke="#8A9AB0" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
    </div>
    <p style="font-size:15px;font-weight:600;color:var(--navy);margin:0 0 6px;">No exams open for revaluation</p>
    <p style="font-size:13px;color:var(--muted);margin:0;">Check back after results are published.</p>
</div>
@endif

@endsection
