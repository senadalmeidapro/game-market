<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<style>.gamehub-centered-tabs > .fi-tabs { width: 100%; justify-content: center; }</style>',
            )
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationGroups([
                'Catalogue',
                'Avis et commentaires',
                'Commerce',
                'Paiements',
                'Finance',
                'Bibliotheque',
                'Support',
                'Identite et acces',
                'Plateforme',
            ])
            ->discoverResources(in: app_path('Filament/Resources/Catalog'), for: 'App\Filament\Resources\Catalog')
            ->discoverResources(in: app_path('Filament/Resources/Feedback'), for: 'App\Filament\Resources\Feedback')
            ->discoverResources(in: app_path('Filament/Resources/Commerce'), for: 'App\Filament\Resources\Commerce')
            ->discoverResources(in: app_path('Filament/Resources/Payments'), for: 'App\Filament\Resources\Payments')
            ->discoverResources(in: app_path('Filament/Resources/Finance'), for: 'App\Filament\Resources\Finance')
            ->discoverResources(in: app_path('Filament/Resources/Library'), for: 'App\Filament\Resources\Library')
            ->discoverResources(in: app_path('Filament/Resources/Support'), for: 'App\Filament\Resources\Support')
            ->discoverResources(in: app_path('Filament/Resources/Identity'), for: 'App\Filament\Resources\Identity')
            ->discoverResources(in: app_path('Filament/Resources/Platform'), for: 'App\Filament\Resources\Platform')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
