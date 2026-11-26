<?php

namespace App\Filament\Resources\Catalog\Publishers\Schemas;

use App\Enums\Catalog\PublisherContractStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PublisherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                FileUpload::make('logo_storage_key')
                    ->label('Logo editeur')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                    ->disk('public')
                    ->directory('publishers/logos')
                    ->visibility('public'),
                TextInput::make('contact_email')
                    ->email(),
                TextInput::make('country'),
                Select::make('contract_status')
                    ->options(PublisherContractStatus::class)
                    ->default('unsigned')
                    ->required(),
                FileUpload::make('contract_storage_key')
                    ->label('Contrat signe')
                    ->disk('local')
                    ->directory('publishers/contracts')
                    ->visibility('private'),
            ]);
    }
}
