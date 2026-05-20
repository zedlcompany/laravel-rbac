<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Role $adminRole;
    protected Permission $createUsersPermission;
    protected Permission $editUsersPermission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'level' => 80,
            'is_active' => true,
        ]);

        $this->createUsersPermission = Permission::create([
            'name' => 'Create Users',
            'slug' => 'users.create',
            'module' => 'users',
        ]);

        $this->editUsersPermission = Permission::create([
            'name' => 'Edit Users',
            'slug' => 'users.edit',
            'module' => 'users',
        ]);

        $this->user = User::factory()->create();
    }

    public function test_can_give_direct_permission(): void
    {
        $this->user->givePermission('users.create');

        $this->assertTrue($this->user->hasPermission('users.create'));
        $this->assertFalse($this->user->hasPermission('users.edit'));
    }

    public function test_can_revoke_permission(): void
    {
        $this->user->givePermission('users.create', 'users.edit');
        $this->user->revokePermission('users.create');

        $this->assertFalse($this->user->hasPermission('users.create'));
        $this->assertTrue($this->user->hasPermission('users.edit'));
    }

    public function test_permission_from_role(): void
    {
        $this->adminRole->givePermission('users.create', 'users.edit');
        $this->user->assignRole('admin');
        $this->user->clearRbacCache();

        $this->assertTrue($this->user->hasPermission('users.create'));
        $this->assertTrue($this->user->hasPermission('users.edit'));
    }

    public function test_has_permission_with_pipe_separator(): void
    {
        $this->user->givePermission('users.create');

        $this->assertTrue($this->user->hasPermission('users.create|users.edit'));
        $this->assertFalse($this->user->hasPermission('users.delete|roles.create'));
    }

    public function test_has_all_permissions(): void
    {
        $this->user->givePermission('users.create', 'users.edit');

        $this->assertTrue($this->user->hasAllPermissions(['users.create', 'users.edit']));
        $this->assertFalse($this->user->hasAllPermissions(['users.create', 'users.delete']));
    }

    public function test_super_admin_bypasses_permission_check(): void
    {
        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'level' => 100,
            'is_active' => true,
        ]);

        $this->user->assignRole('super-admin');

        $this->assertTrue($this->user->hasPermission('users.create'));
        $this->assertTrue($this->user->hasPermission('any.permission'));
        $this->assertTrue($this->user->hasAllPermissions(['users.create', 'users.edit']));
    }

    public function test_get_all_permissions_merges_role_and_direct(): void
    {
        $this->adminRole->givePermission('users.create');
        $this->user->assignRole('admin');
        $this->user->givePermission('users.edit');
        $this->user->clearRbacCache();

        $allPermissions = $this->user->getAllPermissions();

        $this->assertTrue($allPermissions->contains('slug', 'users.create'));
        $this->assertTrue($allPermissions->contains('slug', 'users.edit'));
    }

    public function test_get_permissions_by_module(): void
    {
        $this->user->givePermission('users.create', 'users.edit');
        $this->user->clearRbacCache();

        $byModule = $this->user->getPermissionsByModule();

        $this->assertArrayHasKey('users', $byModule);
        $this->assertCount(2, $byModule['users']);
    }

    public function test_role_can_sync_permissions(): void
    {
        $this->adminRole->syncPermissions(['users.create', 'users.edit']);

        $this->assertTrue($this->adminRole->hasPermission('users.create'));
        $this->assertTrue($this->adminRole->hasPermission('users.edit'));

        $this->adminRole->syncPermissions(['users.create']);

        $this->assertTrue($this->adminRole->hasPermission('users.create'));
        $this->assertFalse($this->adminRole->hasPermission('users.edit'));
    }
}
