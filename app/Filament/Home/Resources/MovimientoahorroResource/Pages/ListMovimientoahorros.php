<?php

namespace App\Filament\Home\Resources\MovimientoahorroResource\Pages;

use App\Filament\Home\Resources\MovimientoahorroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovimientoahorros extends ListRecords
{
    protected static string $resource = MovimientoahorroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
