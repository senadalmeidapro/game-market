<?php

namespace App\Filament\Resources\Identity\Permissions\Pages;

use App\Filament\Resources\Identity\Permissions\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
