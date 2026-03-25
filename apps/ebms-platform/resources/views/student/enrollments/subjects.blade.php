@extends('layouts.student')
@section('title', 'Select Subjects')

@section('content')

<div class="animate-in" style="margin-bottom:6px;">
    <a href="{{ route('student.enrollments.create') }}" style="font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:16px;">
        ← Back to exams
    </a>
    <h1 class="font-display" style="font-size:24px;font-weight:600;color:var(--navy);margin:0 0 4px;">Select Subjects</h1>
    <p style="font-size:14px;color:var(--muted);margin:0;">{{ $exam->name }} · Semester {{ $exam->semester }}</p>
</div>

<form method="POST" action="{{ route('student.enrollments.confirm') }}" style="margin-top:24px;">
    @csrf
    <input type="hidden" name="exam_id" value="{{ $exam->id }}">

    {{-- Compulsory --}}
    <div class="card animate-in delay-1" style="overflow:hidden;margin-bottom:14px;">
        <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
            <span style="width:8px;height:8px;background:var(--navy);border-radius:50%;flex-shrink:0;"></span>
            <p style="font-size:14px;font-weight:700;color:var(--navy);margin:0;">Compulsory Subjects</p>
            <span style="margin-left:auto;font-size:11px;color:var(--muted);font-weight:600;background:#EEF0F3;padding:2px 8px;border-radius:99px;">{{ $compulsorySubjects->count() }} papers</span>
        </div>
        @foreach($compulsorySubjects as $subject)
        <label style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid var(--border);cursor:pointer;transition:background .1s;" onmouseover="this.style.background='#FAFAF8'" onmouseout="this.style.background='transparent'">
            <input type="checkbox" name="compulsory_subjects[]" value="{{ $subject->id }}" checked
                   style="width:18px;height:18px;border-radius:5px;accent-color:var(--navy);flex-shrink:0;">
            <div style="flex:1;min-width:0;">
                <p style="font-size:14px;font-weight:600;color:var(--navy);margin:0;">{{ $subject->name }}</p>
                <p style="font-size:12px;color:var(--muted);margin:0;font-family:'JetBrains Mono',monospace;">{{ $subject->code }}</p>
            </div>
        </label>
        @endforeach
    </div>

    {{-- Electives --}}
    @if($electiveSubjects->count())
    <div class="card animate-in delay-2" style="overflow:hidden;margin-bottom:14px;">
        <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
            <span style="width:8px;height:8px;background:var(--amber);border-radius:50%;flex-shrink:0;"></span>
            <p style="font-size:14px;font-weight:700;color:var(--navy);margin:0;">Elective Subjects</p>
            <span style="margin-left:auto;font-size:11px;color:var(--muted);font-weight:600;background:#FEF3E2;color:var(--amber);padding:2px 8px;border-radius:99px;">Choose one per group</span>
        </div>
        @foreach($electiveSubjects as $group => $subjects)
        <div style="border-bottom:1px solid var(--border);">
            <p style="padding:10px 20px 6px;font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.5px;text-transform:uppercase;margin:0;">Group {{ $group }}</p>
            @foreach($subjects as $subject)
            <label style="display:flex;align-items:center;gap:14px;padding:12px 20px;cursor:pointer;transition:background .1s;" onmouseover="this.style.background='#FAFAF8'" onmouseout="this.style.background='transparent'">
                <input type="radio" name="elective_subjects[{{ $group }}]" value="{{ $subject->id }}"
                       style="width:18px;height:18px;accent-color:var(--amber);flex-shrink:0;">
                <div style="flex:1;min-width:0;">
                    <p style="font-size:14px;font-weight:600;color:var(--navy);margin:0;">{{ $subject->name }}</p>
                    <p style="font-size:12px;color:var(--muted);margin:0;font-family:'JetBrains Mono',monospace;">{{ $subject->code }}</p>
                </div>
            </label>
            @endforeach
        </div>
        @endforeach
    </div>
    @endif

    {{-- Improvement (opt-in) --}}
    @if($improvementSubjects->count())
    <div class="card animate-in delay-2" style="overflow:hidden;margin-bottom:14px;">
        <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
            <span style="width:8px;height:8px;background:#059669;border-radius:50%;flex-shrink:0;"></span>
            <p style="font-size:14px;font-weight:700;color:var(--navy);margin:0;">Improvement (Optional)</p>
            <span style="margin-left:auto;font-size:11px;font-weight:600;background:#ECFDF5;color:#059669;padding:2px 8px;border-radius:99px;">₹{{ number_format($resolvedFee['fee_improvement'] ?: 300) }}/paper</span>
        </div>
        <p style="font-size:12px;color:var(--muted);margin:0;padding:10px 20px 6px;">Select previously passed subjects you wish to improve your grade in.</p>
        @foreach($improvementSubjects as $subject)
        <label style="display:flex;align-items:center;gap:14px;padding:12px 20px;border-top:1px solid var(--border);cursor:pointer;transition:background .1s;" onmouseover="this.style.background='#FAFAF8'" onmouseout="this.style.background='transparent'">
            <input type="checkbox" name="improvement_subjects[]" value="{{ $subject->id }}"
                   style="width:18px;height:18px;border-radius:5px;accent-color:#059669;flex-shrink:0;">
            <div style="flex:1;min-width:0;">
                <p style="font-size:14px;font-weight:600;color:var(--navy);margin:0;">{{ $subject->name }}</p>
                <p style="font-size:12px;color:var(--muted);margin:0;font-family:'JetBrains Mono',monospace;">{{ $subject->code }}</p>
            </div>
        </label>
        @endforeach
    </div>
    @endif

    {{-- Fee preview --}}
    @php
        $rfRegular = ($resolvedFee['fee_regular'] ?? 0) + ($resolvedFee['fee_fine'] ?? 0);
        $rfSupply  = ($resolvedFee['fee_supply_upto2'] ?? 0) + ($resolvedFee['fee_fine'] ?? 0);
        $rfImprove = $resolvedFee['fee_improvement'] ?? 0;
    @endphp
    @if($rfRegular || $rfSupply)
    <div id="fee-preview" class="animate-in delay-3"
         style="background:#FFFBEB;border:1px solid #FCD34D;border-radius:10px;padding:14px 18px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;"
         data-fee-regular="{{ $rfRegular }}"
         data-fee-supply-upto2="{{ $rfSupply }}"
         data-exam-type="{{ $exam->exam_type }}"
         data-fee-improvement="{{ $rfImprove ?: 300 }}">
        <span style="font-size:13px;font-weight:600;color:#92400E;">Estimated Fee</span>
        <span id="fee-preview-amount" class="font-display" style="font-size:20px;font-weight:700;color:var(--amber);">₹{{ number_format($rfRegular) }}</span>
    </div>
    <script nonce="{{ $csp_nonce ?? '' }}">
    (function () {
        var preview      = document.getElementById('fee-preview');
        var amountEl     = document.getElementById('fee-preview-amount');
        var regular      = parseInt(preview.dataset.feeRegular, 10) || 0;
        var supplyUpto2  = parseInt(preview.dataset.feeSupplyUpto2, 10) || 0;
        var examType     = preview.dataset.examType || 'regular';
        var feeImprov    = parseInt(preview.dataset.feeImprovement, 10) || 0;

        function recalc() {
            var checked = document.querySelectorAll(
                'input[name="compulsory_subjects[]"]:checked, input[name^="elective_subjects["]:checked'
            ).length;
            var improvChecked = document.querySelectorAll(
                'input[name="improvement_subjects[]"]:checked'
            ).length;

            var fee = regular;
            if (examType === 'supplementary' && supplyUpto2 && checked > 0 && checked <= 2) {
                fee = supplyUpto2;
            }
            fee += feeImprov * improvChecked;
            amountEl.textContent = '₹' + fee.toLocaleString('en-IN');
        }

        document.querySelectorAll('input[type="checkbox"], input[type="radio"]')
            .forEach(function (el) { el.addEventListener('change', recalc); });

        recalc();
    }());
    </script>
    @endif

    <div class="animate-in delay-3">
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">
            Continue to Review →
        </button>
    </div>
</form>

@endsection
