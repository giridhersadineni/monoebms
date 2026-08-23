@extends('layouts.admin')
@section('title', 'Permissions - ' . $adminUser->name)

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1 text-sm">
            <a href="{{ route('admin.admin-users.index') }}" class="text-slate-400 hover:text-slate-600">Admin Users</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-600">{{ $adminUser->name }}</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-600">Permissions</span>
        </div>
        <h1 class="text-xl font-semibold text-slate-800">Role-Based Permissions</h1>
        <p class="text-sm text-slate-500 mt-0.5">{{ $adminUser->name }} - {{ ucfirst($adminUser->role->value) }}</p>
    </div>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    @php $isExplicitMode = $adminUser->permissions !== null; @endphp
    <div class="mb-5 px-4 py-3 border rounded-xl text-sm
        {{ $isExplicitMode ? 'bg-blue-50 border-blue-200 text-blue-800' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
        @if($adminUser->role->value === 'superadmin')
            Superadmin always has full access. Explicit grants are not applicable.
        @elseif($isExplicitMode)
            <span class="font-medium">Explicit mode:</span> This user's access is controlled entirely by what is checked below. Role defaults do not apply.
        @else
            <span class="font-medium">Role defaults active:</span>
            {{ $adminUser->role->value === 'admin' ? 'Admin role grants view, edit, and delete on all modules.' : 'Staff role grants view-only access to all modules.' }}
            Saving this form will switch to explicit mode.
        @endif
    </div>

    <form method="POST" action="{{ route('admin.admin-users.permissions.save', $adminUser) }}">
        @csrf

        @foreach($grouped as $group => $features)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-4">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">{{ $group }}</p>
            </div>
            <div class="px-5 py-4 space-y-3">
                @foreach($features as $feature)
                @php
                    $checked = $isExplicitMode
                        ? $adminUser->hasPermission($feature)
                        : $adminUser->roleAllows($feature);
                @endphp
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox"
                           name="permissions[]"
                           value="{{ $feature->value }}"
                           {{ $checked ? 'checked' : '' }}
                           class="mt-0.5 w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <p class="text-sm font-medium text-slate-800">{{ $feature->label() }}</p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="flex items-center gap-3 mt-6">
            <button type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                Save Permissions
            </button>
            @if($isExplicitMode)
            <button type="button"
                    onclick="if(confirm('Reset to role defaults? All explicit grants will be removed.')) { document.getElementById('reset-form').submit(); }"
                    class="border border-slate-300 text-slate-600 hover:bg-slate-50 px-4 py-2 rounded-lg text-sm transition-colors">
                Reset to Role Defaults
            </button>
            @endif
            <a href="{{ route('admin.admin-users.index') }}"
               class="text-slate-500 hover:text-slate-700 text-sm hover:underline">Cancel</a>
        </div>
    </form>

    @if($isExplicitMode)
    <form id="reset-form" method="POST" action="{{ route('admin.admin-users.permissions.save', $adminUser) }}">
        @csrf
        {{-- Submit with no permissions[] → saves null, restoring role defaults --}}
        <input type="hidden" name="_reset" value="1">
    </form>
    @endif
</div>
@endsection
