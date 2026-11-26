<?php

namespace App\Filament\Resources\Platform\AuditLogs\Pages;

use App\Filament\Resources\Platform\AuditLogs\AuditLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuditLog extends CreateRecord
{
    protected static string $resource = AuditLogResource::class;
}
