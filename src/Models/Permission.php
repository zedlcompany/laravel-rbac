<?php

namespace Zedlcompany\LaravelRbac\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'module',
    ];

    /**
     * Get roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role')->withTimestamps();
    }

    /**
     * Get users that have this permission directly.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('rbac.user_model', 'App\\Models\\User'),
            'permission_user'
        )->withTimestamps();
    }

    /**
     * Scope to filter by module.
     */
    public function scopeModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Get all available modules.
     */
    public static function getModules(): array
    {
        return static::distinct('module')->pluck('module')->toArray();
    }
}
