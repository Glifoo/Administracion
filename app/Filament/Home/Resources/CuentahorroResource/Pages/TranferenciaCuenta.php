<?php

namespace App\Filament\Home\Resources\CuentahorroResource\Pages;

use App\Filament\Home\Resources\CuentahorroResource;
use Filament\Resources\Pages\Page;

class TranferenciaCuenta extends Page
{
    protected static string $resource = CuentahorroResource::class;

    protected static string $view = 'filament.home.resources.cuentahorro-resource.pages.tranferencia-cuenta';
}
