<?php

namespace App\Filament\Widgets;

use App\Enums\Feedback\ReviewStatus;
use App\Filament\Resources\Catalog\Products\ProductResource;
use App\Filament\Resources\Catalog\Publishers\PublisherResource;
use App\Filament\Resources\Commerce\Orders\OrderResource;
use App\Filament\Resources\Feedback\ProductReviews\ProductReviewResource;
use App\Models\Catalog\Product;
use App\Models\Catalog\Publisher;
use App\Models\Commerce\Order;
use App\Models\Feedback\ProductReview;
use App\Models\Payments\Refund;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $days = 30;
        $since = now()->subDays($days)->startOfDay();
        $paidOrders = Order::query()->where('status', 'paid')->where('paid_at', '>=', $since);
        $revenue = (clone $paidOrders)->sum('total_amount');
        $orderCount = (clone $paidOrders)->count();
        $refundTotal = Refund::query()->where('status', 'completed')->where('completed_at', '>=', $since)->sum('amount');

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
            Stat::make("Commandes payées · {$days} j", $orderCount)
                ->description('Commandes réglées sur la période')
                ->icon(Heroicon::OutlinedShoppingBag)->color('info')->url(OrderResource::getUrl('index')),
            Stat::make("Ventes brutes · {$days} j", number_format($revenue, 0, ',', ' ').' XOF')
                ->description('Somme des commandes payées avant remboursements')
                ->icon(Heroicon::OutlinedBanknotes)->color('success')->url(OrderResource::getUrl('index')),
            Stat::make("Remboursements · {$days} j", number_format($refundTotal, 0, ',', ' ').' XOF')
                ->description('Remboursements terminés sur la période')
                ->icon(Heroicon::OutlinedArrowUturnLeft)->color('warning')->url(OrderResource::getUrl('index')),
            Stat::make("Ventes nettes · {$days} j", number_format($revenue - $refundTotal, 0, ',', ' ').' XOF')
                ->description('Ventes brutes moins remboursements terminés')
                ->icon(Heroicon::OutlinedChartBar)->color('primary')->url(OrderResource::getUrl('index')),
        ];
    }
}
