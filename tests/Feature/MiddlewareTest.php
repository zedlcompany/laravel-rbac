<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiddlewareTest extends TestCase
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

        Permission::create([
            'name' => 'View Users',
            'slug' => 'users.view',
            'module' => 'users',
        ]);

        Permission::create([
            'name' => 'View Dashboard',
            'slug' => 'dashboard.view',
            'module' => 'dashboard',
        ]);

        $adminRole->givePermission('users.view', 'dashboard.view');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->regularUser = User::factory()->create();
        $this->regularUser->assignRole('user');
    }

    public function test_role_middleware_allows_authorized_user(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_role_middleware_blocks_unauthorized_user(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_role_middleware_redirects_guest(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_throttle_middleware_on_login(): void
    {
        // Make 6 requests (limit is 5 per minute)
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
    }
}
