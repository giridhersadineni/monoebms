@extends('layouts.admin')
@section('title', 'Edit Fee Rule — ' . $exam->name)

@section('content')
<div class="max-w-2xl">

    {{-- Breadcrumb + header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1 text-sm">
            <a href="{{ route('admin.exams.index') }}" class="text-slate-400 hover:text-slate-600">Exams</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.exams.show', $exam) }}" class="text-slate-400 hover:text-slate-600">{{ $exam->name }}</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.exams.fee-rules.index', $exam) }}" class="text-slate-400 hover:text-slate-600">Fee Rules</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-600">Edit</span>
        </div>
        <h1 class="text-xl font-semibold text-slate-800">Edit Fee Rule</h1>
        <p class="text-sm text-slate-500 mt-0.5">
            {{ $exam->course ?? 'All Courses' }} · Semester {{ $exam->semester }} ·
            {{ $exam->month_name }} {{ $exam->year }}
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">

        {{-- Rule identity (read-only) --}}
        <div class="mb-5 p-4 bg-slate-50 rounded-lg border border-slate-200">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Applies to</p>
            <div class="flex gap-6 text-sm">
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Course</p>
                    <p class="font-semibold text-slate-700">{{ $rule->course ?? 'All Courses' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Group</p>
                    <p class="font-semibold text-slate-700">{{ $rule->group_code ?? 'All Groups' }}</p>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-3">Course and group cannot be changed. Delete this rule and create a new one if you need a different scope.</p>
        </div>

        <form method="POST" action="{{ route('admin.exams.fee-rules.update', [$exam, $rule]) }}" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Regular Fee (Rs.)</label>
                    <input type="number" name="fee_regular" min="0"
                           value="{{ old('fee_regular', $rule->fee_regular) }}"
                           placeholder="Leave blank to use exam default"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <p class="text-xs text-slate-400 mt-1">
                        @if($exam->fee_regular !== null)
                            Exam default: Rs.{{ number_format($exam->fee_regular) }}
                        @else
                            No exam default set
                        @endif
                    </p>
                    @error('fee_regular') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Supply ≤2 Fee (Rs.)</label>
                    <input type="number" name="fee_supply_upto2" min="0"
                           value="{{ old('fee_supply_upto2', $rule->fee_supply_upto2) }}"
                           placeholder="Leave blank to use exam default"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <p class="text-xs text-slate-400 mt-1">
                        @if($exam->fee_supply_upto2 !== null)
                            Exam default: Rs.{{ number_format($exam->fee_supply_upto2) }}
                        @else
                            No exam default set
                        @endif
                    </p>
                    @error('fee_supply_upto2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Improvement Fee (Rs. / paper)</label>
                    <input type="number" name="fee_improvement" min="0"
                           value="{{ old('fee_improvement', $rule->fee_improvement) }}"
                           placeholder="Leave blank to use exam default"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <p class="text-xs text-slate-400 mt-1">
                        @if($exam->fee_improvement)
                            Exam default: Rs.{{ number_format($exam->fee_improvement) }}/paper
                        @else
                            No exam default set
                        @endif
                    </p>
                    @error('fee_improvement') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Late Fine (Rs.)</label>
                    <input type="number" name="fee_fine" min="0"
                           value="{{ old('fee_fine', $rule->fee_fine) }}"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    @error('fee_fine') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.exams.fee-rules.index', $exam) }}"
                   class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Cancel</a>

                {{-- Delete --}}
                <form method="POST"
                      action="{{ route('admin.exams.fee-rules.destroy', [$exam, $rule]) }}"
                      class="ml-auto">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors">
                        Delete rule
                    </button>
                </form>
            </div>
        </form>
    </div>

</div>
@endsection
