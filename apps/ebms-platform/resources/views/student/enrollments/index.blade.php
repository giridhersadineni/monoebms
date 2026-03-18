@extends('layouts.student')
@section('title', 'My Enrollments')

@section('content')

<div class="animate-in" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
    <div>
        <h1 class="font-display" style="font-size:24px;font-weight:600;color:var(--navy);margin:0 0 4px;">My Enrollments</h1>
        <p style="font-size:14px;color:var(--muted);margin:0;">All your exam registrations</p>
    </div>
    <a href="{{ route('student.enrollments.create') }}" class="btn-primary btn-sm">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Register
    </a>
</div>

@if($enrollments->count())

{{-- Mobile card stack --}}
<div class="sm:hidden animate-in delay-1" style="display:flex;flex-direction:column;gap:10px;">
    @foreach($enrollments as $i => $enrollment)
    <div class="card" style="padding:16px 18px;animation-delay:{{ $i * .04 }}s;" >
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
            <div style="flex:1;min-width:0;margin-right:12px;">
                <p style="font-size:15px;font-weight:700;color:var(--navy);margin:0 0 2px;">{{ $enrollment->exam?->name }}</p>
                <p style="font-size:12px;color:var(--muted);margin:0;">Sem {{ $enrollment->exam?->semester }} · {{ $enrollment->enrolled_at?->format('d M Y') }}</p>
                <p style="font-size:11px;color:var(--muted);margin:2px 0 0;font-family:'JetBrains Mono',monospace;">Challan #{{ $enrollment->id }}</p>
            </div>
            @php $paid = $enrollment->isFeePaid(); @endphp
            <span class="badge {{ $paid ? 'badge-paid' : 'badge-unpaid' }}">{{ $paid ? 'Paid' : 'Unpaid' }}</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;padding-top:10px;border-top:1px solid var(--border);">
            <span style="font-size:14px;font-weight:700;color:var(--navy);">₹{{ number_format($enrollment->fee_amount) }}</span>
            <div style="display:flex;gap:12px;">
                <a href="{{ route('student.challan.show', $enrollment) }}" style="font-size:13px;font-weight:700;color:var(--amber);text-decoration:none;">Challan</a>
                @if($enrollment->isFeePaid())
                <a href="{{ route('student.results.show', $enrollment->exam) }}" style="font-size:13px;font-weight:700;color:var(--teal);text-decoration:none;">Results</a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Desktop table --}}
<div class="card hidden sm:block animate-in delay-1" style="overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:14px;">
        <thead>
            <tr style="background:#F7F6F3;border-bottom:1px solid var(--border);">
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Exam</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Semester</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Challan #</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Fee</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Status</th>
                <th style="padding:12px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $enrollment)
            @php $paid = $enrollment->isFeePaid(); @endphp
            <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:14px 20px;font-weight:700;color:var(--navy);">{{ $enrollment->exam?->name }}</td>
                <td style="padding:14px 20px;color:var(--muted);">{{ $enrollment->exam?->semester }}</td>
                <td style="padding:14px 20px;font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted);">{{ $enrollment->id }}</td>
                <td style="padding:14px 20px;font-weight:600;color:var(--navy);">₹{{ number_format($enrollment->fee_amount) }}</td>
                <td style="padding:14px 20px;">
                    <span class="badge {{ $paid ? 'badge-paid' : 'badge-unpaid' }}">{{ $paid ? 'Paid' : 'Unpaid' }}</span>
                </td>
                <td style="padding:14px 20px;">
                    <div style="display:flex;gap:16px;">
                        <a href="{{ route('student.challan.show', $enrollment) }}" style="font-size:13px;font-weight:700;color:var(--amber);text-decoration:none;">Challan</a>
                        @if($paid)
                        <a href="{{ route('student.results.show', $enrollment->exam) }}" style="font-size:13px;font-weight:700;color:var(--teal);text-decoration:none;">Results</a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="padding:12px 20px;border-top:1px solid var(--border);font-size:13px;color:var(--muted);">
        {{ $enrollments->links() }}
    </div>
</div>

@else
<div class="card animate-in delay-1" style="padding:56px 24px;text-align:center;">
    <div style="width:60px;height:60px;background:#EEF0F3;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <svg width="28" height="28" fill="none" stroke="#8A9AB0" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
    </div>
    <p style="font-size:16px;font-weight:700;color:var(--navy);margin:0 0 6px;">No enrollments yet</p>
    <p style="font-size:13px;color:var(--muted);margin:0 0 20px;">Register for an open exam to get started.</p>
    <a href="{{ route('student.enrollments.create') }}" class="btn-primary">Register for Exam</a>
</div>
@endif

@endsection
