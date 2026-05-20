<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'level' => 100,
            'is_active' => true,
        ]);

        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'level' => 80,
            'is_active' => true,
        ]);

        $userRole = Role::create([
            'name' => 'User',
            'slug' => 'user',
            'level' => 10,
            'is_active' => true,
        ]);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->regularUser = User::factory()->create();
        $this->regularUser->assignRole('user');
    }

    public function test_unauthenticated_user_cannot_access_api(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_get_profile(): void
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => $this->regularUser->email]);
    }

    public function test_authenticated_user_can_get_permissions(): void
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->getJson('/api/user/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure(['permissions', 'roles']);
    }

    public function test_admin_can_access_admin_api(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_api(): void
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_list_roles_via_api(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/admin/roles');

        $response->assertStatus(200);
    }

    public function test_admin_can_list_permissions_via_api(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/admin/permissions');

        $response->assertStatus(200);
    }
}
