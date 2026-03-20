@extends('layouts.admin')
@section('title', 'Add Admin User')

@section('content')
<div class="max-w-lg">

    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1 text-sm">
            <a href="{{ route('admin.admin-users.index') }}" class="text-slate-400 hover:text-slate-600">Admin Users</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-600">Add User</span>
        </div>
        <h1 class="text-xl font-semibold text-slate-800">Add Admin User</h1>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.admin-users.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                       placeholder="e.g. Ramesh Kumar">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Login ID</label>
                <input type="text" name="login_id" value="{{ old('login_id') }}" required
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm font-mono
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                       placeholder="e.g. ramesh.k">
                <p class="text-xs text-slate-400 mt-1">Used to log in. Must be unique.</p>
                @error('login_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Role</label>
                <select name="role" required
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    <option value="">Select a role…</option>
                    <option value="staff"      {{ old('role') === 'staff'      ? 'selected' : '' }}>Staff — read-only access</option>
                    <option value="admin"      {{ old('role') === 'admin'      ? 'selected' : '' }}>Admin — can edit records</option>
                    <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Superadmin — full access</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                           placeholder="Min 8 characters">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                           class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                           placeholder="Repeat password">
                </div>
            </div>

            <div class="flex items-center gap-2.5 pt-1">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                       class="rounded border-slate-300 text-slate-900 focus:ring-slate-500">
                <label for="is_active" class="text-sm text-slate-700 font-medium">Active (can log in immediately)</label>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">
                    Create User
                </button>
                <a href="{{ route('admin.admin-users.index') }}"
                   class="text-slate-500 hover:text-slate-700 text-sm py-2 hover:underline">Cancel</a>
            </div>

        </form>
    </div>

</div>
@endsection
