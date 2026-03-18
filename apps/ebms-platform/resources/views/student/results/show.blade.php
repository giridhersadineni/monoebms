@extends('layouts.student')
@section('title', 'Result — ' . $exam->name)

@section('content')

{{-- Back + title --}}
<div class="animate-in" style="margin-bottom:24px;">
    <a href="{{ route('student.results.index') }}" style="font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:16px;">
        ← Back to Results
    </a>
    <h1 class="font-display" style="font-size:24px;font-weight:600;color:var(--navy);margin:0 0 4px;">{{ $exam->name }}</h1>
    <p style="font-size:14px;color:var(--muted);margin:0;">Semester {{ $exam->semester }}</p>
</div>

{{-- Student info header --}}
<div class="card animate-in delay-1" style="padding:18px 22px;margin-bottom:14px;display:flex;align-items:center;flex-wrap:wrap;gap:16px;">
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
<div class="card animate-in delay-2" style="overflow:hidden;margin-bottom:14px;">
    <div style="padding:14px 20px;border-bottom:1px solid var(--border);">
        <p style="font-size:13px;font-weight:700;color:var(--navy);margin:0;letter-spacing:.3px;text-transform:uppercase;">Paper-wise Results</p>
    </div>

    {{-- Mobile: card per row --}}
    <div style="display:none;" class="result-mobile">
        @foreach($enrollment->results as $result)
        @php $failed = strtoupper($result->grade ?? '') === 'F' || strtoupper($result->result ?? '') === 'F'; @endphp
        <div style="padding:14px 20px;border-bottom:1px solid var(--border);{{ $failed ? 'background:#FEF2F2;' : '' }}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                <div>
                    <p style="font-size:14px;font-weight:700;color:var(--navy);margin:0 0 2px;">{{ $result->subject?->name }}</p>
                    <code style="font-size:11px;color:var(--muted);font-family:'JetBrains Mono',monospace;">{{ $result->subject?->code }}</code>
                </div>
                <span style="font-size:22px;font-weight:700;{{ $failed ? 'color:#DC2626;' : 'color:var(--teal);' }}">{{ $result->grade }}</span>
            </div>
            <div style="display:flex;gap:16px;font-size:12px;color:var(--muted);">
                <span>Ext: <strong style="color:var(--navy);">{{ $result->is_absent_ext ? 'AB' : $result->ext_marks }}</strong></span>
                <span>Int: <strong style="color:var(--navy);">{{ $result->is_absent_int ? 'AB' : $result->int_marks }}</strong></span>
                <span>Total: <strong style="color:var(--navy);">{{ $result->total_marks }}</strong></span>
                <span>Credits: <strong style="color:var(--navy);">{{ $result->credits }}</strong></span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Desktop: table --}}
    <div class="result-desktop">
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            <thead>
                <tr style="background:#F7F6F3;border-bottom:1px solid var(--border);">
                    <th style="padding:10px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Paper Code</th>
                    <th style="padding:10px 20px;text-align:left;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Subject</th>
                    <th style="padding:10px 20px;text-align:center;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Ext</th>
                    <th style="padding:10px 20px;text-align:center;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Int</th>
                    <th style="padding:10px 20px;text-align:center;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;">Total</th>
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
                    <td style="padding:13px 20px;text-align:center;color:var(--muted);">{{ $result->is_absent_ext ? 'AB' : $result->ext_marks }}</td>
                    <td style="padding:13px 20px;text-align:center;color:var(--muted);">{{ $result->is_absent_int ? 'AB' : $result->int_marks }}</td>
                    <td style="padding:13px 20px;text-align:center;font-weight:600;color:var(--navy);">{{ $result->total_marks }}</td>
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
<div class="card animate-in delay-3" style="overflow:hidden;margin-bottom:14px;">
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
        @if($enrollment->gpa->sec_marks || $enrollment->gpa->total_marks_overall)
        <div style="text-align:center;">
            <p class="font-display" style="font-size:26px;font-weight:700;color:var(--navy);margin:0;line-height:1;">{{ $enrollment->gpa->sec_marks ?? $enrollment->gpa->total_marks_overall }}</p>
            <p style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:6px 0 0;">Sec / Total</p>
        </div>
        @endif
    </div>
    @if($enrollment->gpa->cgpa_overall)
    @php
        $cgpa = (float)($enrollment->gpa->cgpa_overall ?? 0);
        $division = match(true) {
            $cgpa >= 7.00 => 'First Class with Distinction',
            $cgpa >= 6.00 => 'First Class',
            $cgpa >= 5.00 => 'Second Class',
            $cgpa >= 4.00 => 'Pass Class',
            default       => null
        };
    @endphp
    @if($division)
    <div style="padding:14px 22px;border-top:1px solid var(--border);background:#F7F6F3;">
        <p style="font-size:13px;font-weight:700;color:var(--navy);margin:0;">{{ $division }}</p>
        <p style="font-size:11px;color:var(--muted);margin:2px 0 0;">Based on CGPA Overall of {{ $enrollment->gpa->cgpa_overall }}</p>
    </div>
    @endif
    @endif
</div>
@endif

{{-- Grade Scale Legend --}}
<div class="animate-in delay-4" style="background:#F7F6F3;border:1px solid var(--border);border-radius:12px;padding:16px 20px;margin-bottom:24px;">
    <p style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:0 0 10px;">Grade Scale</p>
    <div style="display:flex;flex-wrap:wrap;gap:8px;">
        @foreach([
            ['O', '≥ 85', 'var(--teal)', 'rgba(13,148,136,.1)', 'rgba(13,148,136,.25)'],
            ['A', '≥ 70', 'var(--navy)', '#EEF0F3', 'var(--border)'],
            ['B', '≥ 60', 'var(--navy)', '#EEF0F3', 'var(--border)'],
            ['C', '≥ 55', 'var(--navy)', '#EEF0F3', 'var(--border)'],
            ['D', '≥ 50', 'var(--navy)', '#EEF0F3', 'var(--border)'],
            ['E', '≥ 40', 'var(--amber)', '#FFFBEB', '#FCD34D'],
            ['F', 'FAIL', '#DC2626', '#FEF2F2', '#FECACA'],
        ] as [$grade, $range, $color, $bg, $border])
        <div style="display:flex;align-items:center;gap:6px;background:{{ $bg }};border:1px solid {{ $border }};border-radius:8px;padding:4px 10px;">
            <span style="font-size:14px;font-weight:700;color:{{ $color }};font-family:'Fraunces',serif;">{{ $grade }}</span>
            <span style="font-size:11px;color:var(--muted);">{{ $range }}</span>
        </div>
        @endforeach
    </div>
    <p style="font-size:11px;color:var(--muted);margin:10px 0 0;">
        Division: ≥7.00 First with Distinction · ≥6.00 First · ≥5.00 Second · ≥4.00 Pass
    </p>
</div>

<style>
    @media(max-width:640px) {
        .result-mobile { display:block !important; }
        .result-desktop { display:none !important; }
    }
</style>

@endsection
