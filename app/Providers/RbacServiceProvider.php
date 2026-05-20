<?php

namespace App\Providers;

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        $this->registerMiddleware();
        $this->registerBladeDirectives();
    }

    /**
     * Register RBAC middleware aliases.
     */
    protected function registerMiddleware(): void
    {
        Route::aliasMiddleware('role', CheckRole::class);
        Route::aliasMiddleware('permission', CheckPermission::class);
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
