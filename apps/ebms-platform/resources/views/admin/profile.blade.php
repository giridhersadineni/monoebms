@extends('layouts.admin')
@section('title', 'My Profile')

@section('content')
<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">My Profile</h1>
        <p class="text-sm text-slate-500 mt-0.5">Manage your name and password.</p>
    </div>

    {{-- Profile info card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-5">
        <div class="flex items-center gap-4 mb-5 pb-5 border-b border-slate-100">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                <span class="text-blue-700 font-bold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <div>
                <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                <p class="text-sm text-slate-500">{{ $user->login_id }} &middot;
                    <span class="uppercase text-xs font-medium tracking-wide text-slate-400">{{ $user->role->value }}</span>
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Display Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <x-form-error field="name" />
            </div>
            <div class="mb-1">
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Login ID</label>
                <input type="text" value="{{ $user->login_id }}" disabled
                       class="w-full border border-slate-200 rounded-lg px-3.5 py-2 text-sm bg-slate-50 text-slate-400 cursor-not-allowed">
            </div>
            <p class="text-xs text-slate-400 mb-4">Login ID cannot be changed.</p>
            <button type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Save Changes
            </button>
        </form>
    </div>

    {{-- Change password card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <h2 class="text-sm font-semibold text-slate-700 mb-4">Change Password</h2>
        <form method="POST" action="{{ route('admin.profile.password') }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Current Password</label>
                <input type="password" name="current_password" required autocomplete="current-password"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <x-form-error field="current_password" />
            </div>
            <div class="mb-3">
                <label class="block text-xs font-medium text-slate-600 mb-1.5">New Password</label>
                <input type="password" name="password" required autocomplete="new-password"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                <x-form-error field="password" />
            </div>
            <div class="mb-4">
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>
            <button type="submit"
                    class="bg-emerald-700 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Change Password
            </button>
        </form>
    </div>
</div>
@endsection
