<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminFeature;
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

    public function permissions(AdminUser $adminUser): View
    {
        $grouped = AdminFeature::grouped();
        return view('admin.admin-users.permissions', compact('adminUser', 'grouped'));
    }

    public function savePermissions(Request $request, AdminUser $adminUser): RedirectResponse
    {
        $validSlugs = collect(AdminFeature::cases())->map(fn($f) => $f->value)->all();

        if ($request->input('_reset')) {
            $adminUser->update(['permissions' => null]);
            return redirect()->route('admin.admin-users.permissions', $adminUser)
                ->with('success', "Permissions reset to role defaults for {$adminUser->name}.");
        }

        // Save exactly what was checked — switches user to explicit mode (role defaults no longer apply)
        $permissions = collect($request->input('permissions', []))
            ->filter(fn($v) => in_array($v, $validSlugs, true))
            ->values()
            ->all();

        $adminUser->update(['permissions' => $permissions]);

        return redirect()->route('admin.admin-users.permissions', $adminUser)
            ->with('success', "Permissions updated for {$adminUser->name}.");
    }
}
