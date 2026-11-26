<?php

namespace App\Filament\Widgets;

use App\Enums\Feedback\ReviewStatus;
use App\Filament\Resources\Catalog\Products\ProductResource;
use App\Filament\Resources\Catalog\Publishers\PublisherResource;
use App\Filament\Resources\Feedback\ProductReviews\ProductReviewResource;
use App\Models\Catalog\Product;
use App\Models\Catalog\Publisher;
use App\Models\Feedback\ProductReview;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jeux au catalogue', Product::query()->count())
                ->description('Consulter les fiches de jeu')
                ->icon(Heroicon::OutlinedPuzzlePiece)
                ->color('primary')
                ->url(ProductResource::getUrl('index')),
            Stat::make('Jeux publiés', Product::query()->where('status', 'published')->count())
                ->description('Disponibles dans la boutique')
                ->icon(Heroicon::OutlinedRectangleStack)
                ->color('success')
                ->url(ProductResource::getUrl('index')),
            Stat::make('Éditeurs', Publisher::query()->count())
                ->description('Gérer les fiches éditeur')
                ->icon(Heroicon::OutlinedBuildingOffice2)
                ->color('info')
                ->url(PublisherResource::getUrl('index')),
            Stat::make('Avis à modérer', ProductReview::query()->where('status', ReviewStatus::Pending->value)->count())
                ->description('Ouvrir la modération des avis')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('warning')
                ->url(ProductReviewResource::getUrl('index')),
        ];
    }
}
