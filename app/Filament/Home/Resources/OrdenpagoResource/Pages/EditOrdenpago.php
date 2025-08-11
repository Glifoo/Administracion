<?php

namespace App\Filament\Home\Resources\OrdenpagoResource\Pages;

use App\Filament\Home\Resources\OrdenpagoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrdenpago extends EditRecord
{
    protected static string $resource = OrdenpagoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
