<?php

namespace App\Filament\Resources\Identity\Roles\Pages;

use App\Filament\Resources\Identity\Roles\RoleResource;
use App\Models\Platform\AuditLog;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function afterCreate(): void
    {
        AuditLog::query()->create([
            'actor_id' => auth()->id(), 'action' => 'role.permissions_assigned',
            'subject_type' => $this->record::class, 'subject_id' => $this->record->getKey(),
            'new_values' => ['permissions' => $this->record->fresh()->permissions()->pluck('name')->all()],
            'ip_address' => request()->ip(), 'created_at' => now(),
        ]);
    }
}
