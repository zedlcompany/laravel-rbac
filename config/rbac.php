<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Role
    |--------------------------------------------------------------------------
    |
    | The default role assigned to newly registered users.
    |
    */
    'default_role' => 'user',

    /*
    |--------------------------------------------------------------------------
    | Super Admin Role
    |--------------------------------------------------------------------------
    |
    | The super admin role slug. Users with this role bypass all permission checks.
    |
    */
    'super_admin_role' => 'super-admin',

    /*
    |--------------------------------------------------------------------------
    | Socialite Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for social login integration.
    |
    */
    'socialite' => [
        'enabled' => env('SOCIALITE_ENABLED', true),
        'default_role' => 'user',
        'auto_register' => true,
        'providers' => ['google', 'github'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cache settings for roles and permissions to improve performance.
    |
    */
    'cache' => [
        'enabled' => env('RBAC_CACHE_ENABLED', true),
        'ttl' => 3600, // seconds
        'prefix' => 'rbac_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    |
    | Enable or disable activity logging for RBAC operations.
    |
    */
    'activity_log' => [
        'enabled' => env('RBAC_ACTIVITY_LOG_ENABLED', true),
        'log_name' => 'rbac',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel
    |--------------------------------------------------------------------------
    |
    | Configuration for the admin panel.
    |
    */
    'admin' => [
        'prefix' => 'admin',
        'middleware' => ['web', 'auth', 'role:super-admin|admin'],
        'per_page' => 15,
    ],

];
