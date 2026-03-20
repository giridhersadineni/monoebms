<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminRole;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = AdminUser::orderBy('role')->orderBy('name')->get();
        return view('admin.admin-users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.admin-users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login_id' => ['required', 'string', 'max:50', 'unique:admin_users,login_id'],
            'name'     => ['required', 'string', 'max:80'],
            'role'     => ['required', Rule::enum(AdminRole::class)],
            'password' => ['required', 'confirmed', Password::min(8)],
            'is_active' => ['boolean'],
        ]);

        AdminUser::create([
            'login_id'  => $validated['login_id'],
            'name'      => $validated['name'],
            'role'      => $validated['role'],
            'password'  => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin user created successfully.');
    }

    public function toggleActive(AdminUser $adminUser): RedirectResponse
    {
        $adminUser->update(['is_active' => ! $adminUser->is_active]);

        return back()->with('success', 'User ' . ($adminUser->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function resetPassword(Request $request, AdminUser $adminUser): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $adminUser->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password reset successfully.');
    }
}
