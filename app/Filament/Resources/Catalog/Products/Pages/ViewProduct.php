<?php

namespace App\Filament\Resources\Catalog\Products\Pages;

use App\Filament\Resources\Catalog\Products\ProductResource;
use App\Services\Catalog\PublishProduct;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    public function getContentTabLabel(): ?string
    {
        return 'A propos';
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('publish')
                ->label('Publier le jeu')
                ->requiresConfirmation()
                ->visible(fn (): bool => auth()->user()?->can('catalog.manage') === true && $this->record->status->value !== 'published')
                ->action(function (): void {
                    $issues = app(PublishProduct::class)->publish($this->record);
                    if ($issues !== []) {
                        Notification::make()->title('Publication impossible')->body(implode("\n", $issues))->danger()->send();

                        return;
                    }
                    Notification::make()->title('Jeu publié')->success()->send();
                    $this->record->refresh();
                }),
        ];
    }
}
