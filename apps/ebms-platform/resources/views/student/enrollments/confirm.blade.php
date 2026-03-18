@extends('layouts.student')
@section('title', 'Confirm Enrollment')

@section('content')

<div class="animate-in" style="margin-bottom:24px;">
    <h1 class="font-display" style="font-size:24px;font-weight:600;color:var(--navy);margin:0 0 4px;">Confirm Enrollment</h1>
    <p style="font-size:14px;color:var(--muted);margin:0;">Review your selection before submitting</p>
</div>

{{-- Summary card --}}
<div class="card animate-in delay-1" style="overflow:hidden;margin-bottom:14px;">
    <div style="background:var(--navy);padding:18px 22px;">
        <p style="font-size:12px;font-weight:700;color:rgba(255,255,255,.5);letter-spacing:.5px;text-transform:uppercase;margin:0 0 4px;">Exam</p>
        <p class="font-display" style="font-size:20px;font-weight:600;color:#fff;margin:0;">{{ $exam->name }}</p>
    </div>

    <div style="padding:20px 22px;">
        <p style="font-size:12px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:0 0 12px;">Selected Papers</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
            @foreach($subjects as $subject)
            <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#F7F6F3;border-radius:8px;">
                <span style="width:6px;height:6px;background:var(--navy);border-radius:50%;flex-shrink:0;"></span>
                <div style="flex:1;">
                    <p style="font-size:14px;font-weight:600;color:var(--navy);margin:0;">{{ $subject->name }}</p>
                    <p style="font-size:11px;color:var(--muted);margin:0;font-family:'JetBrains Mono',monospace;">{{ $subject->code }}</p>
                </div>
                <span style="font-size:11px;color:var(--muted);background:#E8E6E0;padding:2px 8px;border-radius:99px;font-weight:600;text-transform:capitalize;">{{ $subject->paper_type }}</span>
            </div>
            @endforeach
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:18px;padding-top:18px;border-top:1px solid var(--border);">
            <p style="font-size:14px;color:var(--muted);margin:0;font-weight:600;">Fee Amount</p>
            <p class="font-display" style="font-size:26px;font-weight:700;color:var(--amber);margin:0;">₹{{ number_format($fee) }}</p>
        </div>
    </div>
</div>

{{-- Payment note --}}
<div class="animate-in delay-2" style="background:#FFFBEB;border:1px solid #FCD34D;border-left:4px solid var(--amber);border-radius:10px;padding:14px 18px;margin-bottom:24px;">
    <p style="font-size:13px;font-weight:700;color:#92400E;margin:0 0 4px;">Payment Instructions</p>
    <p style="font-size:13px;color:#78350F;margin:0;line-height:1.6;">
        Pay the fee via bank challan at SBI —
        <strong>A/C: {{ \App\Services\ChallanPdfService::SBI_ACCOUNT }}</strong>,
        {{ \App\Services\ChallanPdfService::SBI_BRANCH }}.
        Submit the challan to the Examination Branch.
    </p>
</div>

<form method="POST" action="{{ route('student.enrollments.store') }}" class="animate-in delay-3">
    @csrf
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="{{ route('student.enrollments.create') }}" class="btn-ghost" style="flex:1;justify-content:center;">← Back</a>
        <button type="submit" class="btn-primary" style="flex:2;justify-content:center;min-width:180px;">
            Confirm Enrollment
        </button>
    </div>
</form>

@endsection
