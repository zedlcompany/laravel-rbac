<?php

namespace App\Contracts;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface HasPermissionsInterface
{
    /**
     * Get direct permissions for the user.
     */
    public function permissions(): BelongsToMany;

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string|array $permissions): bool;

    /**
     * Check if user has all specified permissions.
     */
    public function hasAllPermissions(array $permissions): bool;

    /**
     * Give direct permission to user.
     */
    public function givePermission(string|Permission ...$permissions): self;

    /**
     * Revoke direct permission from user.
     */
    public function revokePermission(string|Permission ...$permissions): self;

    /**
     * Get all permissions (from roles + direct).
     */
    public function getAllPermissions();

    /**
     * Get permissions grouped by module.
     */
    public function getPermissionsByModule(): array;
}
