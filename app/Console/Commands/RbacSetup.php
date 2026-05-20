<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RbacSetup extends Command
{
    protected $signature = 'rbac:setup
                            {--fresh : Drop all tables and re-run migrations}
                            {--admin-email=admin@example.com : Admin email address}
                            {--admin-password=password : Admin password}';

    protected $description = 'Setup RBAC system with default roles, permissions, and admin user';

    public function handle(): int
    {
        $this->info('Setting up RBAC system...');

        if ($this->option('fresh')) {
            $this->call('migrate:fresh');
        } else {
            $this->call('migrate');
        }

        $this->info('Seeding roles and permissions...');
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\RoleSeeder']);
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\PermissionSeeder']);

        $email = $this->option('admin-email');
        $password = $this->option('admin-password');

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $superAdminRole = Role::where('slug', config('rbac.super_admin_role'))->first();
        if ($superAdminRole) {
            $admin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }

        $this->newLine();
        $this->info('RBAC setup completed successfully!');
        $this->table(['Setting', 'Value'], [
            ['Admin Email', $email],
            ['Admin Password', $password],
            ['Roles Created', Role::count()],
            ['Super Admin Role', config('rbac.super_admin_role')],
        ]);

        $this->newLine();
        $this->warn('Please change the admin password after first login!');

        return Command::SUCCESS;
    }
}
