<?php

namespace App\Filament\Home\Resources\TrabajoResource\Pages;

use App\Filament\Home\Resources\TrabajoResource;
use Filament\Resources\Pages\Page;

class CotizarTrabajo extends Page
{
    protected static string $resource = TrabajoResource::class;

    protected static string $view = 'filament.home.resources.trabajo-resource.pages.cotizar-trabajo';
}
