<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D-Form — {{ $subject->code }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px 24px;
            color: #000;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .header h1 {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .header-meta {
            text-align: right;
            line-height: 1.8;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 24px;
            margin-bottom: 14px;
            font-size: 12px;
        }

        .meta-grid .label { color: #555; }
        .meta-grid .value { font-weight: bold; }

        .section-title {
            background: #000;
            color: #fff;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .ht-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0;
            border-top: 1px solid #000;
            border-left: 1px solid #000;
        }

        .ht-cell {
            width: 25%;
            border-right: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 14px 8px;
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        .count-bar {
            background: #f0f0f0;
            border: 1px solid #ccc;
            padding: 6px 12px;
            margin-bottom: 14px;
            font-size: 12px;
        }

        .count-bar strong { font-size: 16px; }

        @media print {
            body { padding: 10px; }
            .no-print { display: none; }

            @page { margin: 1.5cm; size: A4; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="background:#1e3a5f;color:#fff;padding:8px 14px;margin:-20px -24px 16px;
                                  display:flex;justify-content:space-between;align-items:center;font-size:13px;">
        <span style="font-weight:600;">D-Form Preview</span>
        <button id="print-btn"
                style="background:#fff;color:#1e3a5f;border:none;padding:5px 16px;border-radius:4px;
                       font-size:12px;font-weight:bold;cursor:pointer;">
            &#128438; Print
        </button>
    </div>
    <script nonce="{{ $csp_nonce }}">
        document.getElementById('print-btn').addEventListener('click', function () { window.print(); });
    </script>

    <div class="header">
        <div>
            <h1>D-FORM</h1>
            <div style="font-size:11px;color:#444;margin-top:4px;">
                University Arts &amp; Science College, Warangal
            </div>
        </div>
        <div class="header-meta">
            <div><strong>Paper Code:</strong> {{ $subject->code }}</div>
            <div><strong>Paper:</strong> {{ $subject->name }}</div>
            <div style="margin-top:4px;"><strong>Exam:</strong> {{ $exam->name }}</div>
        </div>
    </div>

    <div class="meta-grid">
        <div>
            <span class="label">Name of Examination: </span>
            <span class="value">{{ $exam->name }}</span>
        </div>
        <div>
            <span class="label">Date of Exam: </span>
            <span class="value">_________________________</span>
        </div>
        <div>
            <span class="label">Title of the Paper: </span>
            <span class="value">{{ $subject->name }}</span>
        </div>
        <div>
            <span class="label">Paper No: </span>
            <span class="value">_________________________</span>
        </div>
    </div>

    <div class="count-bar">
        Total candidates: <strong>{{ $hallTickets->count() }}</strong>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Fee Paid &amp; Enrolled in <strong>{{ $subject->code }}</strong>
    </div>

    <div class="section-title">HALL TICKET NUMBERS</div>

    @if($hallTickets->isEmpty())
        <p style="color:#666;padding:16px 0;">No fee-paid enrollments found for this subject and exam.</p>
    @else
        <div class="ht-grid">
            @foreach($hallTickets as $ht)
                <div class="ht-cell">{{ $ht }}</div>
            @endforeach
            {{-- pad to complete the last row (4 columns) --}}
            @php $rem = $hallTickets->count() % 4; @endphp
            @if($rem > 0)
                @for($i = 0; $i < 4 - $rem; $i++)
                    <div class="ht-cell" style="color:transparent;">—</div>
                @endfor
            @endif
        </div>
    @endif

    <div class="footer">
        <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>
        <span>Invigilator's Signature: ___________________________</span>
    </div>

</body>
</html>
