<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Role $adminRole;
    protected Role $editorRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator',
            'level' => 80,
            'is_active' => true,
        ]);

        $this->editorRole = Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
            'description' => 'Editor',
            'level' => 40,
            'is_active' => true,
        ]);

        $this->user = User::factory()->create();
    }

    public function test_can_assign_role_to_user(): void
    {
        $this->user->assignRole('admin');

        $this->assertTrue($this->user->hasRole('admin'));
        $this->assertFalse($this->user->hasRole('editor'));
    }

    public function test_can_assign_multiple_roles(): void
    {
        $this->user->assignRole('admin', 'editor');

        $this->assertTrue($this->user->hasRole('admin'));
        $this->assertTrue($this->user->hasRole('editor'));
    }

    public function test_can_remove_role(): void
    {
        $this->user->assignRole('admin', 'editor');
        $this->user->removeRole('editor');

        $this->assertTrue($this->user->hasRole('admin'));
        $this->assertFalse($this->user->hasRole('editor'));
    }

    public function test_can_sync_roles(): void
    {
        $this->user->assignRole('admin', 'editor');
        $this->user->syncRoles(['editor']);

        $this->assertFalse($this->user->hasRole('admin'));
        $this->assertTrue($this->user->hasRole('editor'));
    }

    public function test_has_role_with_pipe_separator(): void
    {
        $this->user->assignRole('editor');

        $this->assertTrue($this->user->hasRole('admin|editor'));
        $this->assertFalse($this->user->hasRole('admin|manager'));
    }

    public function test_has_all_roles(): void
    {
        $this->user->assignRole('admin', 'editor');

        $this->assertTrue($this->user->hasAllRoles(['admin', 'editor']));
        $this->assertFalse($this->user->hasAllRoles(['admin', 'editor', 'manager']));
    }

    public function test_is_super_admin(): void
    {
        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'level' => 100,
            'is_active' => true,
        ]);

        $this->user->assignRole('super-admin');

        $this->assertTrue($this->user->isSuperAdmin());
    }

    public function test_get_role_level(): void
    {
        $this->user->assignRole('admin', 'editor');

        $this->assertEquals(80, $this->user->getRoleLevel());
    }

    public function test_inactive_roles_are_not_considered(): void
    {
        $this->editorRole->update(['is_active' => false]);
        $this->user->assignRole('editor');
        $this->user->clearRbacCache();

        $this->assertFalse($this->user->hasRole('editor'));
    }
}
