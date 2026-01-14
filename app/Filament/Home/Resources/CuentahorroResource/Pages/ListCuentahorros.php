<?php

namespace App\Filament\Home\Resources\CuentahorroResource\Pages;

use App\Filament\Home\Resources\CuentahorroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCuentahorros extends ListRecords
{
    protected static string $resource = CuentahorroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
