<?php

namespace App\Observers;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class RoleObserver
{
    /**
     * Handle the Role "updated" event.
     * Clear cache for all users that have this role.
     */
    public function updated(Role $role): void
    {
        $this->clearUsersCache($role);
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        $this->clearUsersCache($role);
    }

    /**
     * Handle pivot (permissions) attached/detached.
     * Called manually from RoleController when permissions are synced.
     */
    public static function clearCacheForRole(Role $role): void
    {
        $prefix = config('rbac.cache.prefix', 'rbac_');

        $role->users()->pluck('users.id')->each(function ($userId) use ($prefix) {
            Cache::forget($prefix . 'user_roles_' . $userId);
            Cache::forget($prefix . 'user_permissions_' . $userId);
        });
    }

    /**
     * Clear cache for all users associated with this role.
     */
    protected function clearUsersCache(Role $role): void
    {
        static::clearCacheForRole($role);
    }
}
