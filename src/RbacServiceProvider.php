<?php

namespace Zedlcompany\LaravelRbac;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Zedlcompany\LaravelRbac\Middleware\CheckPermission;
use Zedlcompany\LaravelRbac\Middleware\CheckRole;
use Zedlcompany\LaravelRbac\Models\Role;
use Zedlcompany\LaravelRbac\Observers\RoleObserver;

class RbacServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/rbac.php',
            'rbac'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerObservers();
        $this->registerBladeDirectives();
        $this->registerSuperAdminGate();
        $this->registerMiddlewareAliases();
    }

    /**
     * Register publishing for config and migrations.
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__ . '/../config/rbac.php' => config_path('rbac.php'),
            ], 'rbac-config');

            // Publish migrations
            $this->publishesMigrations([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'rbac-migrations');
        }
    }

    /**
     * Register model observers.
     */
    protected function registerObservers(): void
    {
        Role::observe(RoleObserver::class);
    }

    /**
     * Super admin bypasses all Gate checks.
     */
    protected function registerSuperAdminGate(): void
    {
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }
        });
    }

    /**
     * Register middleware aliases.
     */
    protected function registerMiddlewareAliases(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('role', CheckRole::class);
        $router->aliasMiddleware('permission', CheckPermission::class);
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
