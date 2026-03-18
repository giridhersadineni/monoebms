@extends('layouts.student')
@section('title', 'Apply for Revaluation')

@section('content')

<div class="animate-in" style="margin-bottom:24px;">
    <a href="{{ route('student.revaluation.index') }}" style="font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:16px;">
        ← Back
    </a>
    <h1 class="font-display" style="font-size:24px;font-weight:600;color:var(--navy);margin:0 0 4px;">Apply for Revaluation</h1>
    <p style="font-size:14px;color:var(--muted);margin:0;">{{ $enrollment->exam?->name }} · ₹275 per paper</p>
</div>

@if($failedResults->count())
<form method="POST" action="{{ route('student.revaluation.store') }}">
    @csrf
    <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">

    <div class="card animate-in delay-1" style="overflow:hidden;margin-bottom:14px;">
        <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;">
            <span style="width:8px;height:8px;background:#DC2626;border-radius:50%;flex-shrink:0;"></span>
            <p style="font-size:14px;font-weight:700;color:var(--navy);margin:0;">Select Papers for Revaluation</p>
        </div>
        @foreach($failedResults as $result)
        <label style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid var(--border);cursor:pointer;transition:background .1s;" onmouseover="this.style.background='#FAFAF8'" onmouseout="this.style.background='transparent'">
            <input type="checkbox" name="subject_ids[]" value="{{ $result->subject_id }}"
                   style="width:18px;height:18px;border-radius:5px;accent-color:var(--navy);flex-shrink:0;"
                   class="reval-check">
            <div style="flex:1;min-width:0;">
                <p style="font-size:14px;font-weight:600;color:var(--navy);margin:0;">{{ $result->subject?->name }}</p>
                <p style="font-size:12px;color:var(--muted);margin:0;font-family:'JetBrains Mono',monospace;">{{ $result->subject?->code }}</p>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <p style="font-size:12px;color:var(--muted);margin:0;">Marks: {{ $result->total_marks }} · Grade: <strong style="color:#DC2626;">{{ $result->grade }}</strong></p>
            </div>
        </label>
        @endforeach
    </div>

    {{-- Fee preview --}}
    <div id="fee-preview" class="animate-in delay-2" style="display:none;background:#FFFBEB;border:1px solid #FCD34D;border-left:4px solid var(--amber);border-radius:10px;padding:14px 18px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <p style="font-size:13px;font-weight:700;color:#92400E;margin:0;">Estimated Fee</p>
            <p class="font-display" style="font-size:22px;font-weight:700;color:var(--amber);margin:0;">₹<span id="fee-amount">0</span></p>
        </div>
        <p style="font-size:12px;color:#78350F;margin:4px 0 0;"><span id="paper-count">0</span> paper(s) × ₹275 each</p>
    </div>

    <div class="animate-in delay-3">
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">
            Submit Revaluation Request
        </button>
    </div>
</form>

<script nonce="{{ $csp_nonce ?? '' }}">
(function() {
    var checks = document.querySelectorAll('.reval-check');
    var preview = document.getElementById('fee-preview');
    var feeEl = document.getElementById('fee-amount');
    var countEl = document.getElementById('paper-count');

    function update() {
        var count = document.querySelectorAll('.reval-check:checked').length;
        feeEl.textContent = (count * 275).toLocaleString('en-IN');
        countEl.textContent = count;
        preview.style.display = count > 0 ? 'block' : 'none';
    }

    checks.forEach(function(cb) { cb.addEventListener('change', update); });
})();
</script>

@else
<div class="card animate-in delay-1" style="padding:56px 24px;text-align:center;">
    <div style="width:56px;height:56px;background:#EEF0F3;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <svg width="24" height="24" fill="none" stroke="#8A9AB0" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <p style="font-size:15px;font-weight:600;color:var(--navy);margin:0 0 6px;">No eligible papers</p>
    <p style="font-size:13px;color:var(--muted);margin:0;">No failed papers found for revaluation.</p>
</div>
@endif

@endsection
