@extends('layouts.student')
@section('title', 'Temporarily Unavailable')

@section('content')
<div class="animate-in" style="display:flex;align-items:center;justify-content:center;min-height:60vh;">
    <div class="card" style="max-width:480px;width:100%;padding:48px 32px;text-align:center;">
        <div style="width:56px;height:56px;background:#FEF3C7;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <svg width="26" height="26" fill="none" stroke="#D97706" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
            </svg>
        </div>
        <h1 class="font-display" style="font-size:20px;font-weight:700;color:var(--navy);margin:0 0 10px;">
            {{ $flag->label }} Temporarily Unavailable
        </h1>
        <p style="font-size:14px;color:var(--muted);margin:0 0 28px;line-height:1.6;">
            {{ $flag->message ?? 'This feature is currently under maintenance. Please check back shortly.' }}
        </p>
        <a href="{{ route('student.dashboard') }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:10px 22px;background:var(--navy);color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">
            &larr; Back to Dashboard
        </a>
    </div>
</div>
@endsection
