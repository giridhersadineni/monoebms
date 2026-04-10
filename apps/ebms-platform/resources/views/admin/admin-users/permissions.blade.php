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

    <div class="mb-5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600">
        <span class="font-medium">Role defaults:</span>
        @if($adminUser->role->value === 'admin')
            Admin role already grants default view, edit, and delete access for core modules, plus view access for pre-exam and grade sheet screens.
        @elseif($adminUser->role->value === 'staff')
            Staff role has default view-only access. Use explicit grants below to add edit, delete, or generate capabilities.
        @else
            Superadmin always has full access. Explicit grants are not applicable.
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
                    $byRole = $adminUser->roleAllows($feature);
                    $explicit = $adminUser->hasPermission($feature);
                    $checked = $byRole || $explicit;
                @endphp
                <label class="flex items-start gap-3 cursor-pointer {{ $byRole ? 'opacity-60' : '' }}">
                    <input type="checkbox"
                           name="permissions[]"
                           value="{{ $feature->value }}"
                           {{ $checked ? 'checked' : '' }}
                           {{ $byRole ? 'disabled' : '' }}
                           class="mt-0.5 w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <p class="text-sm font-medium text-slate-800">{{ $feature->label() }}</p>
                        @if($byRole)
                            <p class="text-xs text-slate-400 mt-0.5">Granted by role defaults and cannot be removed here.</p>
                        @elseif($explicit)
                            <p class="text-xs text-slate-400 mt-0.5">Explicitly granted to this user.</p>
                        @endif
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
            <a href="{{ route('admin.admin-users.index') }}"
               class="text-slate-500 hover:text-slate-700 text-sm hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
