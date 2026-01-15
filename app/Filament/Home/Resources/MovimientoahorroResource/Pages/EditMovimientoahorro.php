<?php

namespace App\Filament\Home\Resources\MovimientoahorroResource\Pages;

use App\Filament\Home\Resources\MovimientoahorroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMovimientoahorro extends EditRecord
{
    protected static string $resource = MovimientoahorroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
