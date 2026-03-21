<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Form – {{ $enrollment->exam?->name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #000;
            background: #fff;
            padding: 20px;
        }

        /* ── Header ─────────────────────────────────── */
        .header {
            text-align: center;
            margin-bottom: 12px;
            position: relative;
        }
        .header .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
        }
        .header h1  { font-size: 18px; font-weight: bold; margin-bottom: 2px; }
        .header h2  { font-size: 13px; font-weight: normal; margin-bottom: 2px; }
        .header h3  { font-size: 14px; font-weight: bold; text-decoration: underline; margin-top: 6px; }

        /* ── Tables ──────────────────────────────────── */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }
        td, th {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th { background: #f5f5f5; font-weight: bold; }

        /* ── Course/Medium/Group summary row ─────────── */
        .summary-row td, .summary-row th {
            padding: 5px 8px;
        }

        /* ── Photo column ────────────────────────────── */
        .photo-cell {
            width: 190px;
            text-align: center;
            vertical-align: top;
            padding: 8px;
        }
        .photo-cell img {
            display: block;
            margin: 0 auto 4px;
            border: 1px solid #999;
        }
        .photo-cell p {
            font-size: 11px;
            color: #444;
            margin-bottom: 6px;
        }

        /* ── Social status ───────────────────────────── */
        .social-table td, .social-table th {
            text-align: center;
            padding: 5px 6px;
        }

        /* ── Subjects table ──────────────────────────── */
        .subjects-table th { text-align: center; }
        .subjects-table td { text-align: center; }

        /* ── Declaration ─────────────────────────────── */
        .declaration {
            border: 1px solid #000;
            padding: 10px 12px;
            margin-bottom: 10px;
            font-size: 12px;
            line-height: 1.5;
        }

        /* ── Signature line ──────────────────────────── */
        .sig-right { text-align: right; font-weight: bold; margin-bottom: 10px; }

        /* ── Acknowledgement section ─────────────────── */
        .ack-header {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
            margin: 14px 0 8px;
        }

        /* ── Print styles ────────────────────────────── */
        @media print {
            body { padding: 10px; }
            .no-print { display: none !important; }
            @page { margin: 10mm; }
        }

        /* ── Print button ────────────────────────────── */
        .print-btn {
            display: inline-block;
            margin-bottom: 16px;
            padding: 8px 20px;
            background: #1e3a5f;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }
        .print-btn:hover { background: #2a5298; }
    </style>
</head>
<body>

{{-- ── Print button (hidden on print) ──────────────────────── --}}
<div class="no-print" style="margin-bottom:14px;">
    <button class="print-btn" onclick="window.print()">🖨 Print Application</button>
    <a href="{{ route('student.enrollments.index') }}" style="margin-left:12px;font-size:13px;color:#1e3a5f;">← Back to Enrollments</a>
</div>

{{-- ── University Header ─────────────────────────────────── --}}
<div class="header">
    @php $logoPath = public_path('images/logo.png'); @endphp
    @if(file_exists($logoPath))
        <img src="{{ asset('images/logo.png') }}" alt="UASC Logo" class="logo">
    @endif
    <h1>University Arts &amp; Science College</h1>
    <h2>(An Autonomous Institute under Kakatiya University)</h2>
    <h3>APPLICATION FORM FOR EXAM REGISTRATION</h3>
</div>

{{-- ── Course / Medium / Group row ─────────────────────────── --}}
<table class="summary-row">
    <tr>
        <td>Course</td>
        <th>{{ $student->course ?? '—' }}</th>
        <td>Medium</td>
        <th>{{ $student->medium ?? '—' }}</th>
        <td>Group</td>
        <th>{{ $student->group_code ?? '—' }}</th>
    </tr>
</table>

{{-- ── Student Details + Photo ─────────────────────────────── --}}
<table>
    <tr>
        <td colspan="2" style="font-size:13px;">
            Hall Ticket No: <strong>{{ $student->hall_ticket }}</strong>
        </td>
    </tr>

    <tr>
        <td>Name of the Candidate: <strong>{{ $student->name }}</strong></td>
        <td class="photo-cell" rowspan="11">
            @if($student->photo_url)
                <p>Photograph</p>
                <img src="{{ $student->photo_url }}" width="150" height="190" alt="Photo">
            @else
                <div style="width:150px;height:190px;border:1px dashed #aaa;display:flex;align-items:center;justify-content:center;margin:0 auto 4px;">
                    <span style="font-size:11px;color:#aaa;">Photograph</span>
                </div>
            @endif
            @if($student->signature_url)
                <p style="margin-top:10px;">Signature</p>
                <img src="{{ $student->signature_url }}" width="150" height="50" alt="Signature">
            @else
                <div style="width:150px;height:50px;border:1px dashed #aaa;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <span style="font-size:11px;color:#aaa;">Signature</span>
                </div>
            @endif
        </td>
    </tr>

    <tr><td>Father Name: <strong>{{ $student->father_name ?? '—' }}</strong></td></tr>
    <tr><td>Mother Name: <strong>{{ $student->mother_name ?? '—' }}</strong></td></tr>
    <tr><td>Aadhaar Card No: <strong>{{ $student->aadhaar ?? '—' }}</strong></td></tr>
    <tr><td>Date of Birth: <strong>{{ $student->dob?->format('d-m-Y') ?? '—' }}</strong></td></tr>
    <tr><td>Gender: <strong>{{ ucfirst($student->gender ?? '—') }}</strong></td></tr>
    <tr><td>Medium: <strong>{{ $student->medium ?? '—' }}</strong></td></tr>
    <tr><td>Course: <strong>{{ $student->course_name ?? $student->course ?? '—' }}</strong></td></tr>
    <tr><td>Group: <strong>{{ $student->group_code ?? '—' }}</strong></td></tr>
    <tr><td>Semester: <strong>{{ $student->semester ? 'Semester ' . $student->semester : '—' }}</strong></td></tr>
    <tr>
        <td>Address for Correspondence:
            <strong>
                {{ implode(', ', array_filter([
                    $student->address,
                    $student->address2,
                    $student->mandal,
                    $student->city,
                    $student->state,
                    $student->pincode,
                ])) ?: '—' }}
            </strong>
        </td>
    </tr>
    <tr>
        <td>Mobile Number: <strong>{{ $student->phone ?? '—' }}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>Email Id: <strong>{{ $student->email ?? '—' }}</strong></td>
        <td></td>
    </tr>
</table>

{{-- ── Social Status ────────────────────────────────────────── --}}
@php
    $caste = strtoupper($student->caste ?? '');
    $casteTick = fn(string $label) => $caste === strtoupper($label) ? '✓' : '';
@endphp
<table class="social-table">
    <tr>
        <th>Social Status (Please Tick)</th>
        <td>SC {{ $casteTick('SC') }}</td>
        <td>ST {{ $casteTick('ST') }}</td>
        <td>BC-A {{ $casteTick('BC-A') }}</td>
        <td>BC-B {{ $casteTick('BC-B') }}</td>
        <td>BC-C {{ $casteTick('BC-C') }}</td>
        <td>BC-D {{ $casteTick('BC-D') }}</td>
        <td>OC {{ $casteTick('OC') }}</td>
        <td>Others {{ (!in_array($caste, ['SC','ST','BC-A','BC-B','BC-C','BC-D','OC','']) ? '✓' : '') }}</td>
    </tr>
</table>

{{-- ── Subjects ─────────────────────────────────────────────── --}}
@php
    $subjects = $enrollment->enrollmentSubjects->sortBy('subject.code');
@endphp
<p style="font-size:13px;font-weight:bold;margin-bottom:4px;">
    Subjects appearing for: {{ $enrollment->exam?->name }}
</p>
<table class="subjects-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Subject Code</th>
            <th>Subject Name</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        @forelse($subjects as $i => $es)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $es->subject?->code ?? '—' }}</td>
            <td style="text-align:left;">{{ $es->subject?->name ?? '—' }}</td>
            <td>{{ ucfirst($es->paper_type ?? '—') }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:#888;">No subjects recorded</td></tr>
        @endforelse
    </tbody>
</table>

{{-- ── Fee Paid ─────────────────────────────────────────────── --}}
<table>
    <tr>
        <th>Fee Paid</th>
        <td>Fee Amount: <strong>Rs. {{ number_format($enrollment->fee_amount ?? 0) }}</strong></td>
        <td>Challan No: <strong>{{ $enrollment->challan_number ?? '_______________' }}</strong></td>
        <td>Date: <strong>{{ $enrollment->challan_submitted_on?->format('d-m-Y') ?? '_______________' }}</strong></td>
    </tr>
</table>

{{-- ── Declaration ──────────────────────────────────────────── --}}
<div class="declaration">
    <strong>Declaration:</strong><br>
    I hereby declare that all the information furnished above is correct to the best of my knowledge.
    In the event of any information being found incorrect or misleading, my candidature shall be liable
    to cancellation by the University at any cost to me, and I shall not be entitled to any refund of
    the fee paid by me to the University.
</div>

<div class="sig-right">Signature of the Candidate</div>

{{-- ══════════════════════════════════════════════════════════ --}}
{{-- ── Candidate Acknowledgement (carbon copy) ────────────── --}}
{{-- ══════════════════════════════════════════════════════════ --}}
<div class="ack-header">Candidate Acknowledgement</div>

<table>
    <tr>
        <td colspan="4">Hall Ticket No: <strong>{{ $student->hall_ticket }}</strong></td>
    </tr>
    <tr>
        <td colspan="4">Name of the Candidate: <strong>{{ $student->name }}</strong></td>
    </tr>
    <tr>
        <td>Course: <strong>{{ $student->course ?? '—' }}</strong></td>
        <td>Medium: <strong>{{ $student->medium ?? '—' }}</strong></td>
        <td>Group: <strong>{{ $student->group_code ?? '—' }}</strong></td>
        <td></td>
    </tr>
    <tr>
        <th>Fee Paid</th>
        <td>Fee Amount: <strong>Rs. {{ number_format($enrollment->fee_amount ?? 0) }}</strong></td>
        <td>Challan No: <strong>{{ $enrollment->challan_number ?? '_______________' }}</strong></td>
        <td>Date: <strong>{{ $enrollment->challan_submitted_on?->format('d-m-Y') ?? '_______________' }}</strong></td>
    </tr>
</table>

<div class="sig-right" style="margin-top:30px;">Signature of the Receiver</div>

</body>
</html>
