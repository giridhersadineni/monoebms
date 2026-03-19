@extends('layouts.admin')
@section('title', $paper->code . ' — Paper')

@section('content')
<div class="max-w-2xl">
    {{-- Breadcrumb + header --}}
    <div class="mb-6 flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.papers.index') }}"
                   class="text-slate-400 hover:text-slate-600 text-sm">Papers</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-600 text-sm">{{ $paper->code }}</span>
            </div>
            <h1 class="text-xl font-semibold text-slate-800">{{ $paper->name }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                <code class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-xs">{{ $paper->code }}</code>
                &nbsp;·&nbsp; {{ $paper->course }} / {{ $paper->group_code ?? 'All groups' }}
                &nbsp;·&nbsp; Sem {{ $paper->semester }}
            </p>
        </div>
        <a href="{{ route('admin.papers.edit', $paper) }}"
           class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Edit Paper
        </a>
    </div>

    {{-- Detail card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
            <div>
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Paper Code</dt>
                <dd><code class="font-mono text-sm text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded">{{ $paper->code }}</code></dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Course</dt>
                <dd class="text-sm font-medium text-slate-800">{{ $paper->course }}</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Paper Name</dt>
                <dd class="text-sm font-medium text-slate-800">{{ $paper->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Group</dt>
                <dd class="text-sm text-slate-800 font-mono">{{ $paper->group_code ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Medium</dt>
                <dd class="text-sm text-slate-800">
                    {{ match($paper->medium) {
                        'TM' => 'Telugu',
                        'EM' => 'English',
                        'BM' => 'Bilingual',
                        default => $paper->medium,
                    } }}
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Semester</dt>
                <dd class="text-sm font-mono text-slate-800">{{ $paper->semester }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Scheme</dt>
                <dd class="text-sm font-mono text-slate-800">{{ $paper->scheme }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Paper Type</dt>
                <dd>
                    @if($paper->paper_type === 'elective')
                        <span class="inline-flex items-center bg-purple-50 text-purple-800 text-xs font-medium px-2 py-0.5 rounded-md ring-1 ring-purple-200">Elective</span>
                    @else
                        <span class="inline-flex items-center bg-blue-50 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-md ring-1 ring-blue-200">Compulsory</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Elective Group</dt>
                <dd class="text-sm font-mono text-slate-800">{{ $paper->elective_group ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-slate-500 mb-0.5">Part</dt>
                <dd class="text-sm font-mono text-slate-800">{{ $paper->part }}</dd>
            </div>
        </dl>
    </div>

    {{-- Delete action --}}
    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-3">
        <form method="POST" action="{{ route('admin.papers.destroy', $paper) }}"
              onsubmit="return confirm('Permanently delete paper {{ $paper->code }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 text-sm hover:underline">Delete Paper</button>
        </form>
    </div>
</div>
@endsection
