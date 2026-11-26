<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use InvalidArgumentException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class GameHubAccessControlSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'catalog.view', 'catalog.manage',
            'reviews.view', 'reviews.moderate',
            'orders.view', 'orders.manage',
            'promotions.view', 'promotions.manage',
            'payments.view',
            'refunds.view', 'refunds.approve', 'refunds.propose',
            'finance.view', 'finance.export',
            'licenses.view', 'licenses.revoke',
            'tickets.view', 'tickets.handle',
            'users.view', 'users.manage',
            'roles.manage', 'permissions.manage',
            'settings.manage', 'audit.view', 'mail_logs.view', 'legal_pages.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // findOrCreate() inserts directly, so clear Spatie's in-process cache
        // before syncPermissions() resolves permission names.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            'super_admin' => $permissions,
            'catalog_manager' => ['catalog.view', 'catalog.manage'],
            'finance_manager' => [
                'catalog.view', 'orders.view', 'payments.view', 'refunds.view', 'refunds.approve',
                'finance.view', 'finance.export', 'tickets.view', 'licenses.view',
            ],
            'marketing_manager' => ['catalog.view', 'promotions.view', 'promotions.manage'],
            'support_agent' => [
                'catalog.view', 'orders.view', 'payments.view', 'refunds.view', 'refunds.propose',
                'licenses.view', 'licenses.revoke', 'tickets.view', 'tickets.handle', 'users.view',
            ],
        ];

        foreach ($roles as $name => $rolePermissions) {
            Role::findOrCreate($name, 'web')->syncPermissions($rolePermissions);
        }

        $this->seedSuperAdmin();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function seedSuperAdmin(): void
    {
        $email = env('GAMEHUB_ADMIN_EMAIL');
        $password = env('GAMEHUB_ADMIN_PASSWORD');

        if (blank($email) && blank($password)) {
            return;
        }

        if (blank($email) || blank($password)) {
            throw new InvalidArgumentException('Set both GAMEHUB_ADMIN_EMAIL and GAMEHUB_ADMIN_PASSWORD to seed the GameHub administrator.');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('GAMEHUB_ADMIN_EMAIL must be a valid email address.');
        }

        if (strlen($password) < 12) {
            throw new InvalidArgumentException('GAMEHUB_ADMIN_PASSWORD must contain at least 12 characters.');
        }

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => env('GAMEHUB_ADMIN_NAME', 'GAMEHUB Admin'),
                'password' => $password,
            ],
        );

        if ($admin->email_verified_at === null) {
            $admin->forceFill(['email_verified_at' => now()])->save();
        }

        $admin->syncRoles(['super_admin']);
    }
}
