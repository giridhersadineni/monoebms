@extends('layouts.student')
@section('title', 'Enrollment Confirmed')

@section('content')

<div class="animate-in" style="text-align:center;padding:32px 0 24px;">
    <div style="width:64px;height:64px;background:#ECFDF5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <svg width="32" height="32" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 class="font-display" style="font-size:24px;font-weight:700;color:var(--navy);margin:0 0 6px;">Enrollment Confirmed!</h1>
    <p style="font-size:14px;color:var(--muted);margin:0;">{{ $enrollment->exam?->name }}</p>
</div>

{{-- Summary --}}
<div class="card animate-in delay-1" style="overflow:hidden;margin-bottom:14px;">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
        <div>
            <p style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:0 0 2px;">Enrollment ID</p>
            <p style="font-size:16px;font-weight:700;color:var(--navy);margin:0;font-family:'JetBrains Mono',monospace;">#{{ $enrollment->id }}</p>
        </div>
        <div style="text-align:right;">
            <p style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:0 0 2px;">Fee Amount</p>
            <p class="font-display" style="font-size:22px;font-weight:700;color:var(--amber);margin:0;">₹{{ number_format($enrollment->fee_amount) }}</p>
        </div>
    </div>
    <div style="padding:16px 20px;">
        <p style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:0 0 10px;">Papers Enrolled</p>
        <div style="display:flex;flex-direction:column;gap:6px;">
            @foreach($enrollment->enrollmentSubjects as $es)
            <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:#F7F6F3;border-radius:8px;">
                <span style="width:6px;height:6px;background:var(--navy);border-radius:50%;flex-shrink:0;"></span>
                <div style="flex:1;">
                    <p style="font-size:13px;font-weight:600;color:var(--navy);margin:0;">{{ $es->subject?->name ?? $es->subject_code }}</p>
                    <p style="font-size:11px;color:var(--muted);margin:0;font-family:'JetBrains Mono',monospace;">{{ $es->subject_code }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Payment note --}}
<div class="animate-in delay-2" style="background:#FFFBEB;border:1px solid #FCD34D;border-left:4px solid var(--amber);border-radius:10px;padding:14px 18px;margin-bottom:24px;">
    <p style="font-size:13px;font-weight:700;color:#92400E;margin:0 0 4px;">Next Step — Pay the Fee</p>
    <p style="font-size:13px;color:#78350F;margin:0;line-height:1.6;">
        Download the challan below, pay at SBI (<strong>{{ \App\Services\ChallanPdfService::SBI_BRANCH }}</strong>)
        and submit the stamped copy to the Examination Branch.
    </p>
</div>

{{-- Actions --}}
<div class="animate-in delay-3" style="display:flex;gap:12px;flex-wrap:wrap;">
    <a href="{{ route('student.challan.show', $enrollment) }}"
       class="btn-primary" style="flex:2;justify-content:center;min-width:180px;">
        ⬇&nbsp; Download Challan
    </a>
    <a href="{{ route('student.enrollments.index') }}"
       class="btn-ghost" style="flex:1;justify-content:center;">
        My Enrollments
    </a>
</div>

@endsection
