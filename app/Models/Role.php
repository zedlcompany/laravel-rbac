<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    /**
     * Get users that belong to this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user')->withTimestamps();
    }

    /**
     * Get permissions that belong to this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role')->withTimestamps();
    }

    /**
     * Assign permissions to this role.
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

        return $this;
    }

    /**
     * Revoke permissions from this role.
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

        return $this;
    }

    /**
     * Sync permissions for this role.
     */
    public function syncPermissions(array $permissions): self
    {
        $permissionIds = collect($permissions)->map(function ($permission) {
            if (is_string($permission)) {
                return Permission::where('slug', $permission)->firstOrFail()->id;
            }
            if ($permission instanceof Permission) {
                return $permission->id;
            }
            return $permission;
        });

        $this->permissions()->sync($permissionIds);

        return $this;
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'description', 'level', 'is_active'])
            ->logOnlyDirty()
            ->useLogName('rbac');
    }

    /**
     * Scope for active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
