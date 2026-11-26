<?php

namespace App\Services\Identity;

use App\Models\Identity\Role;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ProtectLastSuperAdmin
{
    /** @param list<int|string> $selectedRoleIds */
    public function assertCanUpdate(User $user, array $selectedRoleIds, string $status): void
    {
        if (! $user->hasRole('super_admin')) {
            return;
        }

        $keepsSuperAdmin = Role::query()->where('name', 'super_admin')->whereIn('id', $selectedRoleIds)->exists();
        $anotherActiveSuperAdminExists = User::query()
            ->where('status', 'active')
            ->where('id', '!=', $user->getKey())
            ->whereHas('roles', fn ($query) => $query->where('name', 'super_admin'))
            ->exists();

        if (! $anotherActiveSuperAdminExists && (! $keepsSuperAdmin || $status !== 'active')) {
            throw ValidationException::withMessages([
                'data.roles' => 'Il faut conserver au moins un super administrateur actif.',
                'data.status' => 'Il faut conserver au moins un super administrateur actif.',
            ]);
        }
    }
}
