# Laravel RBAC

A flexible Role-Based Access Control (RBAC) package for Laravel with roles, permissions, caching, and Blade directives.

## Installation

```bash
composer require zedlcompany/laravel-rbac
```

## Publish Config & Migrations

```bash
php artisan vendor:publish --tag=rbac-config
php artisan vendor:publish --tag=rbac-migrations
php artisan migrate
```

## Setup

Add the traits to your `User` model:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zedlcompany\LaravelRbac\Contracts\HasPermissionsInterface;
use Zedlcompany\LaravelRbac\Contracts\HasRolesInterface;
use Zedlcompany\LaravelRbac\Traits\HasPermissions;
use Zedlcompany\LaravelRbac\Traits\HasRoles;

class User extends Authenticatable implements HasRolesInterface, HasPermissionsInterface
{
    use HasRoles, HasPermissions;
}
```

## Configuration

After publishing, edit `config/rbac.php`:

```php
return [
    'user_model' => App\Models\User::class,
    'default_role' => 'user',
    'super_admin_role' => 'super-admin',
    'cache' => [
        'enabled' => env('RBAC_CACHE_ENABLED', true),
        'ttl' => 3600,
        'prefix' => 'rbac_',
    ],
];
```

## Usage

### Assigning Roles

```php
$user->assignRole('admin');
$user->assignRole('admin', 'editor');
$user->removeRole('editor');
$user->syncRoles(['admin', 'manager']);
```

### Checking Roles

```php
$user->hasRole('admin');           // single role
$user->hasRole('admin|editor');    // any of these roles
$user->hasAllRoles(['admin', 'editor']); // all roles
$user->isSuperAdmin();
```

### Assigning Permissions

```php
// Direct permissions to user
$user->givePermission('users.create');
$user->revokePermission('users.create');

// Permissions to role
$role->givePermission('users.create', 'users.edit');
$role->revokePermission('users.delete');
$role->syncPermissions(['users.view', 'users.create']);
```

### Checking Permissions

```php
$user->hasPermission('users.create');
$user->hasPermission('users.create|users.edit'); // any
$user->hasAllPermissions(['users.create', 'users.edit']); // all
$user->getAllPermissions();
$user->getPermissionsByModule();
```

### Middleware

```php
// In routes
Route::middleware('role:admin')->group(function () {
    // ...
});

Route::middleware('permission:users.create')->group(function () {
    // ...
});

// Multiple roles (any)
Route::middleware('role:admin|manager')->group(function () {
    // ...
});
```

### Blade Directives

```blade
@role('admin')
    <p>You are an admin</p>
@endrole

@permission('users.create')
    <button>Create User</button>
@endpermission

@anyrole(['admin', 'manager'])
    <p>You are admin or manager</p>
@endanyrole

@allroles(['admin', 'manager'])
    <p>You have both roles</p>
@endallroles

@superadmin
    <p>You are super admin</p>
@endsuperadmin
```

### Super Admin

Users with the `super-admin` role (configurable) automatically bypass all permission checks and Gate checks.

### Caching

Roles and permissions are cached per-user. Cache is automatically cleared when:

- A user's roles or permissions are modified
- A role is updated or deleted
- Role permissions are synced

You can manually clear cache:

```php
$user->clearRbacCache();
```

Or disable caching via environment variable:

```env
RBAC_CACHE_ENABLED=false
```

## License

MIT
