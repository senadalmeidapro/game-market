<?php

namespace App\Services\Identity;

use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProtectSuperAdminRole
{
    /** @param list<int|string> $selectedPermissionIds */
    public function assertCanUpdate(Role $role, string $name, array $selectedPermissionIds): void
    {
        if ($role->name !== 'super_admin') {
            return;
        }

        $selectedPermissions = Permission::query()->whereIn('id', $selectedPermissionIds)->pluck('name')->all();
        $requiredPermissions = ['roles.manage', 'users.manage'];
        if ($name !== 'super_admin' || array_diff($requiredPermissions, $selectedPermissions) !== []) {
            throw ValidationException::withMessages([
                'data.name' => 'Le rôle super_admin doit garder son nom et ses droits d’administration des comptes et des rôles.',
                'data.permissions' => 'Le rôle super_admin doit garder ses droits d’administration des comptes et des rôles.',
            ]);
        }
    }
}
