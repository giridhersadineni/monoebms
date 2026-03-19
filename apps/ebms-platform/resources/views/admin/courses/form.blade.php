@extends('layouts.admin')
@section('title', $course ? 'Edit ' . $course->code : 'New Course')

@section('content')
<div class="max-w-lg">
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.courses.index') }}"
               class="text-slate-400 hover:text-slate-600 text-sm">Courses</a>
            @if($course)
                <span class="text-slate-300">/</span>
                <a href="{{ route('admin.courses.show', $course) }}"
                   class="text-slate-400 hover:text-slate-600 text-sm">{{ $course->code }}</a>
            @endif
            <span class="text-slate-300">/</span>
            <span class="text-slate-600 text-sm">{{ $course ? 'Edit' : 'New' }}</span>
        </div>
        <h1 class="text-xl font-semibold text-slate-800">{{ $course ? 'Edit Course' : 'New Course' }}</h1>
    </div>

    <form method="POST"
          action="{{ $course ? route('admin.courses.update', $course) : route('admin.courses.store') }}"
          class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">
        @csrf
        @if($course) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Code <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="{{ old('code', $course?->code) }}" required
                       placeholder="e.g. BA, BSC, BCOM"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono uppercase
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                              placeholder:font-sans placeholder:normal-case placeholder:text-slate-400">
                <x-form-error field="code" />
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Total Semesters <span class="text-red-500">*</span></label>
                <input type="number" name="total_semesters"
                       value="{{ old('total_semesters', $course?->total_semesters ?? 6) }}"
                       min="1" max="10" required
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <x-form-error field="total_semesters" />
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Course Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $course?->name) }}" required
                   placeholder="e.g. Bachelor of Arts"
                   class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            <x-form-error field="name" />
        </div>

        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $course?->is_active ?? true) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <label for="is_active" class="text-sm text-slate-700 font-medium">Active</label>
        </div>

        <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
            <button type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                {{ $course ? 'Save Changes' : 'Create Course' }}
            </button>
            <a href="{{ $course ? route('admin.courses.show', $course) : route('admin.courses.index') }}"
               class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
