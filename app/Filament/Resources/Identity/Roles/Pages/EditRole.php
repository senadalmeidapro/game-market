<?php

namespace App\Filament\Resources\Identity\Roles\Pages;

use App\Filament\Resources\Identity\Roles\RoleResource;
use App\Models\Platform\AuditLog;
use App\Services\Identity\ProtectSuperAdminRole;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    /** @var list<string> */
    private array $originalPermissionNames = [];

    protected function beforeSave(): void
    {
        $this->originalPermissionNames = $this->record->permissions()->orderBy('name')->pluck('name')->all();

        $selectedPermissionIds = collect($this->data['permissions'] ?? $this->record->permissions()->pluck('permissions.id')->all())
            ->flatten()->filter()->all();
        app(ProtectSuperAdminRole::class)->assertCanUpdate(
            $this->record,
            $this->data['name'] ?? $this->record->name,
            $selectedPermissionIds,
        );
    }

    protected function afterSave(): void
    {
        $newPermissionNames = $this->record->fresh()->permissions()->orderBy('name')->pluck('name')->all();
        if ($this->originalPermissionNames === $newPermissionNames) {
            return;
        }

        AuditLog::query()->create([
            'actor_id' => auth()->id(), 'action' => 'role.permissions_changed',
            'subject_type' => $this->record::class, 'subject_id' => $this->record->getKey(),
            'old_values' => ['permissions' => $this->originalPermissionNames], 'new_values' => ['permissions' => $newPermissionNames],
            'ip_address' => request()->ip(), 'created_at' => now(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
