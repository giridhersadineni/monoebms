<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hallticket</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            margin: 0;
            padding: 12px;
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            background: #fff;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: #eef2f7;
            border-radius: 8px;
            margin-bottom: 14px;
        }

        .toolbar a,
        .toolbar button {
            border: 1px solid #c7d0dc;
            border-radius: 6px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .toolbar a {
            background: #fff;
            color: #16314f;
        }

        .toolbar button {
            background: #16314f;
            border-color: #16314f;
            color: #fff;
        }

        .frame td,
        .frame th {
            vertical-align: top;
        }

        .main-border {
            border: 1px solid #000;
        }

        .split-border {
            border-right: 1px solid #000;
        }

        .detail-box {
            padding: 10px 12px;
        }

        .detail-box h3 {
            margin: 0 0 10px;
            font-size: 18px;
            font-weight: 700;
        }

        .media-box {
            padding: 10px;
            text-align: center;
        }

        .media-box img {
            display: block;
            margin: 0 auto 8px;
            border: 1px solid #000;
            object-fit: cover;
        }

        .photo-placeholder,
        .sign-placeholder {
            margin: 0 auto 8px;
            border: 1px dashed #777;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 12px;
        }

        .subjects td,
        .subjects th {
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 14px;
        }

        .subjects th {
            text-align: left;
        }

        .instructions {
            font-size: 13px;
            line-height: 1.5;
        }

        .instructions h5 {
            margin: 0 0 8px;
            font-size: 15px;
        }

        .instructions ol {
            margin: 0;
            padding-left: 22px;
        }

        .instructions li {
            margin-bottom: 8px;
        }

        .note {
            font-weight: 700;
        }

        @media print {
            body {
                padding: 0;
            }

            .toolbar {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="toolbar">
    <button type="button" id="print-hallticket">Print Hall Ticket</button>
    <a href="{{ route('student.enrollments.index') }}">Back</a>
    <span style="margin-left:auto;font-size:11px;color:#6b7280;">Legacy-style print layout</span>
</div>

@php
    $headerPath = public_path('images/hallticket-header.svg');
    $authSignPath = public_path('images/authsign.jpg');
    $subjects = $enrollment->enrollmentSubjects->sortBy('subject.code')->values();
@endphp

<table class="frame">
    <tr>
        <td colspan="2">
            <center>
                @if(file_exists($headerPath))
                    <img src="{{ asset('images/hallticket-header.svg') }}" alt="Hallticket Header" style="width:100%;max-width:790px;">
                @endif
                <h1 style="text-align:center;margin:0;padding:0;text-decoration:underline;">HALLTICKET</h1>
                <br><br>
            </center>
        </td>
    </tr>

    <tr class="main-border">
        <td class="split-border" width="70%">
            <div class="detail-box">
                <h3>Hallticket No: {{ $student->hall_ticket }}</h3>
                <h3>Candidate Name: {{ $student->name }}</h3>
                <h3>Father Name: {{ $student->father_name ?? '-' }}</h3>
                <h3>Exam Name: {{ $enrollment->exam?->name ?? '-' }}</h3>
                <h3>Course: {{ $student->course }}</h3>
                <h3>Specialization: {{ $student->group_code ?? '-' }}</h3>
            </div>
        </td>

        <td width="30%">
            <div class="media-box">
                <br>
                @if($student->photo_url)
                    <img width="176" height="220" src="{{ $student->photo_url }}" alt="Student Photo">
                @else
                    <div class="photo-placeholder" style="width:176px;height:220px;">Photo</div>
                @endif

                @if($student->signature_url)
                    <img width="176" height="60" src="{{ $student->signature_url }}" alt="Student Signature">
                @else
                    <div class="sign-placeholder" style="width:176px;height:60px;">Signature</div>
                @endif
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <br><br><br>
            <h3 style="text-align:center;">Signature of the Student</h3>
        </td>
        <td>
            <br>
            <center>
                @if(file_exists($authSignPath))
                    <img src="{{ asset('images/authsign.jpg') }}" height="60" alt="Issuing Authority Signature" style="border:none;">
                @endif
            </center>
            <h3 style="text-align:center;">Signature of the Issuing Authority</h3>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <h2 style="text-align:center;">Subjects Appearing</h2>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <table class="subjects">
                <tbody>
                <tr>
                    <td><strong>Subject Code</strong></td>
                    <td><strong>Subject Name</strong></td>
                    <td><strong>Invigilator Signature</strong></td>
                </tr>
                @forelse($subjects as $subject)
                    <tr>
                        <td>{{ $subject->subject_code }}</td>
                        <td>{{ $subject->subject?->name ?? '-' }}</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No registered subjects found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <div class="instructions">
                <h5>Instructions to the Candidates</h5>
                <ol>
                    <li>The examination will be held on the notified dates in the college premises.</li>
                    <li>The college reserves the right to cancel the admission of the candidate at any stage if it is detected that the admission is against the rules.</li>
                    <li>Candidate should reach the examination center 30 minutes before commencement of the exam. <span class="note">Students will not be allowed into the examination hall after commencement of the exam.</span></li>
                    <li>Candidate is prohibited from bringing books, calculators, mobile phones, pagers, and smart watches to the examination hall. Any copying, communication, misconduct, or malpractice may lead to expulsion and further punishment.</li>
                    <li>Whenever the course or scheme changes, only the permitted transition examinations will follow the old syllabus and regulations. Thereafter, candidates must take examinations according to the changed syllabus and regulations.</li>
                    <li>Candidates are requested to bring the hall ticket and identity card every day and produce them whenever demanded.</li>
                </ol>
                <p class="note" style="margin:8px 0 0 0;">Smoking or eatables are prohibited in the examination hall.</p>
            </div>
        </td>
    </tr>
</table>

<script nonce="{{ $csp_nonce ?? '' }}">
window.addEventListener('load', function () {
    window.print();
});

document.getElementById('print-hallticket').addEventListener('click', function () {
    window.print();
});
</script>
</body>
</html>
