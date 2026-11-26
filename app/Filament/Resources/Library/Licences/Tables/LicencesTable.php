<?php

namespace App\Filament\Resources\Library\Licences\Tables;

use App\Models\Library\Licence;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LicencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('product.title')
                    ->searchable(),
                TextColumn::make('orderItem.id')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('issued_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('revoked_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('revoke')
                    ->label('Revoquer la licence')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('revoked_reason')
                            ->label('Motif')
                            ->required()
                            ->rows(3),
                    ])
                    ->visible(fn (Licence $record): bool => $record->status !== 'revoked' && auth()->user()?->can('licenses.revoke') === true)
                    ->action(fn (Licence $record, array $data) => $record->forceFill([
                        'status' => 'revoked',
                        'revoked_at' => now(),
                        'revoked_reason' => $data['revoked_reason'],
                    ])->save()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
