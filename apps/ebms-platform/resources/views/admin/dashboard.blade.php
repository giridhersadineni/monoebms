@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl">

    <div class="mb-7">
        <h1 class="text-2xl font-semibold text-slate-800">Admin Dashboard</h1>
        <p class="text-sm text-slate-500 mt-1">Overview of examination activity</p>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-7">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-3xl font-bold text-blue-700">{{ $stats['open_exams'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Open Exams</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-3xl font-bold text-emerald-600">{{ $stats['enrollments_today'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Enrollments Today</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-3xl font-bold text-amber-600">{{ $stats['fee_unpaid'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Fee Unpaid</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-3xl font-bold text-red-600">{{ $stats['pending_revaluations'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Pending Revaluations</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-700 text-sm">Recent Enrollments</h2>
            <a href="{{ route('admin.enrollments.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium hover:underline">
                View all &rarr;
            </a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold tracking-wide uppercase border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Hall Ticket</th>
                    <th class="px-5 py-2.5 text-left">Student</th>
                    <th class="px-5 py-2.5 text-left">Exam</th>
                    <th class="px-5 py-2.5 text-left">Fee</th>
                    <th class="px-5 py-2.5 text-left">Enrolled</th>
                    <th class="px-5 py-2.5 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEnrollments as $e)
                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors last:border-0">
                    <td class="px-5 py-3">
                        <code class="font-mono text-xs text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $e->hall_ticket }}</code>
                    </td>
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $e->student?->name }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $e->exam?->name }}</td>
                    <td class="px-5 py-3"><x-status-badge :status="$e->getFeeStatus()" /></td>
                    <td class="px-5 py-3 text-slate-400 text-xs">{{ $e->enrolled_at?->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.enrollments.show', $e) }}" class="text-blue-600 hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
