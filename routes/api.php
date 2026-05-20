<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

Route::middleware('auth:sanctum')->group(function () {

    // Current user
    Route::get('/user', function (Request $request) {
        return $request->user()->load('roles', 'permissions');
    })->name('api.user');

    // User roles & permissions
    Route::get('/user/permissions', function (Request $request) {
        return response()->json([
            'permissions' => $request->user()->getAllPermissions()->pluck('slug'),
            'roles' => $request->user()->roles->pluck('slug'),
        ]);
    })->name('api.user.permissions');

    // Admin API routes (requires role)
    Route::middleware('role:super-admin|admin')->prefix('admin')->name('api.admin.')->group(function () {

        // Users
        Route::get('/users', function () {
            return \App\Models\User::with('roles')->paginate(15);
        })->name('users.index');

        // Roles
        Route::get('/roles', function () {
            return \App\Models\Role::with('permissions')->get();
        })->name('roles.index');

        // Permissions
        Route::get('/permissions', function () {
            return \App\Models\Permission::all()->groupBy('module');
        })->name('permissions.index');
    });
});
