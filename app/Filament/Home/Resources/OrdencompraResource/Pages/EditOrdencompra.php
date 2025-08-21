<?php

namespace App\Filament\Home\Resources\OrdencompraResource\Pages;

use App\Filament\Home\Resources\OrdencompraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrdencompra extends EditRecord
{
    protected static string $resource = OrdencompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
