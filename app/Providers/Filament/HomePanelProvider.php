<?php

namespace App\Providers\Filament;

use App\Filament\Home\Pages\Auth\EditProfile;
use App\Filament\Home\Resources\NoResource\Pages\Auth\Register as AuthRegister;
use App\Filament\Home\Widgets\WidgetStats;
use App\Http\Middleware\Checkfecha;
use App\Http\Middleware\VerificarSuscripcionActiva;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\MenuItem;
use Swindon\FilamentHashids\Middleware\FilamentHashidsMiddleware;
use App\Http\Middleware\RedirectToProperPanelMiddleware;


class HomePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('home')
            ->path('home')
            ->login()
            ->passwordReset()
            ->profile(EditProfile::class)
            ->registration(AuthRegister::class)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->middleware([
                FilamentHashidsMiddleware::class,
            ])
            ->discoverResources(in: app_path('Filament/Home/Resources'), for: 'App\\Filament\\Home\\Resources')
            ->discoverPages(in: app_path('Filament/Home/Pages'), for: 'App\\Filament\\Home\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Home/Widgets'), for: 'App\\Filament\\Home\\Widgets')
            ->widgets([
                
                WidgetStats::class,

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                RedirectToProperPanelMiddleware::class,
                Checkfecha::class,
                VerificarSuscripcionActiva::class,
            ]);
    }
}
