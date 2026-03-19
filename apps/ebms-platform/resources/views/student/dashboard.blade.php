@extends('layouts.student')
@section('title', 'Dashboard')

@section('content')

{{-- Greeting --}}
<div class="animate-in" style="margin-bottom:24px;">
    <h1 class="font-display" style="font-size:26px;font-weight:600;color:var(--navy);margin:0 0 4px;">
        Hello, {{ explode(' ', $student->name)[0] }} 👋
    </h1>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <span style="font-size:14px;color:var(--muted);">{{ $student->course_name ?? $student->course }}</span>
        <span style="color:var(--border);">·</span>
        <code class="font-mono-code" style="font-size:12px;background:#EEF0F3;color:var(--navy);padding:2px 8px;border-radius:6px;">{{ $student->hall_ticket }}</code>
    </div>
</div>

{{-- Stats --}}
<div class="animate-in delay-1" style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:20px;">
    <div class="card" style="padding:20px;">
        <p style="font-size:32px;font-weight:700;color:var(--navy);font-family:'Fraunces',serif;line-height:1;margin:0 0 4px;">{{ $enrollments->count() }}</p>
        <p style="font-size:13px;color:var(--muted);margin:0;font-weight:600;">Enrollments</p>
    </div>
    <div class="card" style="padding:20px;border-color:rgba(13,148,136,.3);background:linear-gradient(135deg,#F0FDFA 0%,#fff 100%);">
        <p style="font-size:32px;font-weight:700;color:var(--teal);font-family:'Fraunces',serif;line-height:1;margin:0 0 4px;">{{ $paidEnrollments }}</p>
        <p style="font-size:13px;color:var(--muted);margin:0;font-weight:600;">Fee Paid</p>
    </div>
</div>

{{-- Quick Actions --}}
<div class="animate-in delay-2" style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:24px;">
    <a href="{{ route('student.enrollments.create') }}" class="card" style="padding:20px;text-decoration:none;display:block;background:var(--navy);border-color:var(--navy);transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(22,43,62,.25)'" onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 4px rgba(22,43,62,.06)'">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <p style="font-size:14px;font-weight:700;color:#fff;margin:0;">Register for Exam</p>
            <span style="color:var(--amber);font-size:18px;">→</span>
        </div>
        <p style="font-size:13px;color:rgba(255,255,255,.55);margin:0;">Select an open exam</p>
    </a>
    <a href="{{ route('student.results.index') }}" class="card" style="padding:20px;text-decoration:none;display:block;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(22,43,62,.1)'" onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 4px rgba(22,43,62,.06)'">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <p style="font-size:14px;font-weight:700;color:var(--navy);margin:0;">View Results</p>
            <span style="color:var(--muted);font-size:18px;">→</span>
        </div>
        <p style="font-size:13px;color:var(--muted);margin:0;">Check your scores</p>
    </a>
    <a href="{{ route('student.profile') }}" class="card" style="padding:20px;text-decoration:none;display:block;transition:transform .15s,box-shadow .15s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(22,43,62,.1)'" onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 4px rgba(22,43,62,.06)'">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <p style="font-size:14px;font-weight:700;color:var(--navy);margin:0;">My Profile</p>
            <span style="color:var(--muted);font-size:18px;">→</span>
        </div>
        <p style="font-size:13px;color:var(--muted);margin:0;">Photo &amp; signature</p>
    </a>
</div>

{{-- Recent Enrollments --}}
<div class="animate-in delay-3">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h2 style="font-size:16px;font-weight:700;color:var(--navy);margin:0;">Recent Enrollments</h2>
        @if($enrollments->count())
        <a href="{{ route('student.enrollments.index') }}" style="font-size:13px;color:var(--amber);font-weight:700;text-decoration:none;">View all →</a>
        @endif
    </div>

    @if($enrollments->count())
    <div style="display:flex;flex-direction:column;gap:10px;">
        @foreach($enrollments->take(5) as $i => $enrollment)
        <div class="card animate-in" style="padding:16px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;animation-delay:{{ ($i * .05) + .2 }}s;">
            <div style="flex:1;min-width:0;">
                <p style="font-size:15px;font-weight:700;color:var(--navy);margin:0 0 2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $enrollment->exam?->name }}</p>
                <p style="font-size:12px;color:var(--muted);margin:0;">{{ $enrollment->enrolled_at?->format('d M Y') }}</p>
            </div>
            <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
                @php $paid = $enrollment->isFeePaid(); @endphp
                <span class="badge {{ $paid ? 'badge-paid' : 'badge-unpaid' }}">{{ $paid ? 'Paid' : 'Unpaid' }}</span>
                @if(! $paid)
                    <a href="{{ route('student.challan.show', $enrollment) }}" style="font-size:12px;font-weight:700;color:var(--amber);text-decoration:none;">Challan →</a>
                @else
                    <a href="{{ route('student.results.show', $enrollment->exam) }}" style="font-size:12px;font-weight:700;color:var(--teal);text-decoration:none;">Result →</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card" style="padding:48px 24px;text-align:center;">
        <div style="width:56px;height:56px;background:#EEF0F3;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="24" height="24" fill="none" stroke="#8A9AB0" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p style="font-size:15px;font-weight:600;color:var(--navy);margin:0 0 6px;">No enrollments yet</p>
        <p style="font-size:13px;color:var(--muted);margin:0 0 16px;">Register for an open exam to get started.</p>
        <a href="{{ route('student.enrollments.create') }}" class="btn-primary btn-sm">Register for Exam</a>
    </div>
    @endif
</div>

@endsection
