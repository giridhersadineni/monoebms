<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Sheet — {{ $subject->code }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px 24px;
            color: #000;
        }

        .no-print {
            background: #1e3a5f;
            color: #fff;
            padding: 8px 14px;
            margin: -20px -24px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .no-print button {
            background: #fff;
            color: #1e3a5f;
            border: none;
            padding: 5px 16px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
        }

        .title-block {
            text-align: center;
            margin-bottom: 14px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .title-block h2 { font-size: 16px; font-weight: bold; letter-spacing: 1px; }
        .title-block h3 { font-size: 13px; font-weight: normal; margin-top: 2px; }

        .meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 32px;
            margin-bottom: 12px;
            font-size: 12px;
        }

        .meta-row { display: flex; gap: 6px; }
        .meta-row .lbl { color: #555; white-space: nowrap; }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
            overflow: hidden;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        td.serial  { text-align: center; width: 28px; }
        td.ht      { font-family: 'Courier New', monospace; font-weight: bold; font-size: 12px; width: 88px; }
        td.name    { width: 140px; }
        td.photo   { width: 58px; text-align: center; padding: 2px; height: 70px; }
        td.sig     { width: 90px; }
        td.booklet { width: 90px; }

        tfoot td {
            padding: 10px 8px;
            font-size: 11px;
        }

        .footer-info {
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        @media print {
            body { padding: 10px; }
            .no-print { display: none; }
            @page { margin: 1.5cm; size: A4 portrait; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <span style="font-weight:600;">Attendance Sheet Preview</span>
    <button id="print-btn">&#128438; Print</button>
</div>
<script nonce="{{ $csp_nonce }}">
    document.getElementById('print-btn').addEventListener('click', function () { window.print(); });
</script>

<div class="title-block">
    <h2>University Arts &amp; Science College (Autonomous), Warangal</h2>
    <h3>ATTENDANCE SHEET</h3>
</div>

<div class="meta">
    <div class="meta-row">
        <span class="lbl">Name of Examination:</span>
        <strong>{{ $exam->name }}</strong>
    </div>
    <div class="meta-row">
        <span class="lbl">Date of Exam:</span>
        <span>_______________________</span>
    </div>
    <div class="meta-row">
        <span class="lbl">Title of the Paper:</span>
        <strong>{{ $subject->name }}</strong>
    </div>
    <div class="meta-row">
        <span class="lbl">Paper Code:</span>
        <strong>{{ $subject->code }}</strong>
    </div>
    <div class="meta-row">
        <span class="lbl">Paper No.:</span>
        <span>_______________________</span>
    </div>
    <div class="meta-row">
        <span class="lbl">Total Candidates:</span>
        <strong>{{ $enrollments->count() }}</strong>
    </div>
</div>

@if($enrollments->isEmpty())
    <p style="color:#666;padding:16px 0;">No fee-paid enrollments found for this subject and exam.</p>
@else
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Hall Ticket</th>
            <th>Student Name</th>
            <th>Photo</th>
            <th>Student Signature</th>
            <th>Booklet Number</th>
            <th>Student Signature</th>
        </tr>
    </thead>
    <tbody>
        @foreach($enrollments as $i => $enrollment)
        <tr>
            <td class="serial">{{ $i + 1 }}</td>
            <td class="ht">{{ $enrollment->hall_ticket }}</td>
            <td class="name">{{ $enrollment->student?->name }}</td>
            <td class="photo">
                @if($enrollment->student?->photo_url)
                    <img src="{{ $enrollment->student->photo_url }}" style="height:100%;width:100%;object-fit:contain;display:block;" alt="">
                @endif
            </td>
            <td class="sig">
                @if($enrollment->student?->signature_url)
                    <img src="{{ $enrollment->student->signature_url }}" style="max-height:40px;max-width:100px;object-fit:contain;" alt="">
                @endif
            </td>
            <td class="booklet"></td>
            <td class="sig"></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                Room Number: _______________<br><br>
                Name of Invigilator(s): _________________________________
            </td>
            <td colspan="3" style="text-align:right;">
                <br><br>
                Signature of Invigilator(s): _________________________________
            </td>
        </tr>
    </tfoot>
</table>
@endif

<div class="footer-info">
    <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>
    <span>Total: {{ $enrollments->count() }} candidate(s)</span>
</div>

</body>
</html>
