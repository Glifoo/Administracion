<?php

namespace App\Filament\Home\Resources\OrdenpagoResource\Pages;

use App\Filament\Home\Resources\OrdenpagoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrdenpagos extends ListRecords
{
    protected static string $resource = OrdenpagoResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
