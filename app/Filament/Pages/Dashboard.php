<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Tableau de bord';

    protected static ?string $title = 'Vue d’ensemble GAMEHUB';

    /** @return array<class-string> */
    public function getWidgets(): array
    {
        return [DashboardOverview::class];
    }
}
