<?php

namespace Zedlcompany\LaravelRbac\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zedlcompany\LaravelRbac\Models\Role;

interface HasRolesInterface
{
    /**
     * Get all roles for the user.
     */
    public function roles(): BelongsToMany;

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string|array $roles): bool;

    /**
     * Check if user has all specified roles.
     */
    public function hasAllRoles(array $roles): bool;

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool;

    /**
     * Assign a role to the user.
     */
    public function assignRole(string|Role ...$roles): self;

    /**
     * Remove a role from the user.
     */
    public function removeRole(string|Role ...$roles): self;

    /**
     * Sync roles for the user.
     */
    public function syncRoles(array $roles): self;

    /**
     * Get the highest role level for the user.
     */
    public function getRoleLevel(): int;

    /**
     * Clear RBAC cache for this user.
     */
    public function clearRbacCache(): void;
}
