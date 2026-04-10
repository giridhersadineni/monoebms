@extends('layouts.admin')
@section('title', $course->code . ' — Groups')

@section('content')
<div class="max-w-4xl">
    {{-- Breadcrumb + header --}}
    <div class="mb-6 flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.courses.index') }}"
                   class="text-slate-400 hover:text-slate-600 text-sm">Courses</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-600 text-sm">{{ $course->code }}</span>
            </div>
            <h1 class="text-xl font-semibold text-slate-800">{{ $course->name }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                <code class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-xs">{{ $course->code }}</code>
                &nbsp;·&nbsp; {{ $course->total_semesters }} semesters
                &nbsp;·&nbsp; <x-status-badge :status="$course->is_active ? 'open' : 'closed'" />
            </p>
        </div>
        @if(auth('admin')->user()->canAccess('courses.manage'))
        <a href="{{ route('admin.courses.edit', $course) }}"
           class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Edit Course
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-4 px-4 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Groups table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-5">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between">
            <p class="font-semibold text-slate-700 text-sm">Groups</p>
            <span class="text-xs text-slate-400">{{ $course->groups->count() }} total</span>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold tracking-wide uppercase border-b border-slate-100">
                    <th class="px-5 py-3 text-left">Code</th>
                    <th class="px-5 py-3 text-left">Name</th>
                    <th class="px-5 py-3 text-left">Mediums</th>
                    <th class="px-5 py-3 text-left">Schemes</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($course->groups as $group)
                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors last:border-0
                           {{ $editingGroup?->id === $group->id ? 'bg-blue-50' : '' }}">
                    <td class="px-5 py-3">
                        <code class="font-mono text-xs text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $group->code }}</code>
                    </td>
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $group->name }}</td>
                    <td class="px-5 py-3 text-slate-500 text-xs">
                        {{ $group->mediums ? implode(', ', $group->mediums) : '—' }}
                    </td>
                    <td class="px-5 py-3 text-slate-500 text-xs">
                        {{ $group->schemes ? implode(', ', $group->schemes) : '—' }}
                    </td>
                    <td class="px-5 py-3">
                        <x-status-badge :status="$group->is_active ? 'open' : 'closed'" />
                    </td>
                    <td class="px-5 py-3">
                        @if(auth('admin')->user()->canAccess('courses.manage'))
                        <div class="flex gap-3">
                            <a href="{{ route('admin.courses.groups.edit', [$course, $group]) }}"
                               class="text-blue-600 hover:underline text-xs font-medium">Edit</a>
                            <form method="POST"
                                  action="{{ route('admin.courses.groups.destroy', [$course, $group]) }}"
                                  onsubmit="return confirm('Delete group {{ $group->code }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-sm">
                        No groups yet. Add one below.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Add / Edit group form --}}
    @if(auth('admin')->user()->canAccess('courses.manage'))
    @php $isEditing = $editingGroup !== null; @endphp
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <h2 class="text-sm font-semibold text-slate-700 mb-4">
            {{ $isEditing ? 'Edit Group: ' . $editingGroup->code : 'Add Group' }}
        </h2>

        <form method="POST"
              action="{{ $isEditing
                  ? route('admin.courses.groups.update', [$course, $editingGroup])
                  : route('admin.courses.groups.store', $course) }}">
            @csrf
            @if($isEditing) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Group Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code"
                           value="{{ old('code', $editingGroup?->code) }}" required
                           placeholder="e.g. GEN, HEP, FINANCE"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono uppercase
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                                  placeholder:font-sans placeholder:normal-case placeholder:text-slate-400">
                    <x-form-error field="code" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Group Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name"
                           value="{{ old('name', $editingGroup?->name) }}" required
                           placeholder="e.g. General, HEP Special"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <x-form-error field="name" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Mediums</label>
                    <div class="flex gap-4 mt-1">
                        @foreach(['TM' => 'Telugu', 'EM' => 'English', 'BM' => 'Bilingual'] as $val => $label)
                        <label class="flex items-center gap-1.5 text-sm text-slate-700 cursor-pointer">
                            <input type="checkbox" name="mediums[]" value="{{ $val }}"
                                   {{ in_array($val, old('mediums', $editingGroup?->mediums ?? [])) ? 'checked' : '' }}
                                   class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    <x-form-error field="mediums" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Schemes</label>
                    <input type="text" name="schemes"
                           value="{{ old('schemes', $editingGroup ? implode(', ', $editingGroup->schemes ?? []) : '') }}"
                           placeholder="e.g. 2016, 2020"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none
                                  placeholder:font-sans placeholder:text-slate-400">
                    <p class="text-xs text-slate-400 mt-1">Comma-separated</p>
                    <x-form-error field="schemes" />
                </div>
            </div>

            <div class="flex items-center gap-2 mb-5">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="group_is_active" value="1"
                       {{ old('is_active', $editingGroup?->is_active ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <label for="group_is_active" class="text-sm text-slate-700 font-medium">Active</label>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                    {{ $isEditing ? 'Save Group' : 'Add Group' }}
                </button>
                @if($isEditing)
                <a href="{{ route('admin.courses.show', $course) }}"
                   class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Cancel</a>
                @endif
            </div>
        </form>
    </div>
    @endif

</div>
@endsection
