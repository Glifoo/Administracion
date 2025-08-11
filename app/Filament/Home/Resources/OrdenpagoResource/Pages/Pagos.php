<?php

namespace App\Filament\Home\Resources\OrdenpagoResource\Pages;

use App\Filament\Home\Resources\OrdenpagoResource;
use Filament\Resources\Pages\Page;

class Pagos extends Page 
{
    protected static string $resource = OrdenpagoResource::class;

    protected static string $view = 'filament.home.resources.ordenpago-resource.pages.pagos';
}
