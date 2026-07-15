@extends('layouts.admin')
@section('title', 'Preview Results — ' . $exam->name)

@section('content')

{{-- Admin preview banner --}}
<div style="background:#DBEAFE;border:1px solid #93C5FD;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1E40AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><eye/></svg>
    <span style="font-size:13px;color:#1E40AF;font-weight:500;">Admin Preview: Results are not yet published to student</span>
</div>

{{-- Back + title --}}
<div style="margin-bottom:24px;">
    <a href="{{ route('admin.exams.show', $exam) }}" style="font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:16px;">
        ← Back to Exam
    </a>
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 class="font-display" style="font-size:24px;font-weight:600;color:var(--navy);margin:0 0 4px;">{{ $exam->name }}</h1>
            <p style="font-size:14px;color:var(--muted);margin:0;">Semester {{ $exam->semester }}</p>
        </div>
    </div>
</div>

{{-- Student info header --}}
<div class="card" style="padding:18px 22px;margin-bottom:14px;display:flex;align-items:center;flex-wrap:wrap;gap:16px;">
    <div style="flex:1;min-width:0;">
        <p style="font-size:16px;font-weight:700;color:var(--navy);margin:0 0 2px;">{{ $student->name }}</p>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <code class="font-mono-code" style="font-size:12px;background:#EEF0F3;color:var(--navy);padding:2px 8px;border-radius:6px;">{{ $student->hall_ticket }}</code>
            <span style="font-size:13px;color:var(--muted);">{{ $student->course_name ?? $student->course }}</span>
        </div>
    </div>
    @if($enrollment->gpa)
    <div style="display:flex;gap:12px;flex-shrink:0;flex-wrap:wrap;">
        <div style="text-align:center;padding:12px 18px;background:linear-gradient(135deg,#F0FDFA 0%,#fff 100%);border:1px solid rgba(13,148,136,.2);border-radius:12px;">
            <p class="font-display" style="font-size:28px;font-weight:700;color:var(--teal);margin:0;line-height:1;">{{ $enrollment->gpa->sgpa }}</p>
            <p style="font-size:10px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:4px 0 0;">SGPA</p>
        </div>
        @php
            $res = strtoupper($enrollment->gpa->result ?? '');
            $resBg  = match(true) {
                str_contains($res, 'PASS') || $res === 'PROMOTED' => 'background:rgba(13,148,136,.1);border-color:rgba(13,148,136,.25);color:var(--teal);',
                str_contains($res, 'FAIL') || str_contains($res, 'MALP') || str_contains($res, 'WITH') => 'background:#FEF2F2;border-color:#FECACA;color:#DC2626;',
                default => 'background:#EEF0F3;border-color:var(--border);color:var(--muted);'
            };
        @endphp
        @if($enrollment->gpa->result)
        <div style="text-align:center;padding:12px 18px;{{ $resBg }}border:1px solid;border-radius:12px;">
            <p class="font-display" style="font-size:18px;font-weight:700;margin:0;line-height:1;">{{ $enrollment->gpa->result }}</p>
            <p style="font-size:10px;font-weight:700;opacity:.6;letter-spacing:.5px;text-transform:uppercase;margin:4px 0 0;">Result</p>
        </div>
        @endif
    </div>
    @endif
</div>

{{-- Papers table --}}
<div class="card" style="overflow:hidden;margin-bottom:14px;">
    <div style="padding:14px 20px;border-bottom:1px solid var(--border);">
        <p style="font-size:13px;font-weight:700;color:var(--navy);margin:0;letter-spacing:.3px;text-transform:uppercase;">Paper-wise Results</p>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            <thead>
                <tr style="background:#F7F6F3;border-bottom:1px solid var(--border);">
                    <th style="padding:10px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Paper Code</th>
                    <th style="padding:10px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Subject</th>
                    <th style="padding:10px 20px;text-align:center;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Credits</th>
                    <th style="padding:10px 20px;text-align:center;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollment->results as $result)
                @php $failed = strtoupper($result->grade ?? '') === 'F' || strtoupper($result->result ?? '') === 'F'; @endphp
                <tr style="border-bottom:1px solid var(--border);{{ $failed ? 'background:#FEF2F2;' : '' }}">
                    <td style="padding:13px 20px;font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted);">{{ $result->subject?->code }}</td>
                    <td style="padding:13px 20px;font-weight:600;color:var(--navy);">{{ $result->subject?->name }}</td>
                    <td style="padding:13px 20px;text-align:center;color:var(--muted);">{{ $result->credits }}</td>
                    <td style="padding:13px 20px;text-align:center;">
                        <span style="font-size:16px;font-weight:700;{{ $failed ? 'color:#DC2626;' : 'color:var(--teal);' }}">{{ $result->grade }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- GPA Summary --}}
@if($enrollment->gpa)
<div class="card" style="overflow:hidden;margin-bottom:14px;">
    <div style="padding:14px 20px;border-bottom:1px solid var(--border);">
        <p style="font-size:13px;font-weight:700;color:var(--navy);margin:0;letter-spacing:.3px;text-transform:uppercase;">GPA Summary</p>
    </div>
    <div style="padding:20px 22px;display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:16px;">
        <div style="text-align:center;">
            <p class="font-display" style="font-size:26px;font-weight:700;color:var(--teal);margin:0;line-height:1;">{{ $enrollment->gpa->sgpa }}</p>
            <p style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:6px 0 0;">SGPA</p>
        </div>
        @if($enrollment->gpa->cgpa_part1)
        <div style="text-align:center;">
            <p class="font-display" style="font-size:26px;font-weight:700;color:var(--navy);margin:0;line-height:1;">{{ $enrollment->gpa->cgpa_part1 }}</p>
            <p style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:6px 0 0;">CGPA Part-I</p>
        </div>
        @endif
        @if($enrollment->gpa->cgpa_part2)
        <div style="text-align:center;">
            <p class="font-display" style="font-size:26px;font-weight:700;color:var(--navy);margin:0;line-height:1;">{{ $enrollment->gpa->cgpa_part2 }}</p>
            <p style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:6px 0 0;">CGPA Part-II</p>
        </div>
        @endif
        @if($enrollment->gpa->cgpa_overall)
        <div style="text-align:center;background:var(--navy);border-radius:10px;padding:14px 10px;">
            <p class="font-display" style="font-size:26px;font-weight:700;color:var(--amber);margin:0;line-height:1;">{{ $enrollment->gpa->cgpa_overall }}</p>
            <p style="font-size:11px;font-weight:700;color:rgba(255,255,255,.5);letter-spacing:.5px;text-transform:uppercase;margin:6px 0 0;">CGPA Overall</p>
        </div>
        @endif
    </div>
</div>
@endif

@endsection
