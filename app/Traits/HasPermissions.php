<?php

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    /**
     * Get direct permissions for the user.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user')->withTimestamps();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string|array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (is_string($permissions)) {
            $permissions = explode('|', $permissions);
        }

        $userPermissions = $this->getAllPermissions();

        foreach ($permissions as $permission) {
            if ($userPermissions->contains('slug', trim($permission))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all specified permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $userPermissions = $this->getAllPermissions();

        foreach ($permissions as $permission) {
            if (!$userPermissions->contains('slug', trim($permission))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Give direct permission to user.
     */
    public function givePermission(string|Permission ...$permissions): self
    {
        $permissionModels = collect($permissions)->map(function ($permission) {
            if (is_string($permission)) {
                return Permission::where('slug', $permission)->firstOrFail();
            }
            return $permission;
        });

        $this->permissions()->syncWithoutDetaching($permissionModels->pluck('id'));
        $this->clearRbacCache();

        return $this;
    }

    /**
     * Revoke direct permission from user.
     */
    public function revokePermission(string|Permission ...$permissions): self
    {
        $permissionIds = collect($permissions)->map(function ($permission) {
            if (is_string($permission)) {
                return Permission::where('slug', $permission)->firstOrFail()->id;
            }
            return $permission->id;
        });

        $this->permissions()->detach($permissionIds);
        $this->clearRbacCache();

        return $this;
    }

    /**
     * Get all permissions (from roles + direct).
     */
    public function getAllPermissions()
    {
        if (!config('rbac.cache.enabled')) {
            return $this->computeAllPermissions();
        }

        $cacheKey = config('rbac.cache.prefix') . 'user_permissions_' . $this->id;

        return Cache::remember($cacheKey, config('rbac.cache.ttl'), function () {
            return $this->computeAllPermissions();
        });
    }

    /**
     * Compute all permissions from roles and direct assignments.
     */
    protected function computeAllPermissions()
    {
        // Get permissions from roles
        $rolePermissions = $this->roles()
            ->where('is_active', true)
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');

        // Get direct permissions
        $directPermissions = $this->permissions()->get();

        // Merge and return unique
        return $rolePermissions->merge($directPermissions)->unique('id');
    }

    /**
     * Get permissions grouped by module.
     */
    public function getPermissionsByModule(): array
    {
        return $this->getAllPermissions()
            ->groupBy('module')
            ->toArray();
    }
}
