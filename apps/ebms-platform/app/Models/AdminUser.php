<?php

namespace App\Models;

use App\Enums\AdminFeature;
use App\Enums\AdminRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use HasFactory;
    protected $table = 'admin_users';

    protected $fillable = ['login_id', 'name', 'password', 'role', 'is_active', 'permissions'];

    protected $hidden = ['password'];

    protected $casts = [
        'password'    => 'hashed',
        'role'        => AdminRole::class,
        'is_active'   => 'boolean',
        'permissions' => 'array',
    ];

    public function isStaff(): bool
    {
        return $this->role === AdminRole::Staff;
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role->value, $roles, true);
    }

    /** True if the user has an explicit feature grant saved in their permissions column. */
    public function hasPermission(string|AdminFeature $feature): bool
    {
        $slug = $feature instanceof AdminFeature ? $feature->value : $feature;
        $slug = $this->normalizeLegacyRequest($slug);
        $permissions = $this->permissions ?? [];

        if (in_array($slug, $permissions, true)) {
            return true;
        }

        foreach ($this->legacyAliasesFor($slug) as $alias) {
            if (in_array($alias, $permissions, true)) {
                return true;
            }
        }

        return false;
    }

    /** True if the user's role grants this feature by default (no explicit grant needed). */
    public function roleAllows(string|AdminFeature $feature): bool
    {
        $slug   = $feature instanceof AdminFeature ? $feature->value : $feature;
        $slug   = $this->normalizeLegacyRequest($slug);
        $feat   = AdminFeature::tryFrom($slug);
        return $feat !== null && in_array($this->role->value, $feat->defaultRoles(), true);
    }

    /**
     * True if this user may perform the given feature action.
     * Superadmin always passes. Others pass if their role or an explicit grant covers the feature.
     */
    public function canAccess(string|AdminFeature $feature): bool
    {
        if ($this->role === AdminRole::Superadmin) {
            return true;
        }
        return $this->roleAllows($feature) || $this->hasPermission($feature);
    }

    public function canAccessAny(array $features): bool
    {
        foreach ($features as $feature) {
            if ($this->canAccess($feature)) {
                return true;
            }
        }

        return false;
    }

    private function legacyAliasesFor(string $slug): array
    {
        return match ($slug) {
            'students.view', 'students.edit', 'students.delete' => ['students.manage'],
            'enrollments.view', 'enrollments.edit', 'enrollments.delete' => ['enrollments.manage'],
            'exams.view', 'exams.edit', 'exams.delete' => ['exams.manage'],
            'courses.view', 'courses.edit', 'courses.delete' => ['courses.manage'],
            'papers.view', 'papers.edit', 'papers.delete' => ['papers.manage'],
            'gradesheets.view' => ['gradesheets.generate'],
            default => [],
        };
    }

    private function normalizeLegacyRequest(string $slug): string
    {
        return match ($slug) {
            'students.manage' => 'students.edit',
            'enrollments.manage' => 'enrollments.edit',
            'exams.manage' => 'exams.edit',
            'courses.manage' => 'courses.edit',
            'papers.manage' => 'papers.edit',
            default => $slug,
        };
    }
}
