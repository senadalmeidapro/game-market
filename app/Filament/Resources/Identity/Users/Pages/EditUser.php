<?php

namespace App\Filament\Resources\Identity\Users\Pages;

use App\Filament\Resources\Identity\Users\UserResource;
use App\Models\Platform\AuditLog;
use App\Services\Identity\ProtectLastSuperAdmin;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /** @var list<string> */
    private array $originalRoleNames = [];

    protected function beforeSave(): void
    {
        $this->originalRoleNames = $this->record->roles()->orderBy('name')->pluck('name')->all();

        $selectedRoleIds = collect($this->data['roles'] ?? $this->record->roles()->pluck('roles.id')->all())
            ->flatten()->filter()->all();
        app(ProtectLastSuperAdmin::class)->assertCanUpdate(
            $this->record,
            $selectedRoleIds,
            $this->data['status'] ?? $this->record->status,
        );
    }

    protected function afterSave(): void
    {
        $newRoleNames = $this->record->fresh()->roles()->orderBy('name')->pluck('name')->all();
        if ($this->originalRoleNames === $newRoleNames) {
            return;
        }

        AuditLog::query()->create([
            'actor_id' => auth()->id(), 'action' => 'user.roles_changed',
            'subject_type' => $this->record::class, 'subject_id' => $this->record->getKey(),
            'old_values' => ['roles' => $this->originalRoleNames], 'new_values' => ['roles' => $newRoleNames],
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
