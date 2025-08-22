<?php

namespace App\Filament\Home\Resources\OrdencompraResource\Pages;

use App\Filament\Home\Resources\OrdencompraResource;
use Filament\Resources\Pages\Page;

class PagoInsumo extends Page
{
    protected static string $resource = OrdencompraResource::class;

    protected static string $view = 'filament.home.resources.ordencompra-resource.pages.pago-insumo';
}
