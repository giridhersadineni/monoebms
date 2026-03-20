@extends('layouts.student')
@section('title', 'Dashboard')

@section('content')

{{-- ── Student Profile ─────────────────────────────────────────── --}}
<div class="animate-in" style="margin-bottom:28px;">

    {{-- Top: avatar + name + quick chips --}}
    <div style="display:flex;align-items:flex-start;gap:16px;margin-bottom:20px;">

        {{-- Avatar --}}
        <div style="flex-shrink:0;position:relative;">
            @if($student->photo_url)
                <img src="{{ $student->photo_url }}" alt="Photo"
                     style="width:64px;height:64px;object-fit:cover;border-radius:50%;
                            border:3px solid #fff;
                            box-shadow:0 0 0 2px var(--amber), 0 4px 12px rgba(22,43,62,.15);">
            @else
                <div style="width:64px;height:64px;border-radius:50%;
                            background:linear-gradient(135deg,#EAE5DC 0%,#D6CFC2 100%);
                            border:3px solid #fff;
                            box-shadow:0 0 0 2px #D9D4CA, 0 4px 12px rgba(22,43,62,.08);
                            display:flex;align-items:center;justify-content:center;">
                    <svg width="26" height="26" fill="none" stroke="#A89880" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            @endif
            {{-- Signature thumbnail --}}
            @if($student->signature_url)
                <div style="position:absolute;bottom:-2px;right:-4px;
                            background:#fff;border:1px solid #E8E4DC;border-radius:5px;
                            padding:2px 4px;box-shadow:0 1px 4px rgba(22,43,62,.1);">
                    <img src="{{ $student->signature_url }}" alt="Sig"
                         style="height:14px;max-width:40px;object-fit:contain;display:block;filter:contrast(1.1);">
                </div>
            @endif
        </div>

        {{-- Name + meta --}}
        <div style="flex:1;min-width:0;padding-top:4px;">
            <h1 class="font-display" style="font-size:22px;font-weight:600;color:var(--navy);margin:0 0 4px;line-height:1.15;letter-spacing:-.3px;">
                {{ $student->name }}
            </h1>
            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:6px;">
                <code class="font-mono-code" style="font-size:11px;color:var(--amber);background:rgba(212,145,46,.1);padding:2px 7px;border-radius:5px;font-weight:500;border:1px solid rgba(212,145,46,.2);">{{ $student->hall_ticket }}</code>
                <span style="font-size:12px;font-weight:600;color:var(--muted);">{{ $student->course }}{{ $student->group_code ? ' / ' . $student->group_code : '' }}</span>
                @if($student->semester)
                    <span style="font-size:11px;font-weight:600;color:#fff;background:var(--navy);padding:1px 8px;border-radius:20px;">Sem {{ $student->semester }}</span>
                @endif
            </div>
        </div>

        <a href="{{ route('student.profile') }}"
           style="flex-shrink:0;font-size:12px;font-weight:700;color:var(--muted);text-decoration:none;
                  display:flex;align-items:center;gap:4px;padding:6px 10px;border-radius:8px;
                  border:1px solid var(--border);background:#fff;transition:all .15s;margin-top:4px;"
           onmouseover="this.style.borderColor='var(--amber)';this.style.color='var(--amber)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            Edit
        </a>
    </div>

    {{-- Details grid --}}
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:#fff;">
        @php
            $fields = array_filter([
                'Course'         => $student->course_name ?? $student->course,
                'Father\'s Name' => $student->father_name,
                'Mother\'s Name' => $student->mother_name,
                'Scheme'         => $student->scheme,
                'Medium'         => $student->medium ? ucfirst($student->medium) : null,
                'Phone'          => $student->phone,
                'Admission Year' => $student->admission_year,
            ]);
            $addressParts = array_filter([$student->address, $student->city, $student->state, $student->pincode]);
            if ($addressParts) $fields['Address'] = implode(', ', $addressParts);
            $entries = array_values(array_map(fn($k,$v) => [$k,$v], array_keys($fields), $fields));
            $total = count($entries);
        @endphp
        @foreach($entries as $i => [$label, $value])
        @php
            $isLastRow  = $i >= $total - 2;
            $borderRight = ($i % 2 === 0) ? 'border-right:1px solid var(--border);' : '';
            $borderBottom = !$isLastRow ? 'border-bottom:1px solid var(--border);' : '';
            $isWide = $label === 'Address';
        @endphp
        <div style="padding:11px 16px;{{ $borderRight }}{{ $borderBottom }}{{ $isWide ? 'grid-column:span 2;border-right:none;' : '' }}">
            <p style="font-size:9px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;color:#B8AFA3;margin:0 0 2px;">{{ $label }}</p>
            <p style="font-size:13px;font-weight:600;color:var(--text);margin:0;line-height:1.4;">{{ $value }}</p>
        </div>
        @endforeach
        @if($total === 0)
        <div style="padding:16px;grid-column:span 2;text-align:center;">
            <p style="font-size:13px;color:var(--muted);margin:0;">No details on file. <a href="{{ route('student.profile') }}" style="color:var(--amber);">Update profile</a></p>
        </div>
        @endif
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
