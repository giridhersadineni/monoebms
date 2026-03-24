<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
@page { size: A4 landscape; margin: 1in; }
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8px;
    color: #000;
    page-break-inside: avoid;
}

/* ── Outer 4-column grid — NO fixed height; content must fit naturally ── */
.grid {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    page-break-inside: avoid;
}

.copy-cell {
    width: 25%;
    vertical-align: top;
    padding: 0 3px;
}
.copy-cell.br { border-right: 1px dashed #888; }

/* ── Each copy ── */
.copy {
    width: 100%;
    border: 1.5px solid #000;
    border-collapse: collapse;
}

/* Badge row */
.r-badge td {
    padding: 2px 4px;
    border-bottom: 1px solid #000;
    text-align: center;
}
.badge-label {
    display: inline-block;
    border: 1.5px solid #000;
    border-radius: 3px;
    padding: 1px 5px;
    font-size: 7.5px;
    font-weight: bold;
    margin-bottom: 1px;
}
.badge-sub { font-size: 7px; color: #333; }

/* Header row */
.r-hdr td {
    padding: 2px 4px;
    border-bottom: 1px solid #ccc;
    text-align: center;
}
.college { font-size: 8.5px; font-weight: bold; text-transform: uppercase; line-height: 1.3; }
.college-sub { font-size: 7px; }
.bank-name { font-size: 8px; font-weight: bold; margin-top: 2px; }
.bank-sub { font-size: 7.5px; }

/* Student meta row */
.r-meta td { padding: 3px 4px; border-bottom: 1px solid #ccc; }
.meta-tbl { width: 100%; border-collapse: collapse; }
.meta-tbl td { font-size: 7.5px; padding: 1px 0; vertical-align: top; }
.lbl { color: #444; width: 58px; white-space: nowrap; }
.val { font-weight: bold; }

/* Subject table row */
.r-subjs td { padding: 0; border-bottom: 1px solid #000; }
.subj-tbl { width: 100%; border-collapse: collapse; }
.subj-tbl th {
    font-size: 7.5px;
    font-weight: bold;
    background: #e8e8e8;
    padding: 2px 3px;
    text-align: left;
    border-bottom: 0.5px solid #999;
    border-right: 0.5px solid #ccc;
}
.subj-tbl td {
    font-size: 7.5px;
    padding: 1.5px 3px;
    vertical-align: top;
    border-bottom: 0.5px solid #eee;
    border-right: 0.5px solid #eee;
}
.subj-tbl .amt-col { text-align: right; white-space: nowrap; }

/* Rupees-in-words row */
.r-words td { padding: 3px 4px; border-bottom: 0.5px solid #ccc; font-size: 7.5px; }

/* Signature row */
.r-sig td { padding: 2px 4px 3px; border-bottom: 1px solid #000; }
.sig-candidate { text-align: right; font-size: 7px; padding-top: 15mm; }

/* FOR USE BY THE BANK */
.r-bank td { padding: 3px 4px 2px; }
.bank-hdr { text-align: center; font-size: 7.5px; font-weight: bold; border-bottom: 0.5px solid #aaa; padding-bottom: 2px; margin-bottom: 3px; }
.bank-line { font-size: 7.5px; margin-bottom: 2px; }
.bank-sig-tbl { width: 100%; border-collapse: collapse; margin-top: 3px; }
.bank-sig-tbl td {
    font-size: 7px;
    padding-top: 22px;
    border-top: 0.5px solid #000;
    text-align: center;
    width: 25%;
}
</style>
</head>
<body>

@php
    $examDesc = $exam->name;
    if ($exam->month && $exam->year) {
        $mn = \DateTime::createFromFormat('!m', (int)$exam->month)?->format('M');
        $examDesc .= ' — ' . $mn . ' ' . $exam->year;
    }

    $copies = [
        'QUADRUPLICATE' => 'To be retained by the Bank',
        'TRIPLICATE'    => 'To be retained by the College',
        'DUPLICATE'     => 'To be attached with the Form',
        'ORIGINAL'      => 'To be retained by the Student',
    ];

    $subjList = $subjects->values();
@endphp

<table class="grid">
<tr>
@foreach($copies as $label => $subtitle)
<td class="copy-cell {{ !$loop->last ? 'br' : '' }}">
<table class="copy">

    {{-- Badge --}}
    <tr class="r-badge">
        <td>
            <div class="badge-label">{{ $label }}</div><br>
            <span class="badge-sub">({{ $subtitle }})</span>
        </td>
    </tr>

    {{-- College + Bank header --}}
    <tr class="r-hdr">
        <td>
            <div class="college">University Arts &amp; Science College</div>
            <div class="college-sub">(Autonomous), Hanamkonda</div>
            <div class="bank-name">STATE BANK OF INDIA</div>
            <div class="bank-sub">Subedari Branch, Hanamkonda &nbsp;|&nbsp; A/C No. {{ $sbi_account }} &nbsp;|&nbsp; IFSC: {{ $sbi_ifsc }}</div>
        </td>
    </tr>

    {{-- Student meta --}}
    <tr class="r-meta">
        <td>
            <table class="meta-tbl">
                <tr>
                    <td class="lbl">Challan No.&nbsp;:</td>
                    <td class="val">{{ $challan_no }}</td>
                    <td class="lbl" style="padding-left:4px;">Date&nbsp;:</td>
                    <td>{{ now()->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td class="lbl">Name&nbsp;:</td>
                    <td class="val" colspan="3">{{ $student->name }}</td>
                </tr>
                <tr>
                    <td class="lbl">Class&nbsp;:</td>
                    <td class="val" colspan="3">{{ $student->course_name ?? $student->course }}</td>
                </tr>
                <tr>
                    <td class="lbl">H.T. No.&nbsp;:</td>
                    <td class="val">{{ $student->hall_ticket }}</td>
                    <td class="lbl" style="padding-left:4px;">Sem.&nbsp;:</td>
                    <td>{{ $exam->semester }}</td>
                </tr>
                <tr>
                    <td class="lbl">Exam&nbsp;:</td>
                    <td class="val" colspan="3">{{ $examDesc }}</td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Subject + Fee table --}}
    <tr class="r-subjs">
        <td>
            <table class="subj-tbl">
                <thead>
                    <tr>
                        <th>Exam Fee (Subjects)</th>
                        <th class="amt-col" style="width:38px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjList as $i => $es)
                    <tr>
                        <td>{{ $es->subject_code }}{{ $es->subject ? ' — ' . $es->subject->name : '' }}</td>
                        <td class="amt-col">@if($i === 0) Rs.{{ number_format($enrollment->fee_amount) }} @endif</td>
                    </tr>
                    @endforeach
                    {{-- Pad to minimum 5 rows so the table has some body --}}
                    @for($p = $subjList->count(); $p < 5; $p++)
                    <tr><td>&nbsp;</td><td></td></tr>
                    @endfor
                </tbody>
            </table>
        </td>
    </tr>

    {{-- Rupees in words --}}
    <tr class="r-words">
        <td>
            Rupees in words&nbsp;: <span style="font-style:italic;">{{ $fee_in_words }}</span>
        </td>
    </tr>

    {{-- Candidate signature --}}
    <tr class="r-sig">
        <td>
            <div class="sig-candidate">Signature of Candidate</div>
        </td>
    </tr>

    {{-- FOR USE BY THE BANK --}}
    <tr class="r-bank">
        <td>
            <div class="bank-hdr">FOR USE BY THE BANK</div>
            <div class="bank-line">Rupees (in figure)&nbsp;: <span style="display:inline-block;width:100px;border-bottom:0.5px solid #000;">&nbsp;</span></div>
            <div class="bank-line">Rupees (in words)&nbsp;: <span style="display:inline-block;width:100px;border-bottom:0.5px solid #000;">&nbsp;</span></div>
            <table class="bank-sig-tbl">
                <tr>
                    <td>Receiving<br>Cashier</td>
                    <td>Scroll<br>Cashier</td>
                    <td>Head<br>Cashier</td>
                    <td>Manager/<br>Acctn.</td>
                </tr>
            </table>
        </td>
    </tr>

</table>
</td>
@endforeach
</tr>
</table>

</body>
</html>
