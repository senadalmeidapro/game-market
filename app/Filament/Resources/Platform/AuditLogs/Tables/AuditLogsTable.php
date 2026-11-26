<?php

namespace App\Filament\Resources\Platform\AuditLogs\Tables;

use App\Models\Platform\AuditLog;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Auteur')->placeholder('Système')->searchable(),
                TextColumn::make('action')
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->searchable(),
                TextColumn::make('subject_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user')->label('Acteur')->relationship('user', 'name')->searchable()->preload(),
                SelectFilter::make('action')->label('Action')->options(fn (): array => AuditLog::query()->distinct()->orderBy('action')->pluck('action', 'action')->all()),
                SelectFilter::make('subject_type')->label('Type d’objet')->options(fn (): array => AuditLog::query()->whereNotNull('subject_type')->distinct()->orderBy('subject_type')->pluck('subject_type', 'subject_type')->all()),
                Filter::make('created_at')->label('Période')->form([
                    DatePicker::make('from')->label('Du'), DatePicker::make('until')->label('Au'),
                ])->query(fn ($query, array $data) => $query
                    ->when($data['from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                    ->when($data['until'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
