<?php

namespace App\Filament\Resources\Identity\Users\Pages;

use App\Filament\Resources\Identity\Users\UserResource;
use App\Models\Platform\AuditLog;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Str::random(48);

        return $data;
    }

    protected function afterCreate(): void
    {
        AuditLog::query()->create([
            'actor_id' => auth()->id(), 'action' => 'user.roles_assigned',
            'subject_type' => $this->record::class, 'subject_id' => $this->record->getKey(),
            'new_values' => ['roles' => $this->record->fresh()->roles()->pluck('name')->all()],
            'ip_address' => request()->ip(), 'created_at' => now(),
        ]);
        $this->record->sendEmailVerificationNotification();
        Password::sendResetLink(['email' => $this->record->email]);
    }
}
