<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .challan-copy { border: 1px solid #000; padding: 10px; margin-bottom: 8px; page-break-inside: avoid; }
        .challan-copy h3 { margin: 0 0 4px; font-size: 12px; border-bottom: 1px solid #000; padding-bottom: 3px; }
        .copy-label { float: right; font-weight: bold; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        td, th { border: 1px solid #aaa; padding: 3px 5px; font-size: 10px; }
        th { background: #f0f0f0; text-align: left; }
        .row { display: flex; gap: 10px; margin: 4px 0; }
        .field { flex: 1; }
        .label { color: #666; font-size: 9px; }
        .bank-section { margin-top: 6px; border-top: 1px dashed #999; padding-top: 4px; }
    </style>
</head>
<body>
@foreach($copies as $copy)
<div class="challan-copy">
    <h3>
        University Arts & Science College — Exam Fee Challan
        <span class="copy-label">{{ $copy }}</span>
    </h3>

    <div style="display:flex; gap:12px; margin-top:6px;">
        <div class="field">
            <div class="label">Student Name</div>
            <div><strong>{{ $student->name }}</strong></div>
        </div>
        <div class="field">
            <div class="label">Hall Ticket</div>
            <div>{{ $student->hall_ticket }}</div>
        </div>
        <div class="field">
            <div class="label">Course</div>
            <div>{{ $student->course_name ?? $student->course }}</div>
        </div>
    </div>

    <div style="display:flex; gap:12px; margin-top:4px;">
        <div class="field">
            <div class="label">Exam</div>
            <div>{{ $exam->name }}</div>
        </div>
        <div class="field">
            <div class="label">Challan No</div>
            <div>{{ $challan_no }}</div>
        </div>
        <div class="field">
            <div class="label">Fee Amount</div>
            <div><strong>₹{{ number_format($enrollment->fee_amount) }}/-</strong></div>
        </div>
    </div>

    <table>
        <thead>
            <tr><th>#</th><th>Paper Code</th><th>Subject Name</th><th>Type</th></tr>
        </thead>
        <tbody>
            @foreach($subjects as $i => $es)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $es->subject_code }}</td>
                <td>{{ $es->subject?->name }}</td>
                <td>{{ ucfirst($es->subject_type) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="bank-section">
        <strong>Pay at:</strong> State Bank of India, {{ $sbi_branch }}
        &nbsp;|&nbsp; <strong>A/C No:</strong> {{ $sbi_account }}
        <div style="margin-top:6px; display:flex; gap:20px;">
            <div>For ₹ _________________ (Rupees ____________________________________ only)</div>
        </div>
        <div style="margin-top:8px; display:flex; justify-content:space-between;">
            <div>Bank Seal &amp; Date: _______________</div>
            <div>Authorised Signatory: _______________</div>
        </div>
    </div>
</div>
@endforeach
</body>
</html>
