<?php

namespace Zedlcompany\LaravelRbac\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Zedlcompany\LaravelRbac\Models\Role;

trait HasRoles
{
    /**
     * Get all roles for the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = explode('|', $roles);
        }

        $userRoles = $this->getCachedRoles();

        foreach ($roles as $role) {
            if ($userRoles->contains('slug', trim($role))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all specified roles.
     */
    public function hasAllRoles(array $roles): bool
    {
        $userRoles = $this->getCachedRoles();

        foreach ($roles as $role) {
            if (!$userRoles->contains('slug', trim($role))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('rbac.super_admin_role'));
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(string|Role ...$roles): self
    {
        $roleModels = collect($roles)->map(function ($role) {
            if (is_string($role)) {
                return Role::where('slug', $role)->firstOrFail();
            }
            return $role;
        });

        $this->roles()->syncWithoutDetaching($roleModels->pluck('id'));
        $this->clearRbacCache();

        return $this;
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole(string|Role ...$roles): self
    {
        $roleIds = collect($roles)->map(function ($role) {
            if (is_string($role)) {
                return Role::where('slug', $role)->firstOrFail()->id;
            }
            return $role->id;
        });

        $this->roles()->detach($roleIds);
        $this->clearRbacCache();

        return $this;
    }

    /**
     * Sync roles for the user.
     */
    public function syncRoles(array $roles): self
    {
        $roleIds = collect($roles)->map(function ($role) {
            if (is_string($role)) {
                return Role::where('slug', $role)->firstOrFail()->id;
            }
            if ($role instanceof Role) {
                return $role->id;
            }
            return $role;
        });

        $this->roles()->sync($roleIds);
        $this->clearRbacCache();

        return $this;
    }

    /**
     * Get the highest role level for the user.
     */
    public function getRoleLevel(): int
    {
        return $this->getCachedRoles()->max('level') ?? 0;
    }

    /**
     * Get cached roles.
     */
    protected function getCachedRoles()
    {
        if (!config('rbac.cache.enabled')) {
            return $this->roles()->where('is_active', true)->get();
        }

        $cacheKey = config('rbac.cache.prefix') . 'user_roles_' . $this->id;

        return Cache::remember($cacheKey, config('rbac.cache.ttl'), function () {
            return $this->roles()->where('is_active', true)->get();
        });
    }

    /**
     * Clear RBAC cache for this user.
     */
    public function clearRbacCache(): void
    {
        $prefix = config('rbac.cache.prefix');
        Cache::forget($prefix . 'user_roles_' . $this->id);
        Cache::forget($prefix . 'user_permissions_' . $this->id);
    }
}
