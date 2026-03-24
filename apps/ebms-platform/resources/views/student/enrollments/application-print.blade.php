<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Form – {{ $enrollment->exam?->name }}</title>
    <style>
        /* ─────────────────────────────────────────────────
           PAGE SETUP — A4 portrait, tight margins
        ───────────────────────────────────────────────── */
        @page {
            size: A4 portrait;
            margin: 7mm 8mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { font-size: 9px; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #111;
            background: #fff;
            padding: 10px;
            line-height: 1.25;
        }

        /* ─────────────────────────────────────────────────
           PRINT — strip screen chrome, enforce single page
        ───────────────────────────────────────────────── */
        @media print {
            html { font-size: 8.8px; }
            body { padding: 0; }
            .no-print { display: none !important; }
            .print-page { page-break-inside: avoid; }
        }

        /* ─────────────────────────────────────────────────
           SCREEN TOOLBAR
        ───────────────────────────────────────────────── */
        .no-print {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            background: #f0f3f8;
            border-radius: 6px;
            margin-bottom: 12px;
        }
        .print-btn {
            padding: 5px 14px;
            background: #1a2f55;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.3px;
        }
        .print-btn:hover { background: #253f70; }
        .back-link { font-size: 12px; color: #1a2f55; text-decoration: none; }
        .toolbar-hint {
            margin-left: auto;
            font-size: 10.5px;
            color: #888;
            font-style: italic;
        }

        /* ─────────────────────────────────────────────────
           HEADER
        ───────────────────────────────────────────────── */
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding-bottom: 3px;
            margin-bottom: 3px;
            border-bottom: 1.5px solid #1a2f55;
        }
        .header img.logo { width: 32px; height: 32px; object-fit: contain; flex-shrink: 0; }
        .header-text { text-align: center; }
        .header-text h1 {
            font-size: 13.5px;
            font-weight: 700;
            color: #1a2f55;
            letter-spacing: 0.2px;
        }
        .header-text h2 {
            font-size: 7.5px;
            font-weight: 400;
            color: #555;
        }

        .form-title {
            text-align: center;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #1a2f55;
            background: #e9edf5;
            padding: 2.5px 0;
            border: 0.5px solid #c0c9da;
            margin-bottom: 4px;
        }

        /* ─────────────────────────────────────────────────
           TABLES — the core layout unit
        ───────────────────────────────────────────────── */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 3px;
        }
        td, th {
            border: 0.5px solid #444;
            padding: 2px 4px;
            font-size: 9px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background: #e9edf5;
            font-weight: 600;
            color: #1a2f55;
        }
        .lbl {
            font-size: 7.8px;
            color: #555;
            font-weight: 400;
        }
        strong { font-weight: 600; }

        /* ─────────────────────────────────────────────────
           PHOTO / SIGNATURE CELL
        ───────────────────────────────────────────────── */
        .photo-cell {
            width: 115px;
            text-align: center;
            vertical-align: top;
            padding: 4px 4px 2px;
            border: 0.5px solid #444;
        }
        .photo-cell img {
            display: block;
            margin: 0 auto 2px;
            border: 0.5px solid #999;
            object-fit: cover;
        }
        .photo-cell .plabel {
            font-size: 7px;
            color: #555;
            display: block;
            margin-bottom: 1px;
        }
        .ph-box {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0.5px dashed #aaa;
            margin: 0 auto 2px;
            color: #aaa;
            font-size: 7.5px;
        }

        /* ─────────────────────────────────────────────────
           TICK BOX (social status)
        ───────────────────────────────────────────────── */
        .tick-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 9px;
            height: 9px;
            border: 0.5px solid #444;
            margin-left: 2px;
            font-size: 7.5px;
            vertical-align: middle;
        }

        /* ─────────────────────────────────────────────────
           SUBJECTS — horizontal compact grid
        ───────────────────────────────────────────────── */
        .subj-header {
            background: #e9edf5;
            font-size: 8px;
            font-weight: 600;
            color: #1a2f55;
            padding: 2px 4px;
            border: 0.5px solid #444;
            margin-bottom: 0;
        }
        .subj-table th {
            font-size: 8px;
            text-align: center;
            padding: 2px 2px;
            background: #f4f5f8;
            word-break: break-all;
        }
        .subj-table td {
            font-size: 7.5px;
            text-align: center;
            padding: 2px 2px;
            vertical-align: top;
        }
        .subj-table td.name-cell {
            word-break: break-word;
            hyphens: auto;
            line-height: 1.15;
        }
        .subj-type {
            font-size: 7px;
            color: #666;
        }

        /* ─────────────────────────────────────────────────
           DECLARATION
        ───────────────────────────────────────────────── */
        .declaration {
            border: 0.5px solid #444;
            padding: 3px 6px;
            font-size: 8px;
            line-height: 1.45;
            margin-bottom: 3px;
        }

        /* ─────────────────────────────────────────────────
           SIGNATURE ROW
        ───────────────────────────────────────────────── */
        .sig-row {
            display: flex;
            justify-content: flex-end;
            font-size: 8.5px;
            font-weight: 600;
            margin-bottom: 4px;
            padding-right: 2px;
        }

        /* ─────────────────────────────────────────────────
           DASHED SEPARATOR (tear-off line)
        ───────────────────────────────────────────────── */
        .cut-line {
            position: relative;
            text-align: center;
            margin: 5px 0 4px;
        }
        .cut-line::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0; right: 0;
            border-top: 1px dashed #888;
        }
        .cut-line span {
            position: relative;
            font-size: 7px;
            color: #777;
            background: #fff;
            padding: 0 6px;
        }

        /* ─────────────────────────────────────────────────
           ACKNOWLEDGEMENT
        ───────────────────────────────────────────────── */
        .ack-title {
            text-align: center;
            font-size: 9px;
            font-weight: 700;
            text-decoration: underline;
            color: #1a2f55;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

{{-- ── Screen toolbar (hidden on print) ──────────────────────── --}}
<div class="no-print">
    <button class="print-btn" id="print-btn">🖨&nbsp; Print Application</button>
    <a href="{{ route('student.enrollments.index') }}" class="back-link">← Back</a>
    <span class="toolbar-hint">Set paper to A4, scale: Fit to page</span>
</div>

<div class="print-page">

{{-- ── Header ──────────────────────────────────────────────── --}}
<div class="header">
    @php $logoPath = public_path('images/logo.png'); @endphp
    @if(file_exists($logoPath))
        <img src="{{ asset('images/logo.png') }}" alt="UASC Logo" class="logo">
    @endif
    <div class="header-text">
        <h1>University Arts &amp; Science College</h1>
        <h2>(An Autonomous Institute under Kakatiya University)</h2>
    </div>
</div>
<div class="form-title">Application Form for Examination Registration</div>

{{-- ── Course / Medium / Group / Semester ─────────────────────── --}}
<table>
    <tr>
        <td style="width:42px;" class="lbl">Course</td>
        <th style="width:70px;">{{ $student->course ?? '—' }}</th>
        <td style="width:42px;" class="lbl">Medium</td>
        <th style="width:60px;">{{ $student->medium ?? '—' }}</th>
        <td style="width:42px;" class="lbl">Group</td>
        <th style="width:65px;">{{ $student->group_code ?? '—' }}</th>
        <td style="width:45px;" class="lbl">Semester</td>
        <th>{{ $student->semester ? 'Sem ' . $student->semester : '—' }}</th>
    </tr>
</table>

{{-- ── Student Details + Photo ────────────────────────────────── --}}
@php
    $addressParts = array_filter([
        $student->address,
        $student->address2,
        $student->mandal,
        $student->city,
        $student->state,
        $student->pincode,
    ]);
@endphp
<table>
    {{-- Hall Ticket --}}
    <tr>
        <td colspan="4" style="background:#f7f8fb;">
            <span class="lbl">Hall Ticket No:</span>&nbsp;
            <strong style="font-size:10.5px;letter-spacing:0.8px;color:#1a2f55;">{{ $student->hall_ticket }}</strong>
        </td>
        <td class="photo-cell" rowspan="7">
            @if($student->photo_url)
                <span class="plabel">Photograph</span>
                <img src="{{ $student->photo_url }}" width="96" height="118" alt="Photo">
            @else
                <span class="plabel">Photograph</span>
                <div class="ph-box" style="width:96px;height:118px;">Photo</div>
            @endif
            <div style="height:5px;"></div>
            @if($student->signature_url)
                <span class="plabel">Signature</span>
                <img src="{{ $student->signature_url }}" width="96" height="28" alt="Signature">
            @else
                <span class="plabel">Signature</span>
                <div class="ph-box" style="width:96px;height:28px;">Signature</div>
            @endif
        </td>
    </tr>
    {{-- Row 1: Name | Father --}}
    <tr>
        <td style="width:23%;"><span class="lbl">Name: </span><strong>{{ $student->name }}</strong></td>
        <td colspan="3"><span class="lbl">Father's Name: </span><strong>{{ $student->father_name ?? '—' }}</strong></td>
    </tr>
    {{-- Row 2: Mother | Aadhaar --}}
    <tr>
        <td><span class="lbl">Mother's Name: </span><strong>{{ $student->mother_name ?? '—' }}</strong></td>
        <td><span class="lbl">Aadhaar: </span><strong>{{ $student->aadhaar ?? '—' }}</strong></td>
        <td><span class="lbl">DOB: </span><strong>{{ $student->dob?->format('d-m-Y') ?? '—' }}</strong></td>
        <td><span class="lbl">Gender: </span><strong>{{ ucfirst($student->gender ?? '—') }}</strong></td>
    </tr>
    {{-- Row 3: Course | Group --}}
    <tr>
        <td colspan="2"><span class="lbl">Course: </span><strong>{{ $student->course_name ?? $student->course ?? '—' }}</strong></td>
        <td><span class="lbl">Group: </span><strong>{{ $student->group_code ?? '—' }}</strong></td>
        <td><span class="lbl">Semester: </span><strong>{{ $student->semester ? 'Sem '.$student->semester : '—' }}</strong></td>
    </tr>
    {{-- Row 4: Address --}}
    <tr>
        <td colspan="4"><span class="lbl">Address: </span><strong>{{ implode(', ', $addressParts) ?: '—' }}</strong></td>
    </tr>
    {{-- Row 5: Mobile | Email --}}
    <tr>
        <td colspan="2"><span class="lbl">Mobile: </span><strong>{{ $student->phone ?? '—' }}</strong></td>
        <td colspan="2"><span class="lbl">Email: </span><strong>{{ $student->email ?? '—' }}</strong></td>
    </tr>
    {{-- Row 6: Exam name --}}
    <tr>
        <td colspan="4"><span class="lbl">Exam: </span><strong>{{ $enrollment->exam?->name ?? '—' }}</strong></td>
    </tr>
</table>

{{-- ── Social Status ────────────────────────────────────────── --}}
@php
    $caste    = strtoupper($student->caste ?? '');
    $tick     = fn(string $lbl) => $caste === strtoupper($lbl) ? '✓' : '';
    $cats     = ['SC','ST','BC-A','BC-B','BC-C','BC-D','OC'];
    $isOther  = $caste !== '' && !in_array($caste, $cats);
@endphp
<table>
    <tr>
        <th style="width:120px;font-size:8px;">Social Status (Please Tick)</th>
        @foreach($cats as $cat)
            <td style="text-align:center;width:44px;">
                {{ $cat }}&nbsp;<span class="tick-box">{{ $tick($cat) }}</span>
            </td>
        @endforeach
        <td style="text-align:center;">
            Others&nbsp;<span class="tick-box">{{ $isOther ? '✓' : '' }}</span>
        </td>
    </tr>
</table>

{{-- ── Subjects ─────────────────────────────────────────────── --}}
@php
    $subjects = $enrollment->enrollmentSubjects->sortBy('subject.code')->values();
@endphp
<div class="subj-header">
    Subjects Appearing For: {{ $enrollment->exam?->name }}
</div>
<table class="subj-table" style="margin-bottom:3px;table-layout:fixed;">
    <thead>
        <tr>
            @foreach($subjects as $i => $es)
                <th>{{ $es->subject?->code ?? ('S'.($i+1)) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach($subjects as $es)
                <td class="name-cell">{{ $es->subject?->name ?? '—' }}</td>
            @endforeach
        </tr>
        <tr>
            @foreach($subjects as $es)
                <td class="subj-type">{{ ucfirst($es->paper_type ?? '—') }}</td>
            @endforeach
        </tr>
    </tbody>
</table>

{{-- ── Fee Paid ─────────────────────────────────────────────── --}}
<table>
    <tr>
        <th style="width:60px;font-size:8px;">Fee Paid</th>
        <td><span class="lbl">Amount: </span><strong>Rs.&nbsp;{{ number_format($enrollment->fee_amount ?? 0) }}</strong></td>
        <td><span class="lbl">Challan No: </span><strong>{{ $enrollment->challan_number ?? '________________________' }}</strong></td>
        <td><span class="lbl">Date: </span><strong>{{ $enrollment->challan_submitted_on?->format('d-m-Y') ?? '________________________' }}</strong></td>
    </tr>
</table>

{{-- ── Declaration ──────────────────────────────────────────── --}}
<div class="declaration">
    <strong>Declaration:</strong>&nbsp;I hereby declare that all the information furnished above is correct to the best of my
    knowledge. In the event of any information being found incorrect or misleading, my candidature shall be liable to
    cancellation by the University at any cost to me, and I shall not be entitled to any refund of fee paid by me to the University.
</div>
<div class="sig-row">Signature of the Candidate</div>

{{-- ── Tear-off line ────────────────────────────────────────── --}}
<div class="cut-line"><span>✂&nbsp;&nbsp;Candidate Acknowledgement&nbsp;&nbsp;✂</span></div>

{{-- ── Acknowledgement (compact single-table strip) ─────────── --}}
<div class="ack-title">Candidate Acknowledgement</div>
<table>
    <tr>
        <td style="width:28%;"><span class="lbl">H.T. No: </span><strong>{{ $student->hall_ticket }}</strong></td>
        <td style="width:30%;"><span class="lbl">Name: </span><strong>{{ $student->name }}</strong></td>
        <td style="width:14%;"><span class="lbl">Course: </span><strong>{{ $student->course ?? '—' }}</strong></td>
        <td style="width:14%;"><span class="lbl">Medium: </span><strong>{{ $student->medium ?? '—' }}</strong></td>
        <td><span class="lbl">Group: </span><strong>{{ $student->group_code ?? '—' }}</strong></td>
    </tr>
    <tr>
        <th style="font-size:8px;">Fee Paid</th>
        <td><span class="lbl">Amount: </span><strong>Rs.&nbsp;{{ number_format($enrollment->fee_amount ?? 0) }}</strong></td>
        <td colspan="2"><span class="lbl">Challan No: </span><strong>{{ $enrollment->challan_number ?? '________________________' }}</strong></td>
        <td><span class="lbl">Date: </span><strong>{{ $enrollment->challan_submitted_on?->format('d-m-Y') ?? '___________' }}</strong></td>
    </tr>
</table>
<div class="sig-row" style="margin-top:10px;">Signature of the Receiver</div>

</div>{{-- .print-page --}}
<script nonce="{{ $csp_nonce ?? '' }}">
document.getElementById('print-btn').addEventListener('click', function () { window.print(); });
</script>
</body>
</html>
