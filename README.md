# Laravel RBAC Template

A scalable, production-ready Role-Based Access Control (RBAC) template built with Laravel 12. Designed to be easily integrated into any project with built-in Socialite authentication, activity logging, and a clean admin panel.

## Features

- **Multi-Role System** — Users can have multiple roles simultaneously
- **Hierarchical Roles** — Roles have levels (Super Admin > Admin > Manager > Editor > User)
- **Module-based Permissions** — Permissions grouped by module (e.g., `users.create`, `posts.delete`)
- **Direct Permission Assignment** — Assign permissions directly to users, bypassing roles
- **Super Admin Bypass** — Super admin role automatically bypasses all permission checks
- **Socialite Integration** — Pre-configured Google & GitHub login (easily extendable)
- **Activity Log & Audit Trail** — Track all changes with Spatie Activity Log
- **Admin Panel** — Clean, responsive admin UI with Tailwind CSS + Alpine.js
- **API Ready** — Laravel Sanctum for token-based API authentication
- **Caching** — Built-in role/permission caching for performance
- **Blade Directives** — `@role`, `@permission`, `@superadmin` directives
- **Middleware** — `role:admin`, `permission:users.create` route middleware
- **Artisan Commands** — Quick setup with `php artisan rbac:setup`

## Requirements

- PHP 8.2+
- Composer
- SQLite / MySQL / PostgreSQL
- Node.js (optional, for asset compilation)

## Quick Start

```bash
# Clone the repository
git clone https://github.com/zedlcompany/laravel-rbac.git
cd laravel-rbac

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run the RBAC setup (migrations + seeders + admin user)
php artisan rbac:setup

# Start the development server
php artisan serve
```

**Default Admin Credentials:**

- Email: `admin@example.com`
- Password: `password`

## Configuration

All RBAC settings are in `config/rbac.php`:

```php
return [
    'default_role' => 'user',
    'super_admin_role' => 'super-admin',

    'socialite' => [
        'enabled' => true,
        'default_role' => 'user',
        'auto_register' => true,
        'providers' => ['google', 'github'],
    ],

    'cache' => [
        'enabled' => true,
        'ttl' => 3600,
    ],

    'activity_log' => [
        'enabled' => true,
        'log_name' => 'rbac',
    ],

    'admin' => [
        'prefix' => 'admin',
        'middleware' => ['web', 'auth', 'role:super-admin|admin'],
        'per_page' => 15,
    ],
];
```

## Usage

### Assigning Roles

```php
// Assign a role
$user->assignRole('admin');
$user->assignRole('editor', 'manager');

// Remove a role
$user->removeRole('editor');

// Sync roles (replaces all existing)
$user->syncRoles(['admin', 'editor']);

// Check role
$user->hasRole('admin');           // true/false
$user->hasRole('admin|editor');    // has any
$user->hasAllRoles(['admin', 'editor']); // has all
$user->isSuperAdmin();             // true/false
```

### Working with Permissions

```php
// Give direct permission
$user->givePermission('users.create');

// Revoke permission
$user->revokePermission('users.create');

// Check permission (checks roles + direct)
$user->hasPermission('users.create');
$user->hasPermission('users.create|users.edit'); // has any
$user->hasAllPermissions(['users.create', 'users.edit']);

// Get all permissions
$user->getAllPermissions();
$user->getPermissionsByModule();
```

### Role Permissions

```php
// Assign permissions to a role
$role = Role::where('slug', 'editor')->first();
$role->givePermission('posts.create', 'posts.edit');
$role->syncPermissions(['posts.create', 'posts.edit', 'posts.delete']);
$role->revokePermission('posts.delete');
```

### Middleware

```php
// In routes
Route::middleware('role:admin')->group(function () {
    // Only admin can access
});

Route::middleware('permission:users.create')->group(function () {
    // Only users with 'users.create' permission
});

Route::middleware('role:admin|manager')->group(function () {
    // Admin OR Manager can access
});
```

### Blade Directives

```blade
@role('admin')
    <p>You are an admin!</p>
@endrole

@permission('users.create')
    <a href="/users/create">Create User</a>
@endpermission

@superadmin
    <p>Full system access</p>
@endsuperadmin

@anyrole(['admin', 'manager'])
    <p>Admin or Manager content</p>
@endanyrole
```

## Socialite Setup

### Google

1. Create OAuth credentials at [Google Cloud Console](https://console.cloud.google.com/)
2. Add to `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback
```

### GitHub

1. Create OAuth App at [GitHub Developer Settings](https://github.com/settings/developers)
2. Add to `.env`:

```env
GITHUB_CLIENT_ID=your-client-id
GITHUB_CLIENT_SECRET=your-client-secret
GITHUB_REDIRECT_URI=${APP_URL}/auth/github/callback
```

### Adding More Providers

1. Add the provider to `config/rbac.php`:

```php
'socialite' => [
    'providers' => ['google', 'github', 'facebook'],
],
```

2. Add credentials to `config/services.php`:

```php
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
],
```

3. Install the provider package if needed (some require additional packages).

### Login URLs

```
GET /auth/google/redirect   → Redirects to Google
GET /auth/google/callback   → Handles callback
GET /auth/github/redirect   → Redirects to GitHub
GET /auth/github/callback   → Handles callback
```

## Admin Panel

Access the admin panel at `/admin` (requires `super-admin` or `admin` role).

### Features:

- **Dashboard** — Overview stats and recent activity
- **Users** — CRUD, role assignment, search & filter
- **Roles** — CRUD, permission assignment, hierarchical levels
- **Permissions** — CRUD, module-based grouping
- **Activity Log** — Full audit trail with filtering

## Database Structure

```
users
├── id, name, email, password, avatar, provider, provider_id
├── email_verified_at, remember_token, timestamps

roles
├── id, name, slug, description, level, is_active, timestamps

permissions
├── id, name, slug, description, module, timestamps

role_user (pivot)
├── id, role_id, user_id, timestamps

permission_role (pivot)
├── id, permission_id, role_id, timestamps

permission_user (pivot)
├── id, permission_id, user_id, timestamps

activity_log (spatie)
├── id, log_name, description, subject_type, subject_id
├── causer_type, causer_id, properties, event, batch_uuid, timestamps
```

## Default Roles

| Role        | Slug          | Level | Description                                |
| ----------- | ------------- | ----- | ------------------------------------------ |
| Super Admin | `super-admin` | 100   | Full system access, bypasses all checks    |
| Admin       | `admin`       | 80    | Administrative access with all permissions |
| Manager     | `manager`     | 60    | Management level with limited permissions  |
| Editor      | `editor`      | 40    | Content editing access                     |
| User        | `user`        | 10    | Standard user access                       |

## Artisan Commands

```bash
# Full setup (migrations + seeds + admin user)
php artisan rbac:setup

# Fresh setup (drops all tables first)
php artisan rbac:setup --fresh

# Custom admin credentials
php artisan rbac:setup --admin-email=admin@myapp.com --admin-password=secret123
```

## Extending

### Adding New Permissions

Add to `database/seeders/PermissionSeeder.php`:

```php
['name' => 'Create Posts', 'slug' => 'posts.create', 'module' => 'posts', 'description' => 'Create new posts'],
['name' => 'Edit Posts', 'slug' => 'posts.edit', 'module' => 'posts', 'description' => 'Edit existing posts'],
```

Then run: `php artisan db:seed --class=PermissionSeeder`

### Adding New Roles

Add to `database/seeders/RoleSeeder.php`:

```php
[
    'name' => 'Content Manager',
    'slug' => 'content-manager',
    'description' => 'Manages all content',
    'level' => 50,
    'is_active' => true,
],
```

### Custom Middleware

You can create custom middleware combining roles and permissions:

```php
Route::middleware(['auth', 'role:admin', 'permission:users.create'])->group(function () {
    // Must be admin AND have users.create permission
});
```

## API Authentication

This template includes Laravel Sanctum for API authentication:

```bash
# Create a token
$token = $user->createToken('api-token')->plainTextToken;

# Use in requests
Authorization: Bearer {token}
```

## Tech Stack

- **Laravel 12** — PHP Framework
- **Tailwind CSS** — Utility-first CSS (via CDN)
- **Alpine.js** — Lightweight JS framework
- **Laravel Socialite** — Social authentication
- **Laravel Sanctum** — API authentication
- **Spatie Activity Log** — Audit trail

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
