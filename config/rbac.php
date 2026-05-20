<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The user model class used by the RBAC system.
    |
    */
    'user_model' => App\Models\User::class,

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

];
