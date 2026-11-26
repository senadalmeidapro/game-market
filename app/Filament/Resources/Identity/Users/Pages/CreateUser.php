<?php

namespace App\Filament\Resources\Identity\Users\Pages;

use App\Filament\Resources\Identity\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
