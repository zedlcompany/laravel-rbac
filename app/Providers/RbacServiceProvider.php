<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Observers\RoleObserver;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * RBAC Service Provider
 *
 * Registers observers, policies, Gate rules, and Blade directives
 * for the RBAC system. Middleware aliases are registered in bootstrap/app.php.
 */

class RbacServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            config_path('rbac.php'),
            'rbac'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerObservers();
        $this->registerPolicies();
        $this->registerBladeDirectives();
        $this->registerSuperAdminGate();
    }

    /**
     * Register model observers.
     */
    protected function registerObservers(): void
    {
        Role::observe(RoleObserver::class);
    }

    /**
     * Register policies.
     */
    protected function registerPolicies(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
    }

    /**
     * Super admin bypasses all Gate checks.
     */
    protected function registerSuperAdminGate(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
    }

    /**
     * Register custom Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        // @role('admin')
        Blade::if('role', function (string $roles) {
            return auth()->check() && auth()->user()->hasRole($roles);
        });

        // @permission('users.create')
        Blade::if('permission', function (string $permissions) {
            return auth()->check() && auth()->user()->hasPermission($permissions);
        });

        // @anyrole(['admin', 'manager'])
        Blade::if('anyrole', function (array $roles) {
            return auth()->check() && auth()->user()->hasRole($roles);
        });

        // @allroles(['admin', 'manager'])
        Blade::if('allroles', function (array $roles) {
            return auth()->check() && auth()->user()->hasAllRoles($roles);
        });

        // @superadmin
        Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->isSuperAdmin();
        });
    }
}
