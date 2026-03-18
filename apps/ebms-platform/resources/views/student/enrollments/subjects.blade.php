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
                   style="width:18px;height:18px;border-radius:5px;accent-color:var(--navy);flex-shrink:0;" required>
            <div style="flex:1;min-width:0;">
                <p style="font-size:14px;font-weight:600;color:var(--navy);margin:0;">{{ $subject->name }}</p>
                <p style="font-size:12px;color:var(--muted);margin:0;font-family:'JetBrains Mono',monospace;">{{ $subject->code }}</p>
            </div>
        </label>
        @endforeach
    </div>

    {{-- Electives --}}
    @if($electiveSubjects->count())
    <div class="card animate-in delay-2" style="overflow:hidden;margin-bottom:24px;">
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

    <div class="animate-in delay-3">
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">
            Continue to Review →
        </button>
    </div>
</form>

@endsection
