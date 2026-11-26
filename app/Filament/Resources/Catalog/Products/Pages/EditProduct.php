<?php

namespace App\Filament\Resources\Catalog\Products\Pages;

use App\Enums\Catalog\CoverFormat;
use App\Filament\Resources\Catalog\Products\ProductResource;
use App\Filament\Resources\Catalog\Products\Schemas\ProductEditForm;
use App\Services\Catalog\ProductCoverColorDetector;
use App\Services\Catalog\PublishProduct;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    public function form(Schema $schema): Schema
    {
        return ProductEditForm::configure($schema, $this->getRecord(), static::class);
    }

    protected function getAllRelationManagers(): array
    {
        return [];
    }

    protected function afterSave(): void
    {
        if (filled($this->data['cover_upload'] ?? null)) {
            $cover = $this->record
                ->addMediaFromDisk($this->data['cover_upload'], 'local')
                ->toMediaCollection('cover');

            $cover->setCustomProperty('format', CoverFormat::LibraryCapsule->value);
            $cover->save();

            if (! $this->record->colors_are_manual) {
                $colors = app(ProductCoverColorDetector::class)->detect($cover->getPath());

                if ($colors) {
                    $this->record->forceFill($colors)->save();
                }
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
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
                    $this->refreshFormData(['status', 'published_at']);
                }),
            DeleteAction::make(),
        ];
    }
}
