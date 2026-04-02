@extends('layouts.admin')
@section('title', 'Admin Users')

@section('content')
<div class="max-w-4xl">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Admin Users</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $users->count() }} users</p>
        </div>
        <a href="{{ route('admin.admin-users.create') }}"
           class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            + Add User
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 font-semibold uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-2.5 text-left">Name</th>
                    <th class="px-5 py-2.5 text-left">Login ID</th>
                    <th class="px-5 py-2.5 text-left">Role</th>
                    <th class="px-5 py-2.5 text-left">Status</th>
                    <th class="px-5 py-2.5 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                @php
                    $isSelf = $user->id === auth('admin')->id();
                    $roleColor = match($user->role->value) {
                        'superadmin' => 'bg-purple-100 text-purple-700',
                        'admin'      => 'bg-blue-100 text-blue-700',
                        default      => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3 font-medium text-slate-800">
                        {{ $user->name }}
                        @if($isSelf)
                            <span class="ml-1.5 text-xs text-slate-400">(you)</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 font-mono text-xs text-slate-600 bg-slate-50">{{ $user->login_id }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $roleColor }}">
                            {{ ucfirst($user->role->value) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <x-status-badge :status="$user->is_active ? 'active' : 'inactive'" />
                    </td>
                    <td class="px-5 py-3">
                        @if(! $isSelf)
                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('admin.admin-users.toggle-active', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-xs font-medium {{ $user->is_active ? 'text-red-500 hover:text-red-700' : 'text-emerald-600 hover:text-emerald-800' }} transition-colors">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <button type="button" data-show-pw="{{ $user->id }}"
                                    class="text-xs font-medium text-slate-500 hover:text-slate-800 transition-colors">
                                Reset Password
                            </button>
                        </div>
                        @endif
                    </td>
                </tr>

                {{-- Reset Password Modal --}}
                @if(! $isSelf)
                <tr id="pw-modal-{{ $user->id }}" class="hidden">
                    <td colspan="5" class="px-5 py-4 bg-slate-50 border-b border-slate-100">
                        <form method="POST" action="{{ route('admin.admin-users.reset-password', $user) }}"
                              class="flex items-end gap-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">New Password for {{ $user->name }}</label>
                                <input type="password" name="password" required minlength="8"
                                       placeholder="Min 8 characters"
                                       class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Confirm</label>
                                <input type="password" name="password_confirmation" required minlength="8"
                                       placeholder="Repeat password"
                                       class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <button type="submit"
                                    class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors">
                                Save
                            </button>
                            <button type="button" data-hide-pw="{{ $user->id }}"
                                    class="text-sm text-slate-500 hover:text-slate-700">
                                Cancel
                            </button>
                        </form>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<script nonce="{{ $csp_nonce ?? '' }}">
document.querySelectorAll('[data-show-pw]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('pw-modal-' + btn.dataset.showPw).classList.remove('hidden');
    });
});
document.querySelectorAll('[data-hide-pw]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('pw-modal-' + btn.dataset.hidePw).classList.add('hidden');
    });
});
</script>
@endsection
