@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="max-w-5xl">

    <div class="flex items-center justify-between mb-7">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Dashboard</h1>
            <p class="text-xs text-slate-400 mt-0.5">{{ now()->format('l, d F Y') }}</p>
        </div>
        @if(auth('admin')->user()?->canAccess('enrollments.edit'))
        <a href="{{ route('admin.enrollments.mark-payment') }}"
           class="bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
            Mark Payment
        </a>
        @endif
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['open_exams'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Open for Enrollment</p>
                </div>
                <span class="text-[10px] font-semibold uppercase tracking-wide bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">NOTIFY</span>
            </div>
            @if($canViewExams)
            <a href="{{ route('admin.exams.index') }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">View exams →</a>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['running_exams'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Running Exams</p>
                </div>
                <span class="text-[10px] font-semibold uppercase tracking-wide bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">RUNNING</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['enrollments_today'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Enrolled Today</p>
                </div>
                <span class="text-xs text-slate-400">{{ $stats['enrollments_this_month'] }} this month</span>
            </div>
            @if($canViewEnrollments)
            <a href="{{ route('admin.enrollments.index') }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">All enrollments →</a>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xl font-bold text-amber-600">{{ $stats['fee_unpaid_count'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Fee Unpaid</p>
                </div>
                @if($stats['pending_revaluations'] > 0)
                <span class="text-[10px] font-semibold bg-red-50 text-red-500 px-2 py-0.5 rounded-full">{{ $stats['pending_revaluations'] }} reval</span>
                @endif
            </div>
            <p class="text-xs text-slate-400 mt-1">
                ₹{{ number_format($stats['fee_unpaid_amount']) }} outstanding
                &middot; ₹{{ number_format($stats['fee_paid_amount']) }} collected
            </p>
        </div>

    </div>

    {{-- Fee Statistics by Exam --}}
    @if($canViewEnrollments && $feeStats->isNotEmpty())
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-700">Fee Collection — Exam Wise</h2>
            @if($canViewExams)
            <a href="{{ route('admin.exams.index') }}" class="text-xs text-blue-600 hover:underline">All exams →</a>
            @endif
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Exam</th>
                    <th class="px-5 py-2.5 text-center">Status</th>
                    <th class="px-5 py-2.5 text-right">Total</th>
                    <th class="px-5 py-2.5 text-right">Paid</th>
                    <th class="px-5 py-2.5 text-right">Unpaid</th>
                    <th class="px-5 py-2.5 text-right">Collected</th>
                    <th class="px-5 py-2.5 text-right">Outstanding</th>
                    <th class="px-5 py-2.5 text-center w-28">Progress</th>
                </tr>
            </thead>
            <tbody>
                @foreach($feeStats as $row)
                @php
                    $pct = $row->total > 0 ? round($row->paid_count / $row->total * 100) : 0;
                    $statusColor = match($row->status) {
                        'NOTIFY'    => 'bg-blue-50 text-blue-600',
                        'RUNNING'   => 'bg-emerald-50 text-emerald-700',
                        'REVALOPEN' => 'bg-purple-50 text-purple-700',
                        default     => 'bg-slate-100 text-slate-500',
                    };
                @endphp
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3">
                        @if($canViewExams)
                        <a href="{{ route('admin.exams.show', $row->id) }}" class="font-medium text-slate-800 hover:text-blue-600">{{ $row->name }}</a>
                        @else
                        <span class="font-medium text-slate-800">{{ $row->name }}</span>
                        @endif
                        <p class="text-xs text-slate-400 mt-0.5">{{ ucfirst($row->exam_type) }} &middot; Sem {{ $row->semester }} &middot; {{ $row->year }}</p>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-[10px] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded {{ $statusColor }}">{{ $row->status }}</span>
                    </td>
                    <td class="px-5 py-3 text-right text-slate-700 font-medium">{{ $row->total }}</td>
                    <td class="px-5 py-3 text-right text-emerald-600 font-medium">{{ $row->paid_count }}</td>
                    <td class="px-5 py-3 text-right {{ $row->unpaid_count > 0 ? 'text-amber-600 font-medium' : 'text-slate-400' }}">{{ $row->unpaid_count }}</td>
                    <td class="px-5 py-3 text-right text-slate-700">₹{{ number_format($row->collected) }}</td>
                    <td class="px-5 py-3 text-right {{ $row->outstanding > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                        {{ $row->outstanding > 0 ? '₹' . number_format($row->outstanding) : '—' }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all {{ $pct === 100 ? 'bg-emerald-500' : 'bg-blue-400' }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 w-8 text-right shrink-0">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-slate-50 border-t border-slate-200 text-xs font-semibold text-slate-600">
                    <td class="px-5 py-2.5" colspan="2">Total</td>
                    <td class="px-5 py-2.5 text-right">{{ $feeStats->sum('total') }}</td>
                    <td class="px-5 py-2.5 text-right text-emerald-600">{{ $feeStats->sum('paid_count') }}</td>
                    <td class="px-5 py-2.5 text-right text-amber-600">{{ $feeStats->sum('unpaid_count') }}</td>
                    <td class="px-5 py-2.5 text-right">₹{{ number_format($feeStats->sum('collected')) }}</td>
                    <td class="px-5 py-2.5 text-right text-amber-600">₹{{ number_format($feeStats->sum('outstanding')) }}</td>
                    <td class="px-5 py-2.5"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{-- Recent Enrollments --}}
    @if($canViewEnrollments)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-700">Recent Enrollments</h2>
            <a href="{{ route('admin.enrollments.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
        </div>
        @if($recentEnrollments->isEmpty())
        <p class="px-5 py-6 text-sm text-slate-400 text-center">No enrollments yet.</p>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold tracking-wide uppercase border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Hall Ticket</th>
                    <th class="px-5 py-2.5 text-left">Student</th>
                    <th class="px-5 py-2.5 text-left">Exam</th>
                    <th class="px-5 py-2.5 text-left">Fee</th>
                    <th class="px-5 py-2.5 text-left">Enrolled</th>
                    <th class="px-5 py-2.5"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEnrollments as $e)
                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors last:border-0">
                    <td class="px-5 py-3">
                        <code class="font-mono text-xs text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $e->hall_ticket }}</code>
                    </td>
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $e->student?->name }}</td>
                    <td class="px-5 py-3 text-slate-500 text-xs">{{ $e->exam?->name }}</td>
                    <td class="px-5 py-3"><x-status-badge :status="$e->getFeeStatus()" /></td>
                    <td class="px-5 py-3 text-slate-400 text-xs">{{ $e->enrolled_at?->diffForHumans() }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.enrollments.show', $e) }}" class="text-blue-600 hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @endif

</div>
@endsection
